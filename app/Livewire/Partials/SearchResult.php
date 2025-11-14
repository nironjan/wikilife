<?php

namespace App\Livewire\Partials;

use App\Models\People;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

#[Layout('components.layouts.front')]
class SearchResult extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public $query = '';
    public $totalResults = 0;
    public $searchPerformed = false;

    public function mount()
    {
        if (request()->has('q')) {
            $this->query = request('q');
            $this->searchPerformed = true;
        }
    }

    public function performSearch()
    {
        if (empty(trim($this->query))) {
            $this->searchPerformed = false;
            $this->totalResults = 0;
            $this->resetPage();
            return;
        }

        $this->searchPerformed = true;
        $this->resetPage();
    }

    public function updatedQuery()
    {
        $this->performSearch();
    }

    public function getSearchResultsProperty()
    {
        if (!$this->searchPerformed || empty(trim($this->query))) {
            $this->totalResults = 0;
            return collect();
        }

        // Only search if query is at least 4 characters
        if (strlen(trim($this->query)) < 4) {
            $this->totalResults = 0;
            return collect();
        }

        $searchTerm = strtolower(trim($this->query));

        // Use the same PHP filtering logic as your search suggestions
        $allPeople = People::active()
            ->verified()
            ->with(['seo'])
            ->orderBy('view_count', 'desc')
            ->get();

        $filteredResults = $allPeople->filter(function($person) use ($searchTerm) {
            // Check name (case insensitive)
            if (stripos($person->name, $searchTerm) !== false) {
                return true;
            }

            // Check full_name (case insensitive)
            if ($person->full_name && stripos($person->full_name, $searchTerm) !== false) {
                return true;
            }

            // Check nicknames (case insensitive)
            $nicknames = $person->nicknames ?? [];
            foreach ($nicknames as $nickname) {
                if (stripos($nickname, $searchTerm) !== false) {
                    return true;
                }
            }

            // Check professions (case insensitive)
            $professions = $person->professions ?? [];
            foreach ($professions as $profession) {
                if (stripos($profession, $searchTerm) !== false) {
                    return true;
                }
            }

            return false;
        });

        $this->totalResults = $filteredResults->count();

        // Manual pagination
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $filteredResults->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedResults = new LengthAwarePaginator(
            $currentItems,
            $filteredResults->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return $paginatedResults;
    }

    public function render()
    {
        if (!empty($this->query) && !$this->searchPerformed) {
            $this->searchPerformed = true;
        }

        return view('livewire.partials.search-result', [
            'searchResults' => $this->searchResults
        ]);
    }
}
