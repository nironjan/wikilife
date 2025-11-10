<?php

namespace App\Livewire\Admin\Entrepreneur;

use App\Models\Entrepreneur;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $industry = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'industry' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
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

    public function deleteEntrepreneur($id)
    {
        try {
            $entrepreneur = Entrepreneur::findOrFail($id);
            $companyName = $entrepreneur->company_name;
            $entrepreneur->delete();

            Toaster::success("Entrepreneurship '{$companyName}' deleted successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to delete entrepreneurship: " . $e->getMessage());
        }
    }


    public function toggleStatus($id)
    {
        try {
            $entrepreneur = Entrepreneur::findOrFail($id);
            $entrepreneur->status = $entrepreneur->status === 'active' ? 'inactive' : 'active';
            $entrepreneur->save();

            $status = $entrepreneur->status === 'active' ? 'activated' : 'deactivated';
            Toaster::success("Entrepreneurship {$status} successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update status: " . $e->getMessage());
        }
    }


    public function render()
    {
        $entrepreneurs = Entrepreneur::with(['person'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('company_name', 'like', "%{$this->search}%")
                        ->orWhere('role', 'like', "%{$this->search}%")
                        ->orWhere('industry', 'like', "%{$this->search}%")
                        ->orWhereHas('person', function ($personQuery) {
                            $personQuery->where('name', 'like', "%{$this->search}%")
                                ->orWhere('full_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->industry, function ($query) {
                $query->where('industry', 'like', "%{$this->industry}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'acquired' => 'Acquired',
            'closed' => 'Closed',
        ];

        $industries = Entrepreneur::distinct()->pluck('industry')->filter()->toArray();

        return view('livewire.admin.entrepreneur.index', compact('entrepreneurs', 'statuses', 'industries'));
    }
}
