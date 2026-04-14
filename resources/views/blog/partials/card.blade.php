<article class="blog-card">
    <a href="{{ route('blog.show', $blog->slug) }}" class="blog-card-image-wrap">
        @if($blog->featured_image)
        <img src="{{ Storage::url($blog->featured_image) }}"
             alt="{{ $blog->featured_image_alt ?? $blog->title }}"
             loading="lazy"
             class="blog-card-image">
        @else
        <div class="blog-card-image placeholder-image">
            <span>🏔️</span>
        </div>
        @endif
        <span class="blog-card-category">{{ $blog->category }}</span>
    </a>

    <div class="blog-card-body">
        <div class="blog-card-meta">
            <span><i class="fas fa-calendar"></i> {{ $blog->published_at?->format('d M Y') }}</span>
            <span><i class="fas fa-clock"></i> {{ $blog->reading_time }} min read</span>
        </div>

        <h2 class="blog-card-title">
            <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a>
        </h2>

        @if($blog->excerpt)
        <p class="blog-card-excerpt">{{ Str::limit($blog->excerpt, 120) }}</p>
        @endif

        <a href="{{ route('blog.show', $blog->slug) }}" class="blog-card-link">
            Read More <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</article>
