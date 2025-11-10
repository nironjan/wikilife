<?php

namespace App\Livewire\Settings;

use App\Models\SiteSetting as ModelsSiteSetting;
use App\Services\ImageKitService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class SiteSetting extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $totalSteps = 5;

    // Basic Info
    public $site_name = '';
    public $tagline = '';
    public $site_email = '';
    public $site_phone = '';
    public $site_address = '';

    // Branding - File Uploads
    public $logo = null;
    public $favicon = null;
    public $default_image = null;
    public $og_image = null;
    public $twitter_image = null;

    // Existing images and file IDs
    public $existing_logo = null;
    public $existing_logo_file_id = null;
    public $existing_favicon = null;
    public $existing_favicon_file_id = null;
    public $existing_default_image = null;
    public $existing_default_image_file_id = null;
    public $existing_og_image = null;
    public $existing_og_image_file_id = null;
    public $existing_twitter_image = null;
    public $existing_twitter_image_file_id = null;

    // SEO Meta
    public $meta_title = '';
    public $meta_description = '';
    public $meta_keywords = '';

    // Social Media
    public $social_facebook = '';
    public $social_twitter = '';
    public $social_linkedin = '';
    public $social_instagram = '';
    public $social_youtube = '';

    // Open Graph
    public $og_title = '';
    public $og_description = '';

    // Twitter Meta
    public $twitter_title = '';
    public $twitter_description = '';

    // Analytics & Scripts
    public $header_scripts = '';
    public $footer_scripts = '';

    // Localization
    public $language = 'en';
    public $timezone = 'Asia/Kolkata';
    public $currency = 'INR';
    public $date_format = 'Y-m-d';

    // Maintenance
    public $maintenance_mode = false;
    public $maintenance_message = '';

    // SEO & Performance
    public $index_site = true;
    public $lazy_load_images = true;

    // Image optimization
    public $imageWidth = 800;
    public $imageHeight = 600;
    public $imageQuality = 80;

    protected $imageKitService;

    protected function rules()
    {
        $rules = [
            // Basic Info
            'site_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'site_email' => 'nullable|email|max:255',
            'site_phone' => 'nullable|string|max:20',
            'site_address' => 'nullable|string|max:500',
        ];

        if ($this->currentStep == 2) {
            $rules = array_merge($rules, [
                // Branding - File validation
                'logo' => 'nullable|image|max:2048',
                'favicon' => 'nullable|image|max:1024',
                'default_image' => 'nullable|image|max:5120',
                'og_image' => 'nullable|image|max:5120',
                'twitter_image' => 'nullable|image|max:5120',
            ]);
        }

        if ($this->currentStep == 3) {
            $rules = array_merge($rules, [
                // SEO Meta
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:255',
                'meta_keywords' => 'nullable|string|max:255',

                // Social Media
                'social_facebook' => 'nullable|url|max:255',
                'social_twitter' => 'nullable|url|max:255',
                'social_linkedin' => 'nullable|url|max:255',
                'social_instagram' => 'nullable|url|max:255',
                'social_youtube' => 'nullable|url|max:255',

                // Open Graph
                'og_title' => 'nullable|string|max:255',
                'og_description' => 'nullable|string|max:255',

                // Twitter
                'twitter_title' => 'nullable|string|max:255',
                'twitter_description' => 'nullable|string|max:255',
            ]);
        }

        if ($this->currentStep == 4) {
            $rules = array_merge($rules, [
                'social_facebook' => 'nullable|url|max:255',
                'social_twitter' => 'nullable|url|max:255',
                'social_linkedin' => 'nullable|url|max:255',
                'social_instagram' => 'nullable|url|max:255',
                'social_youtube' => 'nullable|url|max:255',
                'header_scripts' => 'nullable|string',
                'footer_scripts' => 'nullable|string',
            ]);
        }

        if ($this->currentStep == 5) {
            $rules = array_merge($rules, [
                'language' => 'required|string|max:10',
                'timezone' => 'required|string|max:50',
                'currency' => 'required|string|max:10',
                'date_format' => 'required|string|max:20',
                'imageWidth' => 'nullable|integer|min:100|max:4000',
                'imageHeight' => 'nullable|integer|min:100|max:4000',
                'imageQuality' => 'nullable|integer|min:10|max:100',
            ]);
        }
        return $rules;
    }

    public function boot()
    {
        $this->imageKitService = new ImageKitService();
    }

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $settings = ModelsSiteSetting::first();

        if ($settings) {
            // Basic Info
            $this->site_name = $settings->site_name;
            $this->tagline = $settings->tagline;
            $this->site_email = $settings->site_email;
            $this->site_phone = $settings->site_phone;
            $this->site_address = $settings->site_address;

            // Branding - Existing images and file IDs
            $this->existing_logo = $settings->logo_path;
            $this->existing_logo_file_id = $settings->logo_path_file_id;
            $this->existing_favicon = $settings->favicon_path;
            $this->existing_favicon_file_id = $settings->favicon_path_file_id;
            $this->existing_default_image = $settings->default_image_path;
            $this->existing_default_image_file_id = $settings->default_image_file_id;
            $this->existing_og_image = $settings->og_image;
            $this->existing_og_image_file_id = $settings->og_image_file_id;
            $this->existing_twitter_image = $settings->twitter_image;
            $this->existing_twitter_image_file_id = $settings->twitter_image_file_id;

            // SEO Meta
            $this->meta_title = $settings->meta_title;
            $this->meta_description = $settings->meta_description;
            $this->meta_keywords = $settings->meta_keywords;

            // Social Media
            $socialLinks = $settings->social_links ?? [];
            $this->social_facebook = $socialLinks['facebook'] ?? '';
            $this->social_twitter = $socialLinks['twitter'] ?? '';
            $this->social_linkedin = $socialLinks['linkedin'] ?? '';
            $this->social_instagram = $socialLinks['instagram'] ?? '';
            $this->social_youtube = $socialLinks['youtube'] ?? '';

            // Open Graph
            $this->og_title = $settings->og_title;
            $this->og_description = $settings->og_description;

            // Twitter
            $this->twitter_title = $settings->twitter_title;
            $this->twitter_description = $settings->twitter_description;

            // Scripts
            $headerScripts = $settings->header_scripts ?? [];
            $footerScripts = $settings->footer_scripts ?? [];
            $this->header_scripts = implode("\n", $headerScripts);
            $this->footer_scripts = implode("\n", $footerScripts);

            // Localization
            $this->language = $settings->language;
            $this->timezone = $settings->timezone;
            $this->currency = $settings->currency;
            $this->date_format = $settings->date_format;

            // Maintenance
            $this->maintenance_mode = $settings->maintenance_mode;
            $this->maintenance_message = $settings->maintenance_message;

            // SEO & Performance
            $this->index_site = $settings->index_site;
            $this->lazy_load_images = $settings->lazy_load_images;
        }
    }

    public function nextStep()
    {
        $this->validate();
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    /**
     * Remove logo image from ImageKit and database
     */
    public function removeLogo()
    {
        try {
            if ($this->existing_logo_file_id) {
                $settings = ModelsSiteSetting::first();
                if ($settings) {
                    // Delete from ImageKit
                    $this->imageKitService->deleteFile($this->existing_logo_file_id);

                    // Update database
                    $settings->update([
                        'logo_path' => null,
                        'logo_path_file_id' => null,
                    ]);
                }
            }

            $this->existing_logo = null;
            $this->existing_logo_file_id = null;
            $this->logo = null;

            Toaster::success('Logo removed successfully.');
        } catch (Exception $e) {
            Log::error('Failed to remove logo', [
                'error' => $e->getMessage(),
            ]);
            Toaster::error('Failed to remove logo: ' . $e->getMessage());
        }
    }

    /**
     * Remove favicon image from ImageKit and database
     */
    public function removeFavicon()
    {
        try {
            if ($this->existing_favicon_file_id) {
                $settings = ModelsSiteSetting::first();
                if ($settings) {
                    // Delete from ImageKit
                    $this->imageKitService->deleteFile($this->existing_favicon_file_id);

                    // Update database
                    $settings->update([
                        'favicon_path' => null,
                        'favicon_path_file_id' => null,
                    ]);
                }
            }

            $this->existing_favicon = null;
            $this->existing_favicon_file_id = null;
            $this->favicon = null;

            Toaster::success('Favicon removed successfully.');
        } catch (Exception $e) {
            Log::error('Failed to remove favicon', [
                'error' => $e->getMessage(),
            ]);
            Toaster::error('Failed to remove favicon: ' . $e->getMessage());
        }
    }

    /**
     * Remove default image from ImageKit and database
     */
    public function removeDefaultImage()
    {
        try {
            if ($this->existing_default_image_file_id) {
                $settings = ModelsSiteSetting::first();
                if ($settings) {
                    // Delete from ImageKit
                    $this->imageKitService->deleteFile($this->existing_default_image_file_id);

                    // Update database
                    $settings->update([
                        'default_image_path' => null,
                        'default_image_file_id' => null,
                    ]);
                }
            }

            $this->existing_default_image = null;
            $this->existing_default_image_file_id = null;
            $this->default_image = null;

            Toaster::success('Default image removed successfully.');
        } catch (Exception $e) {
            Log::error('Failed to remove default image', [
                'error' => $e->getMessage(),
            ]);
            Toaster::error('Failed to remove default image: ' . $e->getMessage());
        }
    }

    /**
     * Remove OG image from ImageKit and database
     */
    public function removeOgImage()
    {
        try {
            if ($this->existing_og_image_file_id) {
                $settings = ModelsSiteSetting::first();
                if ($settings) {
                    // Delete from ImageKit
                    $this->imageKitService->deleteFile($this->existing_og_image_file_id);

                    // Update database
                    $settings->update([
                        'og_image' => null,
                        'og_image_file_id' => null,
                    ]);
                }
            }

            $this->existing_og_image = null;
            $this->existing_og_image_file_id = null;
            $this->og_image = null;

            Toaster::success('OG image removed successfully.');
        } catch (Exception $e) {
            Log::error('Failed to remove OG image', [
                'error' => $e->getMessage(),
            ]);
            Toaster::error('Failed to remove OG image: ' . $e->getMessage());
        }
    }

    /**
     * Remove Twitter image from ImageKit and database
     */
    public function removeTwitterImage()
    {
        try {
            if ($this->existing_twitter_image_file_id) {
                $settings = ModelsSiteSetting::first();
                if ($settings) {
                    // Delete from ImageKit
                    $this->imageKitService->deleteFile($this->existing_twitter_image_file_id);

                    // Update database
                    $settings->update([
                        'twitter_image' => null,
                        'twitter_image_file_id' => null,
                    ]);
                }
            }

            $this->existing_twitter_image = null;
            $this->existing_twitter_image_file_id = null;
            $this->twitter_image = null;

            Toaster::success('Twitter image removed successfully.');
        } catch (Exception $e) {
            Log::error('Failed to remove Twitter image', [
                'error' => $e->getMessage(),
            ]);
            Toaster::error('Failed to remove Twitter image: ' . $e->getMessage());
        }
    }

    public function saveSettings()
    {
        $this->validate();

        try {
            $data = [
                // Basic Info
                'site_name' => $this->site_name,
                'tagline' => $this->tagline,
                'site_email' => $this->site_email,
                'site_phone' => $this->site_phone,
                'site_address' => $this->site_address,

                // SEO Meta
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,

                // Social Media
                'social_links' => [
                    'facebook' => $this->social_facebook,
                    'twitter' => $this->social_twitter,
                    'linkedin' => $this->social_linkedin,
                    'instagram' => $this->social_instagram,
                    'youtube' => $this->social_youtube,
                ],

                // Open Graph
                'og_title' => $this->og_title,
                'og_description' => $this->og_description,

                // Twitter
                'twitter_title' => $this->twitter_title,
                'twitter_description' => $this->twitter_description,

                // Scripts
                'header_scripts' => $this->header_scripts ? array_filter(array_map('trim', explode("\n", $this->header_scripts))) : [],
                'footer_scripts' => $this->footer_scripts ? array_filter(array_map('trim', explode("\n", $this->footer_scripts))) : [],

                // Localization
                'language' => $this->language,
                'timezone' => $this->timezone,
                'currency' => $this->currency,
                'date_format' => $this->date_format,

                // Maintenance
                'maintenance_mode' => $this->maintenance_mode,
                'maintenance_message' => $this->maintenance_message,

                // SEO & Performance
                'index_site' => $this->index_site,
                'lazy_load_images' => $this->lazy_load_images,
            ];

            DB::transaction(function () use ($data) {
                $settings = ModelsSiteSetting::first();

                if ($settings) {
                    $settings->update($data);
                } else {
                    $settings = ModelsSiteSetting::create($data);
                }

                // Upload images if provided
                if ($this->logo) {
                    $this->uploadLogo($settings);
                }
                if ($this->favicon) {
                    $this->uploadFavicon($settings);
                }
                if ($this->default_image) {
                    $this->uploadDefaultImage($settings);
                }
                if ($this->og_image) {
                    $this->uploadOgImage($settings);
                }
                if ($this->twitter_image) {
                    $this->uploadTwitterImage($settings);
                }
            });

            Toaster::success('Site settings updated successfully.');
            return redirect()->route('webmaster.site-setting');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Site settings save failed', [
                'error' => $e->getMessage(),
            ]);
            Toaster::error('Failed to save site settings: ' . $e->getMessage());
        }
    }

    protected function uploadLogo(ModelsSiteSetting $settings)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->logo,
                'site/logo/',
                $this->imageWidth ?: null,
                $this->imageHeight ?: null,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old logo if exists
            if ($settings->logo_path_file_id) {
                $this->imageKitService->deleteFile($settings->logo_path_file_id);
            }

            $settings->update([
                'logo_path' => $upload->optimizedUrl,
                'logo_path_file_id' => $upload->fileId,
            ]);

            $this->existing_logo = $upload->optimizedUrl;
            $this->existing_logo_file_id = $upload->fileId;
            $this->logo = null;

        } catch (Exception $e) {
            Log::error('Logo upload failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    protected function uploadFavicon(ModelsSiteSetting $settings)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->favicon,
                'site/favicon/',
                32, // Favicon specific size
                32,
                100, // High quality for favicon
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old favicon if exists
            if ($settings->favicon_path_file_id) {
                $this->imageKitService->deleteFile($settings->favicon_path_file_id);
            }

            $settings->update([
                'favicon_path' => $upload->optimizedUrl,
                'favicon_path_file_id' => $upload->fileId,
            ]);

            $this->existing_favicon = $upload->optimizedUrl;
            $this->existing_favicon_file_id = $upload->fileId;
            $this->favicon = null;

        } catch (Exception $e) {
            Log::error('Favicon upload failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    protected function uploadDefaultImage(ModelsSiteSetting $settings)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->default_image,
                'site/default/',
                $this->imageWidth ?: null,
                $this->imageHeight ?: null,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old default image if exists
            if ($settings->default_image_file_id) {
                $this->imageKitService->deleteFile($settings->default_image_file_id);
            }

            $settings->update([
                'default_image_path' => $upload->optimizedUrl,
                'default_image_file_id' => $upload->fileId,
            ]);

            $this->existing_default_image = $upload->optimizedUrl;
            $this->existing_default_image_file_id = $upload->fileId;
            $this->default_image = null;

        } catch (Exception $e) {
            Log::error('Default image upload failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    protected function uploadOgImage(ModelsSiteSetting $settings)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->og_image,
                'site/og/',
                1200, // OG image standard size
                630,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old OG image if exists
            if ($settings->og_image_file_id) {
                $this->imageKitService->deleteFile($settings->og_image_file_id);
            }

            $settings->update([
                'og_image' => $upload->optimizedUrl,
                'og_image_file_id' => $upload->fileId,
            ]);

            $this->existing_og_image = $upload->optimizedUrl;
            $this->existing_og_image_file_id = $upload->fileId;
            $this->og_image = null;

        } catch (Exception $e) {
            Log::error('OG image upload failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    protected function uploadTwitterImage(ModelsSiteSetting $settings)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->twitter_image,
                'site/twitter/',
                1200, // Twitter card standard size
                600,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old Twitter image if exists
            if ($settings->twitter_image_file_id) {
                $this->imageKitService->deleteFile($settings->twitter_image_file_id);
            }

            $settings->update([
                'twitter_image' => $upload->optimizedUrl,
                'twitter_image_file_id' => $upload->fileId,
            ]);

            $this->existing_twitter_image = $upload->optimizedUrl;
            $this->existing_twitter_image_file_id = $upload->fileId;
            $this->twitter_image = null;

        } catch (Exception $e) {
            Log::error('Twitter image upload failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Clean up temporary files when component is destroyed
     */
    public function cleanup()
    {
        if ($this->logo) {
            $this->logo->delete();
        }
        if ($this->favicon) {
            $this->favicon->delete();
        }
        if ($this->default_image) {
            $this->default_image->delete();
        }
        if ($this->og_image) {
            $this->og_image->delete();
        }
        if ($this->twitter_image) {
            $this->twitter_image->delete();
        }
    }

    public function render()
    {
        $languages = [
            'en' => 'English',
            'hi' => 'Hindi',
            'bn' => 'Bengali',
            'te' => 'Telugu',
            'ta' => 'Tamil',
        ];

        $timezones = [
            'Asia/Kolkata' => 'India Standard Time (IST)',
            'UTC' => 'Coordinated Universal Time (UTC)',
            'America/New_York' => 'Eastern Time (ET)',
            'Europe/London' => 'Greenwich Mean Time (GMT)',
        ];

        $currencies = [
            'INR' => 'Indian Rupee (₹)',
            'USD' => 'US Dollar ($)',
            'EUR' => 'Euro (€)',
            'GBP' => 'British Pound (£)',
        ];

        $dateFormats = [
            'Y-m-d' => 'YYYY-MM-DD (2024-01-15)',
            'd/m/Y' => 'DD/MM/YYYY (15/01/2024)',
            'm/d/Y' => 'MM/DD/YYYY (01/15/2024)',
            'd M Y' => 'DD MMM YYYY (15 Jan 2024)',
            'M d, Y' => 'MMM DD, YYYY (Jan 15, 2024)',
        ];

        return view('livewire.settings.site-setting', compact(
            'languages',
            'timezones',
            'currencies',
            'dateFormats'
        ));
    }
}
