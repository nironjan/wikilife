<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            // --- Basic Info ---
            $table->string('site_name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('site_email')->nullable();
            $table->string('site_phone')->nullable();
            $table->string('site_address')->nullable();

            // --- Branding ---
            $table->string('logo_path')->nullable();
             $table->string('logo_path_file_id')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('favicon_path_file_id')->nullable();
            $table->string('default_image_path')->nullable();
            $table->string('default_image_file_id')->nullable();

            // --- SEO Meta ---
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // --- Social Media ---
            $table->json('social_links')->nullable(); // e.g. {"facebook": "...", "twitter": "...", "linkedin": "...", ...}

            // --- Open Graph / Twitter Meta ---
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_image_file_id')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            $table->string('twitter_image_file_id')->nullable();

            // --- Analytics / Scripts ---
            $table->json('header_scripts')->nullable(); // for GA, Tag Manager, etc.
            $table->json('footer_scripts')->nullable();

            // --- Localization & Preferences ---
            $table->string('language')->default('en');
            $table->string('timezone')->default('Asia/Kolkata');
            $table->string('currency')->default('INR');
            $table->string('date_format')->default('Y-m-d');

            // --- Maintenance Mode ---
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();

            // --- SEO & Performance Flags ---
            $table->boolean('index_site')->default(true); // control robots.txt or meta noindex
            $table->boolean('lazy_load_images')->default(true);

            // --- Extra & Versioning ---
            $table->json('extra')->nullable(); // for future extensibility
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
