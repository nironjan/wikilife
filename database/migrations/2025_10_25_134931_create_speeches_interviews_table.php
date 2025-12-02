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
        Schema::create('speeches_interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->enum('type', ['speech', 'interview'])->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('location')->nullable();
            $table->date('date')->nullable();
            $table->string('url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('thumb_file_id')->nullable();
            $table->timestamps();

            $table->index('slug');
            $table->index(['person_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speeches_interviews');
    }
};
