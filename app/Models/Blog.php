<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Blog extends Model
{
    protected $fillable = [
        'title', 'slug', 'meta_title', 'meta_description', 'meta_keywords',
        'og_image', 'canonical_url', 'schema_type',
        'excerpt', 'content', 'featured_image', 'featured_image_alt',
        'category', 'tags', 'reading_time', 'status', 'published_at',
        'author_id', 'views',
    ];

    protected $casts = [
        'tags'         => 'array',
        'published_at' => 'datetime',
    ];

    // ── Auto-generate slug and reading time ──────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Blog $blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
            $blog->reading_time = self::estimateReadingTime($blog->content);

            if (empty($blog->meta_title)) {
                $blog->meta_title = $blog->title . ' | MyManaliTrip';
            }
            if (empty($blog->meta_description) && $blog->excerpt) {
                $blog->meta_description = Str::limit(strip_tags($blog->excerpt), 155);
            }
        });

        static::updating(function (Blog $blog) {
            $blog->reading_time = self::estimateReadingTime($blog->content);
        });
    }

    public static function estimateReadingTime(string $html): int
    {
        $wordCount = str_word_count(strip_tags($html));
        return (int) max(1, ceil($wordCount / 200));
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', 'published')
                 ->where('published_at', '<=', now());
    }

    public function scopeByCategory(Builder $q, string $category): Builder
    {
        return $q->where('category', $category);
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function getUrlAttribute(): string
    {
        return route('blog.show', $this->slug);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Build JSON-LD structured data for this blog post.
     */
    public function getSchemaMarkup(): string
    {
        $schema = [
            '@context'      => 'https://schema.org',
            '@type'         => $this->schema_type ?? 'BlogPosting',
            'headline'      => $this->meta_title ?? $this->title,
            'description'   => $this->meta_description ?? $this->excerpt,
            'image'         => $this->og_image
                                    ? asset('storage/' . $this->og_image)
                                    : asset('images/og-default.jpg'),
            'author'        => [
                '@type' => 'Person',
                'name'  => $this->author?->name ?? 'MyManaliTrip Team',
            ],
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => 'MyManaliTrip',
                'logo'  => ['@type' => 'ImageObject', 'url' => asset('images/logo.png')],
            ],
            'datePublished' => $this->published_at?->toIso8601String(),
            'dateModified'  => $this->updated_at->toIso8601String(),
            'url'           => $this->url,
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $this->url],
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Categories available for blogs.
     */
    public static function categories(): array
    {
        return [
            'Travel Guide',
            'Itinerary',
            'Budget Tips',
            'Things To Do',
            'Best Time To Visit',
            'Accommodation',
            'Adventure',
            'Food & Cafes',
        ];
    }
}
