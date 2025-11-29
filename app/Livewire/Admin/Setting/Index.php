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
        try {
            $persons = People::with(['seo', 'assets', 'mediaProfile', 'socialLinks'])
                ->when($this->search, function ($query) {
                    $search = strtolower($this->search);
                    $searchTerm = $this->search;
                    $searchTermLower = $search;
                    $searchTermTitle = ucwords($search);

                    $query->where(function ($q) use ($search, $searchTerm, $searchTermLower, $searchTermTitle) {
                        // Name and full name search with case sensitivity
                        $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(full_name) LIKE ?', ["%{$search}%"]);

                        // Nicknames search with multiple case variations
                        $q->orWhereJsonContains('nicknames', $searchTerm)
                        ->orWhereJsonContains('nicknames', $searchTermLower)
                        ->orWhereJsonContains('nicknames', $searchTermTitle);

                        // Professions search with multiple case variations
                        $q->orWhere(function ($profQuery) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                            $profQuery->orWhereRaw('professions::text LIKE ?', ["%{$searchTerm}%"])
                                    ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermLower}%"])
                                    ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermTitle}%"]);
                        });
                    });
                })
                ->when($this->profession, function ($query) {
                    $profession = strtolower($this->profession);
                    $professionTerm = $this->profession;
                    $professionTitle = ucwords($profession);

                    $query->where(function ($q) use ($profession, $professionTerm, $professionTitle) {
                        $q->whereJsonContains('professions', $professionTerm)
                        ->orWhereJsonContains('professions', $profession)
                        ->orWhereJsonContains('professions', $professionTitle)
                        ->orWhereRaw('professions::text LIKE ?', ["%{$professionTerm}%"])
                        ->orWhereRaw('professions::text LIKE ?', ["%{$profession}%"])
                        ->orWhereRaw('professions::text LIKE ?', ["%{$professionTitle}%"]);
                    });
                })
                ->when($this->status, function ($query) {
                    $query->where('status', $this->status);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(20);

        } catch (\Exception $e) {
            logger("People search error: " . $e->getMessage());
            $persons = People::where('id', 0)->paginate(20);
        }

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
