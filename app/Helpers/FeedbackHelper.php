<?php

namespace App\Helpers;

use App\Models\FeedbackOTP;

class FeedbackHelper
{
    /**
     * Set user session for 1 hour after email verification
     */
    public static function setVerifiedSession($email)
    {
        session([
            'feedback_verified' => true,
            'feedback_email' => $email,
            'feedback_verified_at' => now()
        ]);
    }

    /**
     * Check if user session is valid (within 1 hour)
     */
    public static function isSessionValid()
    {
        if (!session('feedback_verified')) {
            return false;
        }

        $verifiedAt = session('feedback_verified_at');
        if (!$verifiedAt) {
            return false;
        }

        // Check if session is within 1 hour
        return now()->diffInMinutes($verifiedAt) < 60;
    }

    /**
     * Get verified email from session
     */
    public static function getVerifiedEmail()
    {
        return self::isSessionValid() ? session('feedback_email') : null;
    }

    /**
     * Clear user session
     */
    public static function clearSession()
    {
        session()->forget([
            'feedback_verified',
            'feedback_email',
            'feedback_verified_at'
        ]);
    }

    /**
     * Get recent OTP requests from database (last 1 hour)
     */
    private static function getRecentOTPRequests($email)
    {
        return FeedbackOTP::where('email', $email)
            ->where('created_at', '>=', now()->subHour())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check if user can request OTP (max 3 requests per hour)
     */
    public static function canRequestOTP($email)
    {
        $recentRequests = self::getRecentOTPRequests($email);
        return $recentRequests->count() < 3;
    }


    /**
     * Get remaining OTP requests allowed
     */
    public static function getRemainingOTPRequests($email)
    {
        $recentRequests = self::getRecentOTPRequests($email);
        return max(0, 3 - $recentRequests->count());
    }

    /**
     * Check if email is completely blocked from making OTP requests
     */
    public static function isEmailBlocked($email)
    {
        $recentRequests = self::getRecentOTPRequests($email);
        return $recentRequests->count() >= 3;
    }

    /**
     * Get time until next OTP request is allowed (in minutes - for calculations)
     */
    public static function getTimeUntilNextOTPInMinutes($email)
    {
        $recentRequests = self::getRecentOTPRequests($email);

        if ($recentRequests->count() < 3) {
            return 0;
        }

        // Get the oldest request time from the last 3 requests
        $oldestRequest = $recentRequests->sortBy('created_at')->first();
        
        if (!$oldestRequest) {
            return 0;
        }

        $nextAvailable = $oldestRequest->created_at->addHour();
        $minutesRemaining = max(0, now()->diffInMinutes($nextAvailable, false));
        
        return $minutesRemaining;
    }

    /**
     * Get human-readable time until next OTP request is allowed
     */
    public static function getTimeUntilNextOTP($email)
    {
        $minutesRemaining = self::getTimeUntilNextOTPInMinutes($email);
        return self::formatReadableTime($minutesRemaining);
    }

    /**
     * Format minutes into human-readable time
     */
    private static function formatReadableTime($minutes)
    {
        // Handle negative or zero minutes
        if ($minutes <= 0) {
            return 'less than a minute';
        }

        // Round to nearest whole number
        $minutes = round($minutes);

        if ($minutes < 1) {
            return 'less than a minute';
        }

        if ($minutes < 60) {
            return $minutes . ' ' . ($minutes === 1 ? 'minute' : 'minutes');
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return $hours . ' ' . ($hours === 1 ? 'hour' : 'hours');
        }

        return $hours . ' ' . ($hours === 1 ? 'hour' : 'hours') . ' and ' . 
               $remainingMinutes . ' ' . ($remainingMinutes === 1 ? 'minute' : 'minutes');
    }

    /**
     * Get a friendly message about rate limiting status
     */
    public static function getRateLimitMessage($email)
    {
        if (self::isEmailBlocked($email)) {
            $timeRemaining = self::getTimeUntilNextOTP($email);
            return "Too many verification attempts for this email. Please try again in {$timeRemaining} or use a different email address.";
        }

        $remaining = self::getRemainingOTPRequests($email);
        if ($remaining < 3) {
            $requestsWord = $remaining === 1 ? 'request' : 'requests';
            return "You have {$remaining} {$requestsWord} remaining this hour.";
        }

        return "You can request verification codes.";
    }

}