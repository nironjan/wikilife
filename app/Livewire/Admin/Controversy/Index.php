<?php

namespace App\Livewire\Admin\Controversy;

use App\Models\Controversy;
use App\Models\People;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $person = '';
    public string $year = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'person' => ['except' => ''],
        'year' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
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

    public function togglePublish($id)
    {
        try {
            $controversy = Controversy::findOrFail($id);
            $controversy->update([
                'is_published' => !$controversy->is_published
            ]);

            Toaster::success(
                $controversy->is_published ? 'Controversy published successfully.' : 'Controversy unpublished successfully.'
            );
        } catch (\Exception $e) {
            Toaster::error('Failed to update controversy status.');
        }
    }

    public function toggleResolve($id)
    {
        try {
            $controversy = Controversy::findOrFail($id);
            $controversy->update([
                'is_resolved' => !$controversy->is_resolved
            ]);

            Toaster::success(
                $controversy->is_resolved ? 'Controversy marked as resolved.' : 'Controversy marked as unresolved.'
            );
        } catch (\Exception $e) {
            Toaster::error('Failed to update controversy status.');
        }
    }

    public function deleteControversy($id)
    {
        try {
            $controversy = Controversy::findOrFail($id);
            $controversy->delete();

            Toaster::success('Controversy deleted successfully.');
        } catch (\Exception $e) {
            Toaster::error('Failed to delete controversy.');
        }
    }
    public function render()
    {
        $controversies = Controversy::with(['person'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                        ->orWhere('content', 'like', "%{$this->search}%")
                        ->orWhereHas('person', function ($personQuery) {
                            $personQuery->where('name', 'like', "%{$this->search}%")
                                ->orWhere('full_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->status === 'published', function ($query) {
                $query->where('is_published', true);
            })
            ->when($this->status === 'unpublished', function ($query) {
                $query->where('is_published', false);
            })
            ->when($this->status === 'resolved', function ($query) {
                $query->where('is_resolved', true);
            })
            ->when($this->status === 'unresolved', function ($query) {
                $query->where('is_resolved', false);
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

        $years = Controversy::selectRaw('YEAR(date) as year')
            ->whereNotNull('date')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get()
            ->pluck('year')
            ->toArray();

        $statuses = [
            'published' => 'Published',
            'unpublished' => 'Unpublished',
            'resolved' => 'Resolved',
            'unresolved' => 'Unresolved',
        ];


        return view('livewire.admin.controversy.index', compact('controversies', 'people', 'years', 'statuses'));
    }
}
