<?php

use App\Helpers\SiteSettingsHelper;

if (!function_exists('site_settings')) {
    function site_settings() {
        return SiteSettingsHelper::getSettings();
    }
}

if (!function_exists('site_name')) {
    function site_name() {
        return SiteSettingsHelper::siteName();
    }
}

if (!function_exists('site_logo')) {
    function site_logo($width = null, $height = null, $quality = 80) {
        return SiteSettingsHelper::logo($width, $height, $quality);
    }
}

if (!function_exists('site_favicon')) {
    function site_favicon($width = 32, $height = 32, $quality = 80) {
        return SiteSettingsHelper::favicon($width, $height, $quality);
    }
}

if (!function_exists('default_image')) {
    function default_image($width = null, $height = null, $quality = 80) {
        return SiteSettingsHelper::defaultImage($width, $height, $quality);
    }
}

if (!function_exists('meta_title')) {
    function meta_title() {
        return SiteSettingsHelper::metaTitle();
    }
}

if (!function_exists('meta_description')) {
    function meta_description() {
        return SiteSettingsHelper::metaDescription();
    }
}

if (!function_exists('og_image')) {
    function og_image($width = 1200, $height = 630, $quality = 80) {
        return SiteSettingsHelper::ogImage($width, $height, $quality);
    }
}

if (!function_exists('social_links')) {
    function social_links() {
        return SiteSettingsHelper::socialLinks();
    }
}
