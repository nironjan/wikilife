<?php

namespace App\Livewire\Front\Person;

use App\Models\People;
use F9Web\Meta\Meta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.front')]
#[Title('People by State')]
class StateList extends Component
{
    use WithPagination;

    public $stateCode;
    public $stateName;
    public $search = '';
    public $professionFilter = '';
    public $sortBy = 'name';
    public $sortOrder = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'professionFilter' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
    ];

    public function mount($stateCode)
    {
        // Convert URL parameter to uppercase and find in config
        $stateCode = strtoupper($stateCode);
        $this->stateName = config("indian_states.all.{$stateCode}", 'Unknown State');

        if ($this->stateName === 'Unknown State') {
            // Try to find by state name (case-insensitive)
            $allStates = config('indian_states.all');
            $foundState = collect($allStates)->first(function($name, $code) use ($stateCode) {
                return strtolower($name) === strtolower($stateCode);
            });

            if ($foundState) {
                $this->stateName = $foundState;
                $this->stateCode = array_search($foundState, $allStates);
            } else {
                abort(404, 'State not found');
            }
        } else {
            $this->stateCode = $stateCode;
        }

        $this->setMetaTags();
    }

    protected function setMetaTags()
    {
        $stateName = $this->stateName;
        $currentTitle = "Famous People from {$stateName} - Complete List";
        $currentDescription = "Explore famous personalities, celebrities, and notable people from {$stateName}. Complete list of actors, politicians, athletes, artists and more from {$stateName} state.";

        $keywords = [
            $stateName . ' famous people',
            'celebrities from ' . $stateName,
            $stateName . ' personalities',
            'notable people from ' . $stateName,
            $stateName . ' celebrities list',
            'famous personalities ' . $stateName,
            $stateName . ' actors',
            $stateName . ' politicians',
            $stateName . ' athletes'
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
        return route('people.states.index', ['stateCode' => $this->stateCode]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedProfessionFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortOrder = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->professionFilter = '';
        $this->sortBy = 'name';
        $this->sortOrder = 'asc';
        $this->resetPage();
    }

    public function getPeople()
    {
        $query = People::active()
            ->verified()
            ->where('state_code', $this->stateCode)
            ->whereNotNull('state_code')
            ->where('state_code', '!=', '');

        // Enhanced Search filter
        if ($this->search) {
            $searchTerm = $this->search;
            $searchTermLower = strtolower($searchTerm);
            $searchTermTitle = ucwords($searchTermLower);

            $query->where(function ($q) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                // Name and full name search
                $q->where(function ($nameQuery) use ($searchTermLower) {
                    $nameQuery->where('name', 'ilike', "%{$searchTermLower}%")
                            ->orWhere('full_name', 'ilike', "%{$searchTermLower}%");
                });

                // Nicknames search
                $q->orWhere(function ($nicknameQuery) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                    $nicknameQuery->whereJsonContains('nicknames', $searchTerm)
                                ->orWhereJsonContains('nicknames', $searchTermLower)
                                ->orWhereJsonContains('nicknames', $searchTermTitle);
                });

                // Professions search
                $q->orWhere(function ($professionQuery) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                    $professionQuery->whereJsonContains('professions', $searchTerm)
                                ->orWhereJsonContains('professions', $searchTermLower)
                                ->orWhereJsonContains('professions', $searchTermTitle);
                });
            });
        }

        // Profession filter
        if ($this->professionFilter) {
            $query->where(function ($q) {
                $q->whereJsonContains('professions', $this->professionFilter)
                ->orWhere('professions', 'ilike', "%{$this->professionFilter}%");
            });
        }

        // Sorting
        switch ($this->sortBy) {
            case 'name':
                $query->orderBy('name', $this->sortOrder);
                break;
            case 'view_count':
                $query->orderBy('view_count', $this->sortOrder);
                break;
            case 'birth_date':
                $query->orderBy('birth_date', $this->sortOrder);
                break;
            case 'popularity':
                $query->orderBy('view_count', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        return $query->paginate(24);
    }

    public function getProfessionsList()
    {
        return People::active()
            ->verified()
            ->where('state_code', $this->stateCode)
            ->whereNotNull('state_code')
            ->where('state_code', '!=', '')
            ->select('professions')
            ->get()
            ->pluck('professions')
            ->flatten()
            ->unique()
            ->filter()
            ->sort()
            ->values();
    }

    public function getTotalPeopleCount()
    {
        return People::active()
            ->verified()
            ->where('state_code', $this->stateCode)
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
                    "item" => route('people.states.index' )
                ],
                [
                    "@type" => "ListItem",
                    "position" => 4,
                    "name" => $this->stateName,
                    "item" => $this->getCanonicalUrl()
                ]
            ]
        ];

        return json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }


    public function render()
    {
        $people = $this->getPeople();
        $professions = $this->getProfessionsList();
        $totalCount = $this->getTotalPeopleCount();
        $structuredData = $this->getBreadcrumbStructuredData();

        return view('livewire.front.person.state-list', compact(
            'people',
            'professions',
            'totalCount',
            'structuredData'
        ));
    }
}
