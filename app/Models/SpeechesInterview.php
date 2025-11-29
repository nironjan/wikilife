<?php

namespace App\Models;

use App\Services\ImageKitService;
use App\Services\SitemapService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SpeechesInterview extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'type',
        'title',
        'slug',
        'description',
        'content',
        'location',
        'date',
        'url',
        'thumbnail_url',
        'thumb_file_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * The "booted" method of the model
     */

    protected static function booted(){
        //Generate sitemap when a person is created
        static::created(function ($speechesInterview){
            if($speechesInterview->status === 'active' && $speechesInterview->verified){
                app(SitemapService::class)->regenerateSpeechesSitemap();
            }
        });

        // Generate sitemap when a person is updated
        static::updated(function ($speechesInterview){
            if($speechesInterview->isDirty(['slug', 'title'])){
                app(SitemapService::class)->regenerateSpeechesSitemap();
            }
        });

        // generate sitemap when a person is deleted
        static::deleted(function($speechesInterview){
            app(SitemapService::class)->regenerateSpeechesSitemap();
        });
    }


    /**
     * Boot function for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Generate slug automatically when creating
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateSlug();
            }
        });

        // Update slug when title changes
        static::updating(function ($model) {
            if ($model->isDirty('title') && empty($model->slug)) {
                $model->slug = $model->generateSlug();
            }
        });
    }

    /**
     * Generate a unique slug
     */
    public function generateSlug(): string
    {
        $baseSlug = Str::slug($this->title);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)
                ->where('id', '!=', $this->id)
                ->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

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

    public function scopeWhereSlug(Builder $query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }



    /**
     * Get image with custom size using ImageKitService
     */
    public function imageSize($width = null, $height = null, $quality = null): ?string
    {
        $imagePath = $this->thumbnail_url ?? $this->image_path ?? null;


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
            $path = preg_replace('#^wikilife/#', '', $path);

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
