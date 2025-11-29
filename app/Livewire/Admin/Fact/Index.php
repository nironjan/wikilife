<?php

namespace App\Livewire\Admin\Fact;

use App\Models\LesserKnownFact;
use App\Models\People;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';
    public string $person = '';
    public string $sortField = 'title';
    public string $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'person' => ['except' => ''],
        'sortField' => ['except' => 'title'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingPerson()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function deleteFact($id)
    {
        try {
            $fact = LesserKnownFact::findOrFail($id);
            $fact->delete();

            session()->flash('message', 'Fact deleted successfully.');
            $this->dispatch('fact-deleted');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete fact.');
        }
    }

    public function render()
    {
        try {
            $facts = LesserKnownFact::with('person')
                ->when($this->search, function ($query) {
                    $search = strtolower($this->search); // Convert search to lowercase
                    $searchTerm = $this->search; // Keep original case for JSON searches
                    $searchTermLower = $search; // Lowercase version
                    $searchTermTitle = ucwords($search); // Title case version

                    $query->where(function ($q) use ($search, $searchTerm, $searchTermLower, $searchTermTitle) {
                        // Search in fact fields with case sensitivity using LOWER()
                        $q->whereRaw('LOWER(lesser_known_facts.title) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(lesser_known_facts.fact) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(lesser_known_facts.category) LIKE ?', ["%{$search}%"])

                        // Search by person NAME with case sensitivity
                        ->orWhereHas('person', function ($p) use ($search) {
                            $p->whereRaw('LOWER(people.name) LIKE ?', ["%{$search}%"]);
                        })

                        // Search by person FULL NAME with case sensitivity
                        ->orWhereHas('person', function ($p) use ($search) {
                            $p->whereRaw('LOWER(people.full_name) LIKE ?', ["%{$search}%"]);
                        })

                        // Search by person NICKNAMES (JSON array)
                        ->orWhereHas('person', function ($p) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                            $p->whereJsonContains('nicknames', $searchTerm)
                            ->orWhereJsonContains('nicknames', $searchTermLower)
                            ->orWhereJsonContains('nicknames', $searchTermTitle);
                        })

                        // Search by person PROFESSIONS (JSON array)
                        ->orWhereHas('person', function ($p) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                            $p->where(function ($professionQuery) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                                $professionQuery->orWhereRaw('professions::text LIKE ?', ["%{$searchTerm}%"])
                                            ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermLower}%"])
                                            ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermTitle}%"]);
                            });
                        });
                    });
                })
                ->when($this->category, fn($q) => $q->where('category', $this->category))
                ->when($this->person, fn($q) => $q->where('person_id', $this->person))
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(20);

        } catch (\Exception $e) {
            logger("Search error: " . $e->getMessage());
            $facts = LesserKnownFact::where('id', 0)->paginate(20);
        }


        $people = People::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn($person) => [$person->id => $person->display_name])
            ->toArray();

        $categories = LesserKnownFact::whereNotNull('category')
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        return view('livewire.admin.fact.index', compact('facts', 'people', 'categories'));
    }
}
