@extends('layouts.app')

@section('title', 'MyManaliTrip – Manali Tour Packages from Delhi | Starting ₹6,999')
@section('meta_description', 'Book Manali tour packages from ₹6,999. Volvo packages, honeymoon trips, family tours, adventure packages with hotels, meals & sightseeing. 10,000+ happy travelers.')

@push('head')
<link rel="canonical" href="{{ route('home') }}">

{{-- Homepage Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TravelAgency",
  "name": "MyManaliTrip",
  "url": "https://mymanalitrip.com",
  "logo": "{{ asset('images/logo.png') }}",
  "description": "Book Manali tour packages starting ₹6,999. Volvo, hotels, sightseeing, adventure — all included.",
  "telephone": "+91-99999-99999",
  "email": "hello@mymanalitrip.com",
  "address": { "@type": "PostalAddress", "addressCountry": "IN" },
  "aggregateRating": { "@type": "AggregateRating", "ratingValue": "4.8", "reviewCount": "10000" },
  "sameAs": ["https://www.instagram.com/mymanalitrip", "https://www.facebook.com/mymanalitrip"]
}
</script>

{{-- FAQ Schema for homepage --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {"@type":"Question","name":"What is the cheapest Manali package from Delhi?","acceptedAnswer":{"@type":"Answer","text":"Our Budget Manali Volvo Package starts at ₹6,999 per person for 3 nights / 4 days including Volvo bus, hotel, meals and sightseeing."}},
    {"@type":"Question","name":"How do I book a Manali trip?","acceptedAnswer":{"@type":"Answer","text":"Simply choose a package, select your travel date, enter traveler details and pay 30% advance online. We'll confirm your booking instantly via WhatsApp."}},
    {"@type":"Question","name":"Is there a honeymoon package for Manali?","acceptedAnswer":{"@type":"Answer","text":"Yes! Our Manali Honeymoon Special at ₹11,999 per couple includes candlelight dinner, flower bed decoration, private cab and 3★ hotel stay."}}
  ]
}
</script>
@endpush

@section('content')

{{-- ════════════════════════════════════════════════════════
     1. HERO SECTION
     ════════════════════════════════════════════════════════ --}}
<section class="hero" id="hero">
    <div class="container hero-inner">

        <div class="hero-content">
            <div class="hero-badge">
                <span>❄️</span> #1 Manali Travel Partner
            </div>
            <h1 class="hero-headline">
                Plan Your Dream<br>
                <span>Manali Trip</span><br>
                in Minutes
            </h1>
            <p class="hero-sub">
                Volvo · Hotels · Sightseeing · Adventure<br>
                All in One Package — Starting ₹6,999
            </p>
            <div class="hero-pills">
                <span class="hero-pill">✔ Free Cancellation</span>
                <span class="hero-pill">✔ Pay 30% Now</span>
                <span class="hero-pill">✔ 24x7 Support</span>
                <span class="hero-pill">✔ 10,000+ Travelers</span>
            </div>
            <div class="hero-btns">
                <a href="{{ route('packages.index') }}" class="btn-primary">
                    View All Packages <i class="fas fa-arrow-right"></i>
                </a>
                <a href="https://wa.me/919999999999?text=Hi! I want to plan a Manali trip" target="_blank" class="btn-outline">
                    <i class="fab fa-whatsapp"></i> WhatsApp Us
                </a>
            </div>
        </div>

        {{-- Quick Search Card --}}
        <div class="hero-card">
            <h3>Find My Manali Trip</h3>
            <p>Tell us your preferences & we'll match the perfect package</p>

            <form action="{{ route('packages.index') }}" method="GET">
                <div class="search-field">
                    <label>📅 Travel Month</label>
                    <select name="month">
                        <option value="">Select Month</option>
                        @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $i => $m)
                        <option value="{{ $i+1 }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="search-field">
                    <label>💰 Your Budget</label>
                    <select name="budget">
                        <option value="">Select Budget</option>
                        <option value="5000">Under ₹5,000</option>
                        <option value="10000">₹5,000 – ₹10,000</option>
                        <option value="15000">₹10,000 – ₹15,000</option>
                        <option value="20000">₹15,000+</option>
                    </select>
                </div>
                <div class="search-field">
                    <label>❤️ Trip Type</label>
                    <select name="type">
                        <option value="">Select Type</option>
                        <option value="honeymoon">💕 Couple / Honeymoon</option>
                        <option value="family">👨‍👩‍👧 Family</option>
                        <option value="group">👥 Friends / Group</option>
                        <option value="adventure">🪂 Adventure</option>
                        <option value="winter">❄️ Snowfall Special</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search"></i> Find My Trip
                </button>
            </form>

            <div class="trust-mini">
                <span><i class="fas fa-star"></i> 4.8/5 Rating</span>
                <span><i class="fas fa-shield-alt"></i> Secure Booking</span>
                <span><i class="fas fa-undo"></i> Free Cancel</span>
            </div>
        </div>

    </div>
</section>

{{-- ════════════════════════════════════════════════════════
     2. TRUST BAR
     ════════════════════════════════════════════════════════ --}}
<div class="trust-bar">
    <div class="container trust-bar-inner">
        <div class="trust-stat">
            <span class="trust-stat-icon">⭐</span>
            <div class="trust-stat-text">
                <strong>10,000+</strong>
                <span>Happy Travelers</span>
            </div>
        </div>
        <div class="trust-divider"></div>
        <div class="trust-stat">
            <span class="trust-stat-icon">🚌</span>
            <div class="trust-stat-text">
                <strong>Daily Departures</strong>
                <span>Volvo from Delhi</span>
            </div>
        </div>
        <div class="trust-divider"></div>
        <div class="trust-stat">
            <span class="trust-stat-icon">🏨</span>
            <div class="trust-stat-text">
                <strong>50+ Hotels</strong>
                <span>Partner Properties</span>
            </div>
        </div>
        <div class="trust-divider"></div>
        <div class="trust-stat">
            <span class="trust-stat-icon">💬</span>
            <div class="trust-stat-text">
                <strong>24x7 Support</strong>
                <span>WhatsApp & Phone</span>
            </div>
        </div>
        <div class="trust-divider"></div>
        <div class="trust-stat">
            <span class="trust-stat-icon">💳</span>
            <div class="trust-stat-text">
                <strong>Pay 30% Now</strong>
                <span>Rest at Destination</span>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════
     3. FEATURED PACKAGES SLIDER
     ════════════════════════════════════════════════════════ --}}
<section>
    <div class="container">
        <div class="section-header" style="display:flex;align-items:flex-end;justify-content:space-between;">
            <div>
                <div class="label-pill">Best Sellers</div>
                <h2 class="section-title">Most Booked Manali Packages ✨</h2>
                <p class="section-sub">Trusted by 10,000+ travelers — handpicked deals for every budget</p>
            </div>
            <a href="{{ route('packages.index') }}" class="section-header view-all">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="packages-slider">
            <div class="packages-track">
                @forelse($featuredPackages as $pkg)
                @php
                    $highlights = is_array($pkg->highlights)
                        ? $pkg->highlights
                        : (json_decode($pkg->highlights, true) ?? []);
                @endphp
                <div class="pkg-card">
                    <div class="pkg-image">
                        @if($pkg->featured_image)
                            <img src="{{ Storage::url($pkg->featured_image) }}" alt="{{ $pkg->name }}" loading="lazy">
                        @else
                            <img src="https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?w=600&q=70" alt="{{ $pkg->name }}" loading="lazy">
                        @endif
                        @if($pkg->is_bestseller)
                            <span class="pkg-badge bestseller">🔥 Best Seller</span>
                        @elseif($pkg->type === 'winter')
                            <span class="pkg-badge winter">❄️ Winter Special</span>
                        @elseif($pkg->type === 'honeymoon')
                            <span class="pkg-badge honeymoon">💕 Honeymoon</span>
                        @endif
                    </div>
                    <div class="pkg-body">
                        <div class="pkg-meta">
                            <span class="pkg-duration">{{ $pkg->duration }}</span>
                            <span class="pkg-rating">⭐ {{ $pkg->rating }}</span>
                        </div>
                        <h3 class="pkg-name">{{ $pkg->name }}</h3>
                        @if(count($highlights))
                        <div class="pkg-highlights">
                            @foreach(array_slice($highlights, 0, 3) as $h)
                            <span class="pkg-tag">{{ $h }}</span>
                            @endforeach
                        </div>
                        @endif
                        <div class="pkg-footer">
                            <div class="pkg-price">
                                ₹{{ number_format($pkg->price) }}
                                <small>{{ $pkg->price_label }}</small>
                            </div>
                            <a href="{{ route('packages.show', $pkg->slug) }}" class="pkg-btn">View Details</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="pkg-card" style="min-width:300px;">
                    <div class="pkg-image">
                        <img src="https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?w=600&q=70" alt="Budget Manali Package" loading="lazy">
                        <span class="pkg-badge bestseller">🔥 Best Seller</span>
                    </div>
                    <div class="pkg-body">
                        <div class="pkg-meta"><span class="pkg-duration">3N / 4D</span><span class="pkg-rating">⭐ 4.8</span></div>
                        <h3 class="pkg-name">Budget Manali Volvo Package</h3>
                        <div class="pkg-highlights">
                            <span class="pkg-tag">Volvo Bus</span>
                            <span class="pkg-tag">Solang Valley</span>
                            <span class="pkg-tag">Meals</span>
                        </div>
                        <div class="pkg-footer">
                            <div class="pkg-price">₹6,999 <small>per person</small></div>
                            <a href="{{ route('packages.index') }}" class="pkg-btn">View Details</a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════
     4. HOW IT WORKS
     ════════════════════════════════════════════════════════ --}}
<section class="how-section">
    <div class="container">
        <div class="section-header center">
            <div class="label-pill" style="background:rgba(245,166,35,.2);color:var(--saffron);">Simple Process</div>
            <h2 class="section-title" style="color:#fff;">Plan Your Trip in 3 Easy Steps 🧭</h2>
            <p class="section-sub">No complicated booking. No hidden charges. Just pure travel joy.</p>
        </div>
        <div class="how-grid">
            <div class="how-step">
                <div class="how-icon">🔍</div>
                <div class="how-num">01</div>
                <h3 class="how-title">Choose Your Package</h3>
                <p class="how-desc">Browse our curated Manali packages — from budget Volvo trips to luxury resorts. Filter by budget, type & duration.</p>
            </div>
            <div class="how-step">
                <div class="how-icon">💳</div>
                <div class="how-num">02</div>
                <h3 class="how-title">Pay 30% Advance</h3>
                <p class="how-desc">Secure your seat with just 30% online. Pay the rest at the hotel. UPI, cards, net banking — all accepted.</p>
            </div>
            <div class="how-step">
                <div class="how-icon">🎒</div>
                <div class="how-num">03</div>
                <h3 class="how-title">Pack & Enjoy!</h3>
                <p class="how-desc">Receive instant confirmation on WhatsApp. Our team handles everything — you just show up and enjoy Manali! ❤️</p>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════
     5. HONEYMOON SECTION
     ════════════════════════════════════════════════════════ --}}
<section class="honeymoon-section">
    <div class="container">
        <div class="section-header center">
            <div class="label-pill" style="background:#fce7f3;color:#9d174d;">Romance</div>
            <h2 class="section-title">Romantic Manali Honeymoon Packages ❤️</h2>
            <p class="section-sub">Candlelight dinners, snow views & cozy stays — crafted for couples</p>
        </div>
        <div class="honeymoon-cards">
            <div class="honey-card">
                <div class="honey-img" style="background-image:url('https://images.unsplash.com/photo-1518611012118-696072aa579a?w=600&q=70')"></div>
                <div class="honey-body">
                    <div class="honey-tag">💕 Honeymoon Special</div>
                    <h3 class="honey-name">Manali Honeymoon Special</h3>
                    <p class="honey-tagline">4 Nights · Candlelight dinner · Flower decoration</p>
                    <div class="honey-footer">
                        <div class="honey-price">₹11,999 <small style="font-size:.7rem;color:var(--muted);font-weight:400">per couple</small></div>
                        <a href="{{ route('packages.index') }}?type=honeymoon" class="honey-btn">View Details</a>
                    </div>
                </div>
            </div>
            <div class="honey-card">
                <div class="honey-img" style="background-image:url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&q=70')"></div>
                <div class="honey-body">
                    <div class="honey-tag">⭐ Luxury</div>
                    <h3 class="honey-name">Luxury Manali Tour</h3>
                    <p class="honey-tagline">4 Nights · 4★ Resort · Riverside room · Bonfire</p>
                    <div class="honey-footer">
                        <div class="honey-price">₹18,999 <small style="font-size:.7rem;color:var(--muted);font-weight:400">per person</small></div>
                        <a href="{{ route('packages.index') }}?type=luxury" class="honey-btn">View Details</a>
                    </div>
                </div>
            </div>
            <div class="honey-card">
                <div class="honey-img" style="background-image:url('https://images.unsplash.com/photo-1601921004897-b7d582836990?w=600&q=70')"></div>
                <div class="honey-body">
                    <div class="honey-tag">🏔️ Extended</div>
                    <h3 class="honey-name">Manali Extended Trip</h3>
                    <p class="honey-tagline">6 Nights · Atal Tunnel · Sissu · Kasol</p>
                    <div class="honey-footer">
                        <div class="honey-price">₹15,999 <small style="font-size:.7rem;color:var(--muted);font-weight:400">per person</small></div>
                        <a href="{{ route('packages.index') }}?type=luxury" class="honey-btn">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════
     6. EXPERIENCE GRID
     ════════════════════════════════════════════════════════ --}}
<section class="experience-section">
    <div class="container">
        <div class="section-header center">
            <div class="label-pill">The Magic</div>
            <h2 class="section-title">Experience The Magic of Manali ❄️</h2>
            <p class="section-sub">Snow peaks, cafe culture, adrenaline & serenity — all in one destination</p>
        </div>
        <div class="exp-grid">
            <div class="exp-item" style="background-image:url('https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?w=800&q=70')"><span class="exp-label">❄️ Snowfall</span></div>
            <div class="exp-item" style="background-image:url('https://images.unsplash.com/photo-1472745433479-4556f22e32c2?w=800&q=70')"><span class="exp-label">🪂 Paragliding</span></div>
            <div class="exp-item" style="background-image:url('https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&q=70')"><span class="exp-label">☕ Kasol Cafes</span></div>
            <div class="exp-item" style="background-image:url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&q=70')"><span class="exp-label">🌊 River Rafting</span></div>
            <div class="exp-item" style="background-image:url('https://images.unsplash.com/photo-1601921004897-b7d582836990?w=800&q=70')"><span class="exp-label">🛍️ Mall Road</span></div>
            <div class="exp-item" style="background-image:url('https://images.unsplash.com/photo-1503614472-8c93d56e92ce?w=800&q=70')"><span class="exp-label">🏔️ Solang Valley</span></div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════
     7. WHY CHOOSE US
     ════════════════════════════════════════════════════════ --}}
<section style="background:var(--snow);">
    <div class="container">
        <div class="section-header center">
            <div class="label-pill">Why Us</div>
            <h2 class="section-title">Why Choose MyManaliTrip.com? ⭐</h2>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon">💰</div>
                <h3 class="why-title">Best Price Guarantee</h3>
                <p class="why-desc">We match any lower price you find. Transparent pricing with zero hidden charges — ever.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">🏔️</div>
                <h3 class="why-title">Local Himachal Experts</h3>
                <p class="why-desc">Our team lives and breathes Himachal Pradesh. Local expertise means better trips for you.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">📞</div>
                <h3 class="why-title">24x7 Support</h3>
                <p class="why-desc">WhatsApp, phone, or email — we're always available. Even at 2am on a mountain.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">💳</div>
                <h3 class="why-title">Pay Just 30% Now</h3>
                <p class="why-desc">Lock in your spot with only 30% advance. Pay the balance at the hotel. Less financial stress.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">🚫</div>
                <h3 class="why-title">No Hidden Charges</h3>
                <p class="why-desc">What you see is what you pay. Inclusions are crystal clear before you book.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">⭐</div>
                <h3 class="why-title">5-Star Rated Service</h3>
                <p class="why-desc">4.8/5 average rating from 10,000+ travelers. Our reviews speak louder than our words.</p>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════
     8. REVIEWS
     ════════════════════════════════════════════════════════ --}}
<section class="reviews-section">
    <div class="container">
        <div class="section-header center">
            <div class="label-pill">Reviews</div>
            <h2 class="section-title">Stories from Happy Travelers ❤️</h2>
        </div>
        <div class="reviews-grid">
            <div class="review-card">
                <div class="review-stars">★★★★★</div>
                <p class="review-text">"Best trip ever! Everything was perfectly managed — from the Volvo pickup to the hotel. No hassle at all. Will definitely book again!"</p>
                <div class="review-author">
                    <div class="review-avatar">P</div>
                    <div>
                        <div class="review-name">Priya Sharma</div>
                        <div class="review-place">Delhi</div>
                        <span class="review-pkg">Budget Volvo Package</span>
                    </div>
                </div>
            </div>
            <div class="review-card">
                <div class="review-stars">★★★★★</div>
                <p class="review-text">"Booked the honeymoon package for our anniversary. The flower decoration and candlelight dinner were so romantic. 10/10 experience!"</p>
                <div class="review-author">
                    <div class="review-avatar">R</div>
                    <div>
                        <div class="review-name">Rahul & Neha</div>
                        <div class="review-place">Mumbai</div>
                        <span class="review-pkg">Honeymoon Special</span>
                    </div>
                </div>
            </div>
            <div class="review-card">
                <div class="review-stars">★★★★★</div>
                <p class="review-text">"Went with 12 friends for the group tour. DJ night, bonfire, Solang Valley — all amazing. The price was unbeatable. Highly recommend!"</p>
                <div class="review-author">
                    <div class="review-avatar">A</div>
                    <div>
                        <div class="review-name">Arjun Mehta</div>
                        <div class="review-place">Chandigarh</div>
                        <span class="review-pkg">Group Tour Package</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════
     9. BLOG / TRAVEL GUIDES
     ════════════════════════════════════════════════════════ --}}
@if($latestBlogs->count())
<section style="background:var(--white);">
    <div class="container">
        <div class="section-header" style="display:flex;align-items:flex-end;justify-content:space-between;">
            <div>
                <div class="label-pill">Travel Resource</div>
                <h2 class="section-title">Manali Travel Guides ✍️</h2>
                <p class="section-sub">Expert tips, itineraries & insider advice for your trip</p>
            </div>
            <a href="{{ route('blog.index') }}" class="section-header view-all">
                View All Guides <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="blog-grid">
            @foreach($latestBlogs as $blog)
            @include('blog.partials.card', ['blog' => $blog])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ════════════════════════════════════════════════════════
     10. FINAL CTA
     ════════════════════════════════════════════════════════ --}}
<section class="cta-banner">
    <div class="container cta-inner">
        <div class="label-pill" style="background:rgba(245,166,35,.2);color:var(--saffron);">Book Now</div>
        <h2>Ready for Snowy Mountains? ❄️</h2>
        <p>Join 10,000+ travelers who chose MyManaliTrip. Packages for every budget.</p>
        <div class="cta-btns">
            <a href="{{ route('packages.index') }}" class="btn-primary" style="font-size:1.1rem;padding:16px 36px;">
                Book MyManaliTrip Now <i class="fas fa-arrow-right"></i>
            </a>
            <a href="https://wa.me/919999999999?text=Hi! I want to book a Manali trip" class="btn-whatsapp" style="font-size:1.05rem;padding:15px 30px;" target="_blank">
                <i class="fab fa-whatsapp"></i> WhatsApp Us
            </a>
        </div>
    </div>
</section>

@endsection