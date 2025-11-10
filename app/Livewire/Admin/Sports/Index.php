<?php

namespace App\Livewire\Admin\Sports;

use App\Models\SportsCareer;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $sport = '';
    public string $team = '';
    public string $international = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'sport' => ['except' => ''],
        'team' => ['except' => ''],
        'international' => ['except' => ''],
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

    public function updatingSport()
    {
        $this->resetPage();
    }

    public function updatingTeam()
    {
        $this->resetPage();
    }

    public function updatingInternational()
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

    public function deleteSportsCareer($id)
    {
        try {
            $sportsCareer = SportsCareer::findOrFail($id);
            $athleteName = $sportsCareer->person->display_name ?? 'Athlete';
            $sportsCareer->delete();

            Toaster::success("Sports career for '{$athleteName}' deleted successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to delete sports career: " . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $sportsCareer = SportsCareer::findOrFail($id);
            $sportsCareer->is_active = !$sportsCareer->is_active;
            $sportsCareer->save();

            $status = $sportsCareer->is_active ? 'activated' : 'deactivated';
            Toaster::success("Sports career {$status} successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update status: " . $e->getMessage());
        }
    }

    public function toggleInternational($id)
    {
        try {
            $sportsCareer = SportsCareer::findOrFail($id);
            $sportsCareer->international_player = !$sportsCareer->international_player;
            $sportsCareer->save();

            $status = $sportsCareer->international_player ? 'marked as international' : 'marked as domestic';
            Toaster::success("Athlete {$status} successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update international status: " . $e->getMessage());
        }
    }
    public function render()
    {

        $sportsCareers = SportsCareer::with(['person'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('sport', 'like', "%{$this->search}%")
                        ->orWhere('team', 'like', "%{$this->search}%")
                        ->orWhere('position', 'like', "%{$this->search}%")
                        ->orWhere('jersey_number', 'like', "%{$this->search}%")
                        ->orWhereHas('person', function ($personQuery) {
                            $personQuery->where('name', 'like', "%{$this->search}%")
                                ->orWhere('full_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($this->status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->when($this->sport, function ($query) {
                $query->where('sport', 'like', "%{$this->sport}%");
            })
            ->when($this->team, function ($query) {
                $query->where('team', 'like', "%{$this->team}%");
            })
            ->when($this->international === 'international', function ($query) {
                $query->where('international_player', true);
            })
            ->when($this->international === 'domestic', function ($query) {
                $query->where('international_player', false);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $sports = SportsCareer::distinct()
            ->whereNotNull('sport')
            ->where('sport', '!=', '')
            ->pluck('sport')
            ->filter()
            ->values()
            ->toArray();

        $teams = SportsCareer::distinct()
            ->whereNotNull('team')
            ->where('team', '!=', '')
            ->pluck('team')
            ->filter()
            ->values()
            ->toArray();


        return view('livewire.admin.sports.index', compact('sportsCareers', 'sports', 'teams'));
    }
}
