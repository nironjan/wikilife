<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'tags',
        'seoable_id',
        'seoable_type',

    ];

    public function seoable()
    {
        return $this->morphTo();
    }

    // ========== SCOPE ==============
    public function scopeForType(Builder $query, string $type)
    {
        return $query->where('seoable_type', $type);
    }
}
