<?php

namespace App\Livewire\Front\Pages;

use App\Models\Page;
use F9Web\Meta\Meta;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\URL as LaravelURL;
use Illuminate\Support\Str;

#[Layout('components.layouts.front')]
class Details extends Component
{
    public $slug;
    public $page;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->loadPage();
        $this->setMetaTags();
    }

    public function loadPage()
    {
        $this->page = Page::with(['user'])
            ->where('slug', $this->slug)
            ->where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->firstOrFail();
    }

    protected function setMetaTags()
    {
        $currentTitle = $this->page->meta_title ?: $this->page->title;
        $currentDescription = $this->page->meta_description ?: $this->generateDescription();

        // Set basic meta tags
        Meta::set('title', $currentTitle)
            ->set('description', $currentDescription)
            ->set('keywords', $this->getKeywords())
            ->set('canonical', $this->getCanonicalUrl())
            ->set('robots', $this->getRobotsMeta())
            ->set('author', $this->page->user->name ?? config('app.name', 'WikiLife'))
            ->set('publisher', config('app.name', 'WikiLife'))
            ->set('language', 'en')
            ->set('revisit-after', '30 days')
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
            ->set('og:updated_time', $this->page->updated_at->toISOString());

        // Twitter Card Tags
        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $currentTitle)
            ->set('twitter:description', $currentDescription)
            ->set('twitter:site', '@' . str_replace(' ', '', config('app.name', 'WikiLife')))
            ->set('twitter:creator', '@' . str_replace(' ', '', config('app.name', 'WikiLife')))
            ->set('twitter:label1', 'Page Type')
            ->set('twitter:data1', 'Information Page')
            ->set('twitter:label2', 'Last Updated')
            ->set('twitter:data2', $this->page->updated_at->diffForHumans());

        // Additional meta tags
        Meta::set('content-type', 'article')
            ->set('content-language', 'en')
            ->set('audience', 'all')
            ->set('content_category', 'information');

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
            ->set('og:image:alt', $this->page->title . ' - ' . config('app.name', 'WikiLife'))
            ->set('og:image:type', 'image/jpeg')
            ->set('twitter:image', $defaultImage)
            ->set('twitter:image:alt', $this->page->title . ' - ' . config('app.name', 'WikiLife'))
            ->set('image', $defaultImage)
            ->set('thumbnail', $defaultImage);

        // Use featured image if available
        if ($this->page->featured_image) {
            Meta::set('og:image', $this->page->featured_image)
                ->set('twitter:image', $this->page->featured_image)
                ->set('image', $this->page->featured_image);
        }
    }

    protected function setAdditionalMetaTags()
    {
        // Mobile and app specific meta
        Meta::set('mobile-web-app-capable', 'yes')
            ->set('theme-color', '#ffffff')
            ->set('apple-mobile-web-app-capable', 'yes')
            ->set('apple-mobile-web-app-status-bar-style', 'black-translucent');

        // Last modified time
        Meta::set('last-modified', $this->page->updated_at->toRfc7231String());

        // Content length
        if ($this->page->content) {
            $contentLength = strlen(strip_tags($this->page->content));
            Meta::set('content-length', $contentLength);
        }
    }

    private function generateDescription()
    {
        if ($this->page->meta_description) {
            return $this->page->meta_description;
        }

        if ($this->page->excerpt) {
            return $this->page->excerpt;
        }

        if ($this->page->content) {
            return Str::limit(strip_tags($this->page->content), 160);
        }

        return "Learn more about {$this->page->title} on " . config('app.name', 'WikiLife') . ". Comprehensive information and details.";
    }

    private function getKeywords()
    {
        $keywords = [];

        // Add page meta keywords if available
        if ($this->page->meta_keywords) {
            $keywords = array_merge($keywords, explode(',', $this->page->meta_keywords));
        }

        // Add title words
        $titleWords = explode(' ', $this->page->title);
        $keywords = array_merge($keywords, array_slice($titleWords, 0, 5));

        // Add category/tags if available
        if ($this->page->tags && is_array($this->page->tags)) {
            $keywords = array_merge($keywords, $this->page->tags);
        }

        // Add general page keywords
        $baseKeywords = [
            'information',
            'details',
            'page',
            config('app.name', 'WikiLife'),
            strtolower($this->page->title) . ' information',
            strtolower($this->page->title) . ' details'
        ];

        $keywords = array_merge($keywords, $baseKeywords);

        // Add current year for freshness
        $keywords[] = date('Y');

        return implode(', ', array_unique(array_slice($keywords, 0, 15)));
    }

    private function getRobotsMeta()
    {
        // Allow indexing of published pages, noindex for unpublished
        if (!$this->page->is_published) {
            return 'noindex, nofollow';
        }

        return 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
    }

    private function getCanonicalUrl()
    {
        return LaravelURL::route('page.details', $this->page->slug);
    }

    public function getStructuredData()
    {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => $this->getCanonicalUrl() . '#webpage',
            'name' => $this->page->title,
            'description' => $this->generateDescription(),
            'url' => $this->getCanonicalUrl(),
            'datePublished' => $this->page->created_at->toISOString(),
            'dateModified' => $this->page->updated_at->toISOString(),
            'lastReviewed' => $this->page->updated_at->toISOString(),
            'author' => [
                '@type' => 'Person',
                'name' => $this->page->user->name ?? config('app.name', 'WikiLife')
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
                '@id' => $this->getCanonicalUrl()
            ],
            'inLanguage' => 'en',
            'isFamilyFriendly' => true,
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
                'itemListElement' => $this->getBreadcrumbItems()
            ]
        ];

        // Add primary image if available
        if ($this->page->featured_image) {
            $structuredData['primaryImageOfPage'] = [
                '@type' => 'ImageObject',
                'url' => $this->page->featured_image,
                'width' => '1200',
                'height' => '630'
            ];
        }

        // Add potential action for search
        $structuredData['potentialAction'] = [
            '@type' => 'SearchAction',
            'target' => route('articles.index') . '?search={search_term_string}',
            'query-input' => 'required name=search_term_string'
        ];

        return $structuredData;
    }

    private function getBreadcrumbItems()
    {
        return [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => url('/')
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $this->page->title,
                'item' => $this->getCanonicalUrl()
            ]
        ];
    }

    #[Title('page.title')]
    public function render()
    {
        // Determine which template to use
        $template = $this->page->template ?: 'custom';

        // Check if template view exists, fallback to custom
        $view = "livewire.front.pages.templates.{$template}";
        if (!view()->exists($view)) {
            $view = 'livewire.front.pages.templates.custom';
        }

        $structuredData = $this->getStructuredData();

        return view($view, [
            'page' => $this->page,
            'structuredData' => $structuredData
        ]);
    }
}
