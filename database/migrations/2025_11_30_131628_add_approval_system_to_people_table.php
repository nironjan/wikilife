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
        Schema::table('people', function (Blueprint $table) {
            // Add approval system columns
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('verified');
            $table->foreignId('verified_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('rejection_reason')->nullable()->after('verified_at');

            // Add indexes for performance
            $table->index(['approval_status', 'created_at']);
            $table->index(['verified_by']);
            $table->index(['verified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn(['approval_status', 'verified_by', 'verified_at', 'rejection_reason']);

            // Drop indexes
            $table->dropIndex(['approval_status_created_at_index']);
            $table->dropIndex(['verified_by']);
            $table->dropIndex(['verified_at']);

        });
    }
};
