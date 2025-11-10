<?php

namespace App\Models;

use App\Services\ImageKitService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $table = 'site_settings';

    protected $fillable = [
        'site_name',
        'tagline',
        'site_email',
        'site_phone',
        'site_address',
        'logo_path',
        'logo_path_file_id',
        'favicon_path',
        'favicon_path_file_id',
        'default_image_path',
        'default_image_file_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'social_links',
        'og_title',
        'og_description',
        'og_image',
        'og_image_file_id',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'twitter_image_file_id',
        'header_scripts',
        'footer_scripts',
        'language',
        'timezone',
        'currency',
        'date_format',
        'maintenance_mode',
        'maintenance_message',
        'index_site',
        'lazy_load_images',
        'extra',
    ];

    protected $casts = [
        'social_links' => 'array',
        'header_scripts' => 'array',
        'footer_scripts' => 'array',
        'extra' => 'array',
        'maintenance_mode' => 'boolean',
        'index_site' => 'boolean',
        'lazy_load_images' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        // Clear cache on save
        static::saved(function ($settings) {
            \App\Helpers\SiteSettingsHelper::clearCache();
        });

        static::deleted(function ($settings) {
            \App\Helpers\SiteSettingsHelper::clearCache();
        });

        // Delete images from ImageKit when settings are updated with null values
        static::updating(function ($settings) {
            $original = $settings->getOriginal();

            // Delete logo if being removed
            if ($settings->logo_path === null && $original['logo_path_file_id']) {
                app(ImageKitService::class)->deleteFile($original['logo_path_file_id']);
            }

            // Delete favicon if being removed
            if ($settings->favicon_path === null && $original['favicon_path_file_id']) {
                app(ImageKitService::class)->deleteFile($original['favicon_path_file_id']);
            }

            // Delete default image if being removed
            if ($settings->default_image_path === null && $original['default_image_file_id']) {
                app(ImageKitService::class)->deleteFile($original['default_image_file_id']);
            }

            // Delete OG image if being removed
            if ($settings->og_image === null && $original['og_image_file_id']) {
                app(ImageKitService::class)->deleteFile($original['og_image_file_id']);
            }

            // Delete Twitter image if being removed
            if ($settings->twitter_image === null && $original['twitter_image_file_id']) {
                app(ImageKitService::class)->deleteFile($original['twitter_image_file_id']);
            }
        });
    }

    /**
     * Get image with custom size using ImageKitService
     */
    public function getImageSize($imageType, $width = null, $height = null, $quality = null): ?string
    {
        $imagePath = null;
        $fileId = null;

        switch ($imageType) {
            case 'logo':
                $imagePath = $this->logo_path;
                $fileId = $this->logo_path_file_id;
                break;
            case 'favicon':
                $imagePath = $this->favicon_path;
                $fileId = $this->favicon_path_file_id;
                break;
            case 'default_image':
                $imagePath = $this->default_image_path;
                $fileId = $this->default_image_file_id;
                break;
            case 'og_image':
                $imagePath = $this->og_image;
                $fileId = $this->og_image_file_id;
                break;
            case 'twitter_image':
                $imagePath = $this->twitter_image;
                $fileId = $this->twitter_image_file_id;
                break;
        }

        if (!$imagePath) {
            return null;
        }

        // If no custom size requested, return original optimized image
        if (!$width && !$height && !$quality) {
            return $imagePath;
        }

        // Use ImageKitService to generate custom size
        return app(ImageKitService::class)->getUrlWithTransformations(
            $this->extractBaseFilePath($imagePath),
            $width,
            $height,
            $quality
        );
    }

    /**
     * Extract base file path from ImageKit URL
     */
    protected function extractBaseFilePath($imageKitUrl): ?string
    {
        try {
            $parsedUrl = parse_url($imageKitUrl);

            if (!isset($parsedUrl['path'])) {
                return null;
            }

            $path = $parsedUrl['path'];

            // Remove transformation part to get base path
            if (str_contains($path, '/tr:')) {
                $parts = explode('/tr:', $path, 2);
                if (isset($parts[1])) {
                    $transformationAndPath = $parts[1];
                    $firstSlash = strpos($transformationAndPath, '/');
                    if ($firstSlash !== false) {
                        return substr($transformationAndPath, $firstSlash);
                    }
                }
            }

            return $path;

        } catch (\Exception $e) {
            return null;
        }
    }
}
