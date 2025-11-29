<?php

namespace App\Models;

use App\Services\SitemapService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Politician extends Model
{
    use HasFactory;
    protected $fillable = [
        'person_id',
        'political_party',
        'slug',
        'constituency',
        'joining_date',
        'end_date',
        'position',
        'tenure_start',
        'tenure_end',
        'political_journey',
        'notable_achievements',
        'major_initiatives',
        'memberships',
        'office_type',
        'award_id',
        'award_ids',
        'notes',
        'source_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'end_date' => 'date',
        'tenure_start' => 'date',
        'tenure_end' => 'date',
        'memberships' => 'array',
        'award_ids' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
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

    // ========= RELATIONS ============
    public function person()
    {
        return $this->belongsTo(People::class, 'person_id');
    }

    public function awards()
    {
        return PersonAward::whereIn('id', $this->award_ids ?? []);
    }

    // ======== SCOPES ============
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByParty(Builder $query, string $party)
    {
        return $query->where('political_party', $party);
    }

    public function scopeByOfficeType(Builder $query, string $officeType)
    {
        return $query->where('office_type', $officeType);
    }

    public function scopeCurrentTenure(Builder $query)
    {
        return $query->where('tenure_end')
            ->orWhere('tenure_end', '>=', now());
    }

    // ======== ATTRIBUTES ===========
    public function getTenureDurationAttribute()
    {
        if ($this->tenure_start) {
            $start = Carbon::parse($this->tenure_start);
            $end = $this->tenure_end ? Carbon::parse($this->tenure_end) : now();

            $diff = $start->diff($end);

            // Example: "45 years, 6 months"
            $years = $diff->y;
            $months = $diff->m;

            $duration = "{$years} years";
            if ($months > 0) {
                $duration .= ", {$months} months";
            }

            return $this->tenure_end
                ? $duration
                : "{$duration} (ongoing)";
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

    public function getIsCurrentAttribute()
    {
        return is_null($this->tenure_end) || $this->tenure_end >= now();
    }
}
