<?php

namespace App\Livewire\Front;

use App\Models\People;
use Livewire\Component;

class MobileSearch extends Component
{
    public $search = '';
    public $searchSuggestions = [];
    public $trendingPeople = [];
    public $isOpen = false;

    public function mount()
    {
        // Get trending people for suggestions
        $this->trendingPeople = People::active()
            ->verified()
            ->orderBy('view_count', 'desc')
            ->limit(3)
            ->get(['id', 'name', 'slug', 'professions', 'profile_image']);
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

        // Get people suggestions
        $peopleResults = People::active()
            ->verified()
            ->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('full_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhereJsonContains('nicknames', $searchTerm)
                  ->orWhereJsonContains('professions', $searchTerm)
                  ->orWhereRaw('LOWER(JSON_SEARCH(nicknames, "one", ?)) IS NOT NULL', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(JSON_SEARCH(professions, "one", ?)) IS NOT NULL', ["%{$searchTerm}%"]);
            })
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get(['id', 'name', 'slug', 'professions', 'profile_image']);

        // Get profession suggestions
        $professionResults = $this->getProfessionSuggestions($searchTerm);

        $this->searchSuggestions = [
            'people' => $peopleResults,
            'professions' => $professionResults,
            'query' => $query
        ];
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
            $this->isOpen = false;
            return redirect()->route('people.search', ['query' => $this->search]);
        }
    }

    public function searchBySuggestion($type, $value, $display = null)
    {
        $this->isOpen = false;

        if ($type === 'profession') {
            return redirect()->to($value);
        }

        if ($type === 'person') {
            return redirect()->route('people.people.show', $value);
        }

        return redirect()->route('people.people.search', ['query' => $display ?: $value]);
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->searchSuggestions = [];
    }

    public function close()
    {
        $this->isOpen = false;
        $this->search = '';
        $this->searchSuggestions = [];
    }
    public function render()
    {
        return view('livewire.front.mobile-search');
    }
}
