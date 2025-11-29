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

    // ========= RELATIONS ============
    public function person()
    {
        return $this->belongsTo(People::class);
    }

    // ========= SCOPES ===============
    public function scopeByYear(Builder $query, int $year)
    {
        return $query->where('year_estimated', $year);
    }

    public function scopeWealthy(Builder $query, int $minNetWorth = 15200000)
    {
        return $query->where('net_worth', '>=', $minNetWorth);
    }

 // ========= ATTRIBUTES ===========
    public function getFormattedNetWorthAttribute(): string
    {
        if (!$this->net_worth) {
            return 'N/A';
        }

        $symbol = getCurrencySymbol($this->currency);
        return $symbol . ' ' . number_format($this->net_worth);
    }

    public function getIsHighNetWorthAttribute(): bool
    {
        return $this->net_worth >= 15200000;
    }

    public function getReferencesListAttribute(): array
    {
        return $this->references ?: [];
    }

    public function getFormattedIncomeAttribute(): string
    {
        if (!$this->income) {
            return 'N/A';
        }

        $symbol = getCurrencySymbol($this->currency);
        return $symbol . ' ' . number_format($this->income);
    }

    public function getFormattedCurrentAssetsAttribute(): string
    {
        if (!$this->current_assets) {
            return 'N/A';
        }

        $symbol = getCurrencySymbol($this->currency);
        return $symbol . ' ' . number_format($this->current_assets);
    }

    public function getEstimatedYearLabelAttribute(): ?string
    {
        return $this->year_estimated ? $this->year_estimated . ' Estimate' : null;
    }
}
