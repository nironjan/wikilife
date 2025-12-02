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
        Schema::create('filmographies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->string('movie_title');
            $table->string('slug')->unique();
            $table->date('release_date')->nullable();
            $table->string('role')->nullable();
            $table->enum('profession_type', ['actor', 'director', 'producer', 'writer', 'cinematographer', 'editor', 'composer', 'other'])->default('actor');

            // Industry details
            $table->string('industry')->nullable();
            $table->foreignId('director_id')->nullable()->constrained('people')->onDelete('set null');
            $table->string('unlisted_director_name')->nullable();
            $table->string('production_company')->nullable();

            // Flexible data
            $table->json('genres')->nullable();
            $table->text('description')->nullable();

            // financials and awards
            $table->string('box_office_collection')->nullable();
            $table->json('award_ids')->nullable();
            $table->foreignId('person_award_id')->nullable()->constrained('person_awards')->onDelete('set null');

            // Management
            $table->boolean('is_verified')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Indexing
            $table->index(['person_id', 'director_id', 'release_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filmographies');
    }
};
