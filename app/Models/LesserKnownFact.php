<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LesserKnownFact extends Model
{
    use HasFactory;
    protected $fillable = [
        'person_id',
        'title',
        'fact',
        'category',
    ];

    // ======== RELATIONS ===========
    public function person()
    {
        return $this->belongsTo(People::class, 'person_id');
    }

    // ======== SCOPES =============
    public function scopeByCategory(Builder $query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeRandomFacts(Builder $query, int $limit = 5)
    {
        return $query->inRandomOrder()->limit($limit);
    }
}
