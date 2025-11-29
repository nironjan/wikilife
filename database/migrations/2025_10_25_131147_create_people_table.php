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
        Schema::create('people', function (Blueprint $table) {
            $table->id();

            // Basic Identity
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('full_name')->nullable();
            $table->json('nicknames')->nullable();

            // Media
            $table->string('cover_image')->nullable();
            $table->string('cover_img_caption')->nullable();
            $table->string('cover_file_id')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('profile_image_caption')->nullable();
            $table->string('profile_img_file_id')->nullable();


            // Biography
            $table->text('about')->nullable();
            $table->longText('early_life')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('birth_date')->nullable();
            $table->date('death_date')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('place_of_death')->nullable();
            $table->text('death_cause')->nullable();
            $table->integer('age')->nullable();
            $table->string('hometown')->nullable();
            $table->text('address')->nullable();

            // Background
            $table->string('state_code', 5)->nullable();
            $table->string('state_name')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('caste')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('zodiac_sign')->nullable();
            $table->string('blood_group')->nullable();

            // professional & personal data
            $table->json('professions')->nullable();
            $table->json('physical_stats')->nullable();
            $table->json('favourite_things')->nullable();
            $table->json('references')->nullable();

            // Metadata
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('like_count')->default(0);
            $table->unsignedBigInteger('comment_count')->default(0);
            $table->unsignedBigInteger('follower_count')->default(0);

            // Management
            $table->enum('status', ['active', 'inactive', 'deceased'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('verified')->default(false);

            // Audit
            $table->timestamps();

            // 🔥 OPTIMIZED INDEXES FOR BIOGRAPHY QUERIES
            $table->index(['status', 'verified', 'created_at']); // For admin listings
            $table->index(['birth_date']); // For age-based queries
            $table->index(['state_code', 'status']); // For location filtering
            $table->index(['gender', 'status']); // For demographic queries
            $table->index(['nationality', 'status']); // For nationality filtering
            $table->index(['created_at']); // For recent additions
            $table->index(['view_count']); // For popular biographies
            $table->index(['name']); // For name searches
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
