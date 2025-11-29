<?php

namespace App\Models;

use App\Services\SitemapService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'content',
        'template',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    /**
     * The "booted" method of the model
     */

    protected static function booted(){
    // Generate sitemap when a page is created
    static::created(function ($page){
        if($page->is_published){
            app(SitemapService::class)->regeneratePagesSitemap();
        }
    });

    // Generate sitemap when a page is updated
    static::updated(function ($page){
        $relevantFields = ['title', 'slug', 'is_published', 'published_at'];

        if($page->isDirty($relevantFields)){
            app(SitemapService::class)->regeneratePagesSitemap();
        }
    });

    // Generate sitemap when a page is deleted
    static::deleted(function(){
        app(SitemapService::class)->regeneratePagesSitemap();
    });
}

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
                    ->where(function ($query) {
                        $query->whereNull('published_at')
                              ->orWhere('published_at', '<=', now());
                    });
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('is_published', false)
                    ->orWhere(function ($query) {
                        $query->where('published_at', '>', now());
                    });
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('is_published', true)
                    ->where('published_at', '>', now());
    }

    public function scopeByTemplate(Builder $query, string $template): Builder
    {
        return $query->where('template', $template);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }

    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Attributes
     */
    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    public function getUrlAttribute(): string
    {
        return url('/pages/' . $this->slug);
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_published) {
            return 'draft';
        }

        if ($this->published_at && $this->published_at->isFuture()) {
            return 'scheduled';
        }

        return 'published';
    }

    public function getIsScheduledAttribute(): bool
    {
        return $this->is_published && $this->published_at && $this->published_at->isFuture();
    }

    public function getIsDraftAttribute(): bool
    {
        return !$this->is_published;
    }

    public function getIsLiveAttribute(): bool
    {
        return $this->is_published &&
               (!$this->published_at || $this->published_at->isPast());
    }

    public function getPublishedDateAttribute(): ?string
    {
        return $this->published_at?->format('M j, Y');
    }

    public function getCreatedDateAttribute(): string
    {
        return $this->created_at->format('M j, Y');
    }

    public function setTitleAttribute($value): void
    {
        $this->attributes['title'] = $value;

        if (empty($this->attributes['slug']) && !empty($value)) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function setSlugAttribute($value): void
    {
        $this->attributes['slug'] = Str::slug(trim($value));
    }

    public function setIsPublishedAttribute($value): void
    {
        $this->attributes['is_published'] = $value;

        if ($value && empty($this->attributes['published_at'])) {
            $this->attributes['published_at'] = now();
        }
    }


    /**
     * Model Events/Boot
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }

            $originalSlug = $page->slug;
            $counter = 1;

            while (static::where('slug', $page->slug)->exists()) {
                $page->slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('is_published') && $page->is_published && !$page->published_at) {
                $page->published_at = now();
            }
        });
    }

    /**
     * Custom Methods
     */
    public function publish(): bool
    {
        return $this->update([
            'is_published' => true,
            'published_at' => $this->published_at ?: now(),
        ]);
    }

    public function unpublish(): bool
    {
        return $this->update([
            'is_published' => false,
        ]);
    }

    public function schedule(Carbon $publishDate): bool
    {
        return $this->update([
            'is_published' => true,
            'published_at' => $publishDate,
        ]);
    }

    public function duplicate(array $overrides = []): Page
    {
        $newPage = $this->replicate();

        $defaultOverrides = [
            'title' => $this->title . ' (Copy)',
            'slug' => $this->slug . '-copy',
            'is_published' => false,
            'published_at' => null,
        ];

        $newPage->fill(array_merge($defaultOverrides, $overrides));
        $newPage->save();

        return $newPage;
    }
}
