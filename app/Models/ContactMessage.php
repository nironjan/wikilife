<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'type',
        'status',
        'otp_code',
        'email_verified_at',
        'ip_address',
        'user_agent',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
        'email_verified_at' => 'datetime',
    ];

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

    public function scopeGeneral($query)
    {
        return $query->where('type', 'general');
    }

    public function scopeSupport($query)
    {
        return $query->where('type', 'support');
    }
}
