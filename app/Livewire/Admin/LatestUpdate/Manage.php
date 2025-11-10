<?php

namespace App\Livewire\Admin\LatestUpdate;

use App\Models\People;
use App\Models\LatestUpdate;
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

    // Update fields
    public $person_id = '';
    public $title = '';
    public $slug = '';
    public $description = '';
    public $content = '';
    public $html_content = '';
    public $update_image = null;
    public $update_type = '';
    public $is_approved = false;
    public $status = 'draft';
    public $sort_order = 0;

    // Search fields
    public $person_search = '';
    public $existing_update_image = null;
    public $existing_update_image_file_id = null;

    // Image optimization
    public $imageWidth = 800;
    public $imageHeight = 600;
    public $imageQuality = 85;

    protected $imageKitService;

    protected function rules()
    {
        return [
            'person_id' => 'required|exists:people,id',
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', Rule::unique('latest_updates', 'slug')->ignore($this->editingId)],
            'description' => 'nullable|string',
            'content' => 'required|string',
            'update_image' => 'nullable|image|max:5120',
            'update_type' => 'nullable|string|max:100',
            'is_approved' => 'boolean',
            'status' => 'required|in:draft,published',
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
            $this->loadUpdate($id);
            $this->autoSlug = false;
        } else {
            $this->autoSlug = true;
            // Set current user as the author
            $this->user_id = auth()->id();
        }
    }

    public function loadUpdate($id)
    {
        $update = LatestUpdate::findOrFail($id);

        $this->person_id = $update->person_id;
        $this->title = $update->title;
        $this->slug = $update->slug;
        $this->description = $update->description;
        $this->content = $update->content;
        $this->html_content = $update->html_content;
        $this->update_type = $update->update_type;
        $this->is_approved = $update->is_approved;
        $this->status = $update->status;
        $this->sort_order = $update->sort_order;
        $this->existing_update_image = $update->image_url;
        $this->existing_update_image_file_id = $update->image_file_id;

        // Pre-fill search field
        if ($update->person) {
            $this->person_search = $update->person->display_name;
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
     * Auto-generate slug when title changes
     */
    public function updatedTitle($value)
    {
        if ($this->autoSlug && !empty($value)) {
            $this->generateSlug();
        }
    }

    /**
     * Generate slug from title
     */
    public function generateSlug()
    {
        if ($this->autoSlug && !empty($this->title)) {
            $baseSlug = Str::slug($this->title);
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
            LatestUpdate::where('slug', $this->slug)
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
        if (!empty($value) && $value !== Str::slug($this->title)) {
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
     * Remove update image from ImageKit and database
     */
    public function removeUpdateImage()
    {
        try {
            if ($this->editingId && $this->existing_update_image_file_id) {
                $update = LatestUpdate::find($this->editingId);

                // Delete from ImageKit
                $this->imageKitService->deleteFile($this->existing_update_image_file_id);

                // Update database
                $update->update([
                    'image' => null,
                    'image_file_id' => null,
                ]);

                $this->existing_update_image = null;
                $this->existing_update_image_file_id = null;

                Toaster::success('Update image removed successfully.');
            } else if ($this->editingId && $this->existing_update_image) {
                // Fallback: if file ID is missing but image exists
                $update = LatestUpdate::find($this->editingId);
                $update->update([
                    'image' => null,
                    'image_file_id' => null,
                ]);
                $this->existing_update_image = null;
                Toaster::success('Update image removed successfully.');
            }
        } catch (Exception $e) {
            Toaster::error('Failed to remove update image: ' . $e->getMessage());
        }

        $this->update_image = null;
    }

    /**
     * Convert markdown to HTML when saving
     */
    protected function convertToHtml($markdown)
    {
        try {
            // Use Laravel's built-in Markdown parser
            $this->html_content = Str::markdown($markdown);
        } catch (Exception $e) {
            // Fallback: use the markdown as-is
            $this->html_content = $markdown;
        }
    }

    public function nextStep()
    {
        // Ensure slug is generated before validation
        if ($this->currentStep === 1 && $this->autoSlug && empty($this->slug) && !empty($this->title)) {
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
                'title' => 'required|string|max:255',
                'slug' => ['required', 'string', Rule::unique('latest_updates', 'slug')->ignore($this->editingId)],
            ],
            2 => [
                'description' => 'nullable|string',
                'content' => 'required|string',
                'update_type' => 'nullable|string|max:100',
            ],
            3 => [
                'status' => 'required|in:draft,published',
                'is_approved' => 'boolean',
                'sort_order' => 'nullable|integer|min:0',
                'update_image' => 'nullable|image|max:5120',
            ]
        ];

        $this->validate($stepRules[$this->currentStep]);
    }

    public function save()
    {
        $rules = $this->rules();

        if ($this->editingId) {
            $rules['slug'] = ['required', 'string', Rule::unique('latest_updates', 'slug')->ignore($this->editingId)];
        }

        $this->validate($rules);

        try {
            // Convert markdown to HTML
            $this->convertToHtml($this->content);

            $data = [
                'user_id' => auth()->id(),
                'person_id' => $this->person_id,
                'title' => $this->title,
                'slug' => Str::slug($this->slug),
                'description' => $this->description,
                'content' => $this->content,
                'html_content' => $this->html_content,
                'update_type' => $this->update_type,
                'is_approved' => $this->is_approved,
                'status' => $this->status,
                'sort_order' => $this->sort_order ?: 0,
            ];

            $update = DB::transaction(function () use ($data) {
                $update = $this->editingId
                    ? tap(LatestUpdate::findOrFail($this->editingId))->update($data)
                    : LatestUpdate::create($data);

                // Upload update image if provided
                if ($this->update_image) {
                    $this->uploadUpdateImage($update);
                }

                return $update;
            });

            Toaster::success($this->editingId ? 'Update updated successfully.' : 'Update created successfully.');

            return redirect()->route('webmaster.persons.latest-update.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save update: ' . $e->getMessage());
        }
    }

    protected function uploadUpdateImage(LatestUpdate $update)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->update_image,
                'latest-updates/',
                $this->imageWidth ?: null,
                $this->imageHeight ?: null,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old update image if exists
            if ($update->image_file_id) {
                $this->imageKitService->deleteFile($update->image_file_id);
            }

            $update->update([
                'image' => $upload->optimizedUrl,
                'image_file_id' => $upload->fileId,
            ]);

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function render()
    {
        $commonUpdateTypes = [
            'News',
            'Achievement',
            'Award',
            'Project',
            'Film Release',
            'Music Release',
            'Book Release',
            'Event',
            'Interview',
            'Social Media',
            'Career Milestone',
            'Personal Update',
            'Charity Work',
            'Collaboration',
            'Announcement',
        ];

        return view('livewire.admin.latest-update.manage', compact('commonUpdateTypes'));
    }
}
