<?php

namespace App\Models;

use App\Services\ImageKitService;
use App\Services\SitemapService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class LiteratureCareer extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'award_ids',
        'role',
        'slug',
        'media_type',
        'start_date',
        'end_date',
        'title',
        'work_type',
        'publishing_year',
        'language',
        'genre',
        'isbn',
        'cover_image',
        'img_file_id',
        'link',
        'description',
        'is_verified',
        'sort_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'award_ids' => 'array',
        'is_verified' => 'boolean',
        'sort_order' => 'integer',
        'publishing_year' => 'integer',
    ];

    protected static function booted()
{
    static::saved(function ($career) {
        app(SitemapService::class)->generateSitemap();
    });

    static::deleted(function () {
        app(SitemapService::class)->generateSitemap();
    });
}


    // ============= RELATIONS ==============
    public function person()
    {
        return $this->belongsTo(People::class);
    }

    // Multiple awards relationship
    public function awards()
    {
        return PersonAward::whereIn('id', $this->award_ids ?? []);
    }

    // Helper method to get award models
    public function getAwardsAttribute()
    {
        if (empty($this->award_ids)) {
            return collect();
        }
        return PersonAward::whereIn('id', $this->award_ids)->get();
    }


    // =========== SCOPES ================
    public function scopeActive(Builder $query)
    {
        return $query->where(function ($q) {
            $q->whereNull('end_date')
                ->orWhere('end_date', '>=', now());
        });
    }

    public function scopeVerified(Builder $query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeByRole(Builder $query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByMediaType(Builder $query, string $mediaType)
    {
        return $query->where('media_type', $mediaType);
    }

    public function scopeByWorkType(Builder $query, string $workType)
    {
        return $query->where('work_type', $workType);
    }

    public function scopeByGenre(Builder $query, string $genre)
    {
        return $query->where('genre', $genre);
    }

    public function scopeByLanguage(Builder $query, string $language)
    {
        return $query->where('language', $language);
    }

    public function scopeRecent(Builder $query, int $years = 5)
    {
        return $query->where('publishing_year', '>=', now()->subYears($years)->year);
    }

    public function scopeByPerson(Builder $query, int $personId)
    {
        return $query->where('person_id', $personId);
    }


    // ============ ATTRIBUTES ============
    public function getCareerStatusAttribute()
    {
        if ($this->end_date && $this->end_date->isPast()) {
            return 'Completed';
        } elseif ($this->start_date && $this->start_date->isFuture()) {
            return 'Upcoming';
        }
        return 'Active';
    }

    public function getCareerDurationAttribute()
    {
        if ($this->start_date && $this->end_date) {
            $years = $this->start_date->diffInYears($this->end_date);
            return "{$years} years";
        } elseif ($this->start_date) {
            $years = $this->start_date->diffInYears(now());
            return "{$years} years (ongoing)";
        }
        return 'N/A';
    }

    public function getIsActiveAttribute()
    {
        return is_null($this->end_date) || $this->end_date >= now();
    }

    public function getAwardsCountAttribute()
    {
        return count($this->award_ids ?? []);
    }

    public function getDisplayTitleAttribute()
    {
        return $this->title ?: 'Untitled Work';
    }

    public function getWorkDetailsAttribute()
    {
        $details = [];

        if ($this->work_type) {
            $details[] = $this->work_type;
        }

        if ($this->publishing_year) {
            $details[] = $this->publishing_year;
        }

        if ($this->language) {
            $details[] = $this->language;
        }

        return implode(' • ', $details);
    }

    // ============ HELPERS ============
    public function hasAward($awardId)
    {
        return in_array($awardId, $this->award_ids ?? []);
    }

    public function addAward($awardId)
    {
        $awardIds = $this->award_ids ?? [];
        if (!in_array($awardId, $awardIds)) {
            $awardIds[] = $awardId;
            $this->award_ids = $awardIds;
        }
    }

    public function removeAward($awardId)
    {
        $awardIds = $this->award_ids ?? [];
        $this->award_ids = array_filter($awardIds, function ($id) use ($awardId) {
            return $id != $awardId;
        });
    }



    /**
     * Get cover image URL - matches Person model structure
     */
    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image ?? null;
    }


    /**
     * Get image with custom size using ImageKitService
     * Usage: $product->imageSize(400, 300) or $product->imageSize(800)
     */
    public function imageSize($width = null, $height = null, $quality = null): ?string
    {
        $image = $this->images->first();

        if (!$image || !$image->image_path) {
            return null;
        }

        // If no custom size requested, return original optimized image
        if (!$width && !$height && !$quality) {
            return $image->image_path;
        }

        // Use ImageKitService to generate custom size
        return app(ImageKitService::class)->getUrlWithTransformations(
            $this->extractBaseFilePath($image->image_path),
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

        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Boot method to handle image deletion
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($literatureCareer) {
            if ($literatureCareer->img_file_id) {
                try {
                    app(ImageKitService::class)->deleteFile($literatureCareer->img_file_id);
                } catch (Exception $e) {
                    Log::error('Failed to delete literature cover image: ' . $e->getMessage());
                }
            }
        });
    }


}
