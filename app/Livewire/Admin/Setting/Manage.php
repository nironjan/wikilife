<?php

namespace App\Livewire\Admin\Setting;

use App\Models\MediaProfile;
use App\Models\People;
use App\Models\SocialLinks;
use App\Services\ImageKitService;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class Manage extends Component
{
    use WithFileUploads;

    public $personId;
    public $person;
    public $activeTab = 'seo';

    // SEO Meta Fields
    public $seo_meta_title = '';
    public $seo_meta_description = '';
    public $seo_meta_keywords = '';
    public $seo_tags = '';

    // Assets Fields
    public $assets_currency = 'INR';
    public $assets_income = '';
    public $assets_income_source = '';
    public $assets_current_assets = '';
    public $assets_net_worth = '';
    public $assets_year_estimated = '';
    public $assets_references = [];

    // Media Profile Fields
    public $media_official_website = '';
    public $media_description = '';
    public $media_official_email = '';
    public $media_banner_image = null;
    public $media_banner_img_caption = null;
    public $media_existing_banner_image = null;
    public $media_signature = null;
    public $media_existing_signature = null;

    // Social Links Fields
    public $social_links = [];
    public $new_social_platform = '';
    public $new_social_url = '';
    public $new_social_username = '';
    public $new_social_icon = '';

    // Image optimization
    public $imageWidth = 1200;
    public $imageHeight = 400;
    public $imageQuality = 80;

    protected function rules()
    {

        return [
            // SEO Meta Rules
            'seo_meta_title' => 'nullable|string|max:60',
            'seo_meta_description' => 'nullable|string|max:160',
            'seo_meta_keywords' => 'nullable|string|max:255',
            'seo_tags' => 'nullable|string|max:500',

            // Assets Rules
            'assets_currency' => 'required|string|max:10',
            'assets_income' => 'nullable|numeric|min:0',
            'assets_income_source' => 'nullable|string|max:255',
            'assets_current_assets' => 'nullable|numeric|min:0',
            'assets_net_worth' => 'nullable|numeric|min:0',
            'assets_year_estimated' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'assets_references' => 'nullable|array',
            'assets_references.*.name' => 'required_with:assets_references.*.url|string|max:255',
            'assets_references.*.url' => 'required_with:assets_references.*.name|url|max:500',

            // Media Profile Rules
            'media_official_website' => 'nullable|url|max:255',
            'media_description' => 'nullable|string',
            'media_official_email' => 'nullable|email|max:255',
            'media_banner_image' => 'nullable|image|max:5120',
            'media_banner_img_caption' => 'nullable|string|max:255',
            'media_signature' => 'nullable|image|max:2048',

            // Social Links Rules
            'new_social_platform' => 'nullable|string|max:50',
            'new_social_url' => 'nullable|url|max:255',
            'new_social_username' => 'nullable|string|max:100',
            'new_social_icon' => 'nullable|string|max:50',
        ];
    }


    public function mount($id)
    {
        $this->personId = $id;
        $this->person = People::findOrFail($id);
        $this->loadPersonData();
    }

    public function loadPersonData()
    {
        $person = People::with(['seo', 'assets', 'mediaProfile', 'socialLinks'])->findOrFail($this->personId);

        // Load SEO Meta
        if ($person->seo) {
            $this->seo_meta_title = $person->seo->meta_title;
            $this->seo_meta_description = $person->seo->meta_description;
            $this->seo_meta_keywords = $person->seo->meta_keywords;
            $this->seo_tags = $person->seo->tags;
        }

        // Load Assets
        if ($person->assets) {
            $this->assets_currency = $person->assets->currency ?? 'INR';
            $this->assets_income = $person->assets->income;
            $this->assets_income_source = $person->assets->income_source;
            $this->assets_current_assets = $person->assets->current_assets;
            $this->assets_net_worth = $person->assets->net_worth;
            $this->assets_year_estimated = $person->assets->year_estimated;

            // Convert JSON references to array format for the form
            $this->assets_references = $this->convertReferencesToForm($person->assets->references);
        } else {
            // Initialize empty references array if no assets exist
            $this->assets_references = [['name' => '', 'url' => '']];
        }

        // Load Media Profile
        if ($person->mediaProfile) {
            $this->media_official_website = $person->mediaProfile->official_website;
            $this->media_description = $person->mediaProfile->description;
            $this->media_official_email = $person->mediaProfile->official_email;
            $this->media_existing_banner_image = $person->mediaProfile->banner_image;
            $this->media_banner_img_caption = $person->media_banner_img_caption;
            $this->media_existing_signature = $person->mediaProfile->signature_url;
        }

        // Load Social Links
        $this->social_links = $person->socialLinks->toArray() ?? [];
    }

    /**
     * Convert JSON references data to name-url pairs for the form
     */
    public function convertReferencesToForm($references): array
    {
        if (empty($references) || !is_array($references)) {
            return [['name' => '', 'url' => '']];
        }

        $formReferences = [];
        foreach ($references as $reference) {
            if (isset($reference['name']) && isset($reference['url'])) {
                $formReferences[] = [
                    'name' => $reference['name'],
                    'url' => $reference['url']
                ];
            }
        }

        return empty($formReferences) ? [['name' => '', 'url' => '']] : $formReferences;
    }

    /**
     * Convert form references to JSON format for storage
     */
    protected function convertReferencesToJson($formReferences): array
    {
        $result = [];
        foreach ($formReferences as $reference) {
            if (!empty($reference['name']) && !empty($reference['url'])) {
                $result[] = [
                    'name' => $reference['name'],
                    'url' => $reference['url']
                ];
            }
        }
        return $result;
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    // SEO Meta Methods
    public function saveSeoMeta()
    {
        $this->validate([
            'seo_meta_title' => 'nullable|string|max:100',
            'seo_meta_description' => 'nullable|string|max:200',
            'seo_meta_keywords' => 'nullable|string|max:255',
            'seo_tags' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () {
                $seoData = [
                    'meta_title' => $this->seo_meta_title,
                    'meta_description' => $this->seo_meta_description,
                    'meta_keywords' => $this->seo_meta_keywords,
                    'tags' => $this->seo_tags,
                ];

                $person = People::findOrFail($this->personId);

                if ($person->seo) {
                    $person->seo->update($seoData);
                } else {
                    $person->seo()->create($seoData);
                }
            });

            Toaster::success('SEO Meta updated successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to save SEO Meta: ' . $e->getMessage());
        }
    }

    // Assets Methods
    public function saveAssets()
    {
        $this->validate([
            'assets_currency' => 'required|string|max:10',
            'assets_income' => 'nullable|numeric|min:0',
            'assets_income_source' => 'nullable|string|max:255',
            'assets_current_assets' => 'nullable|numeric|min:0',
            'assets_net_worth' => 'nullable|numeric|min:0',
            'assets_year_estimated' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'assets_references' => 'nullable|array',
            'assets_references.*.name' => 'required_with:assets_references.*.url|string|max:255',
            'assets_references.*.url' => 'required_with:assets_references.*.name|url|max:500',
        ]);

        try {
            DB::transaction(function () {
                $assetsData = [
                    'currency' => $this->assets_currency,
                    'income' => $this->assets_income ?: null,
                    'income_source' => $this->assets_income_source,
                    'current_assets' => $this->assets_current_assets ?: null,
                    'net_worth' => $this->assets_net_worth ?: null,
                    'year_estimated' => $this->assets_year_estimated ?: null,

                    'references' => $this->convertReferencesToJson($this->assets_references),
                ];

                $person = People::findOrFail($this->personId);

                if ($person->assets) {
                    $person->assets->update($assetsData);
                } else {
                    $person->assets()->create($assetsData);
                }
            });

            Toaster::success('Assets information updated successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to save assets: ' . $e->getMessage());
        }
    }

    /**
     * Add a new reference field
     */
    public function addReference()
    {
        $this->assets_references[] = ['name' => '', 'url' => ''];
    }

    /**
     * Remove a reference field
     */
    public function removeReference($index)
    {
        unset($this->assets_references[$index]);
        $this->assets_references = array_values($this->assets_references);
    }

    // Save media Profile Methods
    public function saveMediaProfile()
    {
        $this->validate([
            'media_official_website' => 'nullable|url|max:255',
            'media_description' => 'nullable|string',
            'media_official_email' => 'nullable|email|max:255',
            'media_banner_image' => 'nullable|image|max:5120',
            'media_banner_img_caption' => 'nullable|string|max:255',
            'media_signature' => 'nullable|image|max:2048',
        ]);

        try {
            DB::transaction(function () {
                $mediaData = [
                    'official_website' => $this->media_official_website,
                    'description' => $this->media_description,
                    'official_email' => $this->media_official_email,
                    'banner_img_caption' => $this->media_banner_img_caption,
                ];

                $person = People::findOrFail($this->personId);

                if ($person->mediaProfile) {
                    $mediaProfile = $person->mediaProfile;
                    $mediaProfile->update($mediaData);
                } else {
                    $mediaProfile = $person->mediaProfile()->create($mediaData);
                }

                // Upload banner image if provided
                if ($this->media_banner_image) {
                    $this->uploadBannerImage($mediaProfile);
                }

                // Upload signature if provided
                if ($this->media_signature) {
                    $this->uploadSignature($mediaProfile);
                }
            });

            Toaster::success('Media profile updated successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to save media profile: ' . $e->getMessage());
        }
    }

    protected function uploadBannerImage(MediaProfile $mediaProfile)
    {
        try {
            $imageKitService = app(ImageKitService::class);

            $upload = $imageKitService->uploadFile(
                $this->media_banner_image,
                'people/banners/',
                $this->imageWidth ?: null,
                $this->imageHeight ?: null,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            $mediaProfile->update([
                'banner_image' => $upload->optimizedUrl,
                'banner_file_id' => $upload->fileId,
            ]);

            $this->media_existing_banner_image = $upload->optimizedUrl;
            $this->media_banner_image = null;

        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function uploadSignature(MediaProfile $mediaProfile)
    {
        try {
            $imageKitService = app(ImageKitService::class);

            $upload = $imageKitService->uploadFile(
                $this->media_signature,
                'people/signatures/',
                400,
                200,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            $mediaProfile->update([
                'signature_url' => $upload->optimizedUrl,
                'signature_file_id' => $upload->fileId,
            ]);

            $this->media_existing_signature = $upload->optimizedUrl;
            $this->media_signature = null;

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function removeBannerImage()
    {
        try {
            $person = People::findOrFail($this->personId);
            if ($person->mediaProfile && $person->mediaProfile->banner_file_id) {
                app(ImageKitService::class)->deleteFile($person->mediaProfile->banner_file_id);
                $person->mediaProfile->update([
                    'banner_image' => null,
                    'banner_file_id' => null,
                ]);
                $this->media_existing_banner_image = null;
            }
            Toaster::success('Banner image removed successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to remove banner image.');
        }
    }

    public function removeSignature()
    {
        try {
            $person = People::findOrFail($this->personId);
            if ($person->mediaProfile && $person->mediaProfile->signature_file_id) {
                app(ImageKitService::class)->deleteFile($person->mediaProfile->signature_file_id);
                $person->mediaProfile->update([
                    'signature_url' => null,
                    'signature_file_id' => null,
                ]);
                $this->media_existing_signature = null;
            }
            Toaster::success('Signature removed successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to remove signature.');
        }
    }


    // Social Links Methods
    public function addSocialLink()
    {
        $this->validate([
            'new_social_platform' => 'required|string|max:50',
            'new_social_url' => 'required|url|max:255',
            'new_social_username' => 'nullable|string|max:100',
            'new_social_icon' => 'nullable|string|max:50',
        ]);

        try {
            $socialLink = SocialLinks::create([
                'person_id' => $this->personId,
                'platform' => $this->new_social_platform,
                'url' => $this->new_social_url,
                'username' => $this->new_social_username,
                'icon' => $this->new_social_icon,
            ]);

            $this->social_links[] = $socialLink->toArray();

            // Reset form
            $this->new_social_platform = '';
            $this->new_social_url = '';
            $this->new_social_username = '';
            $this->new_social_icon = '';

            Toaster::success('Social link added successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to add social link: ' . $e->getMessage());
        }
    }

    public function removeSocialLink($index)
    {
        try {
            $socialLink = $this->social_links[$index] ?? null;
            if ($socialLink && isset($socialLink['id'])) {
                SocialLinks::find($socialLink['id'])->delete();
                unset($this->social_links[$index]);
                $this->social_links = array_values($this->social_links);
                Toaster::success('Social link removed successfully.');
            }
        } catch (Exception $e) {
            Toaster::error('Failed to remove social link.');
        }
    }

    public function updateSocialLinks()
    {
        try {
            foreach ($this->social_links as $index => $link) {
                if (isset($link['id'])) {
                    SocialLinks::find($link['id'])->update([
                        'platform' => $link['platform'],
                        'url' => $link['url'],
                        'username' => $link['username'],
                        'icon' => $link['icon'],
                    ]);
                }
            }
            Toaster::success('Social links updated successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to update social links.');
        }
    }

    /**
     * Clean up temporary files when component is destroyed
     */
    public function cleanup()
    {
        if ($this->media_banner_image) {
            $this->media_banner_image->delete();
        }
        if ($this->media_signature) {
            $this->media_signature->delete();
        }
    }


    public function render()
    {
        $person = People::findOrFail($this->personId);

        $commonPlatforms = config('platforms');

        // Get social icons from config
        $socialIcons = config('social-icons.icons', []);


        // Extract just the platform names
        $commonIcons = array_keys($socialIcons);

        $currencies = config('currencies');


        return view('livewire.admin.setting.manage', compact(
            'person',
            'commonPlatforms',
            'commonIcons',
            'currencies',
            'socialIcons',
        ));
    }
}
