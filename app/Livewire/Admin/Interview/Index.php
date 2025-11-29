<?php

namespace App\Livewire\Admin\Interview;

use App\Models\People;
use App\Models\SpeechesInterview;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $type = '';
    public string $person = '';
    public string $year = '';
    public string $sortField = 'date';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'person' => ['except' => ''],
        'year' => ['except' => ''],
        'sortField' => ['except' => 'date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingPerson()
    {
        $this->resetPage();
    }

    public function updatingYear()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'desc';
        }
        $this->sortField = $field;
    }

    public function deleteInterview($id)
    {
        try {
            $interview = SpeechesInterview::findOrFail($id);
            $interview->delete();

            session()->flash('message', 'Interview/Speech deleted successfully.');
            $this->dispatch('interview-deleted');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete interview/speech.');
        }
    }


    public function render()
    {

       try {
            $interviews = SpeechesInterview::with(['person'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $searchTerm = $this->search;
                        $searchTermLower = strtolower($searchTerm);
                        $searchTermTitle = ucwords($searchTermLower);

                        // Case-sensitive search on interview fields
                        $q->whereRaw('LOWER(title) LIKE ?', ["%{$searchTermLower}%"])
                            ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTermLower}%"])
                            ->orWhereRaw('LOWER(location) LIKE ?', ["%{$searchTermLower}%"])

                            // Search by person name and full name
                            ->orWhereHas('person', function ($personQuery) use ($searchTermLower) {
                                $personQuery->whereRaw('LOWER(name) LIKE ?', ["%{$searchTermLower}%"])
                                    ->orWhereRaw('LOWER(full_name) LIKE ?', ["%{$searchTermLower}%"]);
                            })

                            // Search by person nicknames
                            ->orWhereHas('person', function ($personQuery) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                                $personQuery->whereJsonContains('nicknames', $searchTerm)
                                    ->orWhereJsonContains('nicknames', $searchTermLower)
                                    ->orWhereJsonContains('nicknames', $searchTermTitle);
                            })

                            // Search by person professions
                            ->orWhereHas('person', function ($personQuery) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                                $personQuery->where(function ($professionQuery) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                                    $professionQuery->orWhereRaw('professions::text LIKE ?', ["%{$searchTerm}%"])
                                                ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermLower}%"])
                                                ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermTitle}%"]);
                                });
                            });
                    });
                })
                ->when($this->type, function ($query) {
                    $query->where('type', $this->type);
                })
                ->when($this->person, function ($query) {
                    $query->where('person_id', $this->person);
                })
                ->when($this->year, function ($query) {
                    $query->whereYear('date', $this->year);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(20);

        } catch (\Exception $e) {
            logger("SpeechesInterview search error: " . $e->getMessage());
            $interviews = SpeechesInterview::where('id', 0)->paginate(20);
        }

        $people = People::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn($person) => [$person->id => $person->display_name])
            ->toArray();

        $types = [
            'interview' => 'Interview',
            'speech' => 'Speech',
        ];

        $years = SpeechesInterview::whereNotNull('date')
            ->orderBy('date', 'desc')
            ->get()
            ->pluck('date')
            ->map(function ($date) {
                return $date->format('Y');
            })
            ->unique()
            ->values()
            ->toArray();

        return view('livewire.admin.interview.index', compact('interviews', 'people', 'types', 'years'));
    }
}
