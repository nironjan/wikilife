<?php

namespace App\Livewire\Front;

use App\Models\Menu;
use Livewire\Component;

class Navigation extends Component
{
    public $isSearchOpen = false;
    public $isCategoriesOpen = false;
    public $isMobileMenuOpen = false;
    public $popularCategories = [];
    public $categories = []; // Add this line

    public function mount()
    {
        // Get popular categories for the categories dropdown
        $this->popularCategories = [
            'Actors' => 'actor',
            'Politicians' => 'politician',
            'Sports Persons' => 'sports',
            'Entrepreneurs' => 'entrepreneur',
            'Scientists' => 'scientist',
            'Musicians' => 'musician',
            'Writers' => 'writer',
            'Artists' => 'artist',
        ];

        // Add categories from your professions config
        $this->categories = $this->getCategoriesFromConfig();
    }

    private function getCategoriesFromConfig()
    {
        $categories = [];
        $configCategories = config('professions.categories', []);

        foreach ($configCategories as $key => $categoryData) {
            $categories[$key] = $categoryData['name'] ?? ucfirst($key);
        }

        return $categories;
    }

    public function toggleSearch()
    {
        $this->isSearchOpen = !$this->isSearchOpen;
    }

    public function toggleCategories()
    {
        $this->isCategoriesOpen = !$this->isCategoriesOpen;
    }

    public function toggleMobileMenu()
    {
        $this->isMobileMenuOpen = !$this->isMobileMenuOpen;
    }

    public function render()
    {
        $headerMenu = Menu::active()
            ->header()
            ->root()
            ->ordered()
            ->with(['children' => function($query) {
                $query->active()->ordered();
            }])
            ->get();

        return view('livewire.front.navigation', [
            'headerMenu' => $headerMenu,
        ]);
    }
}
