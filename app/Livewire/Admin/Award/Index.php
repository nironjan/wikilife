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
        try {
            $awards = PersonAward::with(['person'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $searchTerm = $this->search;
                        $searchTermLower = strtolower($searchTerm);
                        $searchTermTitle = ucwords($searchTermLower);

                        // Case-sensitive search on award fields
                        $q->whereRaw('LOWER(award_name) LIKE ?', ["%{$searchTermLower}%"])
                            ->orWhereRaw('LOWER(organization) LIKE ?', ["%{$searchTermLower}%"])
                            ->orWhereRaw('LOWER(category) LIKE ?', ["%{$searchTermLower}%"])

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
                ->when($this->category, function ($query) {
                    $query->where('category', $this->category);
                })
                ->when($this->organization, function ($query) {
                    $searchTerm = strtolower($this->organization);
                    $query->whereRaw('LOWER(organization) LIKE ?', ["%{$searchTerm}%"]);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(15);

        } catch (\Exception $e) {
            logger("PersonAward search error: " . $e->getMessage());
            $awards = PersonAward::where('id', 0)->paginate(15);
        }

        $categories = PersonAward::distinct()->pluck('category')->filter()->toArray();
        $organizations = PersonAward::distinct()->pluck('organization')->filter()->toArray();

        return view('livewire.admin.award.index', compact('awards', 'categories', 'organizations'));
    }
}
