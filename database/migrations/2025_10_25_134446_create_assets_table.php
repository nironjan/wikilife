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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->string('currency')->nullable();
            $table->decimal('income', 15,2)->nullable();
            $table->string('income_source')->nullable();
            $table->decimal('current_assets', 15,2)->nullable();
            $table->decimal('net_worth', 15,2)->nullable();
            $table->integer('year_estimated')->nullable();
            $table->json('references')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
