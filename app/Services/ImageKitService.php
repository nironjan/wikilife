<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageKitService
{
    protected $privateKey;
    protected $publicKey;
    protected $urlEndpoint;

    public function __construct()
    {
        $this->privateKey = config('services.imagekit.private_key');
        $this->publicKey = config('services.imagekit.public_key');
        $this->urlEndpoint = config('services.imagekit.url_endpoint');
    }

    /**
     * Upload Livewire file with ImageKit transformations
     * Best for performance and scalability
     */
    public function uploadFile($livewireFile, $folder = '/products', $width = null, $height = null, $quality = 85)
    {
        try {
            $fileName = $this->generateFileName($livewireFile);

            $payload = [
                'fileName' => $fileName,
                'useUniqueFileName' => 'true',
                'folder' => $folder
            ];

            // Upload original file quickly
            $tempFile = tempnam(sys_get_temp_dir(), 'imgkit_');
            file_put_contents($tempFile, $livewireFile->get());

            $response = Http::withBasicAuth($this->privateKey, '')
                ->timeout(30)
                ->withOptions(['verify' => false])
                ->attach('file', fopen($tempFile, 'r'), $fileName)
                ->post('https://upload.imagekit.io/api/v1/files/upload', $payload);

            @unlink($tempFile);

            if ($response->successful()) {
                $data = $response->json();

                // Generate optimized URL with transformations
                $optimizedUrl = $this->applyTransformations(
                    $data['filePath'],
                    $width,
                    $height,
                    $quality
                );

                return (object) [
                    'success' => true,
                    'url' => $data['url'], // Original URL
                    'optimizedUrl' => $optimizedUrl, // Optimized URL with transformations
                    'fileId' => $data['fileId'],
                    'filePath' => $data['filePath'],
                    'name' => $data['name'],
                    'size' => $data['size'],
                    'width' => $width ?: $data['width'] ?? null,
                    'height' => $height ?: $data['height'] ?? null,
                    'data' => $data
                ];
            }

            return (object) [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Upload failed'
            ];

        } catch (\Exception $e) {
            Log::error('ImageKit upload failed', [
                'message' => $e->getMessage(),
                'file' => $livewireFile->getClientOriginalName()
            ]);

            return (object) [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Apply ImageKit URL transformations (server-side optimization)
     */
    protected function applyTransformations($filePath, $width = null, $height = null, $quality = 85)
    {
        $transformations = [];

        // Dimensions
        if ($width && $height) {
            $transformations[] = "w-{$width}";
            $transformations[] = "h-{$height}";
            $transformations[] = 'c-at_max'; // Maintain aspect ratio
        } elseif ($width) {
            $transformations[] = "w-{$width}";
        } elseif ($height) {
            $transformations[] = "h-{$height}";
        }

        // Quality optimization
        if ($quality) {
            $transformations[] = "q-{$quality}";
        }

        // Auto-optimization features
        $transformations[] = 'f-auto'; // Auto format (WebP/AVIF if supported)
        $transformations[] = 'pr-true'; // Progressive loading

        if (empty($transformations)) {
            return $this->urlEndpoint . $filePath;
        }

        $transformationString = implode(',', $transformations);
        return $this->urlEndpoint . '/tr:' . $transformationString . $filePath;
    }

    /**
     * Generate unique file name
     */
    protected function generateFileName($livewireFile)
    {
        $originalName = pathinfo($livewireFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $livewireFile->getClientOriginalExtension();

        return $originalName . '_' . time() . '.' . $extension;
    }

    /**
     * Get URL with custom transformations
     */

    public function getUrlWithTransformations($filePath, $width = null, $height = null, $quality = null)
    {
        $transformations = [];

        if ($width && $height) {
            $transformations[] = "w-{$width}";
            $transformations[] = "h-{$height}";
            $transformations[] = 'c-at_max';
        } elseif ($width) {
            $transformations[] = "w-{$width}";
        } elseif ($height) {
            $transformations[] = "h-{$height}";
        }

        if ($quality) {
            $transformations[] = "q-{$quality}";
        }

        $transformations[] = 'pr-true';
        $transformations[] = 'f-auto';

        if (empty($transformations)) {
            return $this->urlEndpoint . $filePath;
        }

        $transformationString = implode(',', $transformations);
        return $this->urlEndpoint . '/tr:' . $transformationString . $filePath;
    }

    /**
     * Delete file from ImageKit
     */
    public function deleteFile($fileId)
    {
        try {
            $response = Http::withBasicAuth($this->privateKey, '')
                ->withOptions(['verify' => false])
                ->delete("https://api.imagekit.io/v1/files/{$fileId}");

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('ImageKit delete failed', [
                'fileId' => $fileId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
