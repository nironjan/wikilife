<?php

namespace App\Livewire\Front;

use App\Helpers\SiteSettingsHelper;
use F9Web\Meta\Meta;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.front')]
class Homepage extends Component
{
    public function mount()
    {
        $this->setProfessionalMetaTags();
    }

    protected function setProfessionalMetaTags()
    {
        // Basic Meta Tags
        $this->setBasicMetaTags();

        // Open Graph Tags
        $this->setOpenGraphTags();

        // Twitter Card Tags
        $this->setTwitterTags();

        // Structured Data
        $this->setStructuredData();

        // Additional SEO Tags
        $this->setAdditionalMetaTags();
    }

    protected function setBasicMetaTags()
    {
        $siteName = site_name();
        $tagline = SiteSettingsHelper::tagline() ?? 'The Biography Encyclopedia';
        $description = meta_description() ?? 'Explore comprehensive biographies of famous personalities, historical figures, and influential people from around the world. Discover detailed life stories, career achievements, personal lives, and legacy.';

        Meta::set('title', meta_title() ?? "{$siteName} - {$tagline}")
            ->set('description', $description)
            ->set('keywords', SiteSettingsHelper::metaKeywords() ?? 'biographies, famous people, history, encyclopedia, wiki, life stories, achievements, personal life, career, legacy')
            ->set('canonical', url('/'))
            ->set('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')
            ->set('author', $siteName)
            ->set('language', SiteSettingsHelper::language())
            ->set('revisit-after', '7 days')
            ->set('rating', 'general')
            ->set('distribution', 'global')
            ->set('googlebot', 'index, follow')
            ->set('bingbot', 'index, follow');
    }

    protected function setOpenGraphTags()
    {
        $siteName = site_name();
        $tagline = SiteSettingsHelper::tagline() ?? 'The Biography Encyclopedia';
        $description = SiteSettingsHelper::ogDescription() ?? meta_description() ?? 'Explore comprehensive biographies of famous personalities from around the world.';

        Meta::set('og:title', SiteSettingsHelper::ogTitle() ?? meta_title() ?? "{$siteName} - {$tagline}")
            ->set('og:description', $description)
            ->set('og:type', 'website')
            ->set('og:url', url('/'))
            ->set('og:site_name', $siteName)
            ->set('og:locale', SiteSettingsHelper::language())
            ->set('og:updated_time', now()->toIso8601String());

        // Set OG Image with proper dimensions
        $ogImage = og_image(1200, 630, 90);
        if ($ogImage) {
            Meta::set('og:image', $ogImage)
                ->set('og:image:width', 1200)
                ->set('og:image:height', 630)
                ->set('og:image:alt', "{$siteName} - {$tagline}")
                ->set('og:image:type', 'image/jpeg');
        }

        // Additional OG tags for better engagement
        Meta::set('og:see_also', url('/about'))
            ->set('og:see_also', url('/contact'))
            ->set('og:see_also', url('/people'));
    }

    protected function setTwitterTags()
    {
        $siteName = site_name();
        $tagline = SiteSettingsHelper::tagline() ?? 'The Biography Encyclopedia';
        $description = SiteSettingsHelper::twitterDescription() ?? meta_description() ?? 'Discover detailed biographies of famous personalities.';

        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', SiteSettingsHelper::twitterTitle() ?? meta_title() ?? "{$siteName} - {$tagline}")
            ->set('twitter:description', $description)
            ->set('twitter:site', '@' . str_replace(' ', '', $siteName))
            ->set('twitter:creator', '@' . str_replace(' ', '', $siteName))
            ->set('twitter:label1', 'Category')
            ->set('twitter:data1', 'Biography Encyclopedia')
            ->set('twitter:label2', 'Content Type')
            ->set('twitter:data2', 'Educational');

        // Set Twitter Image
        $twitterImage = SiteSettingsHelper::twitterImage(1200, 600, 90) ?? og_image(1200, 600, 90);
        if ($twitterImage) {
            Meta::set('twitter:image', $twitterImage)
                ->set('twitter:image:alt', "{$siteName} - {$tagline}");
        }
    }

    protected function setStructuredData()
    {
        $siteName = site_name();
        $description = meta_description() ?? 'Comprehensive biography encyclopedia featuring detailed life stories of famous personalities.';
        $siteUrl = url('/');

        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $siteName,
            'description' => $description,
            'url' => $siteUrl,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => "{$siteUrl}/search?q={search_term_string}",
                'query-input' => 'required name=search_term_string'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $siteName,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => site_logo(400, 400, 90)
                ]
            ],
            'inLanguage' => SiteSettingsHelper::language()
        ];

        // Add additional Organization schema
        $organizationData = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $siteName,
            'url' => $siteUrl,
            'logo' => site_logo(400, 400, 90),
            'description' => $description,
            'sameAs' => $this->getSocialLinksArray(),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => SiteSettingsHelper::sitePhone(),
                'email' => SiteSettingsHelper::siteEmail(),
                'contactType' => 'customer service'
            ]
        ];

        // Set structured data
        Meta::set('script:ld+json-website', json_encode($structuredData));
        Meta::set('script:ld+json-organization', json_encode($organizationData));
    }

    protected function setAdditionalMetaTags()
    {
        $siteName = site_name();

        // Mobile and App Meta Tags
        Meta::set('viewport', 'width=device-width, initial-scale=1.0')
            ->set('mobile-web-app-capable', 'yes')
            ->set('apple-mobile-web-app-capable', 'yes')
            ->set('apple-mobile-web-app-status-bar-style', 'black-translucent')
            ->set('apple-mobile-web-app-title', $siteName)
            ->set('theme-color', '#ffffff')
            ->set('msapplication-TileColor', '#ffffff')
            ->set('application-name', $siteName);

        // Content Security and Verification
        Meta::set('content-language', SiteSettingsHelper::language())
            ->set('audience', 'all')
            ->set('coverage', 'Worldwide')
            ->set('target', 'all');

        // Prevent duplicate content
        Meta::set('canonical', url('/'));

        // Article meta for homepage (treated as hub page) - FIXED: Individual tags
        Meta::set('article:section', 'Biography')
            ->set('article:tag', 'biographies')
            ->set('article:tag', 'famous people')
            ->set('article:tag', 'history')
            ->set('article:tag', 'encyclopedia')
            ->set('article:published_time', now()->toIso8601String())
            ->set('article:modified_time', now()->toIso8601String());

        // Geo tags if you have location-based content
        if (SiteSettingsHelper::siteAddress()) {
            Meta::set('geo.region', 'Global')
                ->set('geo.placename', SiteSettingsHelper::siteAddress());
        }

        // Additional security headers
        Meta::set('referrer', 'origin-when-cross-origin')
            ->set('format-detection', 'telephone=no');
    }

    protected function getSocialLinksArray()
    {
        $socialLinks = social_links();
        $sameAs = [];

        foreach ($socialLinks as $platform => $url) {
            if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
                $sameAs[] = $url;
            }
        }

        return $sameAs;
    }

    protected function setDefaultMetaTags()
    {
        // Fallback if no site settings exist
        $defaultTitle = "WikiLife - The Biography Encyclopedia";
        $defaultDescription = "Explore comprehensive biographies of famous personalities, historical figures, and influential people from around the world.";

        Meta::set('title', $defaultTitle)
            ->set('description', $defaultDescription)
            ->set('keywords', 'biographies, famous people, history, encyclopedia, wiki')
            ->set('canonical', url('/'))
            ->set('og:title', $defaultTitle)
            ->set('og:description', $defaultDescription)
            ->set('og:type', 'website')
            ->set('og:url', url('/'))
            ->set('og:site_name', 'WikiLife')
            ->set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $defaultTitle)
            ->set('twitter:description', $defaultDescription);

        // Set default image if available
        $defaultImage = default_image(1200, 630, 90);
        if ($defaultImage) {
            Meta::set('og:image', $defaultImage)
                ->set('twitter:image', $defaultImage);
        }
    }

    public function render()
    {
        return view('livewire.front.homepage');
    }
}
