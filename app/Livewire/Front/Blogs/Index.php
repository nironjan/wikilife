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
use Illuminate\Support\Str;

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
        $baseTitle = "Blog Articles & Insights - Latest News & Updates";
        $baseDescription = "Discover comprehensive blog articles, news, and insights about famous personalities, entertainment trends, political analysis, sports highlights, and lifestyle topics. Stay updated with our expert content.";

        $currentTitle = $baseTitle;
        $currentDescription = $baseDescription;
        $currentKeywords = $this->getKeywords();

        // Handle search results
        if ($this->search) {
            $currentTitle = "Search Results for \"{$this->search}\" - Blog Articles & Insights";
            $currentDescription = "Find comprehensive blog articles matching \"{$this->search}\". Explore in-depth analysis, news, and insights about {$this->search} topics. " . $baseDescription;
        }

        // Handle category filtering
        if ($this->category) {
            $category = BlogCategory::where('slug', $this->category)->first();
            if ($category) {
                $categoryName = $category->name;
                $categoryDescription = $category->description ?: "Explore {$categoryName} articles, news, and insights";

                $currentTitle = "{$categoryName} - Blog Articles & News Updates";
                $currentDescription = "Browse our comprehensive collection of {$categoryName} blog articles. {$categoryDescription}. Latest news, analysis, and expert insights.";
            }
        }

        // Handle sorting
        if ($this->sortBy === 'popular') {
            $currentTitle = "Most Popular Blog Articles - Trending News & Insights";
            $currentDescription = "Discover our most popular and trending blog articles. Read the most engaging content about personalities, entertainment, politics, and more.";
        }

        // Add pagination info if not on first page
        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            $currentTitle .= " - Page {$currentPage}";
            $currentDescription .= " Browse page {$currentPage} of our extensive blog article collection.";
        }

        // Set basic meta tags
        Meta::set('title', $currentTitle)
            ->set('description', $currentDescription)
            ->set('keywords', $currentKeywords)
            ->set('canonical', $this->getCanonicalUrl())
            ->set('robots', $this->getRobotsMeta())
            ->set('author', config('app.name', 'WikiLife'))
            ->set('publisher', config('app.name', 'WikiLife'))
            ->set('language', 'en')
            ->set('revisit-after', '1 days')
            ->set('rating', 'general')
            ->set('distribution', 'global')
            ->set('googlebot', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')
            ->set('bingbot', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1');

        // Open Graph Tags
        Meta::set('og:title', $currentTitle)
            ->set('og:description', $currentDescription)
            ->set('og:type', 'website')
            ->set('og:url', $this->getCanonicalUrl())
            ->set('og:site_name', config('app.name', 'WikiLife'))
            ->set('og:locale', 'en_US')
            ->set('og:updated_time', now()->toISOString());

        // Twitter Card Tags
        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $currentTitle)
            ->set('twitter:description', $currentDescription)
            ->set('twitter:site', '@' . str_replace(' ', '', config('app.name', 'WikiLife')))
            ->set('twitter:creator', '@' . str_replace(' ', '', config('app.name', 'WikiLife')))
            ->set('twitter:label1', 'Content Type')
            ->set('twitter:data1', 'Blog Articles')
            ->set('twitter:label2', 'Total Articles')
            ->set('twitter:data2', BlogPost::published()->count() . '+');

        // Additional Article-specific meta
        Meta::set('article:content_tier', 'free')
            ->set('article:opinion', 'false')
            ->set('article:family_friendly', 'yes')
            ->set('content-type', 'blog')
            ->set('content-language', 'en')
            ->set('audience', 'all')
            ->set('content_category', 'blog');

        // Set meta images
        $this->setMetaImages();

        // Set additional schema-specific meta
        $this->setAdditionalMetaTags();
    }

    protected function setMetaImages()
    {
        $defaultImage = default_image(1200, 630);

        Meta::set('og:image', $defaultImage)
            ->set('og:image:width', 1200)
            ->set('og:image:height', 630)
            ->set('og:image:alt', 'Blog Articles & Insights - ' . config('app.name', 'WikiLife'))
            ->set('og:image:type', 'image/jpeg')
            ->set('twitter:image', $defaultImage)
            ->set('twitter:image:alt', 'Blog Articles & Insights - ' . config('app.name', 'WikiLife'))
            ->set('image', $defaultImage)
            ->set('thumbnail', $defaultImage);

        // Try to get a featured image from latest articles
        $featuredArticle = BlogPost::published()->whereNotNull('featured_image')->latest()->first();
        if ($featuredArticle && $featuredArticle->featured_image) {
            Meta::set('og:image', $featuredArticle->featured_image)
                ->set('twitter:image', $featuredArticle->featured_image)
                ->set('image', $featuredArticle->featured_image);
        }
    }

    protected function setAdditionalMetaTags()
    {
        // News and article specific meta
        Meta::set('news_keywords', $this->getNewsKeywords())
            ->set('standout', $this->getCanonicalUrl())
            ->set('original-source', $this->getCanonicalUrl());

        // Mobile and app specific meta
        Meta::set('mobile-web-app-capable', 'yes')
            ->set('theme-color', '#ffffff')
            ->set('apple-mobile-web-app-capable', 'yes')
            ->set('apple-mobile-web-app-status-bar-style', 'black-translucent');

        // Prevent duplicate content for pagination
        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            Meta::set('canonical', $this->getCanonicalUrl());

            // Add pagination meta for search engines
            Meta::set('pagetype', 'page' . $currentPage);
        }

        // Add last modified time
        $latestArticle = BlogPost::published()->latest('updated_at')->first();
        if ($latestArticle) {
            Meta::set('last-modified', $latestArticle->updated_at->toRfc7231String());
        }
    }

    private function getKeywords()
    {
        $baseKeywords = [
            'blog articles',
            'news updates',
            'personality insights',
            'celebrity news',
            'entertainment blog',
            'political analysis',
            'sports highlights',
            'lifestyle articles',
            'famous personalities',
            'current affairs',
            'trending topics',
            'expert analysis',
            'in-depth articles',
            'latest news',
            'featured stories'
        ];

        // Add search-specific keywords
        if ($this->search) {
            $baseKeywords[] = $this->search;
            $baseKeywords[] = 'articles about ' . $this->search;
            $baseKeywords[] = $this->search . ' news';
            $baseKeywords[] = $this->search . ' blog';
            $baseKeywords[] = 'latest ' . $this->search . ' updates';
        }

        // Add category-specific keywords
        if ($this->category) {
            $category = BlogCategory::where('slug', $this->category)->first();
            if ($category) {
                $categoryName = $category->name;
                $baseKeywords[] = $categoryName;
                $baseKeywords[] = $categoryName . ' articles';
                $baseKeywords[] = $categoryName . ' news';
                $baseKeywords[] = $categoryName . ' blog';
                $baseKeywords[] = 'latest ' . $categoryName;
                $baseKeywords[] = $categoryName . ' updates';

                // Add category-specific industry keywords
                $industryKeywords = $this->getCategoryIndustryKeywords($categoryName);
                $baseKeywords = array_merge($baseKeywords, $industryKeywords);
            }
        }

        // Add sorting-specific keywords
        if ($this->sortBy === 'popular') {
            $baseKeywords[] = 'popular articles';
            $baseKeywords[] = 'trending news';
            $baseKeywords[] = 'most read';
            $baseKeywords[] = 'viral content';
            $baseKeywords[] = 'top stories';
        }

        // Add current year for freshness
        $baseKeywords[] = date('Y');
        $baseKeywords[] = date('Y') . ' articles';

        return implode(', ', array_unique(array_slice($baseKeywords, 0, 20)));
    }

    private function getNewsKeywords()
    {
        $newsKeywords = [
            'breaking news',
            'latest updates',
            'current events',
            'featured stories',
            'top articles'
        ];

        if ($this->search) {
            $newsKeywords[] = $this->search . ' news';
        }

        if ($this->category) {
            $category = BlogCategory::where('slug', $this->category)->first();
            if ($category) {
                $newsKeywords[] = $category->name . ' news';
            }
        }

        return implode(', ', array_unique($newsKeywords));
    }

    private function getCategoryIndustryKeywords($categoryName)
    {
        $industryMap = [
            'entertainment' => ['hollywood', 'bollywood', 'movies', 'celebrities', 'film industry', 'music', 'actors', 'directors'],
            'politics' => ['government', 'elections', 'political news', 'public policy', 'legislation', 'democracy', 'leadership'],
            'sports' => ['athletes', 'sports news', 'championships', 'teams', 'players', 'tournaments', 'competitions'],
            'lifestyle' => ['health', 'fitness', 'wellness', 'relationships', 'personal development', 'self-improvement'],
            'technology' => ['tech news', 'innovation', 'gadgets', 'software', 'digital trends', 'ai', 'machine learning'],
            'business' => ['economy', 'finance', 'entrepreneurship', 'startups', 'corporate news', 'market trends'],
        ];

        $categoryLower = strtolower($categoryName);
        foreach ($industryMap as $key => $keywords) {
            if (str_contains($categoryLower, $key)) {
                return $keywords;
            }
        }

        return [];
    }

    private function getRobotsMeta()
    {
        $currentPage = request()->get('page', 1);

        // Allow indexing of first few pages, discourage deep pagination
        if ($currentPage > 10) {
            return 'noindex, follow';
        }

        return 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
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

        // Remove empty search
        if (isset($queryParams['search']) && empty(trim($queryParams['search']))) {
            unset($queryParams['search']);
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
                        'url' => $article->author ? route('authors.show', $article->author->slug) : url('/')
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => config('app.name', 'WikiLife'),
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => site_logo(180, 60),
                            'width' => '180',
                            'height' => '60'
                        ]
                    ],
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => route('articles.show', $article->slug)
                    ],
                    'articleSection' => $article->blogCategory->name ?? 'General',
                    'keywords' => $article->meta_keywords ?? $this->extractKeywordsFromContent($article->content),
                    'inLanguage' => 'en',
                    'isAccessibleForFree' => true,
                    'isFamilyFriendly' => true
                ]
            ];
        }

        $currentPage = request()->get('page', 1);
        $totalArticles = BlogPost::published()->count();

        return [
            'itemList' => [
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                'name' => $this->getPageTitleForSchema(),
                'description' => $this->getPageDescriptionForSchema(),
                'url' => url()->current(),
                'numberOfItems' => $articles->count(),
                'itemListOrder' => 'http://schema.org/ItemListOrderDescending',
                'itemListElement' => $itemListElement
            ],
            'breadcrumb' => [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $this->getBreadcrumbItems()
            ],
            'website' => [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => config('app.name', 'WikiLife'),
                'url' => url('/'),
                'description' => 'Comprehensive blog articles and insights about famous personalities and current affairs',
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => route('articles.index') . '?search={search_term_string}',
                    'query-input' => 'required name=search_term_string'
                ]
            ],
            'blog' => [
                '@context' => 'https://schema.org',
                '@type' => 'Blog',
                'name' => config('app.name', 'WikiLife') . ' Blog',
                'description' => 'Latest blog articles, news, and insights about personalities and current affairs',
                'url' => route('articles.index'),
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => config('app.name', 'WikiLife')
                ],
                'inLanguage' => 'en',
                'isFamilyFriendly' => true
            ]
        ];
    }

    private function getPageTitleForSchema()
    {
        if ($this->search) {
            return "Search Results for \"{$this->search}\" - Blog Articles";
        }

        if ($this->category) {
            $category = BlogCategory::where('slug', $this->category)->first();
            if ($category) {
                return "{$category->name} - Blog Articles";
            }
        }

        if ($this->sortBy === 'popular') {
            return "Most Popular Blog Articles";
        }

        return "Latest Blog Articles & Insights";
    }

    private function getPageDescriptionForSchema()
    {
        if ($this->search) {
            return "Browse articles matching \"{$this->search}\" in our comprehensive blog collection";
        }

        if ($this->category) {
            $category = BlogCategory::where('slug', $this->category)->first();
            if ($category) {
                return "Explore {$category->name} articles and insights in our blog collection";
            }
        }

        return "Discover comprehensive blog articles, news, and insights about various topics";
    }

    private function getBreadcrumbItems()
    {
        $items = [
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
                'item' => route('articles.index')
            ]
        ];

        $position = 3;

        if ($this->category) {
            $category = BlogCategory::where('slug', $this->category)->first();
            if ($category) {
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => $category->name,
                    'item' => route('articles.index', ['category' => $this->category])
                ];
            }
        }

        if ($this->search) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => "Search: {$this->search}",
                'item' => url()->current()
            ];
        }

        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => "Page {$currentPage}",
                'item' => url()->current()
            ];
        }

        return $items;
    }

    private function extractKeywordsFromContent($content)
    {
        $content = strip_tags($content);
        $words = str_word_count($content, 1);
        $wordFreq = array_count_values($words);
        arsort($wordFreq);

        $keywords = array_slice(array_keys($wordFreq), 0, 10);
        return implode(', ', $keywords);
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->sortBy = 'latest';
        $this->resetPage();
    }
}
