<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonEducation extends Model
{
    use HasFactory;
    protected $fillable = [
        'person_id',
        'degree',
        'slug',
        'institution',
        'details',
        'start_year',
        'end_year',
        'field_of_study',
        'grade_or_honors',
        'location',
        'sort_order',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
        'sort_order' => 'integer',
    ];

    // ======== RELATIONS ===========
    public function person()
    {
        return $this->belongsTo(People::class);
    }

    // ========= SCOPES =============
    public function scopeCompleted(Builder $query)
    {
        return $query->whereNotNull('end_year');
    }

    public function scopeByInstitution(Builder $query, string $institution)
    {
        return $query->where('institution', 'LIKE', "%{$institution}%");
    }

    public function scopeHighestDegreeFirst(Builder $query)
    {
        return $query->orderBy('sort_order')->orderBy('end_year', 'desc');
    }


    // ======== ATTRIBUTES ===========
    public function getDurationAttribute()
    {
        if ($this->start_year && $this->end_year) {
            return "{$this->start_year} - {$this->end_year}";
        } elseif ($this->start_year) {
            return "Since {$this->start_year}";
        }

        return 'N/A';
    }

    public function getIsCompletedAttribute()
    {
        return !is_null($this->end_year);
    }
}
