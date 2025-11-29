<?php

namespace App\Models;

use App\Services\ImageKitService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'official_website',
        'description',
        'banner_image',
        'banner_img_caption',
        'banner_file_id',
        'official_email',
        'signature_url',
        'signature_file_id',
    ];


    // ========== RELATIONSS ================
    public function person()
    {
        return $this->belongsTo(People::class);
    }

    // =========== ATTRIBUTES ===============

    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ?? null;
    }


    public function getSignatureUrlAttribute()
    {
        return $this->signature_url ?? null;
    }


    /**
     * Get image with custom size using ImageKitService
     * Usage: $product->imageSize(400, 300) or $product->imageSize(800)
     */
    public function imageSize($width = null, $height = null, $quality = null): ?string
    {
        $image = $this->images->first();

        if (!$image || !$image->image_path) {
            return null;
        }

        // If no custom size requested, return original optimized image
        if (!$width && !$height && !$quality) {
            return $image->image_path;
        }

        // Use ImageKitService to generate custom size
        return app(ImageKitService::class)->getUrlWithTransformations(
            $this->extractBaseFilePath($image->image_path),
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
