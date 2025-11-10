<?php

namespace App\Models;

use App\Services\ImageKitService;
use App\Services\SitemapService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'featured_image_file_id',
        'is_published',
        'published_at',
        'views',
        'meta_title',
        'meta_description',
        'tags',
        'sort_order',
        'author_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'tags' => 'array',
    ];

    protected static function booted(){
    // Generate sitemap when a blog post is created
    static::created(function ($post){
        if($post->is_published){
            app(SitemapService::class)->generateSitemap();
        }
    });

    // Generate sitemap when a blog post is updated
    static::updated(function ($post){
        if($post->isDirty(['is_published', 'slug', 'title', 'blog_category_id']) && $post->is_published){
            app(SitemapService::class)->generateSitemap();
        }
    });

    // Generate sitemap when a blog post is deleted
    static::deleted(function(){
        app(SitemapService::class)->generateSitemap();
    });
}

    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function author(){
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if ($post->is_published && !$post->published_at) {
                $post->published_at = now();
            }
        });

        static::updating(function ($post) {
            if ($post->is_published && !$post->published_at) {
                $post->published_at = now();
            }
        });

        // Delete featured image from ImageKit when blog post is deleted
        static::deleting(function ($post) {
            if ($post->featured_image_file_id) {
                app(ImageKitService::class)->deleteFile($post->featured_image_file_id);
            }
        });
    }

    // ========= ATTRIBUTES ===========

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ?? null;
    }

    /**
     * Get image with custom size using ImageKitService
     * Usage: $blogPost->imageSize(400, 300) or $blogPost->imageSize(800)
     */
    public function imageSize($width = null, $height = null, $quality = null): ?string
    {
        $imagePath = $this->featured_image_url ?? $this->featured_image ?? null;

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

    // Add to your BlogPost model
    public function getReadTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // Average reading speed: 200 words per minute
        return max(1, $minutes); // Minimum 1 minute
    }

    // ======== SCOPES ============
    public function scopePublished(Builder $query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByCategory(Builder $query, $categoryId)
    {
        return $query->where('blog_category_id', $categoryId);
    }

    public function scopeRecent(Builder $query, $days = 30)
    {
        return $query->where('published_at', '>=', now()->subDays($days));
    }
}
