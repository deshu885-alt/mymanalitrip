<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MyManaliTrip – Manali Tour Packages from Delhi')</title>
    <meta name="description" content="@yield('meta_description', 'Book Manali tour packages starting ₹6,999. Volvo packages, honeymoon trips, family tours, adventure packages. 10,000+ happy travelers.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&family=Bebas+Neue&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('head')
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="logo">
            <span class="logo-icon">🏔️</span>
            <span class="logo-text">MyManaliTrip<span class="logo-dot">.com</span></span>
        </a>

        <ul class="nav-links">
            <li><a href="{{ route('packages.index') }}">Packages</a></li>
            <li class="has-dropdown">
                <a href="#">Trip Types <i class="fas fa-chevron-down fa-xs"></i></a>
                <ul class="dropdown">
                    <li><a href="{{ route('packages.index') }}?type=honeymoon">💕 Honeymoon</a></li>
                    <li><a href="{{ route('packages.index') }}?type=family">👨‍👩‍👧 Family</a></li>
                    <li><a href="{{ route('packages.index') }}?type=adventure">🪂 Adventure</a></li>
                    <li><a href="{{ route('packages.index') }}?type=group">👥 Group Tours</a></li>
                    <li><a href="{{ route('packages.index') }}?type=winter">❄️ Snowfall Special</a></li>
                </ul>
            </li>
            <li><a href="{{ route('blog.index') }}">Travel Guide</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>

        <div class="nav-cta">
            <a href="https://wa.me/919999999999" class="btn-whatsapp" target="_blank">
                <i class="fab fa-whatsapp"></i> WhatsApp Us
            </a>
            <a href="{{ route('packages.index') }}" class="btn-primary">View Packages</a>
        </div>

        <button class="hamburger" id="hamburger">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <a href="{{ route('packages.index') }}">All Packages</a>
    <a href="{{ route('packages.index') }}?type=honeymoon">💕 Honeymoon</a>
    <a href="{{ route('packages.index') }}?type=family">👨‍👩‍👧 Family</a>
    <a href="{{ route('packages.index') }}?type=adventure">🪂 Adventure</a>
    <a href="{{ route('blog.index') }}">Travel Guide</a>
    <a href="https://wa.me/919999999999" class="wa-link">
        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
    </a>
</div>

@yield('content')

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-top">
        <div class="container footer-grid">
            <div class="footer-brand">
                <div class="footer-logo">🏔️ MyManaliTrip.com</div>
                <p>Your trusted Himachal travel partner. 10,000+ happy travelers since 2019.</p>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="https://wa.me/919999999999"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Top Packages</h4>
                <ul>
                    <li><a href="#">Budget Volvo Package ₹6,999</a></li>
                    <li><a href="#">Honeymoon Special ₹11,999</a></li>
                    <li><a href="#">Shimla Manali Combo ₹12,999</a></li>
                    <li><a href="#">Adventure Package ₹12,499</a></li>
                    <li><a href="#">Snowfall Package ₹8,999</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('packages.index') }}">All Packages</a></li>
                    <li><a href="{{ route('blog.index') }}">Travel Guide</a></li>
                    <li><a href="{{ route('booking.track') }}">Track Booking</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Policies</h4>
                <ul>
                    <li><a href="{{ route('page', 'cancellation-policy') }}">Cancellation Policy</a></li>
                    <li><a href="{{ route('page', 'refund-policy') }}">Refund Policy</a></li>
                    <li><a href="{{ route('page', 'terms') }}">Terms & Conditions</a></li>
                    <li><a href="{{ route('page', 'privacy-policy') }}">Privacy Policy</a></li>
                </ul>
                <div class="footer-contact" id="contact">
                    <p><i class="fas fa-phone"></i> +91 99999 99999</p>
                    <p><i class="fas fa-envelope"></i> hello@mymanalitrip.com</p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p>© {{ date('Y') }} MyManaliTrip.com · All rights reserved · Made with ❤️ in Himachal Pradesh</p>
        </div>
    </div>
</footer>

<!-- Floating WhatsApp -->
<a href="https://wa.me/919999999999?text=Hi! I want to book a Manali trip" class="float-wa" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
