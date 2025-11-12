<?php

namespace App\Livewire\Front\Person\LatestUpdate;

use App\Models\People;
use F9Web\Meta\Meta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\URL as LaravelURL;

#[Layout('components.layouts.front')]
#[Title('Latest Updates')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $updateType = 'all';

    public $person;
    public $personSlug;

    public function mount($personSlug)
    {
        $this->personSlug = $personSlug;
        $this->loadPerson();
        $this->setMetaTags();
    }

    public function loadPerson()
    {
        $this->person = People::active()
            ->verified()
            ->where('slug', $this->personSlug)
            ->with(['seo'])
            ->firstOrFail();
    }

    public function updatedUpdateType()
    {
        $this->resetPage();
        $this->setMetaTags();
    }

    protected function setMetaTags()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;
        $professions = $this->person->professions ?? [];

        // Base title and description
        $baseTitle = "Latest Updates & News - {$personName}";
        $baseDescription = "Stay updated with the latest news, achievements, events, and developments about {$personName}";

        // Add profession context to description
        if ($primaryProfession) {
            $baseDescription .= ", the renowned {$primaryProfession}";
        }

        $baseDescription .= ". Get real-time updates on career milestones, awards, and personal developments.";

        $currentTitle = $baseTitle;
        $currentDescription = $baseDescription;

        // Handle update type filter
        if ($this->updateType !== 'all') {
            $typeLabel = $this->getTypeLabel($this->updateType);
            $currentTitle = "{$typeLabel} - {$personName} Updates";
            $currentDescription = "Latest {$typeLabel} and developments about {$personName}";

            if ($primaryProfession) {
                $currentDescription .= ", the prominent {$primaryProfession}";
            }

            $currentDescription .= ". Stay informed about recent {$typeLabel}.";
        }

        // Add pagination info if not on first page
        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            $currentTitle .= " - Page {$currentPage}";
            $currentDescription .= " Browse page {$currentPage} for more updates.";
        }

        // Set basic meta tags
        Meta::set('title', $currentTitle)
            ->set('description', $currentDescription)
            ->set('keywords', $this->getKeywords())
            ->set('canonical', $this->getCanonicalUrl());

        // Open Graph Tags
        Meta::set('og:title', $currentTitle)
            ->set('og:description', $currentDescription)
            ->set('og:type', 'website')
            ->set('og:url', $this->getCanonicalUrl())
            ->set('og:site_name', config('app.name', 'WikiLife'))
            ->set('article:published_time', $this->person->created_at->toISOString())
            ->set('article:modified_time', $this->person->updated_at->toISOString());

        // Add profession-specific Open Graph tags
        if ($primaryProfession) {
            Meta::set('article:section', $primaryProfession);
        }

        // Twitter Card Tags
        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $currentTitle)
            ->set('twitter:description', $currentDescription);

        // Set meta images
        $this->setMetaImages();

        // Additional professional meta tags
        $this->setProfessionalMetaTags();
    }

    protected function setMetaImages()
    {
        // Use person's profile image if available
        $image = $this->person->profile_image_url ?: default_image(1200, 630);

        Meta::set('og:image', $image)
            ->set('twitter:image', $image)
            ->set('og:image:width', 1200)
            ->set('og:image:height', 630);

        // Add image alt text for accessibility and SEO
        if ($this->person->profile_image_url) {
            Meta::set('og:image:alt', "Latest updates for {$this->person->name}");
        }
    }

    protected function setProfessionalMetaTags()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;
        $professions = $this->person->professions ?? [];

        // Professional meta tags
        if ($primaryProfession) {
            Meta::set('profile:username', $personName)
                ->set('profile:gender', $this->person->gender ?? '')
                ->set('job_title', $primaryProfession);
        }

        // Location-based tags if available
        if ($this->person->nationality) {
            Meta::set('country_name', $this->person->nationality);
        }

        // Additional Open Graph article tags
        Meta::set('og:article:author', $personName);

        if ($this->person->birth_date) {
            Meta::set('birth_date', $this->person->birth_date->format('Y-m-d'));
        }

        // Professional network references
        if ($primaryProfession) {
            Meta::set('business:contact_data:job_title', $primaryProfession);
        }
    }

    private function getKeywords()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;
        $professions = $this->person->professions ?? [];
        $nationality = $this->person->nationality;

        $baseKeywords = [
            $personName,
            'latest updates',
            'news',
            'updates',
            'recent developments',
            'career news',
            'professional updates'
        ];

        // Add profession-based keywords
        if ($primaryProfession) {
            $baseKeywords[] = $primaryProfession;
            $baseKeywords[] = "{$primaryProfession} news";
            $baseKeywords[] = "{$primaryProfession} updates";
            $baseKeywords[] = "famous {$primaryProfession}";
        }

        // Add all professions as keywords
        foreach ($professions as $profession) {
            if ($profession !== $primaryProfession) {
                $baseKeywords[] = $profession;
                $baseKeywords[] = "{$profession} updates";
            }
        }

        // Add nationality keywords
        if ($nationality) {
            $baseKeywords[] = $nationality;
            $baseKeywords[] = "{$nationality} {$primaryProfession}";
        }

        // Add update type specific keywords
        if ($this->updateType !== 'all') {
            $typeLabel = $this->getTypeLabel($this->updateType);
            $baseKeywords[] = $typeLabel;
            $baseKeywords[] = "{$typeLabel} updates";
            $baseKeywords[] = "latest {$typeLabel}";
        }

        // Add pagination keywords
        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            $baseKeywords[] = "page {$currentPage}";
            $baseKeywords[] = "updates page {$currentPage}";
        }

        // Add industry-specific keywords based on profession
        $industryKeywords = $this->getIndustryKeywords($primaryProfession);
        $baseKeywords = array_merge($baseKeywords, $industryKeywords);

        return implode(', ', array_unique(array_filter($baseKeywords)));
    }

    private function getIndustryKeywords($profession)
    {
        $industryMap = [
            'actor' => ['hollywood', 'bollywood', 'film industry', 'movies', 'cinema', 'entertainment'],
            'singer' => ['music industry', 'album', 'song', 'concert', 'music awards', 'recording artist'],
            'politician' => ['government', 'politics', 'election', 'public service', 'legislation', 'policy'],
            'athlete' => ['sports', 'championship', 'tournament', 'team', 'competition', 'athletics'],
            'writer' => ['literature', 'books', 'author', 'publishing', 'novel', 'bestseller'],
            'director' => ['film director', 'movie director', 'cinema', 'filmmaking', 'production'],
            'model' => ['fashion', 'modeling', 'runway', 'photoshoot', 'fashion industry'],
            'scientist' => ['research', 'academia', 'discovery', 'innovation', 'technology', 'study'],
            'business' => ['entrepreneur', 'CEO', 'executive', 'corporation', 'industry leader', 'business news'],
            'doctor' => ['medical', 'healthcare', 'physician', 'hospital', 'medical research'],
        ];

        $professionLower = strtolower($profession);
        foreach ($industryMap as $key => $keywords) {
            if (str_contains($professionLower, $key)) {
                return $keywords;
            }
        }

        return [];
    }

    private function getCanonicalUrl()
    {
        $baseUrl = LaravelURL::route('people.updates.index', ['personSlug' => $this->personSlug]);
        $queryParams = request()->query();

        // Remove page parameter if it's page 1
        if (isset($queryParams['page']) && $queryParams['page'] == 1) {
            unset($queryParams['page']);
        }

        // Remove updateType parameter if it's 'all'
        if (isset($queryParams['updateType']) && $queryParams['updateType'] === 'all') {
            unset($queryParams['updateType']);
        }

        if (empty($queryParams)) {
            return $baseUrl;
        }

        return $baseUrl . '?' . http_build_query($queryParams);
    }

    private function getTypeLabel($type)
    {
        $labels = [
            'all' => 'All Updates',
            'news' => 'News',
            'achievement' => 'Achievements',
            'event' => 'Events',
            'milestone' => 'Milestones',
            'award' => 'Awards',
        ];

        return $labels[$type] ?? ucfirst($type);
    }

    public function getUpdates()
    {
        $query = $this->person->latestUpdates()
            ->published()
            ->approved()
            ->latestFirst();

        if ($this->updateType !== 'all') {
            $query->where('update_type', $this->getExactUpdateType($this->updateType));
        }

        return $query->paginate(10);
    }

    /**
     * Map lowercase filter types to actual database values
     */
    private function getExactUpdateType($filterType)
    {
        $mapping = [
            'news' => 'News',
            'achievement' => 'Achievement',
            'event' => 'Event',
            'milestone' => 'Milestone',
            'award' => 'Award',
        ];

        return $mapping[$filterType] ?? $filterType;
    }

    public function getUpdateTypes()
    {
        // Get actual update types from the database with their counts
        $actualTypes = $this->person->latestUpdates()
            ->published()
            ->approved()
            ->select('update_type')
            ->get()
            ->groupBy('update_type')
            ->map(function ($group) {
                return $group->count();
            });

        $typeLabels = [
            'all' => 'All Updates',
            'news' => 'News',
            'achievement' => 'Achievements',
            'event' => 'Events',
            'milestone' => 'Milestones',
            'award' => 'Awards',
        ];

        // Only show types that actually exist in the data
        $availableTypes = ['all' => $typeLabels['all']];

        foreach ($actualTypes as $dbType => $count) {
            $lowerType = strtolower($dbType);
            if (isset($typeLabels[$lowerType])) {
                $availableTypes[$lowerType] = $typeLabels[$lowerType];
            } else {
                $availableTypes[$lowerType] = ucfirst($dbType);
            }
        }

        return $availableTypes;
    }

    public function getTypeCounts()
    {
        return $this->person->latestUpdates()
            ->published()
            ->approved()
            ->select('update_type')
            ->get()
            ->groupBy('update_type')
            ->map(function ($group) {
                return $group->count();
            });
    }

    public function clearFilters()
    {
        $this->updateType = 'all';
        $this->resetPage();
        $this->setMetaTags();
    }

    public function render()
    {
        // Set meta tags during render to ensure they're updated
        $this->setMetaTags();

        $updates = $this->getUpdates();

        // Generate structured data
        $structuredData = $this->getStructuredData($updates);

        return view('livewire.front.person.latest-update.index', [
            'updates' => $updates,
            'updateTypes' => $this->getUpdateTypes(),
            'typeCounts' => $this->getTypeCounts(),
            'structuredData' => $structuredData,
        ]);
    }

    private function getStructuredData($updates)
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;

        // Person structured data
        $personData = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $personName,
            'url' => LaravelURL::route('people.people.show', $this->personSlug),
        ];

        if ($primaryProfession) {
            $personData['jobTitle'] = $primaryProfession;
        }

        if ($this->person->profile_image_url) {
            $personData['image'] = $this->person->profile_image_url;
        }

        if ($this->person->birth_date) {
            $personData['birthDate'] = $this->person->birth_date->format('Y-m-d');
        }

        // Updates structured data
        $updateItems = [];
        foreach ($updates as $index => $update) {
            $updateItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'Article',
                    'headline' => $update->title,
                    'description' => $update->excerpt ?? "Update about {$personName}",
                    'datePublished' => $update->created_at->toISOString(),
                    'dateModified' => $update->updated_at->toISOString(),
                    'author' => [
                        '@type' => 'Person',
                        'name' => $personName
                    ]
                ]
            ];
        }

        $currentPage = request()->get('page', 1);
        $updatesData = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => "Latest Updates - {$personName}" . ($currentPage > 1 ? " - Page {$currentPage}" : ''),
            'description' => "Recent news and developments about {$personName}",
            'url' => url()->current(),
            'numberOfItems' => $updates->count(),
            'itemListElement' => $updateItems
        ];

        // Breadcrumb structured data
        $breadcrumbData = [
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
                    'name' => $personName,
                    'item' => LaravelURL::route('people.people.show', $this->personSlug)
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => 'Latest Updates',
                    'item' => url()->current()
                ]
            ]
        ];

        return [
            'person' => $personData,
            'updates' => $updatesData,
            'breadcrumb' => $breadcrumbData,
        ];
    }
}
