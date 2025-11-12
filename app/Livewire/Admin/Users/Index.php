<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Exception;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

#[Lazy]
#[Title("User Management")]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    protected $paginationTheme = 'tailwind';
    public string $status = '';
    public string $role = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public bool $loading = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'role' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function placeholder()
    {
        return view('livewire.admin.users.user-list-skeleton');
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->loading = true;
    }

    public function search()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->loading = false;
    }

    public function updatingStatus()
    {
        $this->loading = true;
    }

    public function updatedStatus()
    {
        $this->loading = false;
    }

    public function updatingRole()
    {
        $this->loading = true;
    }

    public function updatedRole()
    {
        $this->loading = false;
    }

    public function updatingSortField()
    {
        $this->loading = true;
    }

    public function updatedSortField()
    {
        $this->loading = false;
    }

    public function sortBy($field)
    {
        $this->loading = true;

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->loading = false;
    }

    public function deleteUser($id)
    {
        $this->loading = true;

        try {
            // Prevent users from deleting themselves
            if ($id === auth()->id()) {
                Toaster::error('You cannot delete your own account.');
                $this->loading = false;
                return;
            }

            $user = User::findOrFail($id);
            $userName = $user->name;

            // Delete profile image from ImageKit if exists
            if ($user->profile_image_file_id) {
                app(\App\Services\ImageKitService::class)->deleteFile($user->profile_image_file_id);
            }

            $user->delete();

            Toaster::success("{$userName} deleted successfully.");
        } catch (Exception $e) {
            Toaster::error('Failed to delete user: ' . $e->getMessage());
        }

        $this->loading = false;
    }

    public function toggleStatus($id)
    {
        $this->loading = true;

        try {
            // Prevent users from deactivating themselves
            if ($id === auth()->id()) {
                Toaster::error('You cannot deactivate your own account.');
                $this->loading = false;
                return;
            }

            $user = User::findOrFail($id);
            $user->status = $user->status === 'active' ? 'inactive' : 'active';
            $user->save();

            $status = $user->status === 'active' ? 'activated' : 'deactivated';
            Toaster::success("User {$status} successfully.");
        } catch (Exception $e) {
            Toaster::error("Failed to update status: " . $e->getMessage());
        }

        $this->loading = false;
    }

    public function toggleTeamMember($id)
    {
        $this->loading = true;

        try {
            $user = User::findOrFail($id);
            $user->is_team_member = !$user->is_team_member;
            $user->save();

            $status = $user->is_team_member ? 'added to' : 'removed from';
            Toaster::success("User {$status} team successfully.");
        } catch (Exception $e) {
            Toaster::error("Failed to update team status: " . $e->getMessage());
        }

        $this->loading = false;
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $searchTerm = $this->search;
                    $q->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%")
                      ->orWhere('bio', 'like', "%{$searchTerm}%");
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->role, function ($query) {
                $query->where('role', $this->role);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.users.index', compact('users'));
    }
}
