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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->string('type')->default('general'); // general, support, partnership, etc.
            $table->string('status')->default('pending');
            $table->string('otp_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('attachments')->nullable(); // Store file paths if needed
            $table->timestamps();

            $table->index(['email', 'status']);
            $table->index('created_at');
        });

        Schema::create('contact_o_t_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp_code');
            $table->string('type')->default('contact');
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at');
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();

            $table->index(['email', 'is_used', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('contact_otps');
    }
};
