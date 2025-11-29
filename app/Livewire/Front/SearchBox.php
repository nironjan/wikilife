<?php

namespace App\Livewire\Front;

use App\Models\People;
use Livewire\Component;

class SearchBox extends Component
{
    public $search = '';
    public $searchSuggestions = [];
    public $trendingPeople = [];
    public $placeholder = 'Search names, professions...';
    public $variant = 'default';
    public $showTrending = true;
    public $inputClass = '';
    public $showButton = true;
    public $buttonClass = '';

    public function mount(
        $variant = 'default',
        $showTrending = true,
        $placeholder = null,
        $inputClass = '',
        $showButton = true,
        $buttonClass = ''
    ) {
        $this->variant = $variant;
        $this->showTrending = $showTrending;
        $this->inputClass = $inputClass;
        $this->showButton = $showButton;
        $this->buttonClass = $buttonClass;

        if ($placeholder) {
            $this->placeholder = $placeholder;
        }

        if ($this->showTrending) {
            $this->trendingPeople = People::active()
                ->verified()
                ->orderBy('view_count', 'desc')
                ->limit(4)
                ->get(['id', 'name', 'slug', 'professions', 'profile_image']);
        }
    }

    public function updatedSearch($value)
    {
        if (strlen($value) >= 2) {
            $this->getSearchSuggestions($value);
        } else {
            $this->searchSuggestions = [];
        }
    }

    private function getSearchSuggestions($query)
    {
        $searchTerm = strtolower(trim($query));

        // Use PHP filtering for more reliable results
        $peopleResults = $this->getPeopleSuggestions($searchTerm);

        // Get profession suggestions
        $professionResults = $this->getProfessionSuggestions($searchTerm);

        $this->searchSuggestions = [
            'people' => $peopleResults,
            'professions' => $professionResults,
            'query' => $query
        ];
    }

    /**
     * Primary method using PHP filtering for reliable JSON search
     */
    private function getPeopleSuggestions($searchTerm)
    {
        // Get all active people and filter in PHP
        $allPeople = People::active()
            ->verified()
            ->orderBy('view_count', 'desc')
            ->limit(50)
            ->get(['id', 'name', 'slug', 'professions', 'profile_image', 'full_name', 'nicknames']);

        return $allPeople->filter(function($person) use ($searchTerm) {
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
        })->take(5); // Limit to 5 results
    }

    private function getProfessionSuggestions($query)
    {
        $allCategories = config('professions.categories', []);
        $suggestions = [];

        foreach ($allCategories as $categoryKey => $categoryData) {
            $professions = $categoryData['professions'] ?? [];

            if (!is_array($professions)) {
                continue;
            }

            foreach ($professions as $profession) {
                if (is_string($profession) && stripos($profession, $query) !== false) {
                    $suggestions[] = [
                        'name' => ucwords($profession),
                        'url' => route('people.people.index', $categoryKey),
                        'type' => 'profession'
                    ];
                    break;
                }
            }
        }

        return array_slice($suggestions, 0, 3);
    }

    public function performSearch()
    {
        if (!empty(trim($this->search))) {
            return redirect()->route('people.people.search', ['q' => $this->search]);
        }
    }

    public function searchBySuggestion($slug)
    {
        return redirect()->route('people.people.show', $slug);
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->searchSuggestions = [];
    }

    public function render()
    {
        return view('livewire.front.search-box');
    }
}
