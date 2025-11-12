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
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('avatar_file_id')->nullable()->after('avatar');
            $table->string('profile_image')->nullable()->after('avatar_file_id');
            $table->string('profile_image_file_id')->nullable()->after('profile_image');
            $table->boolean('is_team_member')->default(false)->after('status');
            $table->timestamp('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'avatar',
                'avatar_file_id',
                'profile_image',
                'profile_image_file_id',
                'is_team_member',
                'last_login_at',
            ]);
        });
    }
};
