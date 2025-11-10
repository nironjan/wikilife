<?php

namespace App\Livewire\Admin\Award;

use App\Models\PersonAward;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';
    public string $organization = '';
    public string $sortField = 'awarded_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'organization' => ['except' => ''],
        'sortField' => ['except' => 'awarded_at'],
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

    public function deleteAward($id)
    {
        try {
            $award = PersonAward::findOrFail($id);
            $awardName = $award->award_name;
            $award->delete();

            Toaster::success("Award '{$awardName}' deleted successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to delete award: " . $e->getMessage());
        }
    }

    public function toggleVerification($id)
    {
        try {
            $award = PersonAward::findOrFail($id);
            $award->is_verified = !$award->is_verified;
            $award->save();

            $status = $award->is_verified ? 'verified' : 'unverified';
            Toaster::success("Award {$status} successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update verification: " . $e->getMessage());
        }
    }

    public function render()
    {
        $awards = PersonAward::with(['person'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('award_name', 'like', "%{$this->search}%")
                        ->orWhere('organization', 'like', "%{$this->search}%")
                        ->orWhere('category', 'like', "%{$this->search}%")
                        ->orWhereHas('person', function ($personQuery) {
                            $personQuery->where('name', 'like', "%{$this->search}%")
                                ->orWhere('full_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->when($this->organization, function ($query) {
                $query->where('organization', 'like', "%{$this->organization}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $categories = PersonAward::distinct()->pluck('category')->filter()->toArray();
        $organizations = PersonAward::distinct()->pluck('organization')->filter()->toArray();

        return view('livewire.admin.award.index', compact('awards', 'categories', 'organizations'));
    }
}
