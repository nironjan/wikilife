<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'currency',
        'income',
        'income_source',
        'current_assets',
        'net_worth',
        'year_estimated',
        'references'
    ];

    protected $casts = [
        'year_estimated' => 'integer',
        'income' => 'decimal:2',
        'current_assets' => 'decimal:2',
        'net_worth' => 'decimal:2',
        'references' => 'array',
    ];

    // ========= RELATIONS ================
    public function person()
    {
        return $this->belongsTo(People::class);
    }



    // ========== SCOPES ==================

    public function scopeByYear(Builder $query, int $year)
    {
        return $query->where('year_estiated', $year);
    }

    public function scopeWealthy(Builder $query, int $minNetWorth = 15200000)
    {
        return $query->where('net_worth', '>=', $minNetWorth);
    }


    // ========= ATTRIBUTES ================

    public function getFormattedNetWorthAttribute()
    {
        if (!$this->net_worth)
            return 'N/A';

        return $this->currency . ' ' . number_format($this->net_worth);
    }
}
