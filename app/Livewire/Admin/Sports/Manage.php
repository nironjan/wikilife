<?php

namespace App\Livewire\Admin\Sports;

use App\Models\SportsCareer;
use App\Models\People;
use App\Models\PersonAward;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Manage extends Component
{
    public ?int $editingId = null;
    public int $currentStep = 1;
    public bool $autoSlug = true;

    // Sports Career fields
    public $person_id = '';
    public $sport = '';
    public $slug = '';
    public $team = '';
    public $position = '';
    public $debut_date = '';
    public $retirement_date = '';
    public $achievements = [''];
    public $jersey_number = '';
    public $coach_name = '';
    public $award_ids = [];
    public $international_player = false;
    public $stats = [''];
    public $notable_events = [''];
    public $leagues_participated = [''];
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
            'sport' => 'required|string|max:255',
            'slug' => 'required|string|unique:sports_careers,slug',
            'team' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'debut_date' => 'nullable|date',
            'retirement_date' => 'nullable|date|after_or_equal:debut_date',
            'achievements' => 'nullable|array',
            'achievements.*' => 'string|max:500',
            'jersey_number' => 'nullable|string|max:10',
            'coach_name' => 'nullable|string|max:255',
            'award_ids' => 'nullable|array',
            'award_ids.*' => 'exists:person_awards,id',
            'international_player' => 'boolean',
            'stats' => 'nullable|array',
            'stats.*' => 'string|max:500',
            'notable_events' => 'nullable|array',
            'notable_events.*' => 'string|max:500',
            'leagues_participated' => 'nullable|array',
            'leagues_participated.*' => 'string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadSportsCareer($id);
            $this->autoSlug = false;
        } else {
            $this->achievements = [''];
            $this->stats = [''];
            $this->notable_events = [''];
            $this->leagues_participated = [''];
            $this->award_ids = [];
            $this->autoSlug = true;
        }
    }

    public function loadSportsCareer($id)
    {
        $sportsCareer = SportsCareer::findOrFail($id);

        $this->person_id = $sportsCareer->person_id;
        $this->sport = $sportsCareer->sport;
        $this->slug = $sportsCareer->slug;
        $this->team = $sportsCareer->team;
        $this->position = $sportsCareer->position;
        $this->debut_date = optional($sportsCareer->debut_date)->format('Y-m-d');
        $this->retirement_date = optional($sportsCareer->retirement_date)->format('Y-m-d');
        $this->achievements = $sportsCareer->achievements ?? [''];
        $this->jersey_number = $sportsCareer->jersey_number;
        $this->coach_name = $sportsCareer->coach_name;
        $this->award_ids = $sportsCareer->award_ids ?? [];
        $this->international_player = $sportsCareer->international_player;
        $this->stats = $sportsCareer->stats ?? [''];
        $this->notable_events = $sportsCareer->notable_events ?? [''];
        $this->leagues_participated = $sportsCareer->leagues_participated ?? [''];
        $this->is_active = $sportsCareer->is_active;
        $this->sort_order = $sportsCareer->sort_order;

        // Pre-fill search fields
        if ($sportsCareer->person) {
            $this->person_search = $sportsCareer->person->display_name;
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
                $query->byProfessionCategory('sports');
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
     * Auto-generate slug when sport and team change
     */
    public function updatedSport($value)
    {
        if ($this->autoSlug && !empty($value) && !empty($this->team)) {
            $this->generateSlug();
        }
    }

    public function updatedTeam($value)
    {
        if ($this->autoSlug && !empty($value) && !empty($this->sport)) {
            $this->generateSlug();
        }
    }

    /**
     * Generate slug from sport and team
     */
    public function generateSlug()
    {
        if ($this->autoSlug && !empty($this->sport) && !empty($this->team)) {
            $baseSlug = Str::slug($this->sport . ' ' . $this->team);
            $this->slug = $baseSlug;

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
            SportsCareer::where('slug', $this->slug)
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
        $expectedSlug = Str::slug($this->sport . ' ' . $this->team);
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
     * Add achievement field
     */
    public function addAchievement()
    {
        $this->achievements[] = '';
    }

    /**
     * Remove achievement field
     */
    public function removeAchievement($index)
    {
        unset($this->achievements[$index]);
        $this->achievements = array_values($this->achievements);
    }

    /**
     * Add stat field
     */
    public function addStat()
    {
        $this->stats[] = '';
    }

    /**
     * Remove stat field
     */
    public function removeStat($index)
    {
        unset($this->stats[$index]);
        $this->stats = array_values($this->stats);
    }

    /**
     * Add notable event field
     */
    public function addNotableEvent()
    {
        $this->notable_events[] = '';
    }

    /**
     * Remove notable event field
     */
    public function removeNotableEvent($index)
    {
        unset($this->notable_events[$index]);
        $this->notable_events = array_values($this->notable_events);
    }

    /**
     * Add league field
     */
    public function addLeague()
    {
        $this->leagues_participated[] = '';
    }

    /**
     * Remove league field
     */
    public function removeLeague($index)
    {
        unset($this->leagues_participated[$index]);
        $this->leagues_participated = array_values($this->leagues_participated);
    }

    public function nextStep()
    {
        if ($this->currentStep === 1 && $this->autoSlug && empty($this->slug) && !empty($this->sport) && !empty($this->team)) {
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
                'sport' => 'required|string|max:255',
                'slug' => 'required|string|unique:sports_careers,slug' . ($this->editingId ? ',' . $this->editingId : ''),
                'team' => 'required|string|max:255',
                'position' => 'nullable|string|max:255',
            ],
            2 => [
                'debut_date' => 'nullable|date',
                'retirement_date' => 'nullable|date|after_or_equal:debut_date',
                'jersey_number' => 'nullable|string|max:10',
                'coach_name' => 'nullable|string|max:255',
                'international_player' => 'boolean',
                'leagues_participated' => 'nullable|array',
                'leagues_participated.*' => 'string|max:255',
            ],
            3 => [
                'achievements' => 'nullable|array',
                'achievements.*' => 'string|max:500',
                'stats' => 'nullable|array',
                'stats.*' => 'string|max:500',
                'notable_events' => 'nullable|array',
                'notable_events.*' => 'string|max:500',
                'award_ids' => 'nullable|array',
                'award_ids.*' => 'exists:person_awards,id',
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
            $rules['slug'] = 'required|string|unique:sports_careers,slug,' . $this->editingId;
        }

        $this->validate($rules);

        try {
            $data = [
                'person_id' => $this->person_id,
                'sport' => $this->sport,
                'slug' => Str::slug($this->slug),
                'team' => $this->team,
                'position' => $this->position,
                'debut_date' => $this->debut_date ?: null,
                'retirement_date' => $this->retirement_date ?: null,
                'achievements' => array_filter($this->achievements),
                'jersey_number' => $this->jersey_number,
                'coach_name' => $this->coach_name,
                'award_ids' => $this->award_ids,
                'international_player' => $this->international_player,
                'stats' => array_filter($this->stats),
                'notable_events' => array_filter($this->notable_events),
                'leagues_participated' => array_filter($this->leagues_participated),
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order ?: 0,
            ];

            $sportsCareer = DB::transaction(function () use ($data) {
                return $this->editingId
                    ? tap(SportsCareer::findOrFail($this->editingId))->update($data)
                    : SportsCareer::create($data);
            });

            Toaster::success($this->editingId ? 'Sports career updated successfully.' : 'Sports career created successfully.');

            return redirect()->route('webmaster.persons.sports.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save sports career: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $commonSports = [
            'Cricket',
            'Football',
            'Hockey',
            'Tennis',
            'Badminton',
            'Basketball',
            'Volleyball',
            'Athletics',
            'Swimming',
            'Boxing',
            'Wrestling',
            'Table Tennis',
            'Golf',
            'Rugby',
            'Baseball',
            'Cycling',
            'Gymnastics',
            'Martial Arts',
            'Skiing',
            'Snowboarding',
        ];

        $commonPositions = [
            'Batsman',
            'Bowler',
            'All-rounder',
            'Wicket-keeper',
            'Forward',
            'Midfielder',
            'Defender',
            'Goalkeeper',
            'Point Guard',
            'Shooting Guard',
            'Small Forward',
            'Power Forward',
            'Center',
            'Setter',
            'Outside Hitter',
            'Opposite Hitter',
            'Middle Blocker',
            'Libero',
            'Singles Player',
            'Doubles Player',
        ];

        $commonTeams = [
            'Indian Cricket Team',
            'Mumbai Indians',
            'Chennai Super Kings',
            'Royal Challengers Bangalore',
            'Indian Football Team',
            'Indian Hockey Team',
            'Indian Badminton Team',
            'Indian Tennis Team',
            'National Team',
            'State Team',
            'Club Team',
            'University Team',
        ];

        return view('livewire.admin.sports.manage', compact('commonSports', 'commonPositions', 'commonTeams'));
    }
}
