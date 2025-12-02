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
        Schema::create('person_education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->string('degree');
            $table->string('slug')->unique();
            $table->string('institution');
            $table->text('details')->nullable();
            $table->year('start_year')->nullable();
            $table->year('end_year')->nullable();
            $table->string('field_of_study')->nullable();
            $table->string('grade_or_honors')->nullable();
            $table->string('location')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['person_id', 'start_year', 'end_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_education');
    }
};
