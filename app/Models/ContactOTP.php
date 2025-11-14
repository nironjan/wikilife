<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactOTP extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp_code',
        'type',
        'is_used',
        'expires_at',
        'ip_address',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function scopeValid($query)
    {
        return $query->where('is_used', false)
                    ->where('expires_at', '>', now());
    }
}
