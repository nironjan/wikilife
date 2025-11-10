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
        Schema::create('politicians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->string('political_party')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('constituency')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('position')->nullable();
            $table->date('tenure_start')->nullable();
            $table->date('tenure_end')->nullable();
            $table->text('political_journey')->nullable();
            $table->text('notable_achievements')->nullable();
            $table->text('major_initiatives')->nullable();
            $table->json('memberships')->nullable();
            $table->enum('office_type', ['Local', 'State', 'National', 'International'])->nullable();
            $table->json('award_ids')->nullable();
            $table->foreignId('award_id')->nullable()->constrained('person_awards')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->string('source_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['person_id', 'political_party', 'constituency', 'position'], 'unique_politician_record');
            $table->index(['political_party', 'constituency', 'position'], 'politician_search_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('politicians');
    }
};
