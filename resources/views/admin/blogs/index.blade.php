@extends('layouts.admin')
@section('title', 'Manage Blogs')

@section('content')
<div class="admin-page-header">
    <div>
        <h1>Blog Posts</h1>
        <p class="text-muted">{{ $blogs->total() }} total posts — manage content & SEO</p>
    </div>
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Blog Post
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="filter-bar">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search blog titles..." class="form-control filter-search">
    <select name="status" class="form-control filter-select" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>✅ Published</option>
        <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>📝 Draft</option>
        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>🕐 Scheduled</option>
    </select>
    <select name="category" class="form-control filter-select" onchange="this.form.submit()">
        <option value="">All Categories</option>
        @foreach(\App\Models\Blog::categories() as $cat)
        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-outline">Search</button>
    @if(request()->hasAny(['search','status','category']))
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-ghost">Clear</a>
    @endif
</form>

{{-- Flash message --}}
@if(session('success'))
<div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

{{-- Table --}}
<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Views</th>
                <th>Published</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($blogs as $blog)
        <tr>
            <td>
                <div class="table-title">{{ $blog->title }}</div>
                <div class="table-slug">{{ $blog->slug }}</div>
                <div class="seo-mini-bar">
                    <span class="reading-time"><i class="fas fa-clock"></i> {{ $blog->reading_time }} min read</span>
                    @if($blog->meta_description)
                    <span class="seo-ok"><i class="fas fa-check-circle"></i> Meta</span>
                    @else
                    <span class="seo-warn"><i class="fas fa-exclamation-circle"></i> No Meta</span>
                    @endif
                    @if($blog->featured_image)
                    <span class="seo-ok"><i class="fas fa-image"></i> Image</span>
                    @endif
                </div>
            </td>
            <td><span class="badge badge-outline">{{ $blog->category }}</span></td>
            <td>
                @if($blog->status === 'published')
                    <span class="badge badge-green">Published</span>
                @elseif($blog->status === 'scheduled')
                    <span class="badge badge-blue">Scheduled</span>
                @else
                    <span class="badge badge-gray">Draft</span>
                @endif
            </td>
            <td><span class="views-count">{{ number_format($blog->views) }}</span></td>
            <td>
                {{ $blog->published_at ? $blog->published_at->format('d M Y') : '—' }}
                <div class="table-sub">{{ $blog->author?->name }}</div>
            </td>
            <td>
                <div class="action-group">
                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-sm btn-outline" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                    @if($blog->status === 'published')
                    <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="btn btn-sm btn-ghost" title="View Live">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    @endif
                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST"
                          onsubmit="return confirm('Delete this blog post?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="empty-state">
                <i class="fas fa-pen-nib"></i>
                <p>No blog posts yet. <a href="{{ route('admin.blogs.create') }}">Write your first post →</a></p>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">
        {{ $blogs->withQueryString()->links() }}
    </div>
</div>
@endsection
