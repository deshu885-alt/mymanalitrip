<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();

            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_image')->nullable();       // Open Graph image
            $table->string('canonical_url')->nullable();  // Canonical URL
            $table->string('schema_type')->default('Article'); // Article / BlogPosting / FAQPage

            // Content
            $table->string('excerpt', 500)->nullable();
            $table->longText('content');                  // HTML from rich editor
            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable();

            // Organisation
            $table->string('category')->default('Travel Guide');
            $table->json('tags')->nullable();
            $table->unsignedInteger('reading_time')->default(5); // minutes

            // Status & Scheduling
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->timestamp('published_at')->nullable();

            // Author
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');

            // Analytics (basic)
            $table->unsignedBigInteger('views')->default(0);

            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
