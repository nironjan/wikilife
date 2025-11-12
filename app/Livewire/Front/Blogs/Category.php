<?php

namespace App\Livewire\Front\Blogs;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use F9Web\Meta\Meta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\URL as LaravelURL;
use Illuminate\Support\Str;

#[Layout('components.layouts.front')]
class Category extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'latest';

    public $category;

    public function mount($slug)
    {
        $this->category = BlogCategory::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $this->setMetaTags();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'sortBy'])) {
            $this->resetPage();
            $this->setMetaTags();
        }
    }

    public function updatingPage($page)
    {
        $this->setMetaTags();
    }

    #[Title('Blog Category')]
    protected function setMetaTags()
    {
        $categoryName = $this->category->name;
        $categoryDescription = $this->category->description ?: "Explore comprehensive {$categoryName} articles, news, and expert insights. Stay updated with the latest trends and analysis in {$categoryName}.";

        $currentTitle = "{$categoryName} - Blog Articles & News";
        $currentDescription = $categoryDescription;

        // Handle search within category
        if ($this->search) {
            $currentTitle = "Search in {$categoryName} for \"{$this->search}\" - Blog Articles";
            $currentDescription = "Find {$categoryName} articles matching \"{$this->search}\". " . $categoryDescription;
        }

        // Handle sorting
        if ($this->sortBy === 'popular') {
            $currentTitle = "Most Popular {$categoryName} Articles - Trending News";
            $currentDescription = "Discover the most popular and trending {$categoryName} articles. Read the most engaging content about {$categoryName} topics.";
        }

        // Add pagination info
        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            $currentTitle .= " - Page {$currentPage}";
            $currentDescription .= " Browse page {$currentPage} of our {$categoryName} article collection.";
        }

        // Set basic meta tags
        Meta::set('title', $currentTitle)
            ->set('description', $currentDescription)
            ->set('keywords', $this->getKeywords())
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
            ->set('twitter:label1', 'Category')
            ->set('twitter:data1', $this->category->name)
            ->set('twitter:label2', 'Total Articles')
            ->set('twitter:data2', $this->category->blog_posts_count ?? '50+');

        // Additional meta tags
        Meta::set('article:content_tier', 'free')
            ->set('article:opinion', 'false')
            ->set('article:family_friendly', 'yes')
            ->set('content-type', 'blog')
            ->set('content-language', 'en')
            ->set('audience', 'all')
            ->set('content_category', $this->category->name);

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
            ->set('og:image:alt', "{$this->category->name} Articles - " . config('app.name', 'WikiLife'))
            ->set('og:image:type', 'image/jpeg')
            ->set('twitter:image', $defaultImage)
            ->set('twitter:image:alt', "{$this->category->name} Articles - " . config('app.name', 'WikiLife'))
            ->set('image', $defaultImage)
            ->set('thumbnail', $defaultImage);

        // Try to get a featured image from category articles
        $featuredArticle = BlogPost::published()
            ->where('blog_category_id', $this->category->id)
            ->whereNotNull('featured_image')
            ->latest()
            ->first();

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
            Meta::set('pagetype', 'page' . $currentPage);
        }

        // Add last modified time
        $latestArticle = BlogPost::published()
            ->where('blog_category_id', $this->category->id)
            ->latest('updated_at')
            ->first();

        if ($latestArticle) {
            Meta::set('last-modified', $latestArticle->updated_at->toRfc7231String());
        }
    }

    private function getKeywords()
    {
        $categoryName = $this->category->name;

        $baseKeywords = [
            $categoryName,
            $categoryName . ' articles',
            $categoryName . ' blog',
            $categoryName . ' news',
            $categoryName . ' updates',
            'blog articles',
            'news updates',
            'expert analysis',
            'latest news',
            'featured stories'
        ];

        // Add category-specific industry keywords
        $industryKeywords = $this->getCategoryIndustryKeywords($categoryName);
        $baseKeywords = array_merge($baseKeywords, $industryKeywords);

        // Add search-specific keywords
        if ($this->search) {
            $baseKeywords[] = $this->search;
            $baseKeywords[] = 'articles about ' . $this->search;
            $baseKeywords[] = $this->search . ' news';
            $baseKeywords[] = $this->search . ' blog';
            $baseKeywords[] = 'latest ' . $this->search . ' updates';
        }

        // Add sorting-specific keywords
        if ($this->sortBy === 'popular') {
            $baseKeywords[] = 'popular ' . $categoryName . ' articles';
            $baseKeywords[] = 'trending ' . $categoryName . ' news';
            $baseKeywords[] = 'most read ' . $categoryName;
            $baseKeywords[] = 'viral ' . $categoryName . ' content';
            $baseKeywords[] = 'top ' . $categoryName . ' stories';
        }

        // Add current year for freshness
        $baseKeywords[] = date('Y');
        $baseKeywords[] = date('Y') . ' ' . $categoryName . ' articles';

        return implode(', ', array_unique(array_slice($baseKeywords, 0, 20)));
    }

    private function getNewsKeywords()
    {
        $categoryName = $this->category->name;

        $newsKeywords = [
            'breaking news',
            'latest updates',
            'current events',
            'featured stories',
            'top articles',
            $categoryName . ' news',
            $categoryName . ' updates'
        ];

        if ($this->search) {
            $newsKeywords[] = $this->search . ' news';
        }

        return implode(', ', array_unique($newsKeywords));
    }

    private function getCategoryIndustryKeywords($categoryName)
    {
        $industryMap = [
            'entertainment' => ['hollywood', 'bollywood', 'movies', 'celebrities', 'film industry', 'music', 'actors', 'directors', 'entertainment news'],
            'politics' => ['government', 'elections', 'political news', 'public policy', 'legislation', 'democracy', 'leadership', 'political analysis'],
            'sports' => ['athletes', 'sports news', 'championships', 'teams', 'players', 'tournaments', 'competitions', 'sports highlights'],
            'lifestyle' => ['health', 'fitness', 'wellness', 'relationships', 'personal development', 'self-improvement', 'lifestyle tips'],
            'technology' => ['tech news', 'innovation', 'gadgets', 'software', 'digital trends', 'ai', 'machine learning', 'technology updates'],
            'business' => ['economy', 'finance', 'entrepreneurship', 'startups', 'corporate news', 'market trends', 'business insights'],
            'health' => ['medical news', 'healthcare', 'wellness tips', 'fitness advice', 'nutrition', 'health updates'],
            'travel' => ['travel guides', 'destination reviews', 'travel tips', 'adventure', 'tourism', 'travel news'],
        ];

        $categoryLower = strtolower($categoryName);
        foreach ($industryMap as $key => $keywords) {
            if (str_contains($categoryLower, $key)) {
                return $keywords;
            }
        }

        // Default keywords for general categories
        return ['insights', 'analysis', 'trends', 'updates', 'stories', 'features'];
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
        $baseUrl = LaravelURL::route('articles.category', $this->category->slug);
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

    public function clearFilters()
    {
        $this->search = '';
        $this->sortBy = 'latest';
        $this->resetPage();
        $this->setMetaTags();
    }

    public function render()
    {
        $query = BlogPost::query()
            ->published()
            ->where('blog_category_id', $this->category->id)
            ->with(['author']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'LIKE', "%{$this->search}%")
                  ->orWhere('excerpt', 'LIKE', "%{$this->search}%")
                  ->orWhere('content', 'LIKE', "%{$this->search}%");
            });
        }

        $query->when($this->sortBy === 'latest', function ($q) {
            $q->latest('published_at');
        })->when($this->sortBy === 'popular', function ($q) {
            $q->orderBy('views', 'desc');
        });

        $articles = $query->paginate(10);

        $categories = BlogCategory::where('is_active', true)
            ->withCount(['blogPosts' => function ($q) {
                $q->published();
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $popularPosts = BlogPost::published()
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $structuredData = $this->getStructuredData($articles);

        return view('livewire.front.blogs.category', [
            'articles' => $articles,
            'categories' => $categories,
            'popularPosts' => $popularPosts,
            'structuredData' => $structuredData,
        ]);
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
                    'articleSection' => $this->category->name,
                    'keywords' => $article->meta_keywords ?? $this->extractKeywordsFromContent($article->content),
                    'inLanguage' => 'en',
                    'isAccessibleForFree' => true,
                    'isFamilyFriendly' => true
                ]
            ];
        }

        $currentPage = request()->get('page', 1);

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
            'collection' => [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => $this->category->name . ' Articles',
                'description' => $this->category->description ?: "Collection of {$this->category->name} articles and news",
                'url' => url()->current(),
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'name' => $this->category->name . ' Article Collection',
                    'numberOfItems' => $this->category->blog_posts_count ?? 0,
                    'itemListElement' => $itemListElement
                ]
            ],
            'category' => [
                '@context' => 'https://schema.org',
                '@type' => 'CategoryCode',
                'name' => $this->category->name,
                'description' => $this->category->description,
                'url' => url()->current(),
                'inCodeSet' => config('app.name', 'WikiLife') . ' Blog Categories'
            ]
        ];
    }

    private function getPageTitleForSchema()
    {
        if ($this->search) {
            return "Search Results for \"{$this->search}\" in {$this->category->name}";
        }

        if ($this->sortBy === 'popular') {
            return "Most Popular {$this->category->name} Articles";
        }

        return "Latest {$this->category->name} Articles";
    }

    private function getPageDescriptionForSchema()
    {
        if ($this->search) {
            return "Browse {$this->category->name} articles matching \"{$this->search}\"";
        }

        if ($this->sortBy === 'popular') {
            return "Discover the most popular {$this->category->name} articles and trending news";
        }

        return "Explore latest {$this->category->name} articles, news, and insights";
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
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $this->category->name,
                'item' => url()->current()
            ]
        ];

        $position = 4;

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
}
