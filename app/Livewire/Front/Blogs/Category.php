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
        $currentTitle = "{$categoryName} - Blog Articles";
        $currentDescription = $this->category->description ?: "Browse our latest {$categoryName} blog articles, news, and insights.";

        if ($this->search) {
            $currentTitle = "Search in {$categoryName} for \"{$this->search}\"";
            $currentDescription = "Find {$categoryName} articles matching \"{$this->search}\". " . $currentDescription;
        }

        $currentPage = request()->get('page', 1);
        if ($currentPage > 1) {
            $currentTitle .= " - Page {$currentPage}";
        }

        Meta::set('title', $currentTitle)
            ->set('description', $currentDescription)
            ->set('keywords', $this->getKeywords())
            ->set('canonical', $this->getCanonicalUrl());

        Meta::set('og:title', $currentTitle)
            ->set('og:description', $currentDescription)
            ->set('og:type', 'website')
            ->set('og:url', $this->getCanonicalUrl())
            ->set('og:site_name', config('app.name', 'WikiLife'));

        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $currentTitle)
            ->set('twitter:description', $currentDescription);

        $this->setMetaImages();
    }

    protected function setMetaImages()
    {
        $defaultImage = asset('images/blog-category-og.jpg');
        Meta::set('og:image', $defaultImage)
            ->set('twitter:image', $defaultImage)
            ->set('og:image:width', 1200)
            ->set('og:image:height', 630);
    }

    private function getKeywords()
    {
        $baseKeywords = [
            $this->category->name,
            $this->category->name . ' articles',
            $this->category->name . ' blog',
            'blog',
            'articles',
            'news'
        ];

        if ($this->search) {
            $baseKeywords[] = $this->search;
        }

        return implode(', ', array_unique($baseKeywords));
    }

    private function getCanonicalUrl()
    {
        $baseUrl = LaravelURL::route('articles.category', $this->category->slug);
        $queryParams = request()->query();

        if (isset($queryParams['page']) && $queryParams['page'] == 1) {
            unset($queryParams['page']);
        }

        if (isset($queryParams['sortBy']) && $queryParams['sortBy'] === 'latest') {
            unset($queryParams['sortBy']);
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
                        'name' => 'Blog Articles',
                        'item' => route('articles.index')
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $this->category->name,
                        'item' => url()->current()
                    ]
                ]
            ]
        ];
    }
}
