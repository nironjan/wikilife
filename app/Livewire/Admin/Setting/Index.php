<?php

namespace App\Livewire\Admin\Setting;

use App\Models\People;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithPagination;

#[Lazy]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $profession = '';
    public string $status = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'profession' => ['except' => ''],
        'status' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function placeholder(){
        return view('livewire.admin.person.person-list-skeleton');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingProfession()
    {
        $this->resetPage();
    }

    public function updatingStatus()
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


    public function render()
    {
        $persons = People::with(['seo', 'assets', 'mediaProfile', 'socialLinks'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('full_name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->profession, function ($query) {
                $query->whereJsonContains('professions', $this->profession);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

        $professions = People::whereNotNull('professions')
            ->get()
            ->pluck('professions')
            ->flatten()
            ->unique()
            ->filter()
            ->values()
            ->toArray();

        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'draft' => 'Draft',
        ];


        return view('livewire.admin.setting.index', compact('persons', 'professions', 'statuses'));
    }
}
