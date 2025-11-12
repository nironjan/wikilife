<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback_o_t_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp_code');
            $table->foreignId('people_id')->constrained()->onDelete('cascade');
            $table->string('type')->nullable();
            $table->text('message')->nullable();
            $table->json('suggested_changes')->nullable();
            $table->string('name')->nullable();
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at');
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();

            $table->index(['email', 'otp_code', 'is_used']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_o_t_p_s');
    }
};
