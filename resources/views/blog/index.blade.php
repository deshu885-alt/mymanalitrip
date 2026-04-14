@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])

@push('head')
<link rel="canonical" href="{{ $meta['canonical'] }}">

{{-- Blog listing schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Blog",
  "name": "MyManaliTrip Travel Guide",
  "description": "Expert travel guides, itineraries, and tips for Manali trips.",
  "url": "{{ route('blog.index') }}",
  "publisher": {
    "@type": "Organization",
    "name": "MyManaliTrip",
    "url": "https://mymanalitrip.com"
  }
}
</script>
@endpush

@section('content')

{{-- Hero --}}
<section class="page-hero">
    <div class="container">
        <div class="label-pill">Travel Resource</div>
        <h1 class="section-title">Manali Travel Guide ❄️</h1>
        <p class="section-sub">Expert tips, itineraries, budget guides & travel hacks for your perfect Manali trip</p>
    </div>
</section>

{{-- Category filter --}}
<section class="blog-filter-section">
    <div class="container">
        <div class="blog-categories">
            <a href="{{ route('blog.index') }}" class="cat-pill {{ !$category ? 'active' : '' }}">
                All Posts
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('blog.index', ['category' => $cat]) }}"
               class="cat-pill {{ $category === $cat ? 'active' : '' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Blog grid --}}
<section class="blog-listing">
    <div class="container">
        @if($blogs->count())
        <div class="blog-grid">
            @foreach($blogs as $blog)
            @include('blog.partials.card', ['blog' => $blog])
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $blogs->links() }}
        </div>
        @else
        <div class="empty-state center">
            <i class="fas fa-pen-nib"></i>
            <h3>No posts yet in this category</h3>
            <a href="{{ route('blog.index') }}" class="btn btn-primary mt-3">View All Posts</a>
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="cta-banner">
    <div class="container cta-inner">
        <h2>Ready to Book Your Manali Trip? ❄️</h2>
        <p>Packages starting ₹6,999 · Volvo · Hotels · Sightseeing · All Included</p>
        <div class="cta-btns">
            <a href="{{ route('packages.index') }}" class="btn btn-primary">View Packages</a>
            <a href="https://wa.me/919999999999" class="btn btn-outline" target="_blank">
                <i class="fab fa-whatsapp"></i> Chat With Us
            </a>
        </div>
    </div>
</section>

@endsection
