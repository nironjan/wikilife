<?php

namespace App\Livewire\Editor\Person;

use App\Models\People;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

#[Lazy]
#[Title('My Persons - Editor')]
#[Layout('components.layouts.editor')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    protected $paginationTheme = 'tailwind';
    public string $status = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public bool $loading = false;

    public $approvalStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        /**
         * @var \App\Models\User|null $user
         */
        $user = Auth::user();

        // Ensure user is editor and can manage content
        if (!$user || !$user->canManageContent()) {
            abort(403, 'Unauthorized access.');
        }
    }

    public function placeholder(){
        return view('livewire.editor.person.person-list-skeleton');
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->loading = true;
    }

    public function search(){
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

    public function deletePerson($id)
    {
        $this->loading = true;

        try {
            $person = People::where('created_by', Auth::id())->findOrFail($id);
            $personName = $person->display_name;
            $person->delete();

            Toaster::success("{$personName} deleted successfully.");
        } catch (Exception $e) {
            Toaster::error('Failed to delete person: ' . $e->getMessage());
        }

        $this->loading = false;
    }

    public function toggleStatus($id)
    {
        $this->loading = true;

        try {
            $person = People::where('created_by', Auth::id())->findOrFail($id);
            $person->status = $person->status === 'active' ? 'inactive' : 'active';
            $person->save();

            $status = $person->status === 'active' ? 'activated' : 'deactivated';
            Toaster::success("Person {$status} successfully.");
        } catch (Exception $e) {
            Toaster::error("Failed to update status: " . $e->getMessage());
        }

        $this->loading = false;
    }


    public function render()
    {
        $people = People::with(['creator'])
            ->where('created_by', Auth::id()) // Only show current editor's persons
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $searchTerm = $this->search;
                    $searchTermLower = strtolower($searchTerm);

                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTermLower}%"])
                        ->orWhereRaw('LOWER(full_name) LIKE ?', ["%{$searchTermLower}%"])
                        ->orWhereJsonContains('nicknames', $searchTerm)
                        ->orWhereJsonContains('nicknames', $searchTermLower)
                        ->orWhereJsonContains('nicknames', ucwords($searchTermLower));
                    // Profession search with multiple variations
                    $q->orWhere(function ($professionQuery) use ($searchTerm, $searchTermLower) {
                        $professionQuery->orWhereRaw('professions::text LIKE ?', ["%{$searchTerm}%"])
                                    ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermLower}%"])
                                    ->orWhereRaw('professions::text LIKE ?', ["%".ucwords($searchTermLower)."%"]);
                    });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })

            ->when($this->approvalStatus, function ($query) {
                $query->where('approval_status', $this->approvalStatus);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.editor.person.index', compact('people'));
    }
}
