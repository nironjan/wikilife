<?php

namespace App\Livewire\Admin\Person;

use App\Models\People;
use App\Services\ImageKitService;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

#[Title("Person Manage Page")]
class Manage extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;
    public int $currentStep = 1;
    public bool $autoSlug = true;

    // Basic Identity
    public $name = '';
    public $slug = '';
    public $full_name = '';
    public $nicknames = [];

    // Media
    public $cover_image = null;
    public $profile_image = null;
    public $cover_img_caption = '';
    public $profile_image_caption = '';
    public $existing_cover_image = null;
    public $existing_profile_image = null;

    // Biography
    public $about = '';
    public $early_life = '';
    public $gender = '';
    public $birth_date = null;
    public $death_date = null;
    public $place_of_birth = '';
    public $place_of_death = '';
    public $death_cause = '';
    public $hometown = '';
    public $address = '';

    // Background
    public $state_code = '';
    public $nationality = '';
    public $religion = '';
    public $caste = '';
    public $ethnicity = '';
    public $zodiac_sign = '';
    public $blood_group = '';

    // Professional & Personal
    public $professions = [];
    public $physical_stats = [];
    public $favourite_things = [];
    public $references = [];

    // Status
    public $status = 'active';
    public $verified = false;

    // Image optimization
    public $imageWidth = 1200;
    public $imageHeight = 800;
    public $imageQuality = 85;

    protected $imageKitService;

    protected $rules = [

        // Step-1

        'name' => 'required|string|max:255',
        'slug' => 'required|string|unique:people,slug',
        'full_name' => 'nullable|string|max:255',
        'nicknames' => 'nullable|array',
        'nicknames.*' => 'string|max:100',

        // Step 2
        'about' => 'nullable|string',
        'early_life' => 'nullable|string',
        'gender' => 'required|in:male,female,other',
        'birth_date' => 'nullable|date',
        'death_date' => 'nullable|date|after:birth_date',
        'place_of_birth' => 'nullable|string|max:255',
        'place_of_death' => 'nullable|string|max:255',
        'death_cause' => 'nullable|string|max:500',
        'hometown' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:500',

        // Step 3
        'state_code' => 'nullable|string|max:255',
        'nationality' => 'nullable|string|max:100',
        'religion' => 'nullable|string|max:100',
        'caste' => 'nullable|string|max:100',
        'ethnicity' => 'nullable|string|max:100',
        'zodiac_sign' => 'nullable|string|max:50',
        'blood_group' => 'nullable|string|max:10',
        'professions' => 'nullable|array',
        'professions.*' => 'string|max:100',
        'physical_stats' => 'nullable|array',
        'physical_stats.*.key' => 'nullable|string|max:100',
        'physical_stats.*.value' => 'nullable|string|max:100',
        'favourite_things' => 'nullable|array',
        'favourite_things.*.key' => 'nullable|string|max:100',
        'favourite_things.*.value' => 'nullable|string|max:100',
        'references' => 'nullable|array',

        // Step 4
        'cover_image' => 'nullable|image|max:5120',
        'cover_img_caption' => 'nullable|string|max:255',
        'profile_image' => 'nullable|image|max:5120',
        'profile_image_caption' => 'nullable|string|max:255',
        'status' => 'required|in:active,inactive,deceased',
        'verified' => 'boolean',

        'imageWidth' => 'nullable|integer|min:100|max:4000',
        'imageHeight' => 'nullable|integer|min:100|max:4000',
        'imageQuality' => 'nullable|integer|min:10|max:100',
    ];

    public function boot()
    {
        $this->imageKitService = new ImageKitService();
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadPerson($id);
            $this->autoSlug = false;
        } else {
            $this->nicknames = [''];
            $this->professions = [''];
            $this->physical_stats = [['key' => '', 'value' => '']];
            $this->favourite_things = [['key' => '', 'value' => '']];
            $this->autoSlug = true;

            if (!empty($this->name)) {
                $this->generateSlug();
            }

        }
    }

    /**
     * Generate slug from name - called on mount and when name changes
     */
    public function generateSlug()
    {
        if ($this->autoSlug && !empty($this->name)) {
            $baseSlug = Str::slug($this->name);
            $this->slug = $baseSlug;

            // Check uniqueness
            $this->ensureUniqueSlug();
        }
    }

    /**
     * Auto-generatd slug when name changes
     */
    public function updatedName($value)
    {
        if (!$this->autoSlug || !empty($value)) {
            $this->slug = Str::slug($value);

            $this->ensureUniqueSlug();
        }

    }

    /**
     * When user manually edits slug, disable auto-generation
     */
    public function updatedSlug($value)
    {
        // If user manually edits the slug, disable auto-generation
        if (!empty($value) && $value !== Str::slug($this->name)) {
            $this->autoSlug = false;
        }

        // If user clears the slug, re-enable auto-generation
        if (empty($value)) {
            $this->autoSlug = true;
            $this->updatedName($this->name);
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
            People::where('slug', $this->slug)
                ->when($this->editingId, fn($q) => $q->where('id', '!=', $this->editingId))
                ->exists()
        ) {
            $this->slug = $baseSlug . '-' . $counter;
            $counter++;
        }
    }

    /**
     * Reset to auto-generated slug
     */
    public function resetSlug()
    {
        $this->autoSlug = true;
        $this->updatedName($this->name);
        Toaster::success('Slug reset to auto-generated value.');
    }


    /**
     * Convert JSON data to key-value pairs for the form
     * @param mixed $data
     * @return array
     */
    public function convertToKeyValue($data): array
    {
        if (empty($data) || !is_array($data)) {
            return [['key' => '', 'value' => '']];
        }
        $keyValuePairs = [];
        foreach ($data as $key => $value) {
            $keyValuePairs[] = [
                'key' => $key,
                'value' => $value
            ];
        }
        return empty($keyValuePairs) ? [['key' => '', 'value' => '']] : $keyValuePairs;
    }

    /**
     * Convert key-value pairs back to associative array for storage
     */
    protected function convertToAssociativeArray($keyValuePairs): array
    {
        $result = [];
        foreach ($keyValuePairs as $pair) {
            if (!empty($pair['key']) && !empty($pair['value'])) {
                $result[$pair['key']] = $pair['value'];
            }
        }
        return $result;
    }



    public function loadPerson($id)
    {
        $person = People::findOrFail($id);

        $this->name = $person->name;
        $this->slug = $person->slug;
        $this->full_name = $person->full_name;
        $this->nicknames = $person->nicknames ?? [];

        $this->existing_cover_image = $person->cover_image;
        $this->existing_profile_image = $person->profile_image;

        $this->about = $person->about;
        $this->early_life = $person->early_life;
        $this->gender = $person->gender;
        $this->birth_date = $person->birth_date
            ? Carbon::parse($person->birth_date)->format('Y-m-d')
            : null;
        $this->death_date = $person->death_date
            ? Carbon::parse($person->death_date)->format('Y-m-d')
            : null;
        $this->place_of_birth = $person->place_of_birth;
        $this->place_of_death = $person->place_of_death;
        $this->death_cause = $person->death_cause;
        $this->hometown = $person->hometown;
        $this->address = $person->address;

        $this->state_code = $person->state_code;
        $this->nationality = $person->nationality;
        $this->religion = $person->religion;
        $this->caste = $person->caste;
        $this->ethnicity = $person->ethnicity;
        $this->zodiac_sign = $person->zodiac_sign;
        $this->blood_group = $person->blood_group;

        $this->professions = $person->professions ?? [];

        $this->physical_stats = $this->convertToKeyValue($person->physical_stats);
        $this->favourite_things = $this->convertToKeyValue($person->favourite_things);

        $this->references = $person->references ?? [];

        $this->cover_img_caption = $person->cover_img_caption;
        $this->profile_image_caption = $person->profile_image_caption;

        $this->status = $person->status;
        $this->verified = $person->verified;
    }

    public function nextStep()
    {
        // validate current step before proceeding
        $this->validateCurrentStep();

        if ($this->currentStep < 4) {
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
                'name' => 'required|string|max:255',
                'slug' => ['required', 'string', Rule::unique('people', 'slug')->ignore($this->editingId)],
                'full_name' => 'nullable|string|max:255',
                'nicknames' => 'nullable|array',
                'nicknames.*' => 'string|max:100',
            ],
            2 => [
                'about' => 'nullable|string',
                'early_life' => 'nullable|string',
                'gender' => 'required|in:male,female,other',
                'birth_date' => 'nullable|date',
                'death_date' => 'nullable|date|after:birth_date',
                'place_of_birth' => 'nullable|string|max:255',
                'place_of_death' => 'nullable|string|max:255',
                'death_cause' => 'nullable|string|max:500',
                'hometown' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:500',
            ],
            3 => [
                'state_code' => 'nullable|string|max:255',
                'nationality' => 'nullable|string|max:100',
                'religion' => 'nullable|string|max:100',
                'caste' => 'nullable|string|max:100',
                'ethnicity' => 'nullable|string|max:100',
                'zodiac_sign' => 'nullable|string|max:50',
                'blood_group' => 'nullable|string|max:10',
                'professions' => 'nullable|array',
                'professions.*' => 'string|max:100',
                'physical_stats' => 'nullable|array',
                'physical_stats.*.key' => 'required_with:physical_stats.*.value|string|max:100',
                'physical_stats.*.value' => 'required_with:physical_stats.*.key|string|max:100',
                'favourite_things' => 'nullable|array',
                'favourite_things.*.key' => 'required_with:favourite_things.*.value|string|max:100',
                'favourite_things.*.value' => 'required_with:favourite_things.*.key|string|max:100',
            ],
            4 => [
                'cover_image' => 'nullable|image|max:5120',
                'cover_img_caption' => 'nullable|string|max:255',
                'profile_image' => 'nullable|image|max:5120',
                'profile_image_caption' => 'nullable|string|max:255',
                'status' => 'required|in:active,inactive,deceased',
                'verified' => 'boolean',
            ]
        ];

        $this->validate($stepRules[$this->currentStep]);
    }




    public function addNickname()
    {
        $this->nicknames[] = '';
    }

    public function removeNickname($index)
    {
        unset($this->nicknames[$index]);
        $this->nicknames = array_values($this->nicknames);
    }

    public function addProfession()
    {
        $this->professions[] = '';
    }


    public function removeProfession($index)
    {
        unset($this->professions[$index]);
        $this->professions = array_values($this->professions);
    }

    public function addPhysicalStat()
    {
        $this->physical_stats[] = ['key' => '', 'value' => ''];
    }

    public function removePhysicalStat($index)
    {
        unset($this->physical_stats[$index]);
        $this->physical_stats = array_values($this->physical_stats);
    }

    public function addFavoriteThing()
    {
        $this->favourite_things[] = ['key' => '', 'value' => ''];
    }

    public function removeFavoriteThing($index)
    {
        unset($this->favourite_things[$index]);
        $this->favourite_things = array_values($this->favourite_things);
    }

    public function save()
    {
        // validate all steps before saving
        $rules = $this->rules;

        if ($this->editingId) {
            $rules['slug'] = [
                'required',
                'string',
                Rule::unique('people', 'slug')->ignore($this->editingId),
            ];
        }

        $this->validate($rules);

        try {
            $data = [
                'name' => $this->name,
                'slug' => Str::slug($this->slug),
                'full_name' => $this->full_name,
                'nicknames' => array_filter($this->nicknames),
                'about' => $this->about,
                'early_life' => $this->early_life,
                'gender' => $this->gender,
                'birth_date' => $this->birth_date ?: null,
                'death_date' => $this->death_date ?: null,
                'place_of_birth' => $this->place_of_birth,
                'place_of_death' => $this->place_of_death,
                'death_cause' => $this->death_cause,
                'hometown' => $this->hometown,
                'address' => $this->address,
                'state_code' => $this->state_code,
                'nationality' => $this->nationality,
                'religion' => $this->religion,
                'caste' => $this->caste,
                'ethnicity' => $this->ethnicity,
                'zodiac_sign' => $this->zodiac_sign,
                'blood_group' => $this->blood_group,
                'professions' => array_filter($this->professions),
                'physical_stats' => $this->convertToAssociativeArray($this->physical_stats),
                'favourite_things' => $this->convertToAssociativeArray($this->favourite_things),
                'references' => $this->references,
                'cover_img_caption' => $this->cover_img_caption,
                'profile_image_caption' => $this->profile_image_caption,
                'status' => $this->status,
                'verified' => $this->verified,
                'created_by' => Auth::id(),
            ];

            $data = $this->cleanEmptyArrays($data);


            $person = DB::transaction(function () use ($data) {
                $person = $this->editingId
                    ? tap(People::findOrFail($this->editingId))->update($data)
                    : People::create($data);

                // Uplod images if provided
                if ($this->cover_image) {
                    $this->uploadCoverImage($person);
                }
                if ($this->profile_image) {
                    $this->uploadProfileImage($person);
                }

                return $person;
            });

            Toaster::success($this->editingId ? 'Person updated successfully.' : 'Person created successfully.');

            return redirect()->route('webmaster.persons.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save person:' . $e->getMessage());
        }
    }

    /**
     * Clean empty arrays to prevent JSON encoding issues
     */
    protected function cleanEmptyArrays(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value) && empty($value)) {
                $data[$key] = null;
            }
        }
        return $data;
    }

    protected function uploadCoverImage(People $person)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->cover_image,
                'people/cover/',
                $this->imageWidth ?: null,
                $this->imageHeight ?: null,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old cover image if exists
            if ($person->cover_file_id) {
                $this->imageKitService->deleteFile($person->cover_file_id);
            }

            $person->update([
                'cover_image' => $upload->optimizedUrl,
                'cover_file_id' => $upload->fileId,
            ]);

        } catch (Exception $e) {
            throw $e;
        }

    }

    protected function uploadProfileImage(People $person)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->profile_image,
                'people/profile/',
                400,
                400,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old profile image if exists
            if ($person->profile_img_file_id) {
                $this->imageKitService->deleteFile($person->profile_img_file_id);
            }

            $person->update([
                'profile_image' => $upload->optimizedUrl,
                'profile_img_file_id' => $upload->fileId,
            ]);

        } catch (Exception $e) {
            throw $e;
        }

    }

    public function removeCoverImage()
    {
        if ($this->editingId && $this->existing_cover_image) {
            $person = People::find($this->editingId);
            if ($person->cover_file_id) {
                $this->imageKitService->deleteFile($person->cover_file_id);
            }
            $person->update([
                'cover_image' => null,
                'cover_file_id' => null,
            ]);
            $this->existing_cover_image = null;
        }
        $this->cover_image = null;
        Toaster::success('Cover image removed successfully.');
    }

    public function removeProfileImage()
    {
        if ($this->editingId && $this->existing_profile_image) {
            $person = People::find($this->editingId);
            if ($person->profile_img_file_id) {
                $this->imageKitService->deleteFile($person->profile_img_file_id);
            }
            $person->update([
                'profile_image' => null,
                'profile_img_file_id' => null,
            ]);
            $this->existing_profile_image = null;
        }
        $this->profile_image = null;
        Toaster::success('Profile image removed successfully.');
    }


    public function render()
    {
        $indianStates = config('indian_states.all', []);

        return view('livewire.admin.person.manage', compact('indianStates'));
    }

}
