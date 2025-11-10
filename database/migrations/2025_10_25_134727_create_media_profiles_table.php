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
        Schema::create('media_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->string('official_website')->nullable();
            $table->text('description')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('banner_file_id')->nullable();
            $table->string('official_email')->nullable();
            $table->string('signature_url')->nullable();
            $table->string('signature_file_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_profiles');
    }
};
