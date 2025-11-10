<?php

namespace App\Livewire\Partials;

use App\Models\People;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.front')]
class SearchResult extends Component
{
    use WithPagination;

    #[Url]
    public $query = '';
    public $totalResults = 0;
    public $searchPerformed = false;

    public function mount($query = null)
    {
        if ($query) {
            $this->query = $query;
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
        // If we have a query from URL parameter but searchPerformed is false, set it to true
        if (!empty($this->query) && !$this->searchPerformed) {
            $this->searchPerformed = true;
        }

        if (!$this->searchPerformed || empty(trim($this->query))) {
            $this->totalResults = 0;
            return collect();
        }

        $searchTerm = strtolower($this->query);

        $results = People::active()
            ->verified()
            ->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$this->query}%")
                  ->orWhere('full_name', 'LIKE', "%{$this->query}%")
                  ->orWhereJsonContains('nicknames', $searchTerm)
                  ->orWhereJsonContains('professions', $searchTerm);
            })
            ->with(['seo'])
            ->orderBy('view_count', 'desc')
            ->paginate(20);

        $this->totalResults = $results->total();

        return $results;
    }

    public function render()
    {
        // Ensure search is performed when component renders with a query
        if (!empty($this->query) && !$this->searchPerformed) {
            $this->searchPerformed = true;
        }

        return view('livewire.partials.search-result', [
            'searchResults' => $this->searchResults
        ]);
    }
}
