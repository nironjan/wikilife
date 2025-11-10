<?php

namespace App\Helpers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettingsHelper
{
    private static $settings;

    /**
     * Get all site settings with caching
     */
    public static function getSettings(): ?SiteSetting
    {
        if (self::$settings) {
            return self::$settings;
        }

        self::$settings = Cache::remember('site_settings', 3600, function () {
            return SiteSetting::first();
        });

        return self::$settings;
    }

    /**
     * Clear the settings cache (call this when settings are updated)
     */
    public static function clearCache(): void
    {
        Cache::forget('site_settings');
        self::$settings = null;
    }

    /**
     * Basic Site Information
     */
    public static function siteName(): string
    {
        return self::getSettings()->site_name ?? config('app.name', 'Default Site Name');
    }

    public static function tagline(): ?string
    {
        return self::getSettings()->tagline ?? null;
    }

    public static function siteEmail(): ?string
    {
        return self::getSettings()->site_email ?? null;
    }

    public static function sitePhone(): ?string
    {
        return self::getSettings()->site_phone ?? null;
    }

    public static function siteAddress(): ?string
    {
        return self::getSettings()->site_address ?? null;
    }

    /**
     * Images & Media
     */
    public static function logo($width = null, $height = null, $quality = 80): ?string
    {
        $settings = self::getSettings();
        if (!$settings?->logo_path) {
            return asset('images/default-logo.png');
        }

        return $settings->getImageSize('logo', $width, $height, $quality);
    }

    public static function favicon($width = 32, $height = 32, $quality = 80): ?string
    {
        $settings = self::getSettings();
        if (!$settings?->favicon_path) {
            return asset('images/default-favicon.ico');
        }

        return $settings->getImageSize('favicon', $width, $height, $quality);
    }

    public static function defaultImage($width = null, $height = null, $quality = 80): ?string
    {
        $settings = self::getSettings();
        if (!$settings?->default_image_path) {
            return asset('images/default-image.jpg');
        }

        return $settings->getImageSize('default_image', $width, $height, $quality);
    }

    public static function ogImage($width = 1200, $height = 630, $quality = 80): ?string
    {
        $settings = self::getSettings();
        if (!$settings?->og_image) {
            return self::defaultImage($width, $height, $quality);
        }

        return $settings->getImageSize('og_image', $width, $height, $quality);
    }

    public static function twitterImage($width = 1200, $height = 600, $quality = 80): ?string
    {
        $settings = self::getSettings();
        if (!$settings?->twitter_image) {
            return self::defaultImage($width, $height, $quality);
        }

        return $settings->getImageSize('twitter_image', $width, $height, $quality);
    }

    /**
     * SEO Meta Information
     */
    public static function metaTitle(): string
    {
        return self::getSettings()->meta_title ?? self::siteName();
    }

    public static function metaDescription(): ?string
    {
        return self::getSettings()->meta_description ?? self::tagline();
    }

    public static function metaKeywords(): ?string
    {
        return self::getSettings()->meta_keywords ?? null;
    }

    public static function ogTitle(): ?string
    {
        return self::getSettings()->og_title ?? self::metaTitle();
    }

    public static function ogDescription(): ?string
    {
        return self::getSettings()->og_description ?? self::metaDescription();
    }

    public static function twitterTitle(): ?string
    {
        return self::getSettings()->twitter_title ?? self::metaTitle();
    }

    public static function twitterDescription(): ?string
    {
        return self::getSettings()->twitter_description ?? self::metaDescription();
    }

    /**
     * Social Media Links
     */
    public static function socialLinks(): array
    {
        return self::getSettings()->social_links ?? [];
    }

    public static function socialLink($platform): ?string
    {
        $links = self::socialLinks();
        return $links[$platform] ?? null;
    }

    /**
     * Configuration & Settings
     */
    public static function language(): string
    {
        return self::getSettings()->language ?? config('app.locale', 'en');
    }

    public static function timezone(): string
    {
        return self::getSettings()->timezone ?? config('app.timezone', 'UTC');
    }

    public static function currency(): string
    {
        return self::getSettings()->currency ?? 'USD';
    }

    public static function dateFormat(): string
    {
        return self::getSettings()->date_format ?? 'Y-m-d';
    }

    public static function isMaintenanceMode(): bool
    {
        return self::getSettings()->maintenance_mode ?? false;
    }

    public static function maintenanceMessage(): ?string
    {
        return self::getSettings()->maintenance_message ?? 'Site is under maintenance';
    }

    public static function shouldIndexSite(): bool
    {
        return self::getSettings()->index_site ?? true;
    }

    public static function lazyLoadImages(): bool
    {
        return self::getSettings()->lazy_load_images ?? true;
    }

    /**
     * Extra/Custom Settings
     */
    public static function extra($key = null, $default = null)
    {
        $extra = self::getSettings()->extra ?? [];

        if ($key === null) {
            return $extra;
        }

        return $extra[$key] ?? $default;
    }
}
