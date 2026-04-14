<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Package;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPackages = Package::featured()->orderBy('sort_order')->take(6)->get();
        $honeymoonPackages = Package::active()->byType('honeymoon')->take(3)->get();
        $latestBlogs = Blog::published()->latest('published_at')->take(3)->get();

        return view('home.index', compact('featuredPackages', 'honeymoonPackages', 'latestBlogs'));
    }

    /**
     * Sitemap.xml — crucial for SEO.
     * Lists all pages, packages, and published blogs.
     */
    public function sitemap()
    {
        $packages = Package::active()->select('slug', 'updated_at')->get();
        $blogs    = Blog::published()->select('slug', 'updated_at', 'published_at')->get();

        $content = view('sitemap', compact('packages', 'blogs'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * robots.txt — tells Google what to crawl.
     */
    public function robots()
    {
        $content = "User-agent: *\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /booking/\n";
        $content .= "Disallow: /track-booking\n";
        $content .= "Allow: /\n\n";
        $content .= "Sitemap: " . route('sitemap') . "\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
