<?php

namespace App\Livewire\Admin\Person;

use App\Models\People;
use Exception;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

#[Lazy]
#[Title("Person List Page")]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    protected $paginationTheme = 'tailwind';
    public string $status = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public bool $loading = false;


    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function placeholder(){
        return view('livewire.admin.person.person-list-skeleton');
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
            $person = People::findOrFail($id);
            $personName = $person->display_name;
            $person->delete();

            Toaster::success("{$personName} deleted successfully.");
        } catch (Exception $e) {
            Toaster::error('Failed to delete person:' . $e->getMessage());
        }

        $this->loading = false;
    }

    public function toggleStatus($id)
    {
        $this->loading = true;

        try {
            $person = People::findOrFail($id);
            $person->status = $person->status === 'active' ? 'inactive' : 'active';
            $person->save();

            $status = $person->status === 'active' ? 'activated' : 'deactivated';
            Toaster::success("Person {$status} successfully.");
        } catch (Exception $e) {
            Toaster::error("Failed to update status:" . $e->getMessage());
        }

        $this->loading = false;
    }



    public function render()
    {
        $people = People::with(['creator'])
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $searchTerm = $this->search;
                $searchTermLower = strtolower($searchTerm);
                $searchTermUpper = ucwords($searchTerm);

                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('full_name', 'like', "%{$searchTerm}%")
                    ->orWhereJsonContains('nicknames', $searchTerm)
                    ->orWhereJsonContains('nicknames', $searchTermLower)
                    ->orWhereJsonContains('nicknames', $searchTermUpper)
                    // Profession search with multiple variations
                    ->orWhereJsonContains('professions', $searchTerm)
                    ->orWhereJsonContains('professions', $searchTermLower)
                    ->orWhereJsonContains('professions', $searchTermUpper);
            });
        })
        ->when($this->status, function ($query) {
            $query->where('status', $this->status);
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate(10);


        return view('livewire.admin.person.index', compact('people'));
    }
}
