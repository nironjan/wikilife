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
        Schema::create('entrepreneurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('slug')->unique()->nullable();
            $table->string('role')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('industry')->nullable();
            $table->date('founding_date')->nullable();
            $table->date('exit_date')->nullable();
            $table->string('investment')->nullable();
            $table->string('headquarters_location')->nullable();
            $table->enum('status', ['active', 'inactive', 'acquired', 'closed'])->default('active');
            $table->json('notable_achievements')->nullable();
            $table->foreignId('award_id')->nullable()->constrained('person_awards')->onDelete('set null');
            $table->json('award_ids')->nullable();
            $table->string('website_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['person_id', 'company_name', 'role', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrepreneurs');
    }
};
