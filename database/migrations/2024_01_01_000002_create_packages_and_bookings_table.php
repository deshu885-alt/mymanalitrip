<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_image')->nullable();

            // Package details
            $table->string('type');               // budget, honeymoon, family, adventure, winter, group, luxury
            $table->string('duration');           // e.g. "3 Nights / 4 Days"
            $table->integer('nights');
            $table->integer('days');
            $table->text('excerpt')->nullable();
            $table->longText('overview')->nullable();
            $table->longText('itinerary')->nullable();   // JSON or HTML
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('places_covered')->nullable();
            $table->json('highlights')->nullable();
            $table->json('activities')->nullable();

            // Pricing
            $table->decimal('price', 10, 2);
            $table->decimal('price_child', 10, 2)->nullable();
            $table->string('price_label')->default('per person'); // per person / per couple
            $table->decimal('discount_percent', 5, 2)->default(0);

            // Seasonal pricing (JSON array: [{months:[12,1], price:8999}, ...])
            $table->json('seasonal_pricing')->nullable();

            // Images
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();

            // Departure
            $table->string('starting_city')->default('Delhi');
            $table->string('departure_type')->default('daily'); // daily / fixed
            $table->json('departure_days')->nullable();         // [5,6] = Fri,Sat

            // Reviews
            $table->decimal('rating', 3, 2)->default(4.8);
            $table->unsignedInteger('reviews_count')->default(0);

            // Flags
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_ref')->unique();   // e.g. MMT-2024-0001
            $table->foreignId('package_id')->constrained('packages');

            // Traveller
            $table->string('full_name');
            $table->string('phone');
            $table->string('email');
            $table->string('city')->nullable();
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->string('room_type')->default('Standard'); // Standard / Deluxe / Luxury
            $table->string('pickup_point')->nullable();
            $table->text('special_requests')->nullable();

            // Travel
            $table->date('travel_date');

            // Payment
            $table->decimal('total_amount', 10, 2);
            $table->decimal('advance_paid', 10, 2)->default(0);
            $table->decimal('balance_due', 10, 2)->default(0);
            $table->string('payment_type')->default('partial'); // partial / full
            $table->string('payment_status')->default('pending'); // pending / partial / paid
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();

            // Status
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('admin_notes')->nullable();

            // Notifications
            $table->boolean('email_sent')->default(false);
            $table->boolean('whatsapp_sent')->default(false);

            $table->timestamps();

            $table->index(['status', 'travel_date']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('packages');
    }
};
