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
        Schema::create('sports_careers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained()->onDelete('cascade');
            $table->string('sport');
            $table->string('slug')->unique()->nullable();
            $table->string('team')->nullable();
            $table->string('position')->nullable();
            $table->date('debut_date')->nullable();
            $table->date('retirement_date')->nullable();
            $table->json('achievements')->nullable();
            $table->string('jersey_number')->nullable();
            $table->string('coach_name')->nullable();
            $table->json('award_ids')->nullable();
            $table->foreignId('award_id')->nullable()->constrained('person_awards')->onDelete('set null');
            $table->boolean('international_player')->default(false);
            $table->json('stats')->nullable();
            $table->json('notable_events')->nullable();
            $table->json('leagues_participated')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['person_id', 'sport', 'team', 'position']);
            $table->index('international_player');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sports_careers');
    }
};
