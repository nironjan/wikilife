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
        try {
            $controversies = Controversy::with(['person'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $searchTerm = $this->search;
                        $searchTermLower = strtolower($searchTerm);
                        $searchTermTitle = ucwords($searchTermLower);

                        // Case-sensitive search on controversy fields
                        $q->whereRaw('LOWER(title) LIKE ?', ["%{$searchTermLower}%"])
                            ->orWhereRaw('LOWER(content) LIKE ?', ["%{$searchTermLower}%"])

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

        } catch (\Exception $e) {
            logger("Controversy search error: " . $e->getMessage());
            $controversies = Controversy::where('id', 0)->paginate(20);
        }

        $people = People::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn($person) => [$person->id => $person->display_name])
            ->toArray();

        $years = Controversy::whereNotNull('date')
            ->orderBy('date', 'desc')
            ->get()
            ->pluck('date')
            ->map(function ($date) {
                return $date->format('Y');
            })
            ->unique()
            ->values()
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
