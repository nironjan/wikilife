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
        Schema::create('person_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->string('award_name');
            $table->string('slug')->unique()->nullable();
            $table->text('description')->nullable();
            $table->date('awarded_at')->nullable();
            $table->string('award_image')->nullable();
            $table->string('image_caption')->nullable();
            $table->string('award_image_file_id')->nullable();
            $table->string('category')->nullable();
            $table->string('organization')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['person_id', 'awarded_at']);
            $table->index(['organization']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_awards');
    }
};
