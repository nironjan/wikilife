<?php

namespace App\Livewire\Front\Blogs;

use App\Models\BlogPost;
use F9Web\Meta\Meta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\URL as LaravelURL;

#[Layout('components.layouts.front')]
class Details extends Component
{
    public $article;
    public $relatedArticles;
    public $categories;
    public $popularArticles;

    public function mount($slug)
    {
        $this->article = BlogPost::published()
            ->with(['blogCategory', 'author'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views
        $this->article->increment('views');

        $this->relatedArticles = BlogPost::published()
            ->where('blog_category_id', $this->article->blog_category_id)
            ->where('id', '!=', $this->article->id)
            ->latest()
            ->take(3)
            ->get();

        $this->categories = \App\Models\BlogCategory::withCount(['blogPosts' => function ($q) {
            $q->published();
        }])->orderBy('name')->get();

        // Get popular articles with images
        $this->popularArticles = BlogPost::published()
            ->with(['blogCategory', 'author'])
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $this->setMetaTags();
    }

    #[Title('Blog Article')]
    protected function setMetaTags()
    {
        $currentTitle = $this->article->meta_title ?: $this->article->title;
        $currentDescription = $this->article->meta_description ?: $this->article->excerpt;

        Meta::set('title', $currentTitle)
            ->set('description', $currentDescription)
            ->set('keywords', $this->getKeywords())
            ->set('canonical', $this->getCanonicalUrl());

        // Open Graph Tags
        Meta::set('og:title', $currentTitle)
            ->set('og:description', $currentDescription)
            ->set('og:type', 'article')
            ->set('og:url', $this->getCanonicalUrl())
            ->set('og:site_name', config('app.name', 'WikiLife'))
            ->set('article:published_time', $this->article->published_at->toISOString())
            ->set('article:modified_time', $this->article->updated_at->toISOString());

        if ($this->article->blogCategory) {
            Meta::set('article:section', $this->article->blogCategory->name);
        }

        if ($this->article->author) {
            Meta::set('article:author', $this->article->author->name);
        }

        // Twitter Card Tags
        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $currentTitle)
            ->set('twitter:description', $currentDescription);

        $this->setMetaImages();
    }

    protected function setMetaImages()
    {
        $image = $this->article->featured_image_url ?: default_image(1200, 630);

        Meta::set('og:image', $image)
            ->set('twitter:image', $image)
            ->set('og:image:width', 1200)
            ->set('og:image:height', 630);
    }

    private function getKeywords()
    {
        $keywords = [];

        if ($this->article->tags && is_array($this->article->tags)) {
            $keywords = array_merge($keywords, $this->article->tags);
        }

        if ($this->article->blogCategory) {
            $keywords[] = $this->article->blogCategory->name;
        }

        return implode(', ', array_unique($keywords));
    }

    private function getCanonicalUrl()
    {
        return LaravelURL::route('articles.show', $this->article->slug);
    }

    public function render()
    {
        $structuredData = $this->getStructuredData();

        return view('livewire.front.blogs.details', [
            'relatedArticles' => $this->relatedArticles,
            'categories' => $this->categories,
            'popularArticles' => $this->popularArticles,
            'structuredData' => $structuredData,
        ]);
    }

    private function getStructuredData()
    {
        return [
            'article' => [
                '@context' => 'https://schema.org',
                '@type' => 'BlogPosting',
                'headline' => $this->article->title,
                'description' => $this->article->excerpt,
                'image' => $this->article->featured_image_url ?: default_image(1200, 630),
                'datePublished' => $this->article->published_at->toISOString(),
                'dateModified' => $this->article->updated_at->toISOString(),
                'author' => [
                    '@type' => 'Person',
                    'name' => $this->article->author->name ?? 'Admin',
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => config('app.name', 'WikiLife'),
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('images/logo.png')
                    ]
                ],
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $this->getCanonicalUrl()
                ]
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
                        'item' => route('articles.index')
                    ],
                    ...($this->article->blogCategory ? [
                        [
                            '@type' => 'ListItem',
                            'position' => 3,
                            'name' => $this->article->blogCategory->name,
                            'item' => route('articles.category', $this->article->blogCategory->slug)
                        ]
                    ] : []),
                    [
                        '@type' => 'ListItem',
                        'position' => $this->article->blogCategory ? 4 : 3,
                        'name' => $this->article->title,
                        'item' => $this->getCanonicalUrl()
                    ]
                ]
            ]
        ];
    }
}
