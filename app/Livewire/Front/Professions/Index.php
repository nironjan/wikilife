<?php

namespace App\Livewire\Front\Professions;

use App\Models\People;
use F9Web\Meta\Meta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\URL as LaravelURL;

#[Layout('components.layouts.front')]
#[Title('Professions - Browse Famous Personalities by Category')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $selectedCategory = '';

    public $totalPeopleCount;
    public $perPage = 6;

    public function mount()
    {
        $this->setMetaTags();
        $this->totalPeopleCount = People::active()->verified()->count();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'selectedCategory'])) {
            $this->resetPage();
            $this->setMetaTags();
        }
    }

    protected function setMetaTags()
    {
        $baseTitle = "Professions & Categories - Browse Famous Personalities";
        $baseDescription = "Explore our comprehensive directory of professions and categories. Discover famous personalities, celebrities, and notable figures from various fields including entertainment, sports, politics, and more.";

        $currentTitle = $baseTitle;
        $currentDescription = $baseDescription;

        // Handle search
        if (!empty($this->search)) {
            $currentTitle = "Search Results for \"{$this->search}\" - Professions";
            $currentDescription = "Find professions and categories matching \"{$this->search}\". " . $baseDescription;
        }

        // Handle category filter
        if (!empty($this->selectedCategory)) {
            $category = config('professions.categories')[$this->selectedCategory] ?? null;
            if ($category) {
                $currentTitle = "{$category['name']} - Professional Categories";
                $currentDescription = "Browse {$category['name']} professions and discover famous personalities in this field. " . $baseDescription;
            }
        }

        // Add pagination info if not on first page
        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            $currentTitle .= " - Page {$currentPage}";
            $currentDescription .= " Browse page {$currentPage} of professional categories.";
        }

        // Set meta tags using Meta facade
        Meta::set('title', $currentTitle)
            ->set('description', $currentDescription)
            ->set('keywords', $this->getKeywords())
            ->set('canonical', $this->getCanonicalUrl());

        // Open Graph Tags
        Meta::set('og:title', $currentTitle)
            ->set('og:description', $currentDescription)
            ->set('og:type', 'website')
            ->set('og:url', $this->getCanonicalUrl())
            ->set('og:site_name', config('app.name', 'WikiLife'));

        // Twitter Card Tags
        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $currentTitle)
            ->set('twitter:description', $currentDescription);

        $this->setMetaImages();
    }

    protected function setMetaImages()
    {
        $defaultImage = asset('images/professions-og.jpg');

        Meta::set('og:image', $defaultImage)
            ->set('twitter:image', $defaultImage)
            ->set('og:image:width', 1200)
            ->set('og:image:height', 630);
    }

    private function getKeywords()
    {
        $baseKeywords = [
            'professions', 'careers', 'occupations', 'famous people',
            'celebrities', 'personalities', 'professional categories',
            'notable figures', 'biography', 'celebrity professions'
        ];

        $categories = config('professions.categories');

        // Add category names as keywords
        foreach ($categories as $category) {
            $baseKeywords[] = strtolower($category['name']);
            $baseKeywords[] = $category['name'] . ' professions';
            $baseKeywords[] = 'famous ' . strtolower($category['name']);
        }

        // Add search term if present
        if (!empty($this->search)) {
            $baseKeywords[] = $this->search;
            $baseKeywords[] = 'professions like ' . $this->search;
        }

        return implode(', ', array_unique($baseKeywords));
    }

    private function getCanonicalUrl()
    {
        $baseUrl = LaravelURL::route('people.profession.index');
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

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedCategory = '';
        $this->resetPage();
        $this->setMetaTags();
    }

    public function render()
    {
        $this->setMetaTags();

        $categories = config('professions.categories');

        // Filter categories based on search
        $filteredCategories = $this->filterCategories($categories);

        // Paginate the filtered categories
        $paginatedCategories = $this->paginateCategories($filteredCategories);

        // Get popular professions count
        $popularProfessions = $this->getPopularProfessions();

        $structuredData = $this->getStructuredData($paginatedCategories);

        return view('livewire.front.professions.index', [
            'categories' => $paginatedCategories,
            'totalCategories' => count($categories),
            'filteredCount' => count($filteredCategories),
            'popularProfessions' => $popularProfessions,
            'structuredData' => $structuredData,
            'totalPeopleCount' => $this->totalPeopleCount,
        ]);
    }

    private function filterCategories($categories)
    {
        if (empty($this->search) && empty($this->selectedCategory)) {
            return $categories;
        }

        $filtered = [];

        foreach ($categories as $key => $category) {
            $matchesSearch = empty($this->search) ||
                stripos($category['name'], $this->search) !== false ||
                $this->categoryHasMatchingProfession($category, $this->search);

            $matchesCategory = empty($this->selectedCategory) || $key === $this->selectedCategory;

            if ($matchesSearch && $matchesCategory) {
                $filtered[$key] = $category;
            }
        }

        return $filtered;
    }

    private function categoryHasMatchingProfession($category, $search)
    {
        foreach ($category['professions'] as $profession) {
            if (stripos($profession, $search) !== false) {
                return true;
            }
        }
        return false;
    }

    private function paginateCategories($categories)
    {
        // Convert associative array to collection for pagination
        $collection = collect($categories);

        // Paginate the collection
        $paginated = $collection->slice(($this->getPage() - 1) * $this->perPage, $this->perPage)->all();

        // Create a LengthAwarePaginator instance
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $paginated,
            count($categories),
            $this->perPage,
            $this->getPage(),
            [
                'path' => LaravelURL::route('people.profession.index'),
                'query' => [
                    'search' => $this->search,
                    'selectedCategory' => $this->selectedCategory
                ]
            ]
        );
    }

    private function getPopularProfessions()
    {
        $popularProfessions = [];
        $categories = config('professions.categories');

        foreach ($categories as $category) {
            foreach (array_slice($category['professions'], 0, 5) as $profession) {
                $count = People::active()
                    ->verified()
                    ->where(function ($q) use ($profession) {
                        $q->whereJsonContains('professions', strtolower($profession))
                          ->orWhereJsonContains('professions', ucwords($profession))
                          ->orWhereJsonContains('professions', $profession);
                    })
                    ->count();

                if ($count > 0) {
                    $popularProfessions[$profession] = $count;
                }
            }
        }

        arsort($popularProfessions);
        return array_slice($popularProfessions, 0, 10);
    }

    private function getStructuredData($paginatedCategories)
    {
        $categoryList = [];
        $position = 1;

        foreach ($paginatedCategories as $key => $category) {
            $categoryList[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'item' => [
                    '@type' => 'Category',
                    'name' => $category['name'],
                    'description' => "Browse {$category['name']} professions and famous personalities",
                    'url' => route('people.profession.details', ['professionName' => $key]),
                    'numberOfItems' => count($category['professions'])
                ]
            ];
        }

        return [
            'breadcrumb' => [
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
                        'name' => 'Professions',
                        'item' => url()->current()
                    ]
                ]
            ],
            'itemList' => [
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                'name' => 'Professional Categories',
                'description' => 'Comprehensive list of professional categories and occupations',
                'url' => url()->current(),
                'numberOfItems' => $paginatedCategories->total(),
                'itemListElement' => $categoryList
            ],
            'website' => [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => config('app.name', 'WikiLife'),
                'url' => url('/'),
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => route('people.profession.index') . '?search={search_term_string}',
                    'query-input' => 'required name=search_term_string'
                ]
            ]
        ];
    }
}
