<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackOTP extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp_code',
        'people_id',
        'type',
        'message',
        'suggested_changes',
        'name',
        'is_used',
        'expires_at',
        'ip_address',
    ];

    protected $casts = [
        'suggested_changes' => 'array',
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function person()
    {
        return $this->belongsTo(People::class, 'people_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }

    public function markAsUsed(): void
    {
        $this->update(['is_used' => true]);
    }
}
