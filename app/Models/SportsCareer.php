<?php

namespace App\Models;

use App\Services\SitemapService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportsCareer extends Model
{
    use HasFactory;
    protected $fillable = [
        'person_id',
        'sport',
        'slug',
        'team',
        'position',
        'debut_date',
        'retirement_date',
        'achievements',
        'jersey_number',
        'coach_name',
        'award_id',
        'award_ids',
        'international_player',
        'stats',
        'notable_events',
        'leagues_participated',
        'is_active',
        'sort_order',
    ];
    protected $casts = [
        'debut_date' => 'date',
        'retirement_date' => 'date',
        'achievements' => 'array',
        'stats' => 'array',
        'notable_events' => 'array',
        'leagues_participated' => 'array',
        'award_ids' => 'array',
        'international_player' => 'boolean',
        'is_active' => 'boolean',
        'sorted_order' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(function ($career) {
            app(SitemapService::class)->regenerateDynamicContentSitemap();
        });

        static::deleted(function () {
            app(SitemapService::class)->regenerateDynamicContentSitemap();
        });
    }

    // ============= RELATIONS ==============
    public function person()
    {
        return $this->belongsTo(People::class);
    }

    public function awards()
    {
        return PersonAward::whereIn('id', $this->award_ids ?? []);
    }

    // =========== SCOPES ================
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInternational(Builder $query)
    {
        return $query->where('international_player', true);
    }

    public function scopeBySport(Builder $query, string $sport)
    {
        return $query->where('sport', $sport);
    }

    public function scopeByTeam(Builder $query, string $team)
    {
        return $query->where('team', 'LIKE', "%{$team}%");
    }

    // ============ ATTRIBUTES ============
    public function getCareerStatusAttribute()
    {
        if ($this->retirement_date) {
            return 'Retired';
        } elseif ($this->is_active) {
            return 'Active';
        }
        return 'Inactive';
    }

    public function getCareerDurationAttribute()
    {
        if ($this->debut_date && $this->retirement_date) {
            $years = $this->debut_date->diffInYears($this->retirement_date);
            return "{$years} years";
        } elseif ($this->debut_date) {
            $years = $this->debut_date->diffInYears(now());
            return "{$years} years";
        }
        return 'N/A';
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
