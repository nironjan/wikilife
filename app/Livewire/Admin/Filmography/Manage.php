<?php

namespace App\Livewire\Admin\Filmography;

use App\Models\Filmography;
use App\Models\People;
use App\Models\PersonAward;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Manage extends Component
{
    public ?int $editingId = null;
    public int $currentStep = 1;

    // Filmography fields
    public $person_id = '';
    public $movie_title = '';
    public $release_date = '';
    public $role = '';
    public $profession_type = 'actor';
    public $industry = '';
    public $director_id = '';
    public $unlisted_director_name = '';
    public $production_company = '';
    public $genres = [];
    public $description = '';
    public $box_office_collection = '';
    public $person_award_id = '';
    public $award_ids = [];
    public $is_verified = false;
    public $sort_order = 0;

    // Search fields
    public $person_search = '';
    public $director_search = '';
    public $award_search = '';

    /**
     * Get validation rules
     */
    protected function rules()
    {
        return [
            'person_id' => 'required|exists:people,id',
            'movie_title' => 'required|string|max:255',
            'release_date' => 'nullable|date',
            'role' => 'nullable|string|max:255',
            'profession_type' => 'required|in:actor,director,producer,writer,cinematographer,editor,composer,other',
            'industry' => 'nullable|string|max:100',
            'director_id' => 'nullable|exists:people,id',
            'unlisted_director_name' => 'nullable|string|max:255',
            'production_company' => 'nullable|string|max:255',
            'genres' => 'nullable|array',
            'genres.*' => 'string|max:100',
            'description' => 'nullable|string',
            'box_office_collection' => 'nullable|string|max:100',
            'award_ids' => 'nullable|array',
            'award_ids.*' => 'exists:person_awards,id',
            'is_verified' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadFilmography($id);
        } else {
            $this->genres = [''];
            $this->award_ids = [];
        }
    }

    public function loadFilmography($id)
    {
        $filmography = Filmography::findOrFail($id);

        $this->person_id = $filmography->person_id;
        $this->movie_title = $filmography->movie_title;
        $this->release_date = optional($filmography->release_date)->format('Y-m-d');
        $this->role = $filmography->role;
        $this->profession_type = $filmography->profession_type;
        $this->industry = $filmography->industry;
        $this->director_id = $filmography->director_id;
        $this->unlisted_director_name = $filmography->unlisted_director_name;
        $this->production_company = $filmography->production_company;
        $this->genres = $filmography->genres ?? [''];
        $this->description = $filmography->description;
        $this->box_office_collection = $filmography->box_office_collection;
        $this->award_ids = $filmography->award_ids ?? [];
        $this->is_verified = $filmography->is_verified;
        $this->sort_order = $filmography->sort_order;

        // Pre-fill search fields
        if ($filmography->person) {
            $this->person_search = $filmography->person->display_name;
        }
        if ($filmography->director) {
            $this->director_search = $filmography->director->display_name;
        }
        if ($filmography->award) {
            $this->award_search = $filmography->award->award_name;
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
            ->byProfessionCategory('filmographies')
            ->orderBy('name')
            ->limit(50)
            ->get();
    }

    /**
     * Get filtered directors based on search
     */
    public function getDirectorsProperty()
    {
        return People::active()
            ->when($this->director_search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->director_search}%")
                        ->orWhere('full_name', 'like', "%{$this->director_search}%");
                });
            })
            ->orderBy('name')
            ->limit(50)
            ->get();
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
     * When director is selected, update search field
     */
    public function updatedDirectorId($value)
    {
        if ($value) {
            $director = People::find($value);
            if ($director) {
                $this->director_search = $director->display_name;
            }
        } else {
            $this->director_search = '';
            $this->unlisted_director_name = '';
        }
    }

    /**
     * When award is selected, update search field
     */
    public function updatedPersonAwardId($value)
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
     * Clear person selection
     */
    public function clearPerson()
    {
        $this->person_id = '';
        $this->person_search = '';
    }

    /**
     * Clear director selection
     */
    public function clearDirector()
    {
        $this->director_id = '';
        $this->director_search = '';
        $this->unlisted_director_name = '';
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
     * When unlisted director name is entered, clear director ID
     */
    public function updatedUnlistedDirectorName($value)
    {
        if (!empty($value)) {
            $this->director_id = '';
            $this->director_search = '';
        }
    }

    /**
     * Add genre field
     */
    public function addGenre()
    {
        $this->genres[] = '';
    }

    /**
     * Remove genre field
     */
    public function removeGenre($index)
    {
        unset($this->genres[$index]);
        $this->genres = array_values($this->genres);
    }

    public function nextStep()
    {
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
                'movie_title' => 'required|string|max:255',
                'release_date' => 'nullable|date',
                'role' => 'nullable|string|max:255',
                'profession_type' => 'required|in:actor,director,producer,writer,cinematographer,editor,composer,other',
            ],
            2 => [
                'industry' => 'nullable|string|max:100',
                'director_id' => 'nullable|exists:people,id',
                'unlisted_director_name' => 'nullable|string|max:255',
                'production_company' => 'nullable|string|max:255',
                'genres' => 'nullable|array',
                'genres.*' => 'string|max:100',
                'description' => 'nullable|string',
            ],
            3 => [
                'box_office_collection' => 'nullable|string|max:100',
                'award_ids' => 'nullable|array',
                'award_ids.*' => 'exists:person_awards,id',
                'is_verified' => 'boolean',
                'sort_order' => 'nullable|integer|min:0',
            ]
        ];

        $this->validate($stepRules[$this->currentStep]);
    }


    public function save()
    {
        $this->validate();

        try {
            $data = [
                'person_id' => $this->person_id,
                'movie_title' => $this->movie_title,
                'release_date' => $this->release_date ?: null,
                'role' => $this->role,
                'profession_type' => $this->profession_type,
                'industry' => $this->industry,
                'director_id' => $this->director_id ?: null,
                'unlisted_director_name' => $this->unlisted_director_name,
                'production_company' => $this->production_company,
                'genres' => array_filter($this->genres),
                'description' => $this->description,
                'box_office_collection' => $this->box_office_collection,
                'award_ids' => $this->award_ids,
                'is_verified' => $this->is_verified,
                'sort_order' => $this->sort_order ?: 0,
            ];

            $filmography = DB::transaction(function () use ($data) {
                return $this->editingId
                    ? tap(Filmography::findOrFail($this->editingId))->update($data)
                    : Filmography::create($data);
            });

            Toaster::success($this->editingId ? 'Filmography updated successfully.' : 'Filmography created successfully.');

            return redirect()->route('webmaster.persons.filmography.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save filmography: ' . $e->getMessage());
        }
    }
    public function render()
    {
        $professionTypes = [
            'actor' => 'Actor',
            'director' => 'Director',
            'producer' => 'Producer',
            'writer' => 'Writer',
            'cinematographer' => 'Cinematographer',
            'editor' => 'Editor',
            'composer' => 'Composer',
            'other' => 'Other',
        ];

        $commonGenres = [
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Fantasy',
            'Horror',
            'Mystery',
            'Romance',
            'Sci-Fi',
            'Thriller',
            'Crime',
            'Animation',
            'Documentary',
            'Family',
            'Musical',
            'War',
            'Western',
            'Biography',
            'History',
            'Sport',
            'Superhero',
            'Noir',
            'Musical'
        ];


        return view('livewire.admin.filmography.manage', compact('professionTypes', 'commonGenres'));
    }
}
