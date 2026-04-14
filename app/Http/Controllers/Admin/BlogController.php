<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BlogController extends Controller
{
    // ── Index ────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $blogs = Blog::with('author')
            ->when($request->search, fn($q) => $q->where('title', 'like', '%'.$request->search.'%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->latest('published_at')
            ->paginate(15);

        return view('admin.blogs.index', compact('blogs'));
    }

    // ── Create ───────────────────────────────────────────────────────────────

    public function create()
    {
        $categories = Blog::categories();
        return view('admin.blogs.form', compact('categories'));
    }

    // ── Store ────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|unique:blogs,slug',
            'meta_title'       => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords'    => 'nullable|string|max:255',
            'canonical_url'    => 'nullable|url',
            'schema_type'      => 'nullable|string',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_image_alt'=> 'nullable|string|max:125',
            'og_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category'         => 'required|string',
            'tags'             => 'nullable|string',
            'status'           => 'required|in:draft,published,scheduled',
            'published_at'     => 'nullable|date',
        ]);

        // Slug
        $validated['slug'] = Str::slug($validated['slug'] ?? $validated['title']);

        // Tags — comma separated → array
        $validated['tags'] = $validated['tags']
            ? array_map('trim', explode(',', $validated['tags']))
            : [];

        // Featured image
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blogs/images', 'public');
        }

        // OG image
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')
                ->store('blogs/og', 'public');
        }

        // Published at
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $validated['author_id'] = auth()->id();

        $blog = Blog::create($validated);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', "Blog \"{$blog->title}\" published successfully!");
    }

    // ── Edit ─────────────────────────────────────────────────────────────────

    public function edit(Blog $blog)
    {
        $categories = Blog::categories();
        return view('admin.blogs.form', compact('blog', 'categories'));
    }

    // ── Update ───────────────────────────────────────────────────────────────

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => "nullable|string|unique:blogs,slug,{$blog->id}",
            'meta_title'       => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords'    => 'nullable|string|max:255',
            'canonical_url'    => 'nullable|url',
            'schema_type'      => 'nullable|string',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_image_alt'=> 'nullable|string|max:125',
            'og_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category'         => 'required|string',
            'tags'             => 'nullable|string',
            'status'           => 'required|in:draft,published,scheduled',
            'published_at'     => 'nullable|date',
        ]);

        if ($request->hasFile('featured_image')) {
            Storage::disk('public')->delete($blog->featured_image);
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blogs/images', 'public');
        }

        if ($request->hasFile('og_image')) {
            Storage::disk('public')->delete($blog->og_image);
            $validated['og_image'] = $request->file('og_image')
                ->store('blogs/og', 'public');
        }

        $validated['tags'] = $validated['tags']
            ? array_map('trim', explode(',', $validated['tags']))
            : [];

        if ($validated['status'] === 'published' && empty($validated['published_at']) && !$blog->published_at) {
            $validated['published_at'] = now();
        }

        $blog->update($validated);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog updated successfully!');
    }

    // ── Destroy ──────────────────────────────────────────────────────────────

    public function destroy(Blog $blog)
    {
        Storage::disk('public')->delete([$blog->featured_image, $blog->og_image]);
        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog deleted.');
    }

    // ── Image Upload for TinyMCE / Editor ────────────────────────────────────

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $path = $request->file('file')->store('blogs/editor', 'public');

        // TinyMCE expects { location: "url" }
        return response()->json([
            'location' => Storage::disk('public')->url($path),
        ]);
    }
}
