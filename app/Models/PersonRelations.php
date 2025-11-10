<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonRelations extends Model
{
    use HasFactory;
    protected $fillable = [
        'person_id',
        'related_person_id',
        'related_person_name',
        'marital_status',
        'relation_type',
        'is_reciprocal',
        'notes',
        'since',
        'until',
        'related_person_death_year',
    ];

    protected $casts = [
        'since' => 'integer',
        'until' => 'integer',
        'related_person_death_year' => 'integer',
        'is_reciprocal' => 'boolean',
    ];


    // ======= RELATIONS =============

    public function person()
    {
        return $this->belongsTo(People::class);
    }

    public function relatedPerson()
    {
        return $this->belongsTo(People::class, 'related_person_id');
    }

    // ========== SCOPES =================
    public function scopeByRelationType(Builder $query, string $type)
    {
        return $query->where('relation_type', $type);
    }

    public function scopeFamily(Builder $query)
    {
        return $query->whereIn('relation_type', ['parent', 'sibling', 'spouse', 'child']);
    }

    public function scopeCurrent(Builder $query)
    {
        return $query->where(function ($q) {
            $q->whereNull('until')
                ->orWhere('until', '>=', now()->year);
        });
    }


    // ========== ATTRIBUTES ==============

    /**
     * Get the display name for the related person
     * Uses the stored related_person_name or fetches from relatedPerson relationship
     */
    public function getDisplayRelatedPersonNameAttribute()
    {
        // Use the stored name if available
        if (!empty($this->attributes['related_person_name'])) {
            return $this->attributes['related_person_name'];
        }

        // Fall back to the related person's name from the relationship
        if ($this->relatedPerson) {
            return $this->relatedPerson->name;
        }

        return "Unknown";
    }
    /**
     * Get the duration of the relationship
     */
    public function getDurationAttribute()
    {
        if (!$this->since) {
            return null;
        }

        if ($this->until) {
            return "{$this->since} - {$this->until}";
        }

        return "Since {$this->since}";
    }

    /**
     * Check if the relationship is currently active
     */
    public function getIsCurrentAttribute()
    {
        if (!$this->since) {
            return false;
        }

        $currentYear = date('Y');

        if ($this->until) {
            return $this->until >= $currentYear;
        }

        return $this->since <= $currentYear;
    }

    /**
     * Get the relationship type in a readable format
     */
    public function getRelationTypeDisplayAttribute()
    {
        return match ($this->relation_type) {
            'parent' => 'Parent',
            'sibling' => 'Sibling',
            'spouse' => 'Spouse',
            'child' => 'Child',
            'other' => 'Other',
            default => ucfirst($this->relation_type),
        };
    }

    /**
     * Get marital status in a readable format
     */
    public function getMaritalStatusDisplayAttribute()
    {
        if (!$this->marital_status) {
            return null;
        }

        return match ($this->marital_status) {
            'single' => 'Single',
            'married' => 'Married',
            'divorced' => 'Divorced',
            'widowed' => 'Widowed',
            'other' => 'Other',
            default => ucfirst($this->marital_status),
        };
    }
}
