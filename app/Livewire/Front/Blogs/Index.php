<?php

namespace App\Livewire\Front\Blogs;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use F9Web\Meta\Meta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\URL as LaravelURL;
use Livewire\Attributes\Lazy;

#[Layout('components.layouts.front')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $category = '';

    #[Url]
    public $sortBy = 'latest';

    public function mount()
    {
        $this->setMetaTags();
    }

    public function placeholder(){
        return view('livewire.front.blogs.article-list-skeleton');
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'category', 'sortBy'])) {
            $this->resetPage();
            $this->setMetaTags();
        }
    }

    public function render()
    {
        // Set meta tags during render to ensure they're updated
        $this->setMetaTags();

        $query = BlogPost::query()
            ->published()
            ->with(['blogCategory', 'author']);

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'LIKE', "%{$this->search}%")
                  ->orWhere('excerpt', 'LIKE', "%{$this->search}%")
                  ->orWhere('content', 'LIKE', "%{$this->search}%");
            });
        }

        // Apply category filter
        if ($this->category) {
            $query->whereHas('blogCategory', function ($q) {
                $q->where('slug', $this->category);
            });
        }

        // Apply sorting
        $query->when($this->sortBy === 'latest', function ($q) {
            $q->latest('published_at');
        })->when($this->sortBy === 'popular', function ($q) {
            $q->orderBy('views', 'desc');
        });

        $articles = $query->paginate(10);

        $categories = BlogCategory::withCount(['blogPosts' => function ($q) {
            $q->published();
        }])->orderBy('name')->get();

        $popularPosts = BlogPost::published()
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $structuredData = $this->getStructuredData($articles);

        return view('livewire.front.blogs.index', [
            'articles' => $articles,
            'categories' => $categories,
            'popularPosts' => $popularPosts,
            'structuredData' => $structuredData,
        ]);
    }

    protected function setMetaTags()
    {
        $baseTitle = "Blog Articles & Insights";
        $baseDescription = "Discover our latest blog articles, news, and insights about famous personalities, entertainment, politics, sports, and more.";

        $currentTitle = $baseTitle;
        $currentDescription = $baseDescription;

        if ($this->search) {
            $currentTitle = "Search Results for \"{$this->search}\" - Blog Articles";
            $currentDescription = "Find blog articles matching \"{$this->search}\". " . $baseDescription;
        }

        if ($this->category) {
            $category = BlogCategory::where('slug', $this->category)->first();
            if ($category) {
                $categoryName = $category->name;
                $currentTitle = "{$categoryName} - Blog Articles";
                $currentDescription = "Browse {$categoryName} blog articles and insights. " . $baseDescription;
            }
        }

        // Add pagination info if not on first page
        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            $currentTitle .= " - Page {$currentPage}";
            $currentDescription .= " Browse page {$currentPage} of our blog articles.";
        }

        // Set meta tags
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
        $defaultImage = asset('images/blog-listing-og.jpg');

        Meta::set('og:image', $defaultImage)
            ->set('twitter:image', $defaultImage)
            ->set('og:image:width', 1200)
            ->set('og:image:height', 630);
    }

    private function getKeywords()
    {
        $baseKeywords = ['blog', 'articles', 'news', 'insights', 'updates', 'personalities', 'celebrities'];

        if ($this->search) {
            $baseKeywords[] = $this->search;
            $baseKeywords[] = 'articles about ' . $this->search;
        }

        if ($this->category) {
            $category = BlogCategory::where('slug', $this->category)->first();
            if ($category) {
                $baseKeywords[] = $category->name;
                $baseKeywords[] = $category->name . ' articles';
                $baseKeywords[] = $category->name . ' news';
            }
        }

        return implode(', ', array_unique($baseKeywords));
    }

    private function getCanonicalUrl()
    {
        $baseUrl = LaravelURL::route('articles.index');
        $queryParams = request()->query();

        // Remove page if it's page 1
        if (isset($queryParams['page']) && $queryParams['page'] == 1) {
            unset($queryParams['page']);
        }

        // Remove default sort
        if (isset($queryParams['sortBy']) && $queryParams['sortBy'] === 'latest') {
            unset($queryParams['sortBy']);
        }

        if (empty($queryParams)) {
            return $baseUrl;
        }

        return $baseUrl . '?' . http_build_query($queryParams);
    }

    private function getStructuredData($articles)
    {
        $itemListElement = [];
        $position = 1;

        foreach ($articles as $article) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'item' => [
                    '@type' => 'BlogPosting',
                    'headline' => $article->title,
                    'description' => $article->excerpt,
                    'url' => route('articles.show', $article->slug),
                    'datePublished' => $article->published_at->toISOString(),
                    'dateModified' => $article->updated_at->toISOString(),
                    'author' => [
                        '@type' => 'Person',
                        'name' => $article->author->name ?? 'Admin',
                    ],
                ]
            ];
        }

        return [
            'itemList' => [
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                'name' => 'Blog Articles',
                'description' => 'Latest blog articles and insights',
                'url' => url()->current(),
                'numberOfItems' => $articles->count(),
                'itemListElement' => $itemListElement
            ],
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
                        'name' => 'Blog Articles',
                        'item' => url()->current()
                    ]
                ]
            ],
            'website' => [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => config('app.name', 'WikiLife'),
                'url' => url('/'),
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => route('articles.index') . '?search={search_term_string}',
                    'query-input' => 'required name=search_term_string'
                ]
            ]
        ];
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->sortBy = 'latest';
        $this->resetPage();
    }
}
