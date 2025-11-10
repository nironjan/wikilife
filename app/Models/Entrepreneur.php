<?php

namespace App\Models;

use App\Services\SitemapService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrepreneur extends Model
{
    use HasFactory;
    protected $fillable = [
        'person_id',
        'company_name',
        'slug',
        'role',
        'joining_date',
        'industry',
        'founding_date',
        'exit_date',
        'investment',
        'headquarters_location',
        'status',
        'notable_achievements',
        'award_id',
        'award_ids',
        'website_url',
        'sort_order',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'founding_date' => 'date',
        'exit_date' => 'date',
        'notable_achievements' => 'array',
        'award_ids' => 'array',
        'sort_order' => 'integer',
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

    // =========== RELATIONS ==============
    public function person()
    {
        return $this->belongsTo(People::class);
    }

    public function awards()
    {
        return PersonAward::whereIn('id', $this->award_ids ?? []);
    }

    // ========= SCOPES =============
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByIndustry(Builder $query, string $industry)
    {
        return $query->where('industry', $industry);
    }

    public function scopeFoundeds(Builder $query)
    {
        return $query->where('role', 'LIKE', '%{founder}%');
    }


    // ======== ATTRIBUTES ============
    public function getCompanyAgeAttribute()
    {
        return $this->founding_date?->diffInYears(now());
    }

    public function getIsFounderAttribute()
    {
        return strpos($this->role, 'founder') != false;
    }

    public function getAwardsAttribute()
    {
        if (empty($this->award_ids)) {
            return collect();
        }
        return PersonAward::whereIn('id', $this->award_ids)->get();
    }

    public function getAwardsCountAttribute()
    {
        return count($this->award_ids ?? []);
    }

}
