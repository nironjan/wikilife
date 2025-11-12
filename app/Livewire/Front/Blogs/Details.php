<?php

namespace App\Livewire\Front\Blogs;

use App\Models\BlogPost;
use F9Web\Meta\Meta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\URL as LaravelURL;
use Illuminate\Support\Str;

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

        // Enhanced title with category context
        if ($this->article->blogCategory && !$this->article->meta_title) {
            $currentTitle = "{$this->article->title} - {$this->article->blogCategory->name} Article";
        }

        // Enhanced description
        if (!$this->article->meta_description) {
            $currentDescription = $this->generateEnhancedDescription();
        }

        // Set basic meta tags
        Meta::set('title', $currentTitle)
            ->set('description', $currentDescription)
            ->set('keywords', $this->getKeywords())
            ->set('canonical', $this->getCanonicalUrl())
            ->set('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')
            ->set('author', $this->article->author->name ?? config('app.name', 'WikiLife'))
            ->set('publisher', config('app.name', 'WikiLife'))
            ->set('language', 'en')
            ->set('revisit-after', '7 days')
            ->set('rating', 'general')
            ->set('distribution', 'global')
            ->set('googlebot', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')
            ->set('bingbot', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1');

        // Open Graph Tags
        Meta::set('og:title', $currentTitle)
            ->set('og:description', $currentDescription)
            ->set('og:type', 'article')
            ->set('og:url', $this->getCanonicalUrl())
            ->set('og:site_name', config('app.name', 'WikiLife'))
            ->set('og:locale', 'en_US')
            ->set('og:updated_time', $this->article->updated_at->toISOString());

        // Article-specific Open Graph tags
        Meta::set('article:published_time', $this->article->published_at->toISOString())
            ->set('article:modified_time', $this->article->updated_at->toISOString());

        if ($this->article->blogCategory) {
            Meta::set('article:section', $this->article->blogCategory->name)
                ->set('article:tag', $this->getArticleTags());
        }

        if ($this->article->author) {
            Meta::set('article:author', $this->article->author->name)
                ->set('profile:first_name', $this->getAuthorFirstName())
                ->set('profile:last_name', $this->getAuthorLastName())
                ->set('profile:username', $this->article->author->username ?? 'author');
        }

        // Twitter Card Tags
        $twitterHandle = $this->getTwitterHandle();
        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $currentTitle)
            ->set('twitter:description', $currentDescription)
            ->set('twitter:site', $twitterHandle)
            ->set('twitter:creator', $this->article->author->twitter_handle ?? $twitterHandle)
            ->set('twitter:label1', 'Written by')
            ->set('twitter:data1', $this->article->author->name ?? 'Admin')
            ->set('twitter:label2', 'Category')
            ->set('twitter:data2', $this->article->blogCategory->name ?? 'General');

        // Additional professional meta tags
        Meta::set('content-type', 'article')
            ->set('content-language', 'en')
            ->set('audience', 'all')
            ->set('content_category', $this->article->blogCategory->name ?? 'Blog');

        // News and article specific meta
        Meta::set('news_keywords', $this->getNewsKeywords())
            ->set('standout', $this->getCanonicalUrl())
            ->set('original-source', $this->getCanonicalUrl());

        // Article-specific meta
        Meta::set('article:content_tier', 'free')
            ->set('article:opinion', 'false')
            ->set('article:family_friendly', 'yes');

        // Set meta images
        $this->setMetaImages();

        // Set additional schema-specific meta
        $this->setAdditionalMetaTags();
    }

    protected function setMetaImages()
    {
        $image = $this->article->featured_image_url ?: default_image(1200, 630);

        Meta::set('og:image', $image)
            ->set('og:image:width', 1200)
            ->set('og:image:height', 630)
            ->set('og:image:alt', $this->article->title . ' - ' . config('app.name', 'WikiLife'))
            ->set('og:image:type', 'image/jpeg')
            ->set('twitter:image', $image)
            ->set('twitter:image:alt', $this->article->title . ' - ' . config('app.name', 'WikiLife'))
            ->set('image', $image)
            ->set('thumbnail', $image);

        // Additional images if available
        if ($this->article->gallery_images && count($this->article->gallery_images) > 0) {
            $additionalImages = array_slice($this->article->gallery_images, 0, 3);
            foreach ($additionalImages as $index => $additionalImage) {
                Meta::set('og:image:' . ($index + 2), $additionalImage);
            }
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
        Meta::set('last-modified', $this->article->updated_at->toRfc7231String());

        // Content length and reading time
        $contentLength = strlen(strip_tags($this->article->content));
        Meta::set('content-length', $contentLength)
            ->set('reading-time', $this->article->read_time);
    }

    private function generateEnhancedDescription()
    {
        $baseDescription = $this->article->excerpt;

        // Add category context
        if ($this->article->blogCategory) {
            $baseDescription .= " Explore this {$this->article->blogCategory->name} article with in-depth analysis and insights.";
        }

        // Add author context
        if ($this->article->author) {
            $baseDescription .= " Written by {$this->article->author->name}.";
        }

        // Add reading time context
        if ($this->article->read_time) {
            $baseDescription .= " {$this->article->read_time} min read.";
        }

        return Str::limit($baseDescription, 160);
    }

    private function getKeywords()
    {
        $keywords = [];

        // Add article tags
        if ($this->article->tags && is_array($this->article->tags)) {
            $keywords = array_merge($keywords, $this->article->tags);
        }

        // Add category
        if ($this->article->blogCategory) {
            $keywords[] = $this->article->blogCategory->name;
            $keywords[] = $this->article->blogCategory->name . ' articles';
            $keywords[] = $this->article->blogCategory->name . ' blog';
        }

        // Add author
        if ($this->article->author) {
            $keywords[] = $this->article->author->name;
            $keywords[] = 'articles by ' . $this->article->author->name;
        }

        // Add title words
        $titleWords = explode(' ', $this->article->title);
        $keywords = array_merge($keywords, array_slice($titleWords, 0, 5));

        // Add content-based keywords
        $contentKeywords = $this->extractKeywordsFromContent($this->article->content);
        $keywords = array_merge($keywords, $contentKeywords);

        // Add current year for freshness
        $keywords[] = date('Y');
        $keywords[] = date('Y') . ' article';

        return implode(', ', array_unique(array_slice($keywords, 0, 20)));
    }

    private function getNewsKeywords()
    {
        $newsKeywords = [
            'breaking news',
            'latest article',
            'featured story',
            'exclusive content'
        ];

        // Add category-specific news keywords
        if ($this->article->blogCategory) {
            $newsKeywords[] = $this->article->blogCategory->name . ' news';
            $newsKeywords[] = 'latest ' . $this->article->blogCategory->name;
        }

        return implode(', ', array_unique($newsKeywords));
    }

    private function getArticleTags()
    {
        $tags = [];

        if ($this->article->tags && is_array($this->article->tags)) {
            $tags = array_merge($tags, $this->article->tags);
        }

        if ($this->article->blogCategory) {
            $tags[] = $this->article->blogCategory->name;
        }

        return implode(', ', array_unique($tags));
    }

    private function getAuthorFirstName()
    {
        if (!$this->article->author) return '';

        $nameParts = explode(' ', $this->article->author->name);
        return $nameParts[0] ?? '';
    }

    private function getAuthorLastName()
    {
        if (!$this->article->author) return '';

        $nameParts = explode(' ', $this->article->author->name);
        return $nameParts[1] ?? $nameParts[0] ?? '';
    }

    private function getTwitterHandle()
    {
        $socialLinks = social_links();
        $twitterUrl = $socialLinks['twitter'] ?? null;

        if ($twitterUrl) {
            // Extract handle from URL (handles various formats)
            if (preg_match('/twitter\.com\/([a-zA-Z0-9_]+)/', $twitterUrl, $matches)) {
                return '@' . $matches[1];
            }
        }

        return '@' . str_replace(' ', '', config('app.name', 'WikiLife'));
    }

    private function extractKeywordsFromContent($content)
    {
        $content = strip_tags($content);
        $words = str_word_count($content, 1);
        $wordFreq = array_count_values($words);
        arsort($wordFreq);

        // Remove common words
        $commonWords = ['the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might', 'must', 'can'];
        $filteredWords = array_diff_key($wordFreq, array_flip($commonWords));

        $keywords = array_slice(array_keys($filteredWords), 0, 10);
        return $keywords;
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
        $articleSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $this->article->title,
            'description' => $this->article->excerpt,
            'image' => [
                '@type' => 'ImageObject',
                'url' => $this->article->featured_image_url ?: default_image(1200, 630),
                'width' => '1200',
                'height' => '630',
                'caption' => $this->article->title
            ],
            'datePublished' => $this->article->published_at->toISOString(),
            'dateModified' => $this->article->updated_at->toISOString(),
            'author' => [
                '@type' => 'Person',
                '@id' => $this->article->author ? route('authors.show', $this->article->author->slug) . '#person' : url('/') . '#author',
                'name' => $this->article->author->name ?? 'Admin',
                'url' => $this->article->author ? route('authors.show', $this->article->author->slug) : url('/')
            ],
            'publisher' => [
                '@type' => 'Organization',
                '@id' => url('/') . '#organization',
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
            'articleSection' => $this->article->blogCategory->name ?? 'General',
            'keywords' => $this->getKeywords(),
            'inLanguage' => 'en',
            'copyrightYear' => $this->article->published_at->year,
            'wordCount' => str_word_count(strip_tags($this->article->content)),
            'timeRequired' => 'PT' . $this->article->read_time . 'M',
            'url' => $this->getCanonicalUrl(),
            'isAccessibleForFree' => true,
            'isFamilyFriendly' => true,
            'discussionUrl' => $this->getCanonicalUrl() . '#comments'
        ];

        // Add article body if content is available
        if ($this->article->content) {
            $articleSchema['articleBody'] = Str::limit(strip_tags($this->article->content), 5000);
        }

        // Add category information
        if ($this->article->blogCategory) {
            $articleSchema['about'] = [
                '@type' => 'Thing',
                'name' => $this->article->blogCategory->name,
                'url' => route('articles.category', $this->article->blogCategory->slug)
            ];
        }

        // Add comment count if available
        if ($this->article->comments_count > 0) {
            $articleSchema['commentCount'] = $this->article->comments_count;
        }

        // Add interaction statistics
        $articleSchema['interactionStatistic'] = [
            '@type' => 'InteractionCounter',
            'interactionType' => 'https://schema.org/LikeAction',
            'userInteractionCount' => $this->article->likes_count ?? 0
        ];

        return [
            'article' => $articleSchema,
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
            'organization' => [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                '@id' => url('/') . '#organization',
                'name' => config('app.name', 'WikiLife'),
                'url' => url('/'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => site_logo(180, 60),
                    'width' => '180',
                    'height' => '60'
                ],
                'sameAs' => $this->getOrganizationSocialLinks()
            ]
        ];
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

        if ($this->article->blogCategory) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $this->article->blogCategory->name,
                'item' => route('articles.category', $this->article->blogCategory->slug)
            ];
        }

        $items[] = [
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $this->article->title,
            'item' => $this->getCanonicalUrl()
        ];

        return $items;
    }

    private function getOrganizationSocialLinks()
    {
        $socialLinks = social_links();
        $sameAs = [];

        // Map platform names to schema.org compatible URLs
        $platformMap = [
            'facebook' => 'https://www.facebook.com/',
            'twitter' => 'https://twitter.com/',
            'instagram' => 'https://www.instagram.com/',
            'linkedin' => 'https://www.linkedin.com/company/',
            'youtube' => 'https://www.youtube.com/',
            'pinterest' => 'https://www.pinterest.com/',
            'tiktok' => 'https://www.tiktok.com/@'
        ];

        foreach ($platformMap as $platform => $baseUrl) {
            if (isset($socialLinks[$platform]) && !empty($socialLinks[$platform])) {
                $sameAs[] = $socialLinks[$platform];
            }
        }

        // Add additional social platforms if available
        $additionalPlatforms = ['github', 'reddit', 'telegram', 'whatsapp', 'snapchat'];
        foreach ($additionalPlatforms as $platform) {
            if (isset($socialLinks[$platform]) && !empty($socialLinks[$platform])) {
                $sameAs[] = $socialLinks[$platform];
            }
        }

        return $sameAs;
    }
}
