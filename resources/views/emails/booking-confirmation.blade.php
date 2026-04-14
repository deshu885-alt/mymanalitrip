<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<title>Booking Confirmed – MyManaliTrip</title>
</head>
<body style="margin:0;padding:0;background:#f8f6f2;font-family:'DM Sans',Helvetica,Arial,sans-serif;">

<div style="max-width:580px;margin:32px auto;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(13,27,46,.10);">

    {{-- Header --}}
    <div style="background:#0d1b2e;padding:32px 36px;text-align:center;">
        <div style="font-size:2rem;margin-bottom:8px;">🏔️</div>
        <div style="font-size:1.4rem;font-weight:700;color:#ffffff;">MyManaliTrip.com</div>
        <div style="color:#f5a623;font-size:.85rem;margin-top:4px;">Your Himachal Travel Partner</div>
    </div>

    {{-- Green confirmation bar --}}
    <div style="background:#d1fae5;padding:18px 36px;text-align:center;border-bottom:2px solid #a7f3d0;">
        <div style="font-size:1.5rem;margin-bottom:4px;">✅</div>
        <div style="font-size:1.1rem;font-weight:700;color:#065f46;">Booking Confirmed!</div>
    </div>

    {{-- Body --}}
    <div style="padding:32px 36px;">
        <p style="font-size:1rem;color:#0d1b2e;margin-bottom:24px;">
            Hi <strong>{{ $booking->full_name }}</strong>! 👋<br><br>
            Your Manali trip is locked in and confirmed. We're thrilled to have you travelling with us!
        </p>

        {{-- Booking details table --}}
        <table style="width:100%;border-collapse:collapse;margin-bottom:24px;">
            <tr>
                <td style="padding:12px 0;border-bottom:1px solid #ede9e2;color:#6b7a8d;font-size:.88rem;width:45%;">Booking Reference</td>
                <td style="padding:12px 0;border-bottom:1px solid #ede9e2;font-weight:700;color:#f5a623;font-family:monospace;">{{ $booking->booking_ref }}</td>
            </tr>
            <tr>
                <td style="padding:12px 0;border-bottom:1px solid #ede9e2;color:#6b7a8d;font-size:.88rem;">Package</td>
                <td style="padding:12px 0;border-bottom:1px solid #ede9e2;font-weight:600;font-size:.9rem;">{{ $booking->package->name }}</td>
            </tr>
            <tr>
                <td style="padding:12px 0;border-bottom:1px solid #ede9e2;color:#6b7a8d;font-size:.88rem;">Travel Date</td>
                <td style="padding:12px 0;border-bottom:1px solid #ede9e2;font-weight:600;">{{ $booking->travel_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <td style="padding:12px 0;border-bottom:1px solid #ede9e2;color:#6b7a8d;font-size:.88rem;">Travelers</td>
                <td style="padding:12px 0;border-bottom:1px solid #ede9e2;">{{ $booking->adults }} Adult{{ $booking->adults > 1 ? 's' : '' }}{{ $booking->children ? ', ' . $booking->children . ' Child' : '' }}</td>
            </tr>
            <tr>
                <td style="padding:12px 0;border-bottom:1px solid #ede9e2;color:#6b7a8d;font-size:.88rem;">Pickup Point</td>
                <td style="padding:12px 0;border-bottom:1px solid #ede9e2;">{{ $booking->pickup_point ?? 'Delhi Volvo Stand' }}</td>
            </tr>
            <tr>
                <td style="padding:12px 0;border-bottom:1px solid #ede9e2;color:#6b7a8d;font-size:.88rem;">Amount Paid</td>
                <td style="padding:12px 0;border-bottom:1px solid #ede9e2;font-weight:700;color:#2d6a4f;">₹{{ number_format($booking->advance_paid) }}</td>
            </tr>
            @if($booking->balance_due > 0)
            <tr>
                <td style="padding:12px 0;color:#6b7a8d;font-size:.88rem;">Balance at Hotel</td>
                <td style="padding:12px 0;font-weight:700;">₹{{ number_format($booking->balance_due) }}</td>
            </tr>
            @endif
        </table>

        {{-- CTA --}}
        <div style="text-align:center;margin:28px 0;">
            <a href="{{ route('booking.confirmation', $booking->booking_ref) }}"
               style="background:#f5a623;color:#0d1b2e;padding:14px 32px;border-radius:50px;font-weight:700;text-decoration:none;font-size:1rem;display:inline-block;">
                View Booking Details →
            </a>
        </div>

        {{-- WhatsApp note --}}
        <div style="background:#f0fff4;border:1px solid #a7f3d0;border-radius:10px;padding:16px;margin-bottom:24px;font-size:.88rem;color:#065f46;">
            💬 <strong>Our team will WhatsApp you shortly</strong> with pickup details and final itinerary.
        </div>

        <p style="font-size:.88rem;color:#6b7a8d;line-height:1.7;">
            Need help? WhatsApp us at <strong>+91 99999 99999</strong> or reply to this email.<br>
            We're available 24x7 ❤️
        </p>
    </div>

    {{-- Footer --}}
    <div style="background:#0d1b2e;padding:24px 36px;text-align:center;">
        <p style="color:rgba(255,255,255,.45);font-size:.78rem;margin:0;">
            © {{ date('Y') }} MyManaliTrip.com · hello@mymanalitrip.com · +91 99999 99999<br>
            <a href="{{ route('page.cancellation') }}" style="color:rgba(255,255,255,.4);">Cancellation Policy</a> ·
            <a href="{{ route('page.privacy') }}" style="color:rgba(255,255,255,.4);">Privacy Policy</a>
        </p>
    </div>
</div>

</body>
</html>
