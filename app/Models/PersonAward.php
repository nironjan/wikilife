<?php

namespace App\Models;

use App\Services\ImageKitService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonAward extends Model
{
    use HasFactory;
    protected $fillable = [
        'person_id',
        'award_name',
        'slug',
        'description',
        'awarded_at',
        'award_image',
        'image_caption',
        'award_image_file_id',
        'category',
        'organization',
        'is_verified',
        'sort_order',
    ];

    protected $casts = [
        'awarded_at' => 'date',
        'is_verified' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ========= RELATIONS ==========
    public function person()
    {
        return $this->belongsTo(People::class);
    }

    // ======== SCOPES ============
    public function scopeVerified(Builder $query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeByCategory(Builder $query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByYear(Builder $query, int $years)
    {
        return $query->whereYear('awarded_at', '>=', now()->subYears($years));
    }

    // ========= ATTRIBUTES ===========

    public function getYearAttribute()
    {
        return $this->awarded_at?->year;
    }

    public function getAwardeImageUrlAttribute()
    {
        return $this->award_image ?? null;
    }

    /**
     * Get image with custom size using ImageKitService
     * Usage: $award->imageSize(400, 300) or $award->imageSize(800)
     */
    public function imageSize($width = null, $height = null, $quality = null): ?string
    {
        $imagePath = $this->awarde_image_url ?? $this->award_image ?? null;

        if (!$imagePath) {
            return null;
        }

        // If no custom size requested, return original optimized image
        if (!$width && !$height && !$quality) {
            return $imagePath;
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

            $path = $parsedUrl['path'];

            // Remove transformation part to get base path
            if (str_contains($path, '/tr:')) {
                $parts = explode('/tr:', $path, 2);
                if (isset($parts[1])) {
                    $transformationAndPath = $parts[1];
                    $firstSlash = strpos($transformationAndPath, '/');
                    if ($firstSlash !== false) {
                        return substr($transformationAndPath, $firstSlash);
                    }
                }
            }

            return $path;

        } catch (\Exception $e) {
            return null;
        }
    }

    protected static function boot()
    {
        parent::boot();

        // Delete award image from ImageKit when award is deleted
        static::deleting(function ($award) {
            if ($award->award_image_file_id) {
                app(ImageKitService::class)->deleteFile($award->award_image_file_id);
            }
        });
    }

}
