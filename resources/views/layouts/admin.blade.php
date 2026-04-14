<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — MyManaliTrip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('head')
</head>
<body class="admin-body">

<div class="admin-layout">

    {{-- Sidebar --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-logo">
            <span class="logo-icon">🏔️</span>
            <div>
                <div class="logo-name">MyManaliTrip</div>
                <div class="logo-sub">Admin Panel</div>
            </div>
        </div>

        <nav class="admin-nav">
            <div class="nav-section-label">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Dashboard
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="admin-nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> Bookings
                {{-- Pending count badge --}}
                @php $pending = \App\Models\Booking::where('status','pending')->count(); @endphp
                @if($pending) <span class="nav-badge">{{ $pending }}</span> @endif
            </a>
            <a href="{{ route('admin.packages.index') }}" class="admin-nav-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                <i class="fas fa-mountain"></i> Packages
            </a>

            <div class="nav-section-label">Content</div>
            <a href="{{ route('admin.blogs.index') }}" class="admin-nav-item {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                <i class="fas fa-pen-nib"></i> Blog Posts
            </a>
            <a href="{{ route('admin.blogs.create') }}" class="admin-nav-item {{ request()->routeIs('admin.blogs.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i> New Blog Post
            </a>

            <div class="nav-section-label">Settings</div>
            <a href="{{ route('home') }}" target="_blank" class="admin-nav-item">
                <i class="fas fa-external-link-alt"></i> View Website
            </a>
        </nav>

        <div class="admin-sidebar-footer">
            <div class="admin-user">
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">Administrator</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout-btn" title="Logout"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <main class="admin-main">
        <div class="admin-topbar">
            <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="topbar-breadcrumb">
                @yield('breadcrumb', 'Admin')
            </div>
            <div class="topbar-right">
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-pen"></i> New Blog
                </a>
            </div>
        </div>

        <div class="admin-content">
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script src="{{ asset('js/admin.js') }}"></script>
@stack('scripts')
<script>
function toggleSidebar() {
    document.getElementById('adminSidebar').classList.toggle('collapsed');
}
</script>
</body>
</html>
