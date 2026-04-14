@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- Stats Grid --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;color:#d97706;">📦</div>
        <div class="stat-info">
            <div class="stat-value">{{ number_format($stats['total_bookings']) }}</div>
            <div class="stat-label">Total Bookings</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1fae5;color:#065f46;">✅</div>
        <div class="stat-info">
            <div class="stat-value">{{ number_format($stats['confirmed']) }}</div>
            <div class="stat-label">Confirmed</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;color:#991b1b;">⏳</div>
        <div class="stat-info">
            <div class="stat-value">{{ number_format($stats['pending']) }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;">💰</div>
        <div class="stat-info">
            <div class="stat-value">₹{{ number_format($stats['revenue_month']) }}</div>
            <div class="stat-label">This Month Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f3e8ff;color:#7e22ce;">✍️</div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['published_blogs'] }}</div>
            <div class="stat-label">Published Blogs</div>
            <div class="stat-sub">{{ $stats['total_blogs'] - $stats['published_blogs'] }} drafts</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fce7f3;color:#9d174d;">🏔️</div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['active_packages'] }}</div>
            <div class="stat-label">Active Packages</div>
        </div>
    </div>
</div>

<div class="dashboard-grid">

    {{-- Recent Bookings --}}
    <div class="admin-card" style="grid-column: span 2;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border);">
            <h3 style="font-size:1rem;font-weight:700;">Recent Bookings</h3>
            <a href="{{ route('admin.bookings.index') }}" style="font-size:.85rem;color:var(--saffron);font-weight:600;">View All →</a>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Booking Ref</th>
                    <th>Customer</th>
                    <th>Package</th>
                    <th>Travel Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($recentBookings as $b)
            <tr>
                <td><a href="{{ route('admin.bookings.show', $b) }}" style="color:var(--saffron);font-family:monospace;font-weight:700;">{{ $b->booking_ref }}</a></td>
                <td>
                    <div class="table-title">{{ $b->full_name }}</div>
                    <div class="table-sub">{{ $b->phone }}</div>
                </td>
                <td><div style="font-size:.85rem;">{{ Str::limit($b->package?->name, 30) }}</div></td>
                <td>{{ $b->travel_date->format('d M Y') }}</td>
                <td>
                    <strong>₹{{ number_format($b->advance_paid) }}</strong>
                    <div class="table-sub">of ₹{{ number_format($b->total_amount) }}</div>
                </td>
                <td>{!! $b->status_badge !!}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="empty-state">No bookings yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Top Packages --}}
    <div class="admin-card">
        <div style="padding:20px 24px;border-bottom:1px solid var(--border);">
            <h3 style="font-size:1rem;font-weight:700;">Top Packages</h3>
        </div>
        <div style="padding:16px;">
            @foreach($topPackages as $i => $pkg)
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f1f5f9;">
                <div style="width:28px;height:28px;background:var(--saffron);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;flex-shrink:0;">{{ $i+1 }}</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:.88rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $pkg->name }}</div>
                    <div style="font-size:.75rem;color:var(--muted);">{{ $pkg->bookings_count }} bookings</div>
                </div>
                <div style="font-size:.85rem;font-weight:700;color:var(--navy);">₹{{ number_format($pkg->price) }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Draft Blogs --}}
    <div class="admin-card">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border);">
            <h3 style="font-size:1rem;font-weight:700;">📝 Draft Blogs</h3>
            <a href="{{ route('admin.blogs.create') }}" style="font-size:.82rem;color:var(--saffron);font-weight:700;">+ New Blog</a>
        </div>
        <div style="padding:12px 16px;">
            @forelse($draftBlogs as $blog)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;">
                <div>
                    <div style="font-size:.88rem;font-weight:600;">{{ Str::limit($blog->title, 36) }}</div>
                    <div style="font-size:.72rem;color:var(--muted);">{{ $blog->category }}</div>
                </div>
                <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-sm btn-outline">Edit</a>
            </div>
            @empty
            <p style="color:var(--muted);font-size:.88rem;padding:16px 0;">No drafts. All blogs published! 🎉</p>
            @endforelse
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="admin-card" style="grid-column: span 2;">
        <div style="padding:20px 24px;border-bottom:1px solid var(--border);">
            <h3 style="font-size:1rem;font-weight:700;">Quick Actions</h3>
        </div>
        <div style="padding:20px 24px;display:flex;gap:12px;flex-wrap:wrap;">
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
                <i class="fas fa-pen"></i> Write New Blog
            </a>
            <a href="{{ route('admin.packages.create') }}" class="btn btn-outline" style="color:var(--navy);border-color:var(--border);">
                <i class="fas fa-plus"></i> Add Package
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline" style="color:var(--navy);border-color:var(--border);">
                <i class="fas fa-ticket-alt"></i> View Bookings
            </a>
            <a href="{{ route('admin.bookings.export') }}" class="btn btn-outline" style="color:var(--navy);border-color:var(--border);">
                <i class="fas fa-download"></i> Export Bookings (Excel)
            </a>
            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline" style="color:var(--navy);border-color:var(--border);">
                <i class="fas fa-external-link-alt"></i> View Website
            </a>
        </div>
    </div>

</div>

<style>
.stats-grid { display:grid; grid-template-columns:repeat(6,1fr); gap:16px; margin-bottom:24px; }
.stat-card { background:#fff; border-radius:12px; border:1px solid var(--border); padding:20px; display:flex; align-items:center; gap:14px; }
.stat-icon { width:46px; height:46px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; }
.stat-value { font-size:1.4rem; font-weight:800; color:var(--navy); line-height:1; }
.stat-label { font-size:.75rem; color:var(--muted); margin-top:4px; }
.stat-sub   { font-size:.7rem; color:var(--muted); }
.dashboard-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
.dashboard-grid > .admin-card:first-child { grid-column: span 4; }
.dashboard-grid > .admin-card:nth-child(4) { grid-column: span 4; }
@media(max-width:1200px) { .stats-grid { grid-template-columns:repeat(3,1fr); } }
@media(max-width:768px)  { .stats-grid { grid-template-columns:1fr 1fr; } .dashboard-grid { grid-template-columns:1fr; } .dashboard-grid > .admin-card { grid-column: span 1 !important; } }
</style>
@endsection
