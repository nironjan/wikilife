<?php

use App\Models\SpeechesInterview;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('speeches_interviews', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');

            // Add index for better performance
            $table->index('slug');
            $table->index(['person_id', 'slug']);

        });

        $this->generateSlugsForExistingRecords();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('speeches_interviews', function (Blueprint $table) {
            $table->dropIndex(['person_id', 'slug']);
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }

    /**
     * Generate slugs for existing records
     */
    private function generateSlugsForExistingRecords(): void
    {
        $speechesInterviews = SpeechesInterview::all();

        foreach ($speechesInterviews as $item) {
            $baseSlug = Str::slug($item->title);
            $slug = $baseSlug;
            $counter = 1;

            // Check if slug already exists and make it unique
            while (SpeechesInterview::where('slug', $slug)
                    ->where('id', '!=', $item->id)
                    ->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $item->slug = $slug;
            $item->save();
        }
    }
};
