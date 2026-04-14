@extends('layouts.app')
@section('title', 'Book ' . $package->name . ' | MyManaliTrip')
@section('meta_description', 'Book your ' . $package->name . ' securely. Pay just 30% now — ₹' . number_format($package->price * 0.3) . '. Instant WhatsApp confirmation.')

@push('head')
{{-- Razorpay SDK --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endpush

@section('content')
<section class="booking-section">
    <div class="container">

        <div style="margin-bottom:28px;">
            <a href="{{ route('packages.show', $package->slug) }}" style="color:var(--muted);font-size:.9rem;">
                ← Back to {{ $package->name }}
            </a>
            <h1 style="font-family:'Playfair Display',serif;font-size:1.8rem;margin-top:8px;">Complete Your Booking</h1>
        </div>

        <div class="booking-layout">

            {{-- ── LEFT: Booking Form ── --}}
            <div>
                <form id="bookingForm">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <input type="hidden" name="total_amount"  value="{{ $package->price }}">
                    <input type="hidden" name="advance_paid"  value="{{ $package->price * 0.30 }}">
                    <input type="hidden" name="balance_due"   value="{{ $package->price * 0.70 }}">
                    <input type="hidden" name="payment_type"  value="partial">

                    {{-- STEP 1: Contact Details --}}
                    <div class="booking-form-card" style="margin-bottom:20px;">
                        <h2 class="booking-section-title">
                            <span>1</span> Contact Details
                        </h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="full_name" class="form-control" placeholder="Enter your full name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control" placeholder="10-digit mobile number" pattern="[0-9]{10}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Your City</label>
                                <input type="text" name="city" class="form-control" placeholder="e.g. Delhi, Mumbai">
                            </div>
                        </div>
                    </div>

                    {{-- STEP 2: Travel Details --}}
                    <div class="booking-form-card" style="margin-bottom:20px;">
                        <h2 class="booking-section-title">
                            <span>2</span> Travel Details
                        </h2>

                        <div class="form-group">
                            <label class="form-label">Travel Date *</label>
                            <input type="date" name="travel_date" class="form-control"
                                   min="{{ date('Y-m-d', strtotime('+3 days')) }}" required>
                        </div>

                        {{-- Adults counter --}}
                        <div class="form-group">
                            <label class="form-label">Adults (12+)</label>
                            <div class="counter-field" data-counter data-min="1" data-max="20">
                                <button type="button" class="counter-btn" data-dec>−</button>
                                <span class="counter-val" data-val data-for="adults">1</span>
                                <button type="button" class="counter-btn" data-inc>+</button>
                                <input type="hidden" name="adults" value="1">
                            </div>
                        </div>

                        {{-- Children counter --}}
                        <div class="form-group">
                            <label class="form-label">Children (5–11) <span style="color:var(--muted);font-weight:400;font-size:.8rem;">— 60% of adult price</span></label>
                            <div class="counter-field" data-counter data-min="0" data-max="10">
                                <button type="button" class="counter-btn" data-dec>−</button>
                                <span class="counter-val" data-val data-for="children">0</span>
                                <button type="button" class="counter-btn" data-inc>+</button>
                                <input type="hidden" name="children" value="0">
                            </div>
                        </div>

                        {{-- Room type --}}
                        <div class="form-group">
                            <label class="form-label">Room Type</label>
                            <div class="room-options">
                                <div class="room-option">
                                    <input type="radio" name="room_type" id="room_std" value="Standard" checked>
                                    <label for="room_std">🛏️ Standard<br><small style="font-weight:400;color:var(--muted);">Included</small></label>
                                </div>
                                <div class="room-option">
                                    <input type="radio" name="room_type" id="room_dlx" value="Deluxe">
                                    <label for="room_dlx">🛏️ Deluxe<br><small style="font-weight:400;color:var(--muted);">+₹500/person</small></label>
                                </div>
                                <div class="room-option">
                                    <input type="radio" name="room_type" id="room_lux" value="Luxury">
                                    <label for="room_lux">⭐ Luxury<br><small style="font-weight:400;color:var(--muted);">+₹1,500/person</small></label>
                                </div>
                            </div>
                        </div>

                        {{-- Pickup point --}}
                        <div class="form-group">
                            <label class="form-label">Pickup Point</label>
                            <select name="pickup_point" class="form-control">
                                <option value="Delhi Volvo Stand">Delhi Volvo Stand (Kashmere Gate)</option>
                                <option value="Majnu Ka Tila">Majnu Ka Tila, Delhi</option>
                                <option value="Chandigarh">Chandigarh Bus Stand</option>
                                <option value="Custom">Custom Pickup (mention below)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Special Requests <span style="color:var(--muted);font-weight:400;">(optional)</span></label>
                            <textarea name="special_requests" class="form-control" rows="3" placeholder="Anything specific? Anniversary decoration, dietary preferences, custom pickup location..."></textarea>
                        </div>
                    </div>

                    {{-- STEP 3: Payment --}}
                    <div class="booking-form-card">
                        <h2 class="booking-section-title">
                            <span>3</span> Choose Payment Option
                        </h2>

                        <div class="payment-options">
                            <div class="pay-option selected">
                                <div class="pay-option-header">
                                    <input type="radio" name="payment_type" value="partial" checked onchange="document.querySelector('[name=payment_type]').value=this.value;updateTotalPrice();">
                                    <span class="pay-option-label">Pay 30% Now — Rest at Hotel</span>
                                    <span class="pay-option-badge">RECOMMENDED</span>
                                </div>
                                <div class="pay-option-amount" id="advanceAmount">₹{{ number_format($package->price * 0.30) }}</div>
                                <div class="pay-option-note">Pay remaining ₹<span id="balanceAmount">{{ number_format($package->price * 0.70) }}</span> directly at hotel</div>
                            </div>

                            <div class="pay-option">
                                <div class="pay-option-header">
                                    <input type="radio" name="payment_type" value="full" onchange="document.querySelector('[name=payment_type]').value=this.value;updateTotalPrice();">
                                    <span class="pay-option-label">Pay Full Amount Now</span>
                                    <span class="pay-option-badge" style="background:var(--pine);">SAVE 5%</span>
                                </div>
                                <div class="pay-option-amount" id="fullAmount">₹{{ number_format($package->price * 0.95) }}</div>
                                <div class="pay-option-note" id="fullSaving">Save ₹{{ number_format($package->price * 0.05) }} — 5% instant discount</div>
                            </div>
                        </div>

                        <div class="trust-badges">
                            <span class="trust-badge"><i class="fas fa-shield-alt"></i> 100% Secure Payment</span>
                            <span class="trust-badge"><i class="fas fa-undo"></i> Free Cancellation</span>
                            <span class="trust-badge"><i class="fab fa-whatsapp" style="color:#25D366"></i> Instant WhatsApp Confirmation</span>
                            <span class="trust-badge"><i class="fas fa-lock"></i> Razorpay Secured</span>
                        </div>

                        <button type="button" id="payBtn" onclick="initiatePayment()" class="btn-primary" style="width:100%;justify-content:center;margin-top:20px;font-size:1.1rem;padding:16px;">
                            <i class="fas fa-lock"></i> Pay Securely — <span id="payNowAmount">₹{{ number_format($package->price * 0.30) }}</span>
                        </button>

                        <p style="text-align:center;font-size:.78rem;color:var(--muted);margin-top:10px;">
                            By paying, you agree to our <a href="{{ route('page.terms') }}" style="color:var(--saffron);">Terms</a>
                            &amp; <a href="{{ route('page.cancellation') }}" style="color:var(--saffron);">Cancellation Policy</a>
                        </p>
                    </div>

                </form>
            </div>

            {{-- ── RIGHT: Summary ── --}}
            <div class="booking-sidebar">
                <div class="booking-summary-card">
                    @if($package->featured_image)
                    <img src="{{ Storage::url($package->featured_image) }}" alt="{{ $package->name }}" style="width:100%;height:160px;object-fit:cover;border-radius:12px;margin-bottom:18px;">
                    @endif

                    <div class="bs-pkg-name">{{ $package->name }}</div>
                    <div class="bs-pkg-meta">{{ $package->duration }} · {{ $package->starting_city }}</div>

                    <div id="priceNote" style="display:none;background:var(--snow);padding:8px 12px;border-radius:6px;font-size:.8rem;margin-bottom:12px;"></div>

                    <div class="bs-line">
                        <span>Base Price</span>
                        <span id="basePrice" data-price="{{ $package->price }}" data-child-price="{{ $package->price_child ?? $package->price * 0.6 }}" data-package-id="{{ $package->id }}">₹{{ number_format($package->price) }} × 1 adult</span>
                    </div>
                    <div class="bs-line">
                        <span>Room Upgrade</span>
                        <span>₹0 (Standard)</span>
                    </div>
                    <div class="bs-line">
                        <span>Taxes & Fees</span>
                        <span style="color:var(--pine);">Included ✓</span>
                    </div>
                    <div class="bs-total">
                        <span>Total</span>
                        <span id="totalPrice">₹{{ number_format($package->price) }}</span>
                    </div>

                    <div style="background:var(--snow);border-radius:10px;padding:14px;margin-top:16px;">
                        <div style="font-size:.8rem;font-weight:700;margin-bottom:8px;color:var(--navy);">What's Included:</div>
                        @if($package->inclusions)
                        <ul style="list-style:none;">
                            @foreach(array_slice($package->inclusions, 0, 5) as $inc)
                            <li style="font-size:.82rem;color:var(--muted);padding:3px 0;">✓ {{ $inc }}</li>
                            @endforeach
                        </ul>
                        @else
                        <ul style="list-style:none;">
                            <li style="font-size:.82rem;color:var(--muted);padding:3px 0;">✓ Volvo bus (Delhi ⇄ Manali)</li>
                            <li style="font-size:.82rem;color:var(--muted);padding:3px 0;">✓ Hotel accommodation</li>
                            <li style="font-size:.82rem;color:var(--muted);padding:3px 0;">✓ Breakfast & Dinner</li>
                            <li style="font-size:.82rem;color:var(--muted);padding:3px 0;">✓ Local sightseeing</li>
                        </ul>
                        @endif
                    </div>

                    <div style="display:flex;align-items:center;gap:10px;margin-top:18px;padding-top:14px;border-top:1px solid var(--snow-2);">
                        <div style="font-size:1.6rem;">📞</div>
                        <div>
                            <div style="font-size:.8rem;font-weight:700;">Need help booking?</div>
                            <a href="https://wa.me/919999999999" style="color:#25D366;font-size:.85rem;font-weight:700;">
                                <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
