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

if(!function_exists('header_scripts')) {
    function header_scripts() {
        return SiteSettingsHelper::headerScripts();
    }
}
if(!function_exists('footer_scripts')) {
    function footer_scripts() {
        return SiteSettingsHelper::footerScripts();
    }
}

// Quill list conversion helper
if (!function_exists('convertQuillLists')) {
    function convertQuillLists($content) {
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new DOMXPath($dom);

        // Remove Quill UI elements
        $uiElements = $xpath->query('//span[contains(@class, "ql-ui")]');
        foreach ($uiElements as $element) {
            $element->parentNode->removeChild($element);
        }

        // Remove data-list attributes and Quill classes
        $listItems = $xpath->query('//li[@data-list]');
        foreach ($listItems as $item) {
            $item->removeAttribute('data-list');
            $classes = $item->getAttribute('class');
            if ($classes) {
                $newClasses = preg_replace('/\bql-indent-\d+\b/', '', $classes);
                $newClasses = trim(preg_replace('/\s+/', ' ', $newClasses));
                if ($newClasses) {
                    $item->setAttribute('class', $newClasses);
                } else {
                    $item->removeAttribute('class');
                }
            }
        }

        return $dom->saveHTML();
    }
}
