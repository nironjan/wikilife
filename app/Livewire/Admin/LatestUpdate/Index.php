<?php

namespace App\Livewire\Admin\LatestUpdate;

use App\Models\LatestUpdate;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;


    public string $search = '';
    public string $status = '';
    public string $update_type = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'update_type' => ['except' => ''],
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

    public function updatingUpdateType()
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

    public function deleteUpdate($id)
    {
        try {
            $update = LatestUpdate::findOrFail($id);
            $updateTitle = $update->title;
            $update->delete();

            Toaster::success("Update '{$updateTitle}' deleted successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to delete update: " . $e->getMessage());
        }
    }

    public function toggleApproval($id)
    {
        try {
            $update = LatestUpdate::findOrFail($id);
            $update->is_approved = !$update->is_approved;
            $update->save();

            $status = $update->is_approved ? 'approved' : 'unapproved';
            Toaster::success("Update {$status} successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update approval: " . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $update = LatestUpdate::findOrFail($id);
            $update->status = $update->status === 'published' ? 'draft' : 'published';
            $update->save();

            $status = $update->status;
            Toaster::success("Update {$status} successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update status: " . $e->getMessage());
        }
    }

    public function render()
    {

        $updates = LatestUpdate::with(['person', 'user'])
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('update_content', 'like', "%{$this->search}%")
                    ->orWhere('update_type', 'like', "%{$this->search}%")
                    ->orWhereHas('person', function ($personQuery) {
                        $personQuery->where('name', 'like', "%{$this->search}%")
                            ->orWhere('full_name', 'like', "%{$this->search}%");
                    });
            });
        })
        ->when($this->status, function ($query) {
            $query->where('status', $this->status);
        })
        ->when($this->update_type, function ($query) {
            $query->where('update_type', $this->update_type);
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate(15);

        $statuses = ['draft', 'published'];
        $updateTypes = LatestUpdate::distinct()->pluck('update_type')->filter()->toArray();


        return view('livewire.admin.latest-update.index', compact('updates', 'statuses', 'updateTypes'));
    }
}
