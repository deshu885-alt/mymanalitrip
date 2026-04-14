@extends('layouts.app')

@section('title', ($blog->meta_title ?? $blog->title) . ' | MyManaliTrip')

{{-- ── Core SEO Meta Tags ──────────────────────────────────────────────── --}}
@section('meta_description', $blog->meta_description ?? $blog->excerpt)

@push('head')
{{-- Canonical URL --}}
<link rel="canonical" href="{{ $blog->canonical_url ?? route('blog.show', $blog->slug) }}">

{{-- Open Graph (WhatsApp, Facebook, LinkedIn sharing) --}}
<meta property="og:type"        content="article">
<meta property="og:title"       content="{{ $blog->meta_title ?? $blog->title }}">
<meta property="og:description" content="{{ $blog->meta_description ?? $blog->excerpt }}">
<meta property="og:url"         content="{{ route('blog.show', $blog->slug) }}">
<meta property="og:image"       content="{{ $blog->og_image ? Storage::url($blog->og_image) : asset('images/og-default.jpg') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height"content="630">
<meta property="og:site_name"   content="MyManaliTrip">
<meta property="article:published_time" content="{{ $blog->published_at?->toIso8601String() }}">
<meta property="article:modified_time"  content="{{ $blog->updated_at->toIso8601String() }}">
<meta property="article:author"         content="{{ $blog->author?->name ?? 'MyManaliTrip Team' }}">
<meta property="article:section"        content="{{ $blog->category }}">
@if($blog->tags)
@foreach($blog->tags as $tag)
<meta property="article:tag" content="{{ $tag }}">
@endforeach
@endif

{{-- Twitter Card --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $blog->meta_title ?? $blog->title }}">
<meta name="twitter:description" content="{{ $blog->meta_description ?? $blog->excerpt }}">
<meta name="twitter:image"       content="{{ $blog->og_image ? Storage::url($blog->og_image) : asset('images/og-default.jpg') }}">

{{-- Meta keywords --}}
@if($blog->meta_keywords)
<meta name="keywords" content="{{ $blog->meta_keywords }}">
@endif

{{-- JSON-LD Structured Data --}}
<script type="application/ld+json">{!! $schemaMarkup !!}</script>
<script type="application/ld+json">{!! $breadcrumbSchema !!}</script>
@endpush

@section('content')

{{-- ── Blog Hero ───────────────────────────────────────────────────────── --}}
<section class="blog-hero"
    @if($blog->featured_image)
    style="background-image: linear-gradient(to bottom, rgba(13,27,46,.7) 0%, rgba(13,27,46,.85) 100%), url('{{ Storage::url($blog->featured_image) }}');"
    @endif>
    <div class="container blog-hero-inner">

        {{-- Breadcrumbs — also helps SEO --}}
        <nav class="breadcrumbs" aria-label="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>›</span>
            <a href="{{ route('blog.index') }}">Travel Guide</a>
            <span>›</span>
            <a href="{{ route('blog.category', $blog->category) }}">{{ $blog->category }}</a>
            <span>›</span>
            <span>{{ Str::limit($blog->title, 40) }}</span>
        </nav>

        <div class="label-pill">{{ $blog->category }}</div>
        <h1 class="blog-hero-title">{{ $blog->title }}</h1>

        <div class="blog-meta">
            <span><i class="fas fa-user"></i> {{ $blog->author?->name ?? 'MyManaliTrip Team' }}</span>
            <span><i class="fas fa-calendar"></i> {{ $blog->published_at?->format('d M Y') }}</span>
            <span><i class="fas fa-clock"></i> {{ $blog->reading_time }} min read</span>
            <span><i class="fas fa-eye"></i> {{ number_format($blog->views) }} views</span>
        </div>

        @if($blog->excerpt)
        <p class="blog-excerpt">{{ $blog->excerpt }}</p>
        @endif
    </div>
</section>

{{-- ── Blog Body ───────────────────────────────────────────────────────── --}}
<section class="blog-body-section">
    <div class="container blog-layout">

        {{-- Main article --}}
        <article class="blog-article" itemscope itemtype="https://schema.org/BlogPosting">
            <meta itemprop="datePublished" content="{{ $blog->published_at?->toIso8601String() }}">
            <meta itemprop="dateModified"  content="{{ $blog->updated_at->toIso8601String() }}">
            <meta itemprop="author"        content="{{ $blog->author?->name ?? 'MyManaliTrip Team' }}">

            {{-- Social share buttons --}}
            <div class="share-bar top">
                <span class="share-label">Share:</span>
                <a href="https://wa.me/?text={{ urlencode($blog->title . ' - ' . route('blog.show', $blog->slug)) }}"
                   target="_blank" class="share-btn whatsapp" title="Share on WhatsApp">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $blog->slug)) }}"
                   target="_blank" class="share-btn facebook" title="Share on Facebook">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $blog->slug)) }}&text={{ urlencode($blog->title) }}"
                   target="_blank" class="share-btn twitter" title="Share on Twitter">
                    <i class="fab fa-twitter"></i> Twitter
                </a>
                <button class="share-btn copy" onclick="copyLink()" title="Copy Link">
                    <i class="fas fa-link"></i> Copy Link
                </button>
            </div>

            {{-- The actual HTML content from TinyMCE --}}
            <div class="blog-content" itemprop="articleBody" id="blogContent">
                {!! $blog->content !!}
            </div>

            {{-- Tags --}}
            @if($blog->tags)
            <div class="blog-tags">
                <span class="tags-label"><i class="fas fa-tags"></i> Tags:</span>
                @foreach($blog->tags as $tag)
                <a href="{{ route('blog.index', ['tag' => $tag]) }}" class="tag-chip">{{ $tag }}</a>
                @endforeach
            </div>
            @endif

            {{-- Bottom share --}}
            <div class="share-bar bottom">
                <span>Found this helpful? Share with your travel buddies! 🏔️</span>
                <a href="https://wa.me/?text={{ urlencode($blog->title . ' - ' . route('blog.show', $blog->slug)) }}"
                   target="_blank" class="btn btn-whatsapp">
                    <i class="fab fa-whatsapp"></i> Share on WhatsApp
                </a>
            </div>

            {{-- Author box --}}
            <div class="author-box">
                <div class="author-avatar">{{ substr($blog->author?->name ?? 'M', 0, 1) }}</div>
                <div class="author-info">
                    <strong>{{ $blog->author?->name ?? 'MyManaliTrip Team' }}</strong>
                    <p>Travel expert & content writer at MyManaliTrip. Passionate about Himachal Pradesh mountains, local culture, and helping travelers plan perfect trips.</p>
                </div>
            </div>
        </article>

        {{-- Sidebar --}}
        <aside class="blog-sidebar">

            {{-- CTA Booking Widget --}}
            <div class="sidebar-booking-card">
                <div class="sbc-icon">🏔️</div>
                <h3>Plan Your Manali Trip</h3>
                <p>Packages starting at just ₹6,999</p>
                <a href="{{ route('packages.index') }}" class="btn btn-primary btn-block">View All Packages</a>
                <a href="https://wa.me/919999999999?text=Hi! I read your blog and want to book a Manali trip"
                   class="btn btn-whatsapp btn-block mt-2" target="_blank">
                    <i class="fab fa-whatsapp"></i> Chat with Us
                </a>
                <ul class="sbc-trust">
                    <li>✔ Free Cancellation</li>
                    <li>✔ Pay 30% Now</li>
                    <li>✔ 10,000+ Happy Travelers</li>
                </ul>
            </div>

            {{-- Table of Contents (auto-generated by JS) --}}
            <div class="sidebar-toc" id="tocCard">
                <h4><i class="fas fa-list"></i> Table of Contents</h4>
                <ul id="tocList"></ul>
            </div>

        </aside>
    </div>
</section>

{{-- ── Related Posts ───────────────────────────────────────────────────── --}}
@if($related->count())
<section class="related-posts">
    <div class="container">
        <div class="section-header">
            <div class="label-pill">More Reads</div>
            <h2 class="section-title">Related Travel Guides</h2>
        </div>
        <div class="blog-grid three-col">
            @foreach($related as $post)
            @include('blog.partials.card', ['blog' => $post])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Final CTA ───────────────────────────────────────────────────────── --}}
<section class="cta-banner">
    <div class="container cta-inner">
        <h2>Ready to Visit Manali? ❄️</h2>
        <p>Let our travel experts plan your perfect trip. Packages for every budget.</p>
        <div class="cta-btns">
            <a href="{{ route('packages.index') }}" class="btn btn-primary">View Packages</a>
            <a href="https://wa.me/919999999999" class="btn btn-outline" target="_blank">
                <i class="fab fa-whatsapp"></i> WhatsApp Us
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// ── Auto Table of Contents ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    const content  = document.getElementById('blogContent');
    const tocList  = document.getElementById('tocList');
    const tocCard  = document.getElementById('tocCard');

    const headings = content.querySelectorAll('h2, h3');
    if (headings.length < 2) { tocCard.style.display = 'none'; return; }

    headings.forEach((heading, i) => {
        const id = 'heading-' + i;
        heading.id = id;

        const li = document.createElement('li');
        const a  = document.createElement('a');
        a.href = '#' + id;
        a.textContent = heading.textContent;
        a.className   = heading.tagName === 'H3' ? 'toc-sub' : 'toc-main';

        a.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById(id).scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        li.appendChild(a);
        tocList.appendChild(li);
    });

    // Highlight active heading on scroll
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            const id = entry.target.getAttribute('id');
            const link = tocList.querySelector(`a[href="#${id}"]`);
            if (!link) return;
            if (entry.isIntersecting) {
                tocList.querySelectorAll('a').forEach(a => a.classList.remove('active'));
                link.classList.add('active');
            }
        });
    }, { rootMargin: '0px 0px -70% 0px' });

    headings.forEach(h => observer.observe(h));
});

// ── Copy Link ────────────────────────────────────────────────────────────────
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const btn = document.querySelector('.share-btn.copy');
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        setTimeout(() => btn.innerHTML = '<i class="fas fa-link"></i> Copy Link', 2000);
    });
}
</script>
@endpush
