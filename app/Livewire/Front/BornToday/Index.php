<?php

namespace App\Livewire\Front\BornToday;

use App\Models\People;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use F9Web\Meta\Meta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;

#[Layout('components.layouts.front')]
class Index extends Component
{
    use WithPagination;

    public $today;
    public $totalCount;
    public $perPage = 2;

    public function mount()
    {
        $this->today = Carbon::today();
        $this->setMetaTags();
    }
    public function placeholder(){
        return view('livewire.front.born-today.list-skeleton');
    }

    public function getPeopleProperty()
    {

        $todayMonth = $this->today->month;
        $todayDay = $this->today->day;

        return People::active()
            ->verified()
            ->whereMonth('birth_date', $todayMonth)
            ->whereDay('birth_date', $todayDay)
            ->orderBy('view_count', 'desc')
            ->with(['seo'])
            ->paginate($this->perPage, ['id', 'name', 'slug', 'professions', 'profile_image', 'birth_date', 'death_date', 'view_count']);
    }

    protected function setMetaTags()
    {
        $totalCount = People::active()
            ->verified()
            ->whereMonth('birth_date', $this->today->month)
            ->whereDay('birth_date', $this->today->day)
            ->count();

        $baseTitle = "Famous Personalities Born Today - " . $this->today->format('F j');
        $baseDescription = "Discover famous personalities, celebrities, and notable figures born on " . $this->today->format('F j') . ". Explore their biographies, achievements, and lasting legacies.";

        // Build dynamic title and description based on count
        if ($totalCount > 0) {
            $currentTitle = "{$totalCount}+ Personalities Born Today - " . $this->today->format('F j, Y');
            $currentDescription = "Celebrate birthdays of {$totalCount} famous personalities born on " . $this->today->format('F j') . ". Explore their inspiring stories and achievements.";
        } else {
            $currentTitle = "No Birthdays Today - " . $this->today->format('F j, Y');
            $currentDescription = "No notable personalities in our database were born on " . $this->today->format('F j') . ". Explore our complete collection of famous personalities.";
        }

        // Add pagination info if not on first page
        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            $currentTitle .= " - Page {$currentPage}";
            $currentDescription .= " Browse page {$currentPage} of our born today list.";
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

        // Set default image for listing pages
        $this->setMetaImages();
    }

    protected function getKeywords()
    {
        $baseKeywords = [
            'born today',
            'famous birthdays',
            'celebrity birthdays',
            'personalities born today',
            'notable figures',
            'biographies',
            'famous people',
            $this->today->format('F j birthdays'),
            'birthday calendar',
            'famous personalities',
            'celebrity bios'
        ];

        return implode(', ', array_slice($baseKeywords, 0, 10));
    }

    protected function getCanonicalUrl()
    {
        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            return route('people.born-today', ['page' => $currentPage]);
        }
        return route('people.born-today');
    }

    protected function setMetaImages()
    {
        $firstPerson = $this->people->first();

        if ($firstPerson && $firstPerson->profile_image_url) {
            $imageUrl = $firstPerson->imageSize(1200, 630, 80);

            Meta::set('og:image', $imageUrl);
            Meta::set('twitter:image', $imageUrl);

            // Additional Open Graph image properties
            Meta::set('og:image:width', 1200)
                ->set('og:image:height', 630)
                ->set('og:image:type', 'image/jpeg');
        } else {
            // Default image for born today page
            $defaultImage = asset('images/born-today-og.jpg');
            Meta::set('og:image', $defaultImage);
            Meta::set('twitter:image', $defaultImage);
        }
    }

    public function getStructuredData()
    {
        $totalCount = People::active()
            ->verified()
            ->whereMonth('birth_date', $this->today->month)
            ->whereDay('birth_date', $this->today->day)
            ->count();

        // ItemList Schema
        $itemList = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Famous Personalities Born Today - ' . $this->today->format('F j, Y'),
            'description' => 'Discover famous personalities and notable figures who were born on ' . $this->today->format('F j, Y'),
            'numberOfItems' => $totalCount,
            'itemListElement' => []
        ];

        foreach ($this->people as $index => $person) {
            $itemList['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'Person',
                    'name' => $person->name,
                    'url' => route('people.people.show', $person->slug),
                    'birthDate' => $person->birth_date?->toDateString(),
                    'jobTitle' => $person->primary_profession ?? 'Personality',
                    'image' => $person->profile_image_url,
                    'description' => $person->name . ' - ' . ($person->primary_profession ?? 'Famous Personality') . ' born on ' . $person->birth_date?->format('F j, Y')
                ]
            ];
        }

        // WebPage Schema
        $webPage = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'Famous Personalities Born Today - ' . $this->today->format('F j, Y'),
            'description' => 'Discover famous personalities and notable figures born on ' . $this->today->format('F j') . '. Celebrating birthdays of remarkable individuals across various fields.',
            'url' => $this->getCanonicalUrl(),
            'primaryImageOfPage' => $this->people->first()?->profile_image_url ?? asset('images/born-today-og.jpg'),
            'datePublished' => $this->today->toDateString(),
            'dateModified' => $this->today->toDateString(),
            'breadcrumb' => [
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
                        'item' => route('people.people.index')
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => 'Born Today',
                        'item' => $this->getCanonicalUrl()
                    ]
                ]
            ],
            'mainEntity' => $itemList
        ];

        return [
            'itemList' => $itemList,
            'webPage' => $webPage
        ];
    }

    public function render()
    {


        $totalCount = People::active()
            ->verified()
            ->whereMonth('birth_date', $this->today->month)
            ->whereDay('birth_date', $this->today->day)
            ->count();

        $structuredData = $this->getStructuredData();

        return view('livewire.front.born-today.index', [
            'people' => $this->people,
            'totalCount' => $totalCount,
            'structuredData' => $structuredData
        ]);
    }
}
