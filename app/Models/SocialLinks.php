<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLinks extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'platform',
        'url',
        'username',
        'icon',
    ];


    // ========= RELATIONS ===========
    public function person()
    {
        return $this->belongsTo(People::class);
    }

    // ========== SCOPES =============
    public function scopeByPlatform(Builder $query, string $platform)
    {
        return $query->where('platform', $platform);
    }

    public function scopeHasUrl(Builder $query)
    {
        return $query->whereNotNull('url')->where('url', '!=', '');
    }

    // ========== ATTRIBUTES =============
    public function getDisplayUrlAttribute()
    {
        return $this->url ?: 'https://' . $this->platform . '.com/' . $this->username;
    }
}
