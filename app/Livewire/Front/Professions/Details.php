<?php

namespace App\Livewire\Front\Professions;

use App\Models\People;
use F9Web\Meta\Meta;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\URL as LaravelURL;
use Livewire\Attributes\Lazy;

#[Layout('components.layouts.front')]
class Details extends Component
{
    use WithPagination;

    #[Url]
    public $professionName;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'name';

    #[Url]
    public $status = 'all';

    public $category;
    public $professionList = [];
    public $sortedProfessions = [];
    public $showAllProfessions = false; // Toggle state

    public function mount($professionName)
    {
        $this->professionName = $professionName;
        $this->loadProfessionData();
        $this->setMetaTags();
    }

    public function loadProfessionData()
    {
        $categories = config('professions.categories');

        $readableProfession = str_replace('-', ' ', $this->professionName);

        // Check if it's a category
        if (isset($categories[$this->professionName])) {
            $this->category = $categories[$this->professionName];
            $this->professionList = $this->category['professions'];
        } else {
            // It's an individual profession
            $this->professionList = [$readableProfession];

            // Use helper to find category
            $this->category = $this->findCategoryForProfession($this->professionName);

            if (!$this->category) {
                Log::warning('Category not found for profession, using fallback', [
                    'profession' => $this->professionName,
                    'readable_profession' => $readableProfession
                ]);
            }
        }

        $this->sortProfessions('name');
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'sortBy', 'status'])) {
            $this->resetPage();
        }
    }

    /**
     * Find which category a profession belongs to
     */
    private function findCategoryForProfession($profession)
    {
        $categories = config('professions.categories');
        $readableProfession = str_replace('-', ' ', $profession);

        foreach ($categories as $categoryKey => $category) {
            // Exact match
            if (in_array($readableProfession, $category['professions'])) {
                return $category;
            }

            // Case-insensitive match
            $lowercaseProfessions = array_map('strtolower', $category['professions']);
            if (in_array(strtolower($readableProfession), $lowercaseProfessions)) {
                return $category;
            }

            // Slug match (profession in config might be slugified)
            foreach ($category['professions'] as $configProfession) {
                if (str_replace(' ', '-', $configProfession) === $profession) {
                    return $category;
                }
            }
        }

        return null;
    }

    #[Lazy]
    public function render()
    {
        // Set meta tags during render to ensure they're updated
        $this->setMetaTags();

        $query = People::query()
            ->active()
            ->verified()
            ->with('seo');

        // Convert URL slug back to profession name for database query
        $professionForQuery = str_replace('-', ' ', $this->professionName);

        // Always filter by the current profession from URL
        $query->where(function ($q) use ($professionForQuery) {
            $q->whereJsonContains('professions', strtolower($professionForQuery))
              ->orWhereJsonContains('professions', ucwords($professionForQuery))
              ->orWhereJsonContains('professions', $professionForQuery);
        });

        // Apply search filter
        if (!empty($this->search)) {
            $query->search($this->search);
        }

        // Apply status filter
        if ($this->status === 'alive') {
            $query->alive();
        } elseif ($this->status === 'deceased') {
            $query->whereNotNull('death_date');
        }

        // Apply sorting
        $query->when($this->sortBy === 'name', function ($q) {
            $q->orderBy('name');
        })->when($this->sortBy === 'popular', function ($q) {
            $q->orderBy('view_count', 'desc');
        })->when($this->sortBy === 'latest', function ($q) {
            $q->orderBy('created_at', 'desc');
        });

        $people = $query->paginate(12);

        // Get counts for each profession
        $professionCounts = [];
        if (count($this->professionList) > 1) {
            foreach ($this->professionList as $profession) {
                $count = People::query()
                    ->active()
                    ->verified()
                    ->where(function ($q) use ($profession) {
                        $q->whereJsonContains('professions', strtolower($profession))
                          ->orWhereJsonContains('professions', ucwords($profession))
                          ->orWhereJsonContains('professions', $profession);
                    })
                    ->count();

                $professionCounts[$profession] = $count;
            }
        }

        // Generate structured data
        $structuredData = [
            'itemList' => $this->getItemListStructuredData($people),
            'breadcrumb' => $this->getBreadcrumbStructuredData(),
            'website' => $this->getWebsiteStructuredData(),
        ];

        return view('livewire.front.professions.details', [
            'people' => $people,
            'category' => $this->category,
            'professionName' => $this->professionName,
            'totalCount' => $people->total(),
            'professionCounts' => $professionCounts,
            'displayProfessions' => $this->getDisplayProfessions(),
            'structuredData' => $structuredData,
        ]);
    }

    protected function setMetaTags()
    {
        $baseTitle = "Famous Personalities & Celebrities";
        $baseDescription = "Browse our comprehensive collection of famous personalities, celebrities, and notable figures from various fields.";

        // Build profession/category specific titles and descriptions
        $currentTitle = $baseTitle;
        $currentDescription = $baseDescription;

        $displayProfessionName = ucwords(str_replace('-', ' ', $this->professionName));

        if ($this->category) {
            // Category page
            $categoryName = $this->category['name'];
            $currentTitle = "{$categoryName} - Famous Personalities & Professionals";
            $currentDescription = "Discover famous {$categoryName} and their achievements. Explore notable figures in " . strtolower($categoryName) . " with detailed biographies, careers, and contributions.";
        } else {
            // Individual profession page
            $currentTitle = "{$displayProfessionName}s - Famous Personalities";
            $currentDescription = "Discover famous {$displayProfessionName}s and their remarkable careers. Browse through comprehensive profiles of notable {$displayProfessionName}s worldwide.";
        }

        // Handle search
        if (!empty($this->search)) {
            $searchContext = "personalities matching \"{$this->search}\"";
            $currentTitle = "Search Results for \"{$this->search}\" - " . $currentTitle;
            $currentDescription = "Find information about {$searchContext}. " . $baseDescription;
        }

        // Add pagination info if not on first page
        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            $currentTitle .= " - Page {$currentPage}";
            $currentDescription .= " Browse page {$currentPage} of our comprehensive list.";
        }

        // Handle status filter in description
        if ($this->status === 'alive') {
            $currentDescription = "Discover living " . strtolower($currentTitle) . ". " . $currentDescription;
        } elseif ($this->status === 'deceased') {
            $currentDescription = "Explore deceased " . strtolower($currentTitle) . ". " . $currentDescription;
        }

        // Set basic meta tags using Meta facade
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

        // Set meta images
        $this->setMetaImages();
    }

    protected function setMetaImages()
    {
        // You can set category-specific images or use a default
        $defaultImage = asset('images/profession-listing-og.jpg'); // Create this image

        // Optionally set category-specific images
        if ($this->category && isset($this->category['icon'])) {
            // Use a category-specific OG image if available
            $categoryImage = asset("images/{$this->professionName}-category-og.jpg");
            if (file_exists(public_path("images/{$this->professionName}-category-og.jpg"))) {
                $defaultImage = $categoryImage;
            }
        }

        Meta::set('og:image', $defaultImage)
            ->set('twitter:image', $defaultImage)
            ->set('og:image:width', 1200)
            ->set('og:image:height', 630);
    }

    private function getKeywords()
    {
        $baseKeywords = ['famous people', 'celebrities', 'personalities', 'biography', 'notable figures'];

        if ($this->category) {
            $categoryName = $this->category['name'];
            $categoryKeywords = [
                strtolower($categoryName),
                $categoryName . ' personalities',
                'famous ' . strtolower($categoryName),
                $categoryName . ' celebrities',
                'notable ' . strtolower($categoryName)
            ];

            // Add top professions from the category as keywords
            $topProfessions = array_slice($this->category['professions'], 0, 8);
            foreach ($topProfessions as $profession) {
                $categoryKeywords[] = $profession . 's';
                $categoryKeywords[] = 'famous ' . $profession . 's';
            }

            $baseKeywords = array_merge($baseKeywords, $categoryKeywords);
        } else {
            // Individual profession page
            $professionName = str_replace('-', ' ', $this->professionName);
            $professionKeywords = [
                $professionName . 's',
                'famous ' . $professionName . 's',
                'notable ' . $professionName . 's',
                'professional ' . $professionName . 's',
                'top ' . $professionName . 's'
            ];
            $baseKeywords = array_merge($baseKeywords, $professionKeywords);
        }

        // Add search term if present
        if (!empty($this->search)) {
            $baseKeywords[] = $this->search;
            $baseKeywords[] = 'personalities like ' . $this->search;
        }

        // Add status filter keywords
        if ($this->status === 'alive') {
            $baseKeywords[] = 'living personalities';
            $baseKeywords[] = 'current celebrities';
        } elseif ($this->status === 'deceased') {
            $baseKeywords[] = 'deceased personalities';
            $baseKeywords[] = 'historical figures';
        }

        return implode(', ', array_unique($baseKeywords));
    }

    private function getCanonicalUrl()
    {
        // Use the profession name as-is (already slugified)
        $baseUrl = LaravelURL::route('people.profession.details', ['professionName' => $this->professionName]);
        $queryParams = request()->query();

        // Remove page parameter if it's page 1
        if (isset($queryParams['page']) && $queryParams['page'] == 1) {
            unset($queryParams['page']);
        }

        // Remove parameters that are default values
        if (isset($queryParams['sortBy']) && $queryParams['sortBy'] === 'name') {
            unset($queryParams['sortBy']);
        }
        if (isset($queryParams['status']) && $queryParams['status'] === 'all') {
            unset($queryParams['status']);
        }

        if (empty($queryParams)) {
            return $baseUrl;
        }

        return $baseUrl . '?' . http_build_query($queryParams);
    }

    public function sortProfessions($sortBy = 'name')
    {
        $professions = $this->professionList;

        switch ($sortBy) {
            case 'name':
                sort($professions);
                break;
            case 'name-desc':
                rsort($professions);
                break;
            case 'count':
                // Sort by number of people in each profession
                $professionsWithCounts = [];
                foreach ($professions as $profession) {
                    $count = People::query()
                        ->active()
                        ->verified()
                        ->where(function ($q) use ($profession) {
                            $q->whereJsonContains('professions', strtolower($profession))
                              ->orWhereJsonContains('professions', ucwords($profession))
                              ->orWhereJsonContains('professions', $profession);
                        })
                        ->count();

                    $professionsWithCounts[$profession] = $count;
                }
                arsort($professionsWithCounts);
                $professions = array_keys($professionsWithCounts);
                break;
            case 'count-asc':
                $professionsWithCounts = [];
                foreach ($professions as $profession) {
                    $count = People::query()
                        ->active()
                        ->verified()
                        ->where(function ($q) use ($profession) {
                            $q->whereJsonContains('professions', strtolower($profession))
                              ->orWhereJsonContains('professions', ucwords($profession))
                              ->orWhereJsonContains('professions', $profession);
                        })
                        ->count();

                    $professionsWithCounts[$profession] = $count;
                }
                asort($professionsWithCounts);
                $professions = array_keys($professionsWithCounts);
                break;
        }

        $this->sortedProfessions = $professions;
    }

    public function toggleShowAllProfessions()
    {
        $this->showAllProfessions = !$this->showAllProfessions;
    }

    public function getDisplayProfessions()
    {
        if ($this->showAllProfessions) {
            return $this->sortedProfessions;
        }

        return array_slice($this->sortedProfessions, 0, 10);
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->sortBy = 'name';
        $this->status = 'all';
        $this->showAllProfessions = false;
        $this->resetPage();
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

            $description = $person->display_name;
            if ($person->primary_profession) {
                $description .= ' - ' . $person->primary_profession;
            }
            $itemData['item']['description'] = $description;

            $itemListElement[] = $itemData;
        }

        $listName = $this->category ? $this->category['name'] . ' Personalities' : ucwords(str_replace('-', ' ', $this->professionName)) . 's';
        $listDescription = $this->category ?
            "Comprehensive list of famous {$this->category['name']}" :
            "List of notable " . ucwords(str_replace('-', ' ', $this->professionName)) . "s";

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => $listName,
            'description' => $listDescription,
            'url' => url()->current(),
            'numberOfItems' => $people->count(),
            'itemListElement' => $itemListElement
        ];
    }

    private function getBreadcrumbStructuredData()
    {
        $breadcrumbs = [
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
                'item' => route('people.profession.index')
            ]
        ];

        if ($this->category) {
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $this->category['name'],
                'item' => url()->current()
            ];
        } else {
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => ucwords(str_replace('-', ' ', $this->professionName)) . 's',
                'item' => url()->current()
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        ];
    }

    private function getWebsiteStructuredData()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name', 'WikiLife'),
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('people.profession.details', ['professionName' => $this->professionName]) . '?search={search_term_string}',
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }
}
