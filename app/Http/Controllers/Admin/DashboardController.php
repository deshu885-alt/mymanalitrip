<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Blog;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Key stats ─────────────────────────────────────────────────────────
        $stats = [
            'total_bookings'    => Booking::count(),
            'confirmed'         => Booking::where('status', 'confirmed')->count(),
            'pending'           => Booking::where('status', 'pending')->count(),
            'revenue_total'     => Booking::whereIn('status', ['confirmed', 'completed'])->sum('advance_paid'),
            'revenue_month'     => Booking::whereIn('status', ['confirmed', 'completed'])
                                          ->whereMonth('created_at', now()->month)
                                          ->sum('advance_paid'),
            'total_blogs'       => Blog::count(),
            'published_blogs'   => Blog::where('status', 'published')->count(),
            'active_packages'   => Package::where('is_active', true)->count(),
        ];

        // ── Recent bookings ────────────────────────────────────────────────────
        $recentBookings = Booking::with('package')
            ->latest()
            ->take(8)
            ->get();

        // ── Monthly revenue chart data (last 6 months) ─────────────────────────
        $chartData = Booking::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(advance_paid) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->whereIn('status', ['confirmed', 'completed'])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(fn($r) => [
                'label' => Carbon::createFromDate($r->year, $r->month, 1)->format('M'),
                'total' => (float) $r->total,
            ]);

        // ── Top packages by bookings ───────────────────────────────────────────
        $topPackages = Package::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        // ── Blog draft reminders ───────────────────────────────────────────────
        $draftBlogs = Blog::where('status', 'draft')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'recentBookings', 'chartData', 'topPackages', 'draftBlogs'
        ));
    }
}
