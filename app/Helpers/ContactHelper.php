<?php

namespace App\Helpers;

use App\Models\ContactOTP;
use Illuminate\Support\Facades\Cache;

class ContactHelper
{
    const SESSION_KEY = 'contact_verified_email';
    const SESSION_EXPIRY = 3600; // 1 hour
    const OTP_RATE_LIMIT = 3; // 3 OTPs per hour
    const OTP_BLOCK_DURATION = 3600; // 1 hour block

    /**
     * Set verified session
     */
    public static function setVerifiedSession(string $email): void
    {
        session([self::SESSION_KEY => [
            'email' => $email,
            'verified_at' => now()->timestamp,
            'expires_at' => now()->addSeconds(self::SESSION_EXPIRY)->timestamp
        ]]);
    }

    /**
     * Check if session is valid
     */
    public static function isSessionValid(): bool
    {
        $session = session(self::SESSION_KEY);

        if (!$session) {
            return false;
        }

        return $session['expires_at'] > now()->timestamp;
    }

    /**
     * Get verified email from session
     */
    public static function getVerifiedEmail(): ?string
    {
        return self::isSessionValid() ? session(self::SESSION_KEY)['email'] : null;
    }

    /**
     * Clear session
     */
    public static function clearSession(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Check if email can request OTP
     */
    public static function canRequestOTP(string $email): bool
    {
        if (self::isEmailBlocked($email)) {
            return false;
        }

        $otpCount = ContactOTP::where('email', $email)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        return $otpCount < self::OTP_RATE_LIMIT;
    }

    /**
     * Check if email is blocked from OTP requests
     */
    public static function isEmailBlocked(string $email): bool
    {
        $blockKey = "contact_otp_blocked:{$email}";
        return Cache::has($blockKey);
    }

    /**
     * Block email from OTP requests
     */
    public static function blockEmail(string $email): void
    {
        $blockKey = "contact_otp_blocked:{$email}";
        Cache::put($blockKey, true, self::OTP_BLOCK_DURATION);
    }

    /**
     * Get remaining OTP requests
     */
    public static function getRemainingOTPRequests(string $email): int
    {
        $otpCount = ContactOTP::where('email', $email)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        return max(0, self::OTP_RATE_LIMIT - $otpCount);
    }

    /**
     * Get time until next OTP can be requested
     */
    public static function getTimeUntilNextOTP(string $email): int
    {
        $lastOTP = ContactOTP::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastOTP) {
            return 0;
        }

        $nextAvailable = $lastOTP->created_at->addHour();
        return max(0, $nextAvailable->diffInSeconds(now()));
    }

    /**
     * Get rate limit message
     */
    public static function getRateLimitMessage(string $email): string
    {
        if (self::isEmailBlocked($email)) {
            $blockKey = "contact_otp_blocked:{$email}";
            $remaining = Cache::get($blockKey . '_expiry', now()->addHour()->diffInMinutes(now()));
            return "Too many verification attempts. Please try again in {$remaining} minutes.";
        }

        $remaining = self::getRemainingOTPRequests($email);
        $timeUntil = self::getTimeUntilNextOTP($email);

        if ($remaining === 0) {
            $minutes = ceil($timeUntil / 60);
            return "You've reached the maximum verification attempts. Please try again in {$minutes} minutes.";
        }

        return "You have {$remaining} verification attempts remaining this hour.";
    }
}
