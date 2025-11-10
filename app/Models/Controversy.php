<?php

namespace App\Models;

use App\Services\SitemapService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Controversy extends Model
{
    use HasFactory;
    protected $fillable = [
        'person_id',
        'title',
        'slug',
        'content',
        'html_content',
        'date',
        'source_url',
        'is_resolved',
        'is_published',
    ];

    protected $casts = [
        'date' => 'date',
        'is_resolved' => 'boolean',
        'is_published' => 'boolean',
    ];


    protected static function booted()
{
    static::saved(function ($controversy) {
        if ($controversy->is_published) {
            app(SitemapService::class)->generateSitemap();
        }
    });

    static::deleted(function () {
        app(SitemapService::class)->generateSitemap();
    });
}

    // ========= RELATIONS =============
    public function person()
    {
        return $this->belongsTo(People::class);
    }

    // ========== SCOPES ============
    public function scopePublished(Builder $query)
    {
        return $this->where('is_published', true);
    }

    public function scopeResolved(Builder $query)
    {
        return $query->where('is_resolved', true);
    }

    public function scopeUnresolved(Builder $query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeRecent(Builder $query, int $days = 365)
    {
        return $query->where('date', '>=', now()->subDays($days));
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
     * Get excerpt of HTML content for listing
     */
    public function getExcerptAttribute($length = 120)
    {
        // Use HTML content if available, otherwise convert markdown
        $content = $this->html_content ?: Str::markdown($this->content);

        // Strip HTML tags and limit length
        $plainText = strip_tags($content);

        return Str::limit($plainText, $length);
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

}
