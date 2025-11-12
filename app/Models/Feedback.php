<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'people_id',
        'name',
        'email',
        'type',
        'message',
        'suggested_changes',
        'status',
        'otp_code',
        'email_verified_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'suggested_changes' => 'array',
        'email_verified_at' => 'datetime',
    ];

    public function person()
    {
        return $this->belongsTo(People::class, 'people_id');
    }

    public function isVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }
}
