<?php

namespace App\Livewire\Admin\Award;

use App\Models\People;
use App\Models\PersonAward;
use App\Services\ImageKitService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class Manage extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;
    public int $currentStep = 1;
    public bool $autoSlug = true;

    // Award fields
    public $person_id = '';
    public $award_name = '';
    public $slug = '';
    public $description = '';
    public $awarded_at = '';
    public $award_image = null;
    public $category = '';
    public $organization = '';
    public $is_verified = false;
    public $sort_order = 0;

    // Search fields
    public $person_search = '';
    public $existing_award_image = null;
    public $existing_award_image_file_id = null;

    // Image optimization
    public $imageWidth = 800;
    public $imageHeight = 600;
    public $imageQuality = 85;

    protected $imageKitService;

    /**
     * Get validation rules
     */
    protected function rules()
    {
        return [
            'person_id' => 'required|exists:people,id',
            'award_name' => 'required|string|max:255',
            'slug' => ['required', 'string', Rule::unique('person_awards', 'slug')->ignore($this->editingId)],
            'description' => 'nullable|string',
            'awarded_at' => 'nullable|date',
            'award_image' => 'nullable|image|max:5120',
            'category' => 'nullable|string|max:100',
            'organization' => 'required|string|max:255',
            'is_verified' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',

            'imageWidth' => 'nullable|integer|min:100|max:4000',
            'imageHeight' => 'nullable|integer|min:100|max:4000',
            'imageQuality' => 'nullable|integer|min:10|max:100',
        ];
    }

    public function boot()
    {
        $this->imageKitService = new ImageKitService();
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadAward($id);
            $this->autoSlug = false;
        } else {
            $this->autoSlug = true;
        }
    }

    public function loadAward($id)
    {
        $award = PersonAward::findOrFail($id);

        $this->person_id = $award->person_id;
        $this->award_name = $award->award_name;
        $this->slug = $award->slug;
        $this->description = $award->description;
        $this->awarded_at = optional($award->awarded_at)->format('Y-m-d');
        $this->existing_award_image = $award->awarde_image_url;
        $this->existing_award_image_file_id = $award->award_image_file_id;
        $this->category = $award->category;
        $this->organization = $award->organization;
        $this->is_verified = $award->is_verified;
        $this->sort_order = $award->sort_order;

        // Pre-fill search field
        if ($award->person) {
            $this->person_search = $award->person->display_name;
        }
    }

    /**
     * Get filtered people based on search
     */
    public function getPeopleProperty()
    {
        return People::active()
            ->when($this->person_search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->person_search}%")
                        ->orWhere('full_name', 'like', "%{$this->person_search}%");
                });
            })
            ->orderBy('name')
            ->limit(50)
            ->get();
    }

    /**
     * Auto-generate slug when award name changes
     */
    public function updatedAwardName($value)
    {
        if ($this->autoSlug && !empty($value)) {
            $this->generateSlug();
        }
    }

    /**
     * Generate slug from award name
     */
    public function generateSlug()
    {
        if ($this->autoSlug && !empty($this->award_name)) {
            $baseSlug = Str::slug($this->award_name);
            $this->slug = $baseSlug;

            // Check uniqueness
            $this->ensureUniqueSlug();
        }
    }

    /**
     * Ensure the generated slug is unique
     */
    protected function ensureUniqueSlug()
    {
        $baseSlug = $this->slug;
        $counter = 1;

        while (
        PersonAward::where('slug', $this->slug)
            ->when($this->editingId, fn($q) => $q->where('id', '!=', $this->editingId))
            ->exists()
        ) {
            $this->slug = $baseSlug . '-' . $counter;
            $counter++;

            if ($counter > 100)
                break;
        }
    }

    /**
     * When user manually edits slug, disable auto-generation
     */
    public function updatedSlug($value)
    {
        if (!empty($value) && $value !== Str::slug($this->award_name)) {
            $this->autoSlug = false;
        }

        if (empty($value)) {
            $this->autoSlug = true;
            $this->generateSlug();
        }
    }

    /**
     * Reset to auto-generated slug
     */
    public function resetSlug()
    {
        $this->autoSlug = true;
        $this->generateSlug();
        Toaster::success('Slug reset to auto-generated value.');
    }

    /**
     * When person is selected, update search field
     */
    public function updatedPersonId($value)
    {
        if ($value) {
            $person = People::find($value);
            if ($person) {
                $this->person_search = $person->display_name;
            }
        } else {
            $this->person_search = '';
        }
    }

    /**
     * Clear person selection
     */
    public function clearPerson()
    {
        $this->person_id = '';
        $this->person_search = '';
    }

    /**
     * Remove award image from ImageKit and database
     */
    public function removeAwardImage()
    {
        try {
            if ($this->editingId && $this->existing_award_image_file_id) {
                $award = PersonAward::find($this->editingId);

                // Delete from ImageKit
                $this->imageKitService->deleteFile($this->existing_award_image_file_id);

                // Update database
                $award->update([
                    'award_image' => null,
                    'award_image_file_id' => null,
                ]);

                $this->existing_award_image = null;
                $this->existing_award_image_file_id = null;

                Toaster::success('Award image removed successfully.');
            } else if ($this->editingId && $this->existing_award_image) {
                // Fallback: if file ID is missing but image exists
                $award = PersonAward::find($this->editingId);
                $award->update([
                    'award_image' => null,
                    'award_image_file_id' => null,
                ]);
                $this->existing_award_image = null;
                Toaster::success('Award image removed successfully.');
            }
        } catch (Exception $e) {
            Toaster::error('Failed to remove award image: ' . $e->getMessage());
        }

        $this->award_image = null;
    }

    public function nextStep()
    {
        // Ensure slug is generated before validation
        if ($this->currentStep === 1 && $this->autoSlug && empty($this->slug) && !empty($this->award_name)) {
            $this->generateSlug();
        }

        $this->validateCurrentStep();

        if ($this->currentStep < 3) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function validateCurrentStep()
    {
        $stepRules = [
            1 => [
                'person_id' => 'required|exists:people,id',
                'award_name' => 'required|string|max:255',
                'slug' => ['required', 'string', Rule::unique('person_awards', 'slug')->ignore($this->editingId)],
                'organization' => 'required|string|max:255',
            ],
            2 => [
                'awarded_at' => 'nullable|date',
                'category' => 'nullable|string|max:100',
                'description' => 'nullable|string',
                'award_image' => 'nullable|image|max:5120',
            ],
            3 => [
                'is_verified' => 'boolean',
                'sort_order' => 'nullable|integer|min:0',
            ]
        ];

        $this->validate($stepRules[$this->currentStep]);
    }

    public function save()
    {
        $rules = $this->rules();

        if ($this->editingId) {
            $rules['slug'] = ['required', 'string', Rule::unique('person_awards', 'slug')->ignore($this->editingId)];
        }

        $this->validate($rules);

        try {
            $data = [
                'person_id' => $this->person_id,
                'award_name' => $this->award_name,
                'slug' => Str::slug($this->slug),
                'description' => $this->description,
                'awarded_at' => $this->awarded_at ?: null,
                'category' => $this->category,
                'organization' => $this->organization,
                'is_verified' => $this->is_verified,
                'sort_order' => $this->sort_order ?: 0,
            ];

            $award = DB::transaction(function () use ($data) {
                $award = $this->editingId
                    ? tap(PersonAward::findOrFail($this->editingId))->update($data)
                    : PersonAward::create($data);

                // Upload award image if provided
                if ($this->award_image) {
                    $this->uploadAwardImage($award);
                }

                return $award;
            });

            Toaster::success($this->editingId ? 'Award updated successfully.' : 'Award created successfully.');

            return redirect()->route('webmaster.persons.award.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save award: ' . $e->getMessage());
        }
    }

    protected function uploadAwardImage(PersonAward $award)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->award_image,
                'awards/',
                $this->imageWidth ?: null,
                $this->imageHeight ?: null,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old award image if exists
            if ($award->award_image_file_id) {
                $this->imageKitService->deleteFile($award->award_image_file_id);
            }

            $award->update([
                'award_image' => $upload->optimizedUrl,
                'award_image_file_id' => $upload->fileId,
            ]);

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function render()
    {
        $commonCategories = [
            'Film',
            'Television',
            'Music',
            'Sports',
            'Literature',
            'Science',
            'Technology',
            'Business',
            'Politics',
            'Humanitarian',
            'Lifetime Achievement',
            'Breakthrough',
            'Critics Choice',
            'People\'s Choice',
            'International',
            'National',
        ];

        return view('livewire.admin.award.manage', compact('commonCategories'));
    }
}
