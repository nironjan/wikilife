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
        $facts = LesserKnownFact::with(['person'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('lesser_known_facts.title', 'like', "%{$this->search}%")
                        ->orWhere('lesser_known_facts.fact', 'like', "%{$this->search}%")
                        ->orWhere('lesser_known_facts.category', 'like', "%{$this->search}%")
                        ->orWhereHas('person', function ($personQuery) {
                            $personQuery->where('name', 'like', "%{$this->search}%")
                                ->orWhere('full_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->when($this->person, function ($query) {
                $query->where('person_id', $this->person);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

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
