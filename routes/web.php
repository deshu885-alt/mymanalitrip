<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PackageController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

/*
|--------------------------------------------------------------------------
| Frontend Routes — SEO-Friendly URLs
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Packages
Route::get('/manali-tour-packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/manali-tour-packages/{slug}', [PackageController::class, 'show'])->name('packages.show');

// Booking flow
Route::get('/book/{package:slug}', [BookingController::class, 'show'])->name('booking.show');
Route::post('/book/{package:slug}', [BookingController::class, 'store'])->name('booking.store');
Route::post('/booking/create-order', [BookingController::class, 'createOrder'])->name('booking.create-order');
Route::post('/booking/verify-payment', [BookingController::class, 'verifyPayment'])->name('booking.verify-payment');
Route::get('/booking/confirmation/{ref}', [BookingController::class, 'confirmation'])->name('booking.confirmation');
Route::get('/track-booking', [BookingController::class, 'track'])->name('booking.track');
Route::post('/track-booking', [BookingController::class, 'trackLookup'])->name('booking.track-lookup');

// Blog — SEO-optimised URLs
Route::get('/manali-travel-guide', [BlogController::class, 'index'])->name('blog.index');
Route::get('/manali-travel-guide/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/manali-travel-guide/category/{category}', [BlogController::class, 'category'])->name('blog.category');

// Static pages
Route::get('/cancellation-policy', [PageController::class, 'show'])->defaults('page', 'cancellation-policy')->name('page.cancellation');
Route::get('/refund-policy', [PageController::class, 'show'])->defaults('page', 'refund-policy')->name('page.refund');
Route::get('/terms-and-conditions', [PageController::class, 'show'])->defaults('page', 'terms')->name('page.terms');
Route::get('/privacy-policy', [PageController::class, 'show'])->defaults('page', 'privacy-policy')->name('page.privacy');
Route::get('/about-us', [PageController::class, 'show'])->defaults('page', 'about')->name('page.about');
Route::get('/contact-us', [PageController::class, 'contact'])->name('page.contact');

// Helper to use in layout
Route::get('/page/{page}', [PageController::class, 'show'])->name('page');

// Sitemap.xml — important for SEO
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Admin Routes — Protected by auth + admin middleware
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ── Blog CRUD ──────────────────────────────────────────────────────────
    Route::get('/blogs', [AdminBlogController::class, 'index'])->name('blogs.index');
    Route::get('/blogs/create', [AdminBlogController::class, 'create'])->name('blogs.create');
    Route::post('/blogs', [AdminBlogController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [AdminBlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [AdminBlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [AdminBlogController::class, 'destroy'])->name('blogs.destroy');

    // TinyMCE image upload
    Route::post('/blogs/upload-image', [AdminBlogController::class, 'uploadImage'])->name('blogs.upload-image');

    // ── Packages CRUD ──────────────────────────────────────────────────────
    Route::resource('packages', AdminPackageController::class);

    // ── Bookings ───────────────────────────────────────────────────────────
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::get('/bookings/export', [AdminBookingController::class, 'export'])->name('bookings.export');
});
