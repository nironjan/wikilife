<?php

namespace App\Livewire\Admin\Politician;

use App\Models\People;
use App\Models\PersonAward;
use App\Models\Politician;
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

    // Politician fields
    public $person_id = '';
    public $political_party = '';
    public $slug = '';
    public $constituency = '';
    public $joining_date = '';
    public $end_date = '';
    public $position = '';
    public $tenure_start = '';
    public $tenure_end = '';
    public $political_journey = '';
    public $notable_achievements = '';
    public $major_initiatives = '';
    public $memberships = [''];
    public $office_type = '';
    public $award_ids = [];
    public $notes = '';
    public $source_url = '';
    public $is_active = true;
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
            'political_party' => 'required|string|max:255',
            'slug' => 'required|string|unique:politicians,slug',
            'constituency' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:joining_date',
            'position' => 'required|string|max:255',
            'tenure_start' => 'required|date',
            'tenure_end' => 'nullable|date|after_or_equal:tenure_start',
            'political_journey' => 'nullable|string',
            'notable_achievements' => 'nullable|string',
            'major_initiatives' => 'nullable|string',
            'memberships' => 'nullable|array',
            'memberships.*' => 'string|max:255',
            'office_type' => 'required|in:Local,State,National,International',
            'award_ids' => 'nullable|array',
            'award_ids.*' => 'exists:person_awards,id',
            'notes' => 'nullable|string',
            'source_url' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadPolitician($id);
            $this->autoSlug = false;
        } else {
            $this->memberships = [''];
            $this->award_ids = [];
            $this->autoSlug = true;
        }
    }

    public function loadPolitician($id)
    {
        $politician = Politician::findOrFail($id);

        $this->person_id = $politician->person_id;
        $this->political_party = $politician->political_party;
        $this->slug = $politician->slug;
        $this->constituency = $politician->constituency;
        $this->joining_date = optional($politician->joining_date)->format('Y-m-d');
        $this->end_date = optional($politician->end_date)->format('Y-m-d');
        $this->position = $politician->position;
        $this->tenure_start = optional($politician->tenure_start)->format('Y-m-d');
        $this->tenure_end = optional($politician->tenure_end)->format('Y-m-d');
        $this->political_journey = $politician->political_journey;
        $this->notable_achievements = $politician->notable_achievements;
        $this->major_initiatives = $politician->major_initiatives;
        $this->memberships = $politician->memberships ?? [''];
        $this->office_type = $politician->office_type;
        $this->award_ids = $politician->award_ids ?? [];
        $this->notes = $politician->notes;
        $this->source_url = $politician->source_url;
        $this->is_active = $politician->is_active;
        $this->sort_order = $politician->sort_order;

        // Pre-fill search fields
        if ($politician->person) {
            $this->person_search = $politician->person->display_name;
        }
        if ($politician->award) {
            $this->award_search = $politician->award->award_name;
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
                $query->byProfessionCategory('politician');
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
     * Auto-generate slug when political party and position change
     */
    public function updatedPoliticalParty($value)
    {
        if ($this->autoSlug && !empty($value) && !empty($this->position)) {
            $this->generateSlug();
        }
    }

    public function updatedPosition($value)
    {
        if ($this->autoSlug && !empty($value) && !empty($this->political_party)) {
            $this->generateSlug();
        }
    }

    /**
     * Generate slug from political party and position
     */
    public function generateSlug()
    {
        if ($this->autoSlug && !empty($this->political_party) && !empty($this->position)) {
            $baseSlug = Str::slug($this->political_party . ' ' . $this->position);
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
            Politician::where('slug', $this->slug)
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
        $expectedSlug = Str::slug($this->political_party . ' ' . $this->position);
        if (!empty($value) && $value !== $expectedSlug) {
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
     * When award is selected, update search field
     */
    public function updatedAwardId($value)
    {
        if ($value) {
            $award = PersonAward::find($value);
            if ($award) {
                $this->award_search = $award->award_name;
            }
        } else {
            $this->award_search = '';
        }
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
     * Clear person selection
     */
    public function clearPerson()
    {
        $this->person_id = '';
        $this->person_search = '';
    }

    /**
     * Clear award selection
     */
    public function clearAward($awardId)
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
     * Add membership field
     */
    public function addMembership()
    {
        $this->memberships[] = '';
    }

    /**
     * Remove membership field
     */
    public function removeMembership($index)
    {
        unset($this->memberships[$index]);
        $this->memberships = array_values($this->memberships);
    }

    public function nextStep()
    {
        // Ensure slug is generated before validation
        if ($this->currentStep === 1 && $this->autoSlug && empty($this->slug) && !empty($this->political_party) && !empty($this->position)) {
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
                'political_party' => 'required|string|max:255',
                'slug' => ['required', 'string', Rule::unique('politicians', 'slug')->ignore($this->editingId)],
                'position' => 'required|string|max:255',
                'office_type' => 'required|in:Local,State,National,International',
            ],
            2 => [
                'constituency' => 'nullable|string|max:255',
                'joining_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:joining_date',
                'tenure_start' => 'required|date',
                'tenure_end' => 'nullable|date|after_or_equal:tenure_start',
                'memberships' => 'nullable|array',
                'memberships.*' => 'string|max:255',
            ],
            3 => [
                'political_journey' => 'nullable|string',
                'notable_achievements' => 'nullable|string',
                'major_initiatives' => 'nullable|string',
                'award_ids' => 'nullable|array',
                'award_ids.*' => 'exists:person_awards,id',
                'notes' => 'nullable|string',
                'source_url' => 'nullable|url|max:500',
                'is_active' => 'boolean',
                'sort_order' => 'nullable|integer|min:0',
            ]
        ];

        $this->validate($stepRules[$this->currentStep]);
    }

    public function save()
    {
        $rules = $this->rules();

        if ($this->editingId) {
            $rules['slug'] = 'required|string|unique:politicians,slug,' . $this->editingId;
        }

        $this->validate($rules);

        try {
            $data = [
                'person_id' => $this->person_id,
                'political_party' => $this->political_party,
                'slug' => Str::slug($this->slug),
                'constituency' => $this->constituency,
                'joining_date' => $this->joining_date ?: null,
                'end_date' => $this->end_date ?: null,
                'position' => $this->position,
                'tenure_start' => $this->tenure_start,
                'tenure_end' => $this->tenure_end ?: null,
                'political_journey' => $this->political_journey,
                'notable_achievements' => $this->notable_achievements,
                'major_initiatives' => $this->major_initiatives,
                'memberships' => array_filter($this->memberships),
                'office_type' => $this->office_type,
                'award_ids' => $this->award_ids,
                'notes' => $this->notes,
                'source_url' => $this->source_url,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order ?: 0,
            ];

            $politician = DB::transaction(function () use ($data) {
                return $this->editingId
                    ? tap(Politician::findOrFail($this->editingId))->update($data)
                    : Politician::create($data);
            });

            Toaster::success($this->editingId ? 'Political career updated successfully.' : 'Political career created successfully.');

            return redirect()->route('webmaster.persons.politician.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save political career: ' . $e->getMessage());
        }
    }


    public function render()
    {
        $commonParties = [
            'Bharatiya Janata Party',
            'Indian National Congress',
            'Aam Aadmi Party',
            'Trinamool Congress',
            'Shiv Sena',
            'Bahujan Samaj Party',
            'Communist Party of India',
            'Communist Party of India (Marxist)',
            'Nationalist Congress Party',
            'Telugu Desam Party',
            'Samajwadi Party',
            'Rashtriya Janata Dal',
            'Janata Dal (United)',
            'Biju Janata Dal',
            'Yuvajana Sramika Rythu Congress Party',
            'Telangana Rashtra Samithi',
            'Dravida Munnetra Kazhagam',
            'All India Anna Dravida Munnetra Kazhagam',
            'Shiromani Akali Dal',
            'Lok Janshakti Party',
        ];

        $commonPositions = [
            'Member of Parliament',
            'Member of Legislative Assembly',
            'Member of Legislative Council',
            'Prime Minister',
            'Chief Minister',
            'Cabinet Minister',
            'Minister of State',
            'Deputy Minister',
            'Governor',
            'President',
            'Vice President',
            'Mayor',
            'Councillor',
            'Party President',
            'Party Secretary',
            'Spokesperson',
            'Whip',
            'Leader of Opposition',
            'Deputy Speaker',
            'Chairperson',
        ];

        $commonOfficeTypes = [
            'Central Government',
            'State Government',
            'Local Government',
            'Legislative',
            'Executive',
            'Judicial',
            'Diplomatic',
            'Party Office',
            'Constitutional Body',
            'Statutory Body',
        ];
        return view('livewire.admin.politician.manage', compact('commonParties', 'commonPositions', 'commonOfficeTypes'));
    }
}
