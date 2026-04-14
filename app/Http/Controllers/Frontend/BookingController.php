<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    // ── Show booking form ─────────────────────────────────────────────────────

    public function show(Package $package)
    {
        abort_unless($package->is_active, 404);
        return view('booking.show', compact('package'));
    }

    // ── Create Razorpay Order ─────────────────────────────────────────────────

    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'amount'       => 'required|numeric|min:100',
            'payment_type' => 'required|in:partial,full',
            'package_id'   => 'required|exists:packages,id',
            'full_name'    => 'required|string',
            'phone'        => 'required|string|size:10',
            'email'        => 'required|email',
            'travel_date'  => 'required|date|after:today',
            'adults'       => 'required|integer|min:1',
        ]);

        $package = Package::findOrFail($validated['package_id']);

        // Amount in paise (Razorpay works in paise)
        $amountPaise = (int) ($validated['amount'] * 100);

        try {
            // Razorpay API call
            $response = Http::withBasicAuth(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            )->post('https://api.razorpay.com/v1/orders', [
                'amount'   => $amountPaise,
                'currency' => 'INR',
                'receipt'  => 'MMT-' . time(),
                'notes'    => [
                    'package'  => $package->name,
                    'customer' => $validated['full_name'],
                    'phone'    => $validated['phone'],
                ],
            ]);

            if (!$response->successful()) {
                throw new \Exception($response->body());
            }

            $order = $response->json();

            return response()->json([
                'order_id'     => $order['id'],
                'amount'       => $amountPaise,
                'key'          => config('services.razorpay.key'),
                'package_name' => $package->name,
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed: ' . $e->getMessage());
            return response()->json(['message' => 'Payment gateway error. Please try again.'], 500);
        }
    }

    // ── Verify Payment & Create Booking ──────────────────────────────────────

    public function verifyPayment(Request $request)
    {
        $validated = $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
            'booking_data'        => 'required|array',
            'payment_type'        => 'required|in:partial,full',
        ]);

        // Verify Razorpay signature
        $expectedSignature = hash_hmac(
            'sha256',
            $validated['razorpay_order_id'] . '|' . $validated['razorpay_payment_id'],
            config('services.razorpay.secret')
        );

        if (!hash_equals($expectedSignature, $validated['razorpay_signature'])) {
            Log::warning('Razorpay signature mismatch', $validated);
            return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 400);
        }

        // Create booking
        $data = $validated['booking_data'];
        $pkg  = Package::findOrFail($data['package_id']);

        $totalAmount = (float) ($data['total_amount'] ?? $pkg->price);
        $payType     = $validated['payment_type'];
        $advancePaid = $payType === 'full'
            ? round($totalAmount * 0.95)
            : round($totalAmount * 0.30);
        $balanceDue  = $totalAmount - $advancePaid;
        if ($payType === 'full') $balanceDue = 0;

        $booking = Booking::create([
            'package_id'          => $pkg->id,
            'full_name'           => $data['full_name'],
            'phone'               => $data['phone'],
            'email'               => $data['email'],
            'city'                => $data['city'] ?? null,
            'adults'              => (int) ($data['adults'] ?? 1),
            'children'            => (int) ($data['children'] ?? 0),
            'room_type'           => $data['room_type'] ?? 'Standard',
            'pickup_point'        => $data['pickup_point'] ?? null,
            'special_requests'    => $data['special_requests'] ?? null,
            'travel_date'         => $data['travel_date'],
            'total_amount'        => $totalAmount,
            'advance_paid'        => $advancePaid,
            'balance_due'         => $balanceDue,
            'payment_type'        => $payType,
            'payment_status'      => $payType === 'full' ? 'paid' : 'partial',
            'razorpay_order_id'   => $validated['razorpay_order_id'],
            'razorpay_payment_id' => $validated['razorpay_payment_id'],
            'status'              => 'confirmed',
        ]);

        // Send confirmation email
        $this->sendConfirmationEmail($booking);

        // Send WhatsApp message
        $this->sendWhatsAppConfirmation($booking);

        return response()->json([
            'success'     => true,
            'booking_ref' => $booking->booking_ref,
        ]);
    }

    // ── Booking confirmation page ─────────────────────────────────────────────

    public function confirmation(string $ref)
    {
        $booking = Booking::with('package')->where('booking_ref', $ref)->firstOrFail();
        return view('booking.confirmation', compact('booking'));
    }

    // ── Track booking ─────────────────────────────────────────────────────────

    public function track()
    {
        return view('booking.track');
    }

    public function trackLookup(Request $request)
    {
        $request->validate(['booking_ref' => 'required|string', 'phone' => 'required|string']);

        $booking = Booking::with('package')
            ->where('booking_ref', strtoupper($request->booking_ref))
            ->where('phone', $request->phone)
            ->first();

        if (!$booking) {
            return back()->withErrors(['booking_ref' => 'No booking found. Check your reference number and phone.']);
        }

        return view('booking.track', compact('booking'));
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    private function sendConfirmationEmail(Booking $booking): void
    {
        try {
            Mail::send('emails.booking-confirmation', compact('booking'), function ($m) use ($booking) {
                $m->to($booking->email, $booking->full_name)
                  ->subject('🏔️ Booking Confirmed – ' . $booking->booking_ref . ' | MyManaliTrip');
            });
            $booking->update(['email_sent' => true]);
        } catch (\Exception $e) {
            Log::error('Booking email failed for ' . $booking->booking_ref . ': ' . $e->getMessage());
        }
    }

    /**
     * WhatsApp via Twilio / WA Business API.
     * Replace with your provider. Here using a simple HTTP post.
     */
    private function sendWhatsAppConfirmation(Booking $booking): void
    {
        $message = "Hi {$booking->full_name}! 👋\n\n"
            . "✅ *Your Manali trip is confirmed!*\n\n"
            . "📦 *Package:* {$booking->package->name}\n"
            . "📅 *Travel Date:* {$booking->travel_date->format('d M Y')}\n"
            . "💰 *Amount Paid:* ₹" . number_format($booking->advance_paid) . "\n"
            . ($booking->balance_due > 0 ? "🏨 *Balance at Hotel:* ₹" . number_format($booking->balance_due) . "\n" : "")
            . "🎫 *Booking Ref:* {$booking->booking_ref}\n\n"
            . "Our team will contact you shortly with full details ❤️\n\n"
            . "— Team MyManaliTrip\n📞 +91 99999 99999";

        try {
            // Using Twilio WhatsApp API — swap with any WA provider
            $sid    = config('services.twilio.sid');
            $token  = config('services.twilio.token');
            $from   = config('services.twilio.whatsapp_from');

            if ($sid && $token && $from) {
                Http::withBasicAuth($sid, $token)
                    ->asForm()
                    ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                        'From' => 'whatsapp:' . $from,
                        'To'   => 'whatsapp:+91' . $booking->phone,
                        'Body' => $message,
                    ]);
            }

            $booking->update(['whatsapp_sent' => true]);
        } catch (\Exception $e) {
            Log::error('WhatsApp send failed for ' . $booking->booking_ref . ': ' . $e->getMessage());
        }
    }
}
