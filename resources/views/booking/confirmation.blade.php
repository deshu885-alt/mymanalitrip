@extends('layouts.app')
@section('title', 'Booking Confirmed! — MyManaliTrip')

@section('content')
<section style="padding:120px 0 80px;background:linear-gradient(135deg,#f0fff4,#f8f6f2);min-height:80vh;display:flex;align-items:center;">
    <div class="container" style="max-width:680px;">
        <div style="background:#fff;border-radius:24px;padding:48px;text-align:center;box-shadow:0 24px 64px rgba(13,27,46,.12);">

            <div style="width:80px;height:80px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:2.5rem;">✅</div>

            <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:var(--pine);margin-bottom:8px;">Booking Confirmed!</h1>
            <p style="color:var(--muted);margin-bottom:28px;">Your Manali trip is locked in. We're so excited for you! 🏔️</p>

            <div style="background:var(--snow);border-radius:16px;padding:24px;margin-bottom:28px;text-align:left;">
                <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--snow-2);">
                    <span style="color:var(--muted);font-size:.88rem;">Booking Reference</span>
                    <strong style="font-family:monospace;color:var(--saffron);font-size:1rem;">{{ $booking->booking_ref }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--snow-2);">
                    <span style="color:var(--muted);font-size:.88rem;">Package</span>
                    <strong style="font-size:.9rem;">{{ $booking->package->name }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--snow-2);">
                    <span style="color:var(--muted);font-size:.88rem;">Travel Date</span>
                    <strong>{{ $booking->travel_date->format('d M Y') }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--snow-2);">
                    <span style="color:var(--muted);font-size:.88rem;">Travelers</span>
                    <strong>{{ $booking->adults }} Adult{{ $booking->adults > 1 ? 's' : '' }}{{ $booking->children ? ', ' . $booking->children . ' Child' : '' }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--snow-2);">
                    <span style="color:var(--muted);font-size:.88rem;">Amount Paid</span>
                    <strong style="color:var(--pine);">₹{{ number_format($booking->advance_paid) }}</strong>
                </div>
                @if($booking->balance_due > 0)
                <div style="display:flex;justify-content:space-between;padding:10px 0;">
                    <span style="color:var(--muted);font-size:.88rem;">Balance Due at Hotel</span>
                    <strong>₹{{ number_format($booking->balance_due) }}</strong>
                </div>
                @endif
            </div>

            <div style="background:#f0fff4;border:1.5px solid #a7f3d0;border-radius:12px;padding:16px;margin-bottom:24px;font-size:.88rem;color:#065f46;">
                <strong>📱 WhatsApp confirmation sent to {{ $booking->phone }}</strong><br>
                📧 Confirmation email sent to {{ $booking->email }}<br>
                Our team will contact you within 2 hours.
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;">
                <a href="https://wa.me/919999999999?text=Hi! My booking ref is {{ $booking->booking_ref }}" target="_blank" class="btn-whatsapp">
                    <i class="fab fa-whatsapp"></i> Chat with Our Team
                </a>
                <a href="{{ route('home') }}" class="btn-outline dark">← Back to Home</a>
            </div>

            <p style="margin-top:24px;font-size:.8rem;color:var(--muted);">
                Support: <a href="mailto:hello@mymanalitrip.com" style="color:var(--saffron);">hello@mymanalitrip.com</a> · +91 99999 99999
            </p>
        </div>
    </div>
</section>
@endsection
