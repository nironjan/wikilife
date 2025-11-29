<?php

namespace App\Livewire\Admin\Entrepreneur;

use App\Models\Entrepreneur;
use App\Models\People;
use App\Models\PersonAward;
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

    // Entrepreneur fields
    public $person_id = '';
    public $company_name = '';
    public $slug = '';
    public $role = '';
    public $joining_date = '';
    public $industry = '';
    public $founding_date = '';
    public $exit_date = '';
    public $investment = '';
    public $headquarters_location = '';
    public $status = 'active';
    public $notable_achievements = [];
    public $award_id = '';
    public $award_ids = [];
    public $website_url = '';
    public $sort_order = 0;

    // Search fields
    public $person_search = '';
    public $award_search = '';

    /**
     * Get validation rules
     */
    protected function rules()
    {
        return [
            'person_id' => 'required|exists:people,id',
            'company_name' => 'required|string|max:255',
            'slug' => 'required|string|unique:entrepreneurs,slug',
            'role' => 'required|string|max:255',
            'joining_date' => 'nullable|date',
            'industry' => 'required|string|max:100',
            'founding_date' => 'nullable|date',
            'exit_date' => 'nullable|date|after:joining_date',
            'investment' => 'nullable|string|max:100',
            'headquarters_location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,acquired,closed',
            'notable_achievements' => 'nullable|array',
            'notable_achievements.*' => 'string|max:500',
            'award_ids' => 'nullable|array',
            'award_ids.*' => 'exists:person_awards,id',
            'website_url' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadEntrepreneur($id);
            $this->autoSlug = false;
        } else {
            $this->notable_achievements = [''];
            $this->award_ids = [];
            $this->autoSlug = true;
        }
    }

    public function loadEntrepreneur($id)
    {
        $entrepreneur = Entrepreneur::findOrFail($id);

        $this->person_id = $entrepreneur->person_id;
        $this->company_name = $entrepreneur->company_name;
        $this->slug = $entrepreneur->slug;
        $this->role = $entrepreneur->role;
        $this->joining_date = optional($entrepreneur->joining_date)->format('Y-m-d');
        $this->industry = $entrepreneur->industry;
        $this->founding_date = optional($entrepreneur->founding_date)->format('Y-m-d');
        $this->exit_date = optional($entrepreneur->exit_date)->format('Y-m-d');
        $this->investment = $entrepreneur->investment;
        $this->headquarters_location = $entrepreneur->headquarters_location;
        $this->status = $entrepreneur->status;
        $this->notable_achievements = $entrepreneur->notable_achievements ?? [''];
        $this->award_ids = $entrepreneur->award_ids ?? [];
        $this->website_url = $entrepreneur->website_url;
        $this->sort_order = $entrepreneur->sort_order;

        // Pre-fill search fields
        if ($entrepreneur->person) {
            $this->person_search = $entrepreneur->person->display_name;
        }
        if ($entrepreneur->award) {
            $this->award_search = $entrepreneur->award->award_name;
        }
    }

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
     * Get filtered awards based on search
     */
    public function getAwardsProperty()
    {
        return PersonAward::with('person')
            ->when($this->award_search, function ($query) {
                $query->where(function ($q) {
                    $q->where('award_name', 'like', "%{$this->award_search}%")
                        ->orWhere('organization', 'like', "%{$this->award_search}%");
                });
            })
            ->whereHas('person', function ($query) {
                $query->byProfessionCategory('entrepreneurs');
            })
            ->orderBy('awarded_at', 'desc')
            ->limit(50)
            ->get();
    }

    /**
     * Get selected awards for display
     */
    public function getSelectedAwardsProperty()
    {
        if (empty($this->award_ids)) {
            return collect();
        }
        return PersonAward::whereIn('id', $this->award_ids)->get();
    }

    /**
     * Auto-generate slug when company name changes
     */
    public function updatedCompanyName($value)
    {
        if ($this->autoSlug && !empty($value)) {
            $this->generateSlug();
        }
    }

    /**
     * Generate slug from company name
     */
    public function generateSlug()
    {
        if ($this->autoSlug && !empty($this->company_name)) {
            $baseSlug = Str::slug($this->company_name);
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
            Entrepreneur::where('slug', $this->slug)
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
        if (!empty($value) && $value !== Str::slug($this->company_name)) {
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
     * Add award to selection
     */
    public function addAward($awardId)
    {
        if (!in_array($awardId, $this->award_ids)) {
            $this->award_ids[] = $awardId;
            $this->award_search = '';
        }
    }

    /**
     * Remove award from selection
     */
    public function removeAward($awardId)
    {
        $this->award_ids = array_filter($this->award_ids, function ($id) use ($awardId) {
            return $id != $awardId;
        });
    }

    /**
     * Clear all awards
     */
    public function clearAwards()
    {
        $this->award_ids = [];
    }


    /**
     * Add notable achievement field
     */
    public function addAchievement()
    {
        $this->notable_achievements[] = '';
    }

    /**
     * Remove notable achievement field
     */
    public function removeAchievement($index)
    {
        unset($this->notable_achievements[$index]);
        $this->notable_achievements = array_values($this->notable_achievements);
    }

    public function nextStep()
    {
        // Ensure slug is generated before validation
        if ($this->currentStep === 1 && $this->autoSlug && empty($this->slug) && !empty($this->company_name)) {
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
                'company_name' => 'required|string|max:255',
                'slug' => ['required', 'string', Rule::unique('entrepreneurs', 'slug')->ignore($this->editingId)],
                'role' => 'required|string|max:255',
                'industry' => 'required|string|max:100',
            ],
            2 => [
                'joining_date' => 'nullable|date',
                'founding_date' => 'nullable|date',
                'exit_date' => 'nullable|date|after:joining_date',
                'investment' => 'nullable|string|max:100',
                'headquarters_location' => 'nullable|string|max:255',
                'website_url' => 'nullable|url|max:255',
            ],
            3 => [
                'status' => 'required|in:active,inactive,acquired,closed',
                'notable_achievements' => 'nullable|array',
                'notable_achievements.*' => 'string|max:500',
                'award_ids' => 'nullable|array',
                'award_ids.*' => 'exists:person_awards,id',
                'sort_order' => 'nullable|integer|min:0',
            ]
        ];

        $this->validate($stepRules[$this->currentStep]);
    }


    public function save()
    {
        $rules = $this->rules();

        if ($this->editingId) {
            $rules['slug'] = 'required|string|unique:entrepreneurs,slug,' . $this->editingId;
        }

        $this->validate($rules);

        try {
            $data = [
                'person_id' => $this->person_id,
                'company_name' => $this->company_name,
                'slug' => Str::slug($this->slug),
                'role' => $this->role,
                'joining_date' => $this->joining_date ?: null,
                'industry' => $this->industry,
                'founding_date' => $this->founding_date ?: null,
                'exit_date' => $this->exit_date ?: null,
                'investment' => $this->investment,
                'headquarters_location' => $this->headquarters_location,
                'status' => $this->status,
                'notable_achievements' => array_filter($this->notable_achievements),
                'award_ids' => $this->award_ids,
                'website_url' => $this->website_url,
                'sort_order' => $this->sort_order ?: 0,
            ];

            $entrepreneur = DB::transaction(function () use ($data) {
                return $this->editingId
                    ? tap(Entrepreneur::findOrFail($this->editingId))->update($data)
                    : Entrepreneur::create($data);
            });

            Toaster::success($this->editingId ? 'Entrepreneurship updated successfully.' : 'Entrepreneurship created successfully.');

            return redirect()->route('webmaster.persons.entrepreneur.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save entrepreneurship: ' . $e->getMessage());
        }
    }


    public function render()
    {
        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'acquired' => 'Acquired',
            'closed' => 'Closed',
        ];

        $commonIndustries = [
            'Technology',
            'Healthcare',
            'Finance',
            'E-commerce',
            'Education',
            'Entertainment',
            'Real Estate',
            'Manufacturing',
            'Food & Beverage',
            'Transportation',
            'Energy',
            'Retail',
            'Marketing',
            'Consulting',
            'Biotechnology',
            'Artificial Intelligence',
            'Blockchain',
            'SaaS',
            'Mobile Apps',
            'Gaming',
        ];

        $commonRoles = [
            'Founder',
            'Co-Founder',
            'CEO',
            'CTO',
            'COO',
            'CFO',
            'President',
            'Managing Director',
            'Board Member',
            'Advisor',
            'Investor',
            'Angel Investor',
            'Venture Partner',
            'Entrepreneur in Residence',
        ];

        return view('livewire.admin.entrepreneur.manage', compact('statuses', 'commonIndustries', 'commonRoles'));
    }
}
