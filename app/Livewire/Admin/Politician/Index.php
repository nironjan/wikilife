<?php

namespace App\Livewire\Admin\Politician;

use App\Models\Politician;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $party = '';
    public string $officeType = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';


    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'party' => ['except' => ''],
        'officeType' => ['except' => ''],
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

    public function updatingParty()
    {
        $this->resetPage();
    }

    public function updatingOfficeType()
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

    public function deletePolitician($id)
    {
        try {
            $politician = Politician::findOrFail($id);
            $politicianName = $politician->person->display_name ?? 'Politician';
            $politician->delete();

            Toaster::success("Politician '{$politicianName}' deleted successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to delete politician: " . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $politician = Politician::findOrFail($id);
            $politician->is_active = !$politician->is_active;
            $politician->save();

            $status = $politician->is_active ? 'activated' : 'deactivated';
            Toaster::success("Politician {$status} successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update status: " . $e->getMessage());
        }
    }


    public function render()
    {

        $politicians = Politician::with(['person'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('political_party', 'like', "%{$this->search}%")
                        ->orWhere('constituency', 'like', "%{$this->search}%")
                        ->orWhere('position', 'like', "%{$this->search}%")
                        ->orWhere('office_type', 'like', "%{$this->search}%")
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
            ->when($this->party, function ($query) {
                $query->where('political_party', 'like', "%{$this->party}%");
            })
            ->when($this->officeType, function ($query) {
                $query->where('office_type', 'like', "%{$this->officeType}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $parties = Politician::distinct()
            ->whereNotNull('political_party')
            ->where('political_party', '!=', '')
            ->pluck('political_party')
            ->filter()
            ->values()
            ->toArray();

        $officeTypes = Politician::distinct()
            ->whereNotNull('office_type')
            ->where('office_type', '!=', '')
            ->pluck('office_type')
            ->filter()
            ->values()
            ->toArray();


        return view('livewire.admin.politician.index', compact('politicians', 'parties', 'officeTypes'));
    }
}
