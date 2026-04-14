<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Blog listing page — SEO-friendly with pagination & category filter.
     */
    public function index(Request $request)
    {
        $category = $request->category;

        $blogs = Blog::published()
            ->when($category, fn($q) => $q->byCategory($category))
            ->when($request->tag, function ($q) use ($request) {
                $q->whereJsonContains('tags', $request->tag);
            })
            ->latest('published_at')
            ->paginate(9);

        $categories  = Blog::published()->distinct()->pluck('category');
        $featuredBlog = Blog::published()->latest('published_at')->first();

        // SEO
        $meta = [
            'title'       => 'Manali Travel Guide & Tips | MyManaliTrip Blog',
            'description' => 'Read our expert travel guides on Manali trips — itineraries, budget tips, best time to visit, things to do, and more.',
            'canonical'   => route('blog.index'),
        ];

        return view('blog.index', compact('blogs', 'categories', 'featuredBlog', 'meta', 'category'));
    }

    /**
     * Single blog post with full SEO meta, structured data, and view counting.
     */
    public function show(string $slug)
    {
        $blog = Blog::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view counter (simple, no bot-filtering in demo)
        $blog->incrementViews();

        // Related posts — same category, exclude current
        $related = Blog::published()
            ->byCategory($blog->category)
            ->where('id', '!=', $blog->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        // Structured data
        $schemaMarkup = $blog->getSchemaMarkup();

        // Breadcrumbs schema
        $breadcrumbSchema = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',         'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Travel Guide', 'item' => route('blog.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $blog->title,   'item' => route('blog.show', $blog->slug)],
            ],
        ], JSON_UNESCAPED_SLASHES);

        return view('blog.show', compact('blog', 'related', 'schemaMarkup', 'breadcrumbSchema'));
    }

    /**
     * Category listing — good for SEO long-tail keywords.
     */
    public function category(string $category)
    {
        $blogs = Blog::published()
            ->byCategory($category)
            ->latest('published_at')
            ->paginate(9);

        $meta = [
            'title'       => "{$category} | Manali Travel Guide – MyManaliTrip",
            'description' => "Read all {$category} articles on MyManaliTrip. Expert tips, itineraries and advice for your Manali trip.",
            'canonical'   => route('blog.category', $category),
        ];

        return view('blog.category', compact('blogs', 'category', 'meta'));
    }
}
