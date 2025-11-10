<?php

namespace App\Livewire\Admin\Filmography;

use App\Models\Filmography;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $professionType = '';
    public string $industry = '';
    public string $sortField = 'release_date';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'professionType' => ['except' => ''],
        'industry' => ['except' => ''],
        'sortField' => ['except' => 'release_date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
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

    public function deleteFilmography($id)
    {
        try {
            $filmography = Filmography::findOrFail($id);
            $movieTitle = $filmography->movie_title;
            $filmography->delete();

            Toaster::success("Filmography '{$movieTitle}' deleted successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to delete filmography: " . $e->getMessage());
        }
    }

    public function toggleVerification($id)
    {
        try {
            $filmography = Filmography::findOrFail($id);
            $filmography->is_verified = !$filmography->is_verified;
            $filmography->save();

            $status = $filmography->is_verified ? 'verified' : 'unverified';
            Toaster::success("Filmography {$status} successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update verification: " . $e->getMessage());
        }
    }

    public function render()
    {
        $filmographies = Filmography::with(['person', 'director'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('movie_title', 'like', "%{$this->search}%")
                        ->orWhere('role', 'like', "%{$this->search}%")
                        ->orWhereHas('person', function ($personQuery) {
                            $personQuery->where('name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->professionType, function ($query) {
                $query->where('profession_type', $this->professionType);
            })
            ->when($this->industry, function ($query) {
                $query->where('industry', 'like', "%{$this->industry}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

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

        $industries = Filmography::distinct()->pluck('industry')->filter()->toArray();

        return view('livewire.admin.filmography.index', compact('filmographies', 'professionTypes', 'industries'));
    }
}
