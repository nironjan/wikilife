<?php

namespace App\Livewire\Admin\Education;

use App\Models\People;
use App\Models\PersonEducation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Manage extends Component
{
    public ?int $editingId = null;
    public int $currentStep = 1;
    public bool $autoSlug = true;


   // Education fields
    public $person_id = '';
    public $degree = '';
    public $slug = '';
    public $institution = '';
    public $field_of_study = '';
    public $start_year = '';
    public $end_year = '';
    public $grade_or_honors = '';
    public $location = '';
    public $details = '';
    public $education_image = null;
    public $sort_order = 0;

    // Search fields
    public $person_search = '';


    /**
     * Get validation rules
     */
    protected function rules()
    {
        return [
            'person_id' => 'required|exists:people,id',
            'degree' => 'required|string|max:255',
            'slug' => ['required', 'string', Rule::unique('person_education', 'slug')->ignore($this->editingId)],
            'institution' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'start_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'grade_or_honors' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadEducation($id);
            $this->autoSlug = false;
        } else {
            $this->autoSlug = true;
        }
    }

     public function loadEducation($id)
    {
        $education = PersonEducation::findOrFail($id);

        $this->person_id = $education->person_id;
        $this->degree = $education->degree;
        $this->slug = $education->slug;
        $this->institution = $education->institution;
        $this->field_of_study = $education->field_of_study;
        $this->start_year = $education->start_year;
        $this->end_year = $education->end_year;
        $this->grade_or_honors = $education->grade_or_honors;
        $this->location = $education->location;
        $this->details = $education->details;
        $this->sort_order = $education->sort_order;

        // Pre-fill search field
        if ($education->person) {
            $this->person_search = $education->person->display_name;
        }
    }

    /**
     * Get filtered people based on search
     */
    /**
 * Get filtered people based on search
 */
public function getPeopleProperty()
{
    // Get all active people first
    $allPeople = People::active()
        ->orderBy('name')
        ->limit(50)
        ->get();

    // If no search term, return all people
    if (empty($this->person_search)) {
        return $allPeople;
    }

    $searchTerm = strtolower(trim($this->person_search));

    // Apply the same PHP filtering logic as search suggestions
    return $allPeople->filter(function($person) use ($searchTerm) {
        // Check name (case insensitive)
        if (stripos($person->name, $searchTerm) !== false) {
            return true;
        }

        // Check full_name (case insensitive)
        if ($person->full_name && stripos($person->full_name, $searchTerm) !== false) {
            return true;
        }

        // Check nicknames (case insensitive)
        $nicknames = $person->nicknames ?? [];
        foreach ($nicknames as $nickname) {
            if (stripos($nickname, $searchTerm) !== false) {
                return true;
            }
        }

        // Check professions (case insensitive)
        $professions = $person->professions ?? [];
        foreach ($professions as $profession) {
            if (stripos($profession, $searchTerm) !== false) {
                return true;
            }
        }

        return false;
    });
}

    /**
     * Auto-generate slug when degree changes
     */
    public function updatedDegree($value)
    {
        if ($this->autoSlug && !empty($value)) {
            $this->generateSlug();
        }
    }

    /**
     * Generate slug from degree and institution
     */
    public function generateSlug()
    {
        if ($this->autoSlug && !empty($this->degree) && !empty($this->institution)) {
            $baseSlug = Str::slug($this->degree . ' ' . $this->institution);
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
        PersonEducation::where('slug', $this->slug)
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
        if (!empty($value) && $value !== Str::slug($this->degree . ' ' . $this->institution)) {
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

    public function nextStep()
    {
        // Ensure slug is generated before validation
        if ($this->currentStep === 1 && $this->autoSlug && empty($this->slug) && !empty($this->degree) && !empty($this->institution)) {
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
                'degree' => 'required|string|max:255',
                'slug' => ['required', 'string', Rule::unique('person_education', 'slug')->ignore($this->editingId)],
                'institution' => 'required|string|max:255',
            ],
            2 => [
                'field_of_study' => 'nullable|string|max:255',
                'start_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'grade_or_honors' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
            ],
            3 => [
                'details' => 'nullable|string',
                'sort_order' => 'nullable|integer|min:0',
            ]
        ];

        $this->validate($stepRules[$this->currentStep]);
    }

    public function save()
    {
        $rules = $this->rules();

        if ($this->editingId) {
            $rules['slug'] = ['required', 'string', Rule::unique('person_education', 'slug')->ignore($this->editingId)];
        }

        $this->validate($rules);

        try {
            $data = [
                'person_id' => $this->person_id,
                'degree' => $this->degree,
                'slug' => Str::slug($this->slug),
                'institution' => $this->institution,
                'field_of_study' => $this->field_of_study,
                'start_year' => $this->start_year ?: null,
                'end_year' => $this->end_year ?: null,
                'grade_or_honors' => $this->grade_or_honors,
                'location' => $this->location,
                'details' => $this->details,
                'sort_order' => $this->sort_order ?: 0,
            ];

            $education = DB::transaction(function () use ($data) {
                $education = $this->editingId
                    ? tap(PersonEducation::findOrFail($this->editingId))->update($data)
                    : PersonEducation::create($data);

                return $education;
            });

            Toaster::success($this->editingId ? 'Education updated successfully.' : 'Education created successfully.');

            return redirect()->route('webmaster.persons.education.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save education: ' . $e->getMessage());
        }
    }



    public function render()
    {

        $commonDegrees = [
            'High School Diploma',
            'Associate Degree',
            'Bachelor of Arts (BA)',
            'Bachelor of Science (BS)',
            'Bachelor of Fine Arts (BFA)',
            'Bachelor of Business Administration (BBA)',
            'Master of Arts (MA)',
            'Master of Science (MS)',
            'Master of Business Administration (MBA)',
            'Master of Fine Arts (MFA)',
            'Doctor of Philosophy (PhD)',
            'Doctor of Medicine (MD)',
            'Juris Doctor (JD)',
            'Certificate',
            'Diploma',
            'Postgraduate Diploma',
        ];

        $commonFields = [
            'Computer Science',
            'Business Administration',
            'Psychology',
            'Engineering',
            'Medicine',
            'Law',
            'Arts',
            'Science',
            'Mathematics',
            'Economics',
            'Political Science',
            'Literature',
            'History',
            'Philosophy',
            'Education',
        ];


        return view('livewire.admin.education.manage', compact('commonDegrees', 'commonFields'));
    }
}
