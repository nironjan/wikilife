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
        Schema::create('person_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->foreignId('related_person_id')->nullable()->constrained('people')->onDelete('cascade');
            $table->string('related_person_name')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'other'])->nullable();
            $table->enum('relation_type', ['parent', 'sibling', 'spouse', 'child', 'other']);
            $table->boolean('is_reciprocal')->default(false);
            $table->text('notes')->nullable();
            $table->year('since')->nullable();
            $table->year('until')->nullable();
            $table->year('related_person_death_year')->nullable();
            $table->timestamps();

            $table->index(['person_id', 'relation_type']);
            $table->unique(['person_id', 'related_person_id', 'relation_type'], 'unique_person_relation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_relations');
    }
};
