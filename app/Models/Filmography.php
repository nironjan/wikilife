<?php

namespace App\Models;

use App\Services\SitemapService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filmography extends Model
{
    use HasFactory;
    protected $fillable = [
        'person_id',
        'movie_title',
        'slug',
        'release_date',
        'role',
        'profession_type',
        'industry',
        'director_id',
        'unlisted_director_name',
        'production_company',
        'genres',
        'description',
        'box_office_collection',
        'person_award_id',
        'award_ids',
        'is_verified',
        'sort_order',

    ];

    protected $casts = [
        'release_date' => 'date',
        'genres' => 'array',
        'is_verified' => 'boolean',
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

    // ========== RELATIONS ===============
    public function person()
    {
        return $this->belongsTo(People::class, 'person_id');
    }

    public function director()
    {
        return $this->belongsTo(People::class, 'director_id');
    }

    public function awards()
    {
        return PersonAward::whereIn('id', $this->award_ids ?? []);
    }

    // ========= SCOPES ==============
    public function scopeVerified(Builder $query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeByProfession(Builder $query, string $profession)
    {
        return $query->where('profession_type', $profession);
    }

    public function scopeByYear(Builder $query, int $year)
    {
        return $this->whereYear('release_date', $year);
    }

    public function scopeSuccessful(Builder $query)
    {
        return $query->whereNotNull('box_office_collection')
            ->orderByRaw('CAST(box_office_collection AS UNSIGNED) DESC');
    }

    // ========== ATTRIBUTES ===============
    public function getDirectorNameAttribute()
    {
        return $this->unlisted_director_name ?: ($this->director ? $this->director->name : 'Unknown');
    }

    public function getReleaseYearAttribute()
    {
        return $this->release_date?->year;
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
