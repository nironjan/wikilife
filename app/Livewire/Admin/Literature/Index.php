<?php

namespace App\Livewire\Admin\Literature;

use App\Models\LiteratureCareer;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $role = '';
    public string $workType = '';
    public string $mediaType = '';
    public string $genre = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'role' => ['except' => ''],
        'workType' => ['except' => ''],
        'mediaType' => ['except' => ''],
        'genre' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updating($property)
    {
        if (in_array($property, ['status', 'role', 'workType', 'mediaType', 'genre'])) {
            $this->resetPage();
        }
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

    public function deleteLiteratureCareer($id)
    {
        try {
            $literatureCareer = LiteratureCareer::findOrFail($id);
            $workTitle = $literatureCareer->display_title;
            $literatureCareer->delete();

            Toaster::success("Literature career '{$workTitle}' deleted successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to delete literature career: " . $e->getMessage());
        }
    }

    public function toggleVerification($id)
    {
        try {
            $literatureCareer = LiteratureCareer::findOrFail($id);
            $literatureCareer->is_verified = !$literatureCareer->is_verified;
            $literatureCareer->save();

            $status = $literatureCareer->is_verified ? 'verified' : 'unverified';
            Toaster::success("Literature career {$status} successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update verification status: " . $e->getMessage());
        }
    }
    public function render()
    {
        $literatureCareers = LiteratureCareer::with(['person'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                        ->orWhere('role', 'like', "%{$this->search}%")
                        ->orWhere('work_type', 'like', "%{$this->search}%")
                        ->orWhere('genre', 'like', "%{$this->search}%")
                        ->orWhere('language', 'like', "%{$this->search}%")
                        ->orWhere('isbn', 'like', "%{$this->search}%")
                        ->orWhereHas('person', function ($personQuery) {
                            $personQuery->where('name', 'like', "%{$this->search}%")
                                ->orWhere('full_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->status === 'active', function ($query) {
                $query->active();
            })
            ->when($this->status === 'completed', function ($query) {
                $query->whereNotNull('end_date')->where('end_date', '<', now());
            })
            ->when($this->status === 'verified', function ($query) {
                $query->verified();
            })
            ->when($this->role, function ($query) {
                $query->where('role', $this->role);
            })
            ->when($this->workType, function ($query) {
                $query->where('work_type', $this->workType);
            })
            ->when($this->mediaType, function ($query) {
                $query->where('media_type', $this->mediaType);
            })
            ->when($this->genre, function ($query) {
                $query->where('genre', $this->genre);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $roles = LiteratureCareer::distinct()
            ->whereNotNull('role')
            ->where('role', '!=', '')
            ->pluck('role')
            ->filter()
            ->values()
            ->toArray();

        $workTypes = LiteratureCareer::distinct()
            ->whereNotNull('work_type')
            ->where('work_type', '!=', '')
            ->pluck('work_type')
            ->filter()
            ->values()
            ->toArray();

        $mediaTypes = LiteratureCareer::distinct()
            ->whereNotNull('media_type')
            ->where('media_type', '!=', '')
            ->pluck('media_type')
            ->filter()
            ->values()
            ->toArray();

        $genres = LiteratureCareer::distinct()
            ->whereNotNull('genre')
            ->where('genre', '!=', '')
            ->pluck('genre')
            ->filter()
            ->values()
            ->toArray();


        return view('livewire.admin.literature.index', compact(
            'literatureCareers',
            'roles',
            'workTypes',
            'mediaTypes',
            'genres'
        ));
    }
}
