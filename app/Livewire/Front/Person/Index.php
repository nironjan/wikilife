<?php

namespace App\Livewire\Front\Person;

use App\Models\People;
use F9Web\Meta\Meta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\URL as LaravelURL;

#[Layout('components.layouts.front')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $profession = '';

    #[Url]
    public $category = '';

    public function mount()
    {
        $this->setMetaTags();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'profession', 'category'])) {
            $this->resetPage();
            $this->setMetaTags();
        }
    }

    public function updatingPage($page)
    {
        $this->setMetaTags();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->profession = '';
        $this->category = '';
        $this->resetPage();
        $this->setMetaTags();
    }

    protected function setMetaTags()
    {
        $baseTitle = "Famous Personalities & Celebrities";
        $baseDescription = "Browse our comprehensive collection of famous personalities, celebrities, and notable figures from various fields including entertainment, sports, politics, and business.";

        // Build filter-specific titles and descriptions
        $currentTitle = $baseTitle;
        $currentDescription = $baseDescription;

        if ($this->search) {
            $currentTitle = "Search Results for \"{$this->search}\" - Famous Personalities";
            $currentDescription = "Find information about personalities matching \"{$this->search}\". " . $baseDescription;
        } elseif ($this->category && $categoryConfig = $this->getCategoryConfig($this->category)) {
            $currentTitle = "{$categoryConfig['name']} - Famous Personalities";
            $currentDescription = "Discover famous {$categoryConfig['name']} and their achievements. " . $baseDescription;
        } elseif ($this->profession) {
            $professionName = ucwords(str_replace('_', ' ', $this->profession));
            $currentTitle = "{$professionName}s - Famous Personalities";
            $currentDescription = "Explore notable {$professionName}s and their contributions. " . $baseDescription;
        }

        // Add pagination info if not on first page
        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            $currentTitle .= " - Page {$currentPage}";
            $currentDescription .= " Browse page {$currentPage} of our personalities list.";
        }

        // Handle multiple active filters
        $activeFilters = [];
        if ($this->search) $activeFilters[] = "search: \"{$this->search}\"";
        if ($this->profession) $activeFilters[] = ucwords(str_replace('_', ' ', $this->profession));
        if ($this->category) {
            $categoryConfig = $this->getCategoryConfig($this->category);
            $activeFilters[] = $categoryConfig['name'] ?? ucfirst($this->category);
        }

        if (count($activeFilters) > 1) {
            $filterText = implode(', ', $activeFilters);
            $currentTitle = "Personalities - " . $filterText;
            $currentDescription = "Browse personalities filtered by " . $filterText . ". " . $baseDescription;
        }

        // Set basic meta tags using Meta facade (same as homepage)
        Meta::set('title', $currentTitle)
            ->set('description', $currentDescription)
            ->set('keywords', $this->getKeywords())
            ->set('canonical', $this->getCanonicalUrl());

        // Set Open Graph tags
        Meta::set('og:title', $currentTitle)
            ->set('og:description', $currentDescription)
            ->set('og:type', 'website')
            ->set('og:url', $this->getCanonicalUrl())
            ->set('og:site_name', config('app.name', 'WikiLife'));

        // Set Twitter Card tags
        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $currentTitle)
            ->set('twitter:description', $currentDescription);

        // Set default image for listing pages
        $this->setMetaImages();
    }

    protected function setMetaImages()
    {
        $defaultImage = default_image(1200, 630);

        Meta::set('og:image', $defaultImage)
            ->set('twitter:image', $defaultImage)
            ->set('og:image:width', 1200)
            ->set('og:image:height', 630);
    }

    private function getKeywords()
    {
        $baseKeywords = ['famous people', 'celebrities', 'personalities', 'biography', 'notable figures'];

        $filterKeywords = [];
        if ($this->profession) {
            $professionName = ucwords(str_replace('_', ' ', $this->profession));
            $filterKeywords[] = $this->profession . 's';
            $filterKeywords[] = 'famous ' . $this->profession . 's';
            $filterKeywords[] = $professionName . 's';
        }

        if ($this->category) {
            $categoryConfig = $this->getCategoryConfig($this->category);
            if ($categoryConfig) {
                $filterKeywords[] = strtolower($categoryConfig['name']) . ' personalities';
                $filterKeywords[] = $categoryConfig['name'] . ' celebrities';
                // Add some professions from the category as keywords
                $categoryProfessions = array_slice($categoryConfig['professions'], 0, 5);
                $filterKeywords = array_merge($filterKeywords, $categoryProfessions);
            }
        }

        if ($this->search) {
            $filterKeywords[] = $this->search;
            $filterKeywords[] = 'personalities like ' . $this->search;
        }

        return implode(', ', array_merge($baseKeywords, $filterKeywords));
    }

    private function getCategoryConfig($category)
    {
        return config("professions.categories.{$category}");
    }

    public function getCategories()
    {
        return collect(config('professions.categories'))->mapWithKeys(function ($config, $key) {
            return [$key => $config['name']];
        })->toArray();
    }

    public function getProfessionsForCategory($category)
    {
        $config = $this->getCategoryConfig($category);
        return $config['professions'] ?? [];
    }

    public function getAllProfessions()
    {
        $professions = [];
        foreach (config('professions.categories') as $category) {
            $professions = array_merge($professions, $category['professions']);
        }
        return array_unique($professions);
    }

    public function getCanonicalUrl()
    {
        $baseUrl = LaravelURL::current();
        $queryParams = request()->query();

        // Remove page parameter if it's page 1
        if (isset($queryParams['page']) && $queryParams['page'] == 1) {
            unset($queryParams['page']);
        }

        if (empty($queryParams)) {
            return $baseUrl;
        }

        return $baseUrl . '?' . http_build_query($queryParams);
    }

    public function render()
    {
        $query = People::query()
            ->active()
            ->verified()
            ->with(['seo']);

        // Apply search filter
        if ($this->search) {
            $query->search($this->search);
        }

        // Apply profession filter (takes priority over category)
        if ($this->profession) {
            $query->byProfession($this->profession);
        }
        // Apply category filter only if no specific profession is selected
        elseif ($this->category) {
            $query->byProfessionCategory($this->category);
        }

        $people = $query->orderBy('view_count', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(24);

        $structuredData = [
            'itemList' => $this->getItemListStructuredData($people),
            'breadcrumb' => $this->getBreadcrumbStructuredData(),
            'website' => $this->getWebsiteStructuredData(),
        ];

        return view('livewire.front.person.index', [
            'people' => $people,
            'categories' => $this->getCategories(),
            'structuredData' => $structuredData,
        ]);
    }

    private function getItemListStructuredData($people)
    {
        $itemListElement = [];

        foreach ($people as $index => $person) {
            $itemData = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'Person',
                    'name' => $person->display_name,
                    'url' => route('people.people.show', $person->slug),
                ]
            ];

            if ($person->primary_profession) {
                $itemData['item']['jobTitle'] = $person->primary_profession;
            }

            if ($person->birth_date) {
                $itemData['item']['birthDate'] = $person->birth_date->format('Y-m-d');
            }

            if ($person->is_alive === false && $person->death_date) {
                $itemData['item']['deathDate'] = $person->death_date->format('Y-m-d');
            }

            if ($person->profile_image_url) {
                $itemData['item']['image'] = $person->profile_image_url;
            }

            $itemData['item']['description'] = $person->display_name . ' - ' . ($person->primary_profession ?? 'Notable Personality');

            $itemListElement[] = $itemData;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Famous Personalities List',
            'description' => 'Comprehensive list of famous personalities and notable figures',
            'url' => url()->current(),
            'numberOfItems' => $people->count(),
            'itemListElement' => $itemListElement
        ];
    }

    private function getBreadcrumbStructuredData()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => url('/')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Personalities',
                    'item' => url()->current()
                ]
            ]
        ];
    }

    private function getWebsiteStructuredData()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name', 'Laravel'),
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => url()->current() . '?search={search_term_string}',
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }
}
