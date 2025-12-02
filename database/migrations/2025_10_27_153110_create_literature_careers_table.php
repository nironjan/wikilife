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
        Schema::create('literature_careers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->json('award_ids');

            // Role / appearance
            $table->string('role'); // host, writer
            $table->string('slug')->unique();
            $table->string('media_type')->unique()->nullable(); // TV, Print, digital, portal
            $table->date('start_date')->nullable(); // career start
            $table->date('end_date')->nullable(); // career end

            // Work details
            $table->string('title')->nullable(); // book name, famous article
            $table->string('work_type')->nullable(); // book, news, blog article
            $table->year('publishing_year')->nullable(); // year
            $table->string('language')->nullable(); // language
            $table->string('genre')->nullable(); //
            $table->string('isbn')->nullable();
            $table->string('cover_image')->nullable(); //iamge url
            $table->string('img_file_id')->nullable();
            $table->string('link')->nullable(); // reference

            // Notes & Verification
            $table->longText('description')->nullable(); // career notes
            $table->boolean('is_verified')->default(false); // boolean
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['person_id', 'work_type', 'publishing_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('literature_careers');
    }
};
