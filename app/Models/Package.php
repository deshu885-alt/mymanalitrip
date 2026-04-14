<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Package extends Model
{
    protected $fillable = [
        'name', 'slug', 'meta_title', 'meta_description', 'meta_keywords', 'og_image',
        'type', 'duration', 'nights', 'days',
        'excerpt', 'overview', 'itinerary', 'inclusions', 'exclusions',
        'places_covered', 'highlights', 'activities',
        'price', 'price_child', 'price_label', 'discount_percent', 'seasonal_pricing',
        'featured_image', 'gallery',
        'starting_city', 'departure_type', 'departure_days',
        'rating', 'reviews_count',
        'is_featured', 'is_bestseller', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'inclusions'      => 'array',
        'exclusions'      => 'array',
        'places_covered'  => 'array',
        'highlights'      => 'array',
        'activities'      => 'array',
        'gallery'         => 'array',
        'departure_days'  => 'array',
        'seasonal_pricing'=> 'array',
        'is_featured'     => 'boolean',
        'is_bestseller'   => 'boolean',
        'is_active'       => 'boolean',
    ];

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true)->where('is_active', true);
    }

    public function scopeByType(Builder $q, string $type): Builder
    {
        return $q->where('type', $type);
    }

    // ── Pricing helpers ──────────────────────────────────────────────────────

    /**
     * Get price for a specific travel month (1–12).
     * Falls back to base price if no seasonal rule matches.
     */
    public function getPriceForMonth(int $month): float
    {
        if ($this->seasonal_pricing) {
            foreach ($this->seasonal_pricing as $rule) {
                if (in_array($month, $rule['months'] ?? [])) {
                    return (float) $rule['price'];
                }
            }
        }
        return (float) $this->price;
    }

    public function getDiscountedPrice(): float
    {
        if ($this->discount_percent > 0) {
            return $this->price - ($this->price * $this->discount_percent / 100);
        }
        return (float) $this->price;
    }

    public function getAdvanceAmount(float $price): float
    {
        return round($price * 0.30);
    }

    // ── Schema markup ────────────────────────────────────────────────────────

    public function getSchemaMarkup(): string
    {
        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'TouristTrip',
            'name'        => $this->name,
            'description' => $this->meta_description ?? $this->excerpt,
            'image'       => $this->featured_image ? asset('storage/'.$this->featured_image) : null,
            'url'         => route('packages.show', $this->slug),
            'offers'      => [
                '@type'         => 'Offer',
                'price'         => $this->price,
                'priceCurrency' => 'INR',
                'availability'  => 'https://schema.org/InStock',
            ],
            'provider'    => [
                '@type' => 'TravelAgency',
                'name'  => 'MyManaliTrip',
                'url'   => 'https://mymanalitrip.com',
            ],
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
