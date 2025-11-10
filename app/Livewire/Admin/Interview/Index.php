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

        $interviews = SpeechesInterview::with(['person'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%")
                        ->orWhere('location', 'like', "%{$this->search}%");
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

        $people = People::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn($person) => [$person->id => $person->display_name])
            ->toArray();

        $types = [
            'interview' => 'Interview',
            'speech' => 'Speech',
        ];

        $years = SpeechesInterview::selectRaw('YEAR(date) as year')
            ->whereNotNull('date')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get()
            ->pluck('year')
            ->toArray();


        return view('livewire.admin.interview.index', compact('interviews', 'people', 'types', 'years'));
    }
}
