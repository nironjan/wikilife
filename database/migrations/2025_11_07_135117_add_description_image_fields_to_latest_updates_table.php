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
        Schema::table('latest_updates', function (Blueprint $table) {
            // Add description field (for short summary/excerpt)
            $table->text('description')->nullable()->after('html_content');

            // Add image fields
            $table->string('image')->nullable()->after('description');
            $table->string('image_file_id')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('latest_updates', function (Blueprint $table) {
            $table->dropForeign(['image_file_id']);
            $table->dropColumn(['description', 'image', 'image_file_id']);
        });
    }
};
