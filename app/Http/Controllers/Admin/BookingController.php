<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with('package')
            ->when($request->search, fn($q) => $q->where('booking_ref', 'like', '%'.$request->search.'%')
                                                   ->orWhere('full_name', 'like', '%'.$request->search.'%')
                                                   ->orWhere('phone', 'like', '%'.$request->search.'%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date, fn($q) => $q->whereDate('travel_date', $request->date))
            ->latest()
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load('package');
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,cancelled,completed']);
        $booking->update(['status' => $request->status]);
        return back()->with('success', 'Booking status updated to ' . ucfirst($request->status));
    }

    /**
     * Export all bookings as CSV (Excel-compatible).
     */
    public function export(Request $request)
    {
        $bookings = Booking::with('package')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->get();

        $filename = 'mymanalitrip-bookings-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fwrite($file, "\xEF\xBB\xBF");

            // Header row
            fputcsv($file, [
                'Booking Ref', 'Name', 'Phone', 'Email', 'City',
                'Package', 'Travel Date', 'Adults', 'Children', 'Room Type',
                'Pickup Point', 'Total Amount', 'Advance Paid', 'Balance Due',
                'Payment Type', 'Payment Status', 'Booking Status',
                'WhatsApp Sent', 'Email Sent', 'Booked On',
            ]);

            foreach ($bookings as $b) {
                fputcsv($file, [
                    $b->booking_ref,
                    $b->full_name,
                    $b->phone,
                    $b->email,
                    $b->city,
                    $b->package?->name,
                    $b->travel_date?->format('d/m/Y'),
                    $b->adults,
                    $b->children,
                    $b->room_type,
                    $b->pickup_point,
                    $b->total_amount,
                    $b->advance_paid,
                    $b->balance_due,
                    ucfirst($b->payment_type),
                    ucfirst($b->payment_status),
                    ucfirst($b->status),
                    $b->whatsapp_sent ? 'Yes' : 'No',
                    $b->email_sent ? 'Yes' : 'No',
                    $b->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
