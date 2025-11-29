<?php

namespace App\Models;

use App\Services\ImageKitService;
use App\Services\SitemapService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LatestUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'person_id',
        'title',
        'slug',
        'content',
        'description',
        'html_content',
        'image',
        'image_file_id',
        'update_type',
        'is_approved',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'sort_order' => 'integer',
    ];


    protected static function booted(){
        static::saved(function($update){
            if($update->isPublished() && $update->isApproved()){
                app(SitemapService::class)->regenerateDynamicContentSitemap();
            }
        });

        static::deleted(function(){
            app(SitemapService::class)->regenerateDynamicContentSitemap();
        });
    }

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(People::class, 'person_id');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeByPerson($query, $personId)
    {
        return $query->where('person_id', $personId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('update_type', $type);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    /**
     * Accessors & Mutators
     */
    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    public function getReadTimeAttribute(): string
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200);
        return $minutes . ' min read';
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'published' => ['color' => 'green', 'text' => 'Published'],
            'draft' => ['color' => 'gray', 'text' => 'Draft'],
            default => ['color' => 'gray', 'text' => $this->status],
        };
    }

    public function getApprovalBadgeAttribute(): array
    {
        return $this->is_approved
            ? ['color' => 'green', 'text' => 'Approved']
            : ['color' => 'orange', 'text' => 'Pending'];
    }

    /**
     * Helpers
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    public function canBePublished(): bool
    {
        return $this->is_approved && $this->status === 'draft';
    }

    public function publish(): bool
    {
        return $this->update(['status' => 'published']);
    }

    public function unpublish(): bool
    {
        return $this->update(['status' => 'draft']);
    }

    /**
     * Generate slug from title
     */
    public static function generateSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // ============ ATTRIBUTES =============
    /**
     * Get the HTML content - fallback to markdown conversion if HTML is empty
     */
    public function getHtmlContentAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        // Convert markdown to HTML if html_content is empty
        if (!empty($this->content)) {
            return Str::markdown($this->content);
        }

        return '';
    }

    /**
     * Get safe HTML content for display
     */
    public function getSafeHtmlContentAttribute()
    {
        // Use HTML content if available, otherwise convert markdown
        $content = $this->html_content ?: Str::markdown($this->content);

        // You can add additional sanitization here if needed
        return $content;
    }

     public function getImageUrlAttribute()
    {
        if($this->image){
            return $this->image;
        }

        return null;
    }


    /**
     * Get image with custom size using ImageKitService
     * Usage: $award->imageSize(400, 300) or $award->imageSize(800)
     */
    public function imageSize($width = null, $height = null, $quality = null): ?string
    {
        $imagePath = $this->image ?? null;

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

    public function hasImage(): bool
    {
        return !empty($this->image) || !empty($this->image_file_id);
    }

    protected static function boot()
    {
        parent::boot();

        // Delete award image from ImageKit when award is deleted
        static::deleting(function ($updates) {
            if ($updates->image_file_id) {
                app(ImageKitService::class)->deleteFile($updates->image_file_id);
            }
        });
    }
}
