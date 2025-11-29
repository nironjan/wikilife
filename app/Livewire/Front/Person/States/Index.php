<?php

namespace App\Livewire\Front\Person\States;

use App\Models\People;
use F9Web\Meta\Meta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.front')]
#[Title('Indian States & Union Territories - People Directory')]
class Index extends Component
{
    protected function setMetaTags()
    {
        $currentTitle = "Famous People from Indian States & Union Territories - Complete List";
        $currentDescription = "Explore famous people, celebrities, and notable personalities from all Indian states and union territories. Browse by state to discover people from Maharashtra, Tamil Nadu, Delhi, Karnataka and more.";

        $keywords = [
            'Indian states',
            'Union Territories India',
            'states of India',
            'Indian states list',
            'famous people by state',
            'celebrities by state India',
            'Indian states directory',
            '28 states India',
            '8 union territories India',
            'state-wise people directory'
        ];

        Meta::set('title', $currentTitle)
            ->set('description', $currentDescription)
            ->set('keywords', implode(', ', $keywords))
            ->set('canonical', $this->getCanonicalUrl())
            ->set('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1');

        // Open Graph Tags
        Meta::set('og:title', $currentTitle)
            ->set('og:description', $currentDescription)
            ->set('og:type', 'website')
            ->set('og:url', $this->getCanonicalUrl())
            ->set('og:site_name', config('app.name', 'Wiki Life'))
            ->set('og:locale', app()->getLocale());

        // Twitter Card Tags
        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $currentTitle)
            ->set('twitter:description', $currentDescription);

        // Additional meta tags
        Meta::set('author', config('app.name', 'Wiki Life'))
            ->set('publisher', config('app.name', 'Wiki Life'))
            ->set('content-type', 'website')
            ->set('content-language', app()->getLocale())
            ->set('audience', 'all')
            ->set('rating', 'general')
            ->set('distribution', 'global');
    }

    private function getCanonicalUrl()
    {
        return route('people.states.index');
    }

    public function mount()
    {
        $this->setMetaTags();
    }

    public function getStatesWithCounts()
    {
        $states = config('indian_states.states');
        $unionTerritories = config('indian_states.uts');

        // Get people count for each state
        $stateCounts = People::active()
            ->verified()
            ->whereNotNull('state_code')
            ->where('state_code', '!=', '')
            ->selectRaw('state_code, COUNT(*) as count')
            ->groupBy('state_code')
            ->pluck('count', 'state_code')
            ->toArray();

        return [
            'states' => collect($states)->map(function($name, $code) use ($stateCounts) {
                return [
                    'code' => $code,
                    'name' => $name,
                    'count' => $stateCounts[$code] ?? 0,
                    'url' => route('people.state-list.index', ['stateCode' => $code])
                ];
            })->sortBy('name'),

            'unionTerritories' => collect($unionTerritories)->map(function($name, $code) use ($stateCounts) {
                return [
                    'code' => $code,
                    'name' => $name,
                    'count' => $stateCounts[$code] ?? 0,
                    'url' => route('people.state-list.index', ['stateCode' => $code])
                ];
            })->sortBy('name')
        ];
    }

    public function getTotalPeopleCount()
    {
        return People::active()
            ->verified()
            ->whereNotNull('state_code')
            ->where('state_code', '!=', '')
            ->count();
    }

    public function getBreadcrumbStructuredData()
    {
        $breadcrumbSchema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => [
                [
                    "@type" => "ListItem",
                    "position" => 1,
                    "name" => "Home",
                    "item" => url('/')
                ],
                [
                    "@type" => "ListItem",
                    "position" => 2,
                    "name" => "People",
                    "item" => route('people.people.index')
                ],
                [
                    "@type" => "ListItem",
                    "position" => 3,
                    "name" => "States",
                    "item" => $this->getCanonicalUrl()
                ]
            ]
        ];

        return json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function getWebsiteStructuredData()
    {
        $websiteSchema = [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "name" => config('app.name', 'Wiki Life'),
            "url" => url('/'),
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => route('people.people.index') . "?search={search_term_string}",
                "query-input" => "required name=search_term_string"
            ],
            "description" => "Comprehensive directory of famous people from Indian states and union territories"
        ];

        return json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function getCollectionPageStructuredData()
    {
        $statesData = $this->getStatesWithCounts();
        $allItems = $statesData['states']->merge($statesData['unionTerritories']);

        $itemList = $allItems->values()->map(function($item, $index) {
            return [
                "@type" => "ListItem",
                "position" => (int)$index + 1,
                "name" => $item['name'],
                "url" => $item['url'],
                "description" => "Explore famous people from " . $item['name'] . " - " . $item['count'] . " profiles available"
            ];
        })->toArray();

        $collectionSchema = [
            "@context" => "https://schema.org",
            "@type" => "CollectionPage",
            "name" => "Indian States & Union Territories - People Directory",
            "description" => "Complete directory of famous people categorized by Indian states and union territories",
            "url" => $this->getCanonicalUrl(),
            "mainEntity" => [
                "@type" => "ItemList",
                "numberOfItems" => $allItems->count(),
                "itemListElement" => $itemList
            ]
        ];

        return json_encode($collectionSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function render()
    {
        $statesData = $this->getStatesWithCounts();
        $totalCount = $this->getTotalPeopleCount();
        $breadcrumbStructuredData = $this->getBreadcrumbStructuredData();
        $websiteStructuredData = $this->getWebsiteStructuredData();
        $collectionStructuredData = $this->getCollectionPageStructuredData();

        return view('livewire.front.person.states.index', compact(
            'statesData',
            'totalCount',
            'breadcrumbStructuredData',
            'websiteStructuredData',
            'collectionStructuredData'
        ));
    }
}
