<?php

namespace App\Models;

use App\Services\ImageKitService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeechesInterview extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'type',
        'title',
        'description',
        'location',
        'date',
        'url',
        'thumbnail_url',
        'thumb_file_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // =========== RELATIONS ============
    public function person()
    {
        return $this->belongsTo(People::class);
    }

    // ============ SCOPES ==============
    public function scopeSpeeches(Builder $query)
    {
        return $query->where('type', 'speech');
    }

    public function scopeInterviews(Builder $query)
    {
        return $query->where('type', 'interview');
    }

    public function scopeRecent(Builder $query, int $days = 30)
    {
        return $query->where('date', '>=', now()->subDays($days));
    }

    public function scopeByYear(Builder $query, int $year)
    {
        return $query->whereYear('date', $year);
    }


    /**
     * Get image with custom size using ImageKitService
     * Usage: $product->imageSize(400, 300) or $product->imageSize(800)
     */
    public function imageSize($width = null, $height = null, $quality = null): ?string
    {
        $imagePath = $this->thumbnail_url ?? $this->image_path ?? null;

        // This method seems to be looking for an images relationship
        // but your model doesn't have one. You might want to update this:
        if (!$imagePath) {
            return null;
        }

        // If no custom size requested, return original image
        if (!$width && !$height && !$quality) {
            return $this->imagePath;
        }

        // Use ImageKitService to generate custom size
        return app(ImageKitService::class)->getUrlWithTransformations(
            $this->extractBaseFilePath($imagePath),
            $width,
            $height,
            $quality
        );
    }

    /**
     * Extract base file path from ImageKit URL
     */
    protected function extractBaseFilePath($imageKitUrl): ?string
    {
        try {
            $parsedUrl = parse_url($imageKitUrl);
            if (!isset($parsedUrl['path'])) {
                return null;
            }

            $path = ltrim($parsedUrl['path'], '/');

            // Remove any transformation part (like /tr:w-40,...)
            if (str_starts_with($path, 'tr:')) {
                // e.g. tr:w-40,h-40/... → get only after first slash
                $path = substr($path, strpos($path, '/') + 1);
            } elseif (str_contains($path, '/tr:')) {
                // e.g. thebodoland/tr:.../interviews/thumbnails/file.jpg
                [$before, $after] = explode('/tr:', $path, 2);
                $after = substr($after, strpos($after, '/') + 1);
                $path = $after;
            }

            // Remove duplicate folder prefix (like "thebodoland/")
            $path = preg_replace('#^thebodoland/#', '', $path);

            // Ensure it starts with a leading slash
            if (!str_starts_with($path, '/')) {
                $path = '/' . $path;
            }

            return $path;
        } catch (\Exception $e) {
            return null;
        }
    }

}
