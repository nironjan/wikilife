<?php

namespace App\Livewire\Front\Person;

use App\Models\People;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.front')]
class Details extends Component
{
    public People $person;
    public $slug;

    #[Url]
    public $tab = 'overview';

    public function mount($slug, $tab = 'overview')
    {
        $this->slug = $slug;
        $this->tab = $this->getTabFromSlug($tab);
        $this->loadPerson();

        $this->dispatch('page-loaded', slug: $slug);
    }

    public function loadPerson()
    {
        $this->person = People::active()
            ->verified()
            ->where('slug', $this->slug)
            ->with([
                'seo',
                'socialLinks',
                'educations',
                'awards',
                'relations',
                'filmography',
                'politicalCareers',
                'sportsCareers',
                'entrepreneurs',
                'literatureCareer',
                'controversies',
                'lesserKnownFacts',
                'speechesInterviews',
                'photos',
                'latestUpdates',
            ])
            ->firstOrFail();

        // Increment view count
        $this->person->incrementViewCount();

        $this->setProfessionalMetaTags();
    }

    // Add this method to your App\Livewire\Front\Person\Details component

    public function setActiveTab($tab)
    {
        $this->tab = $tab;

        // Update URL if needed (optional)
        if (request()->has('tab')) {
            $this->dispatch('url-changed', tab: $this->getTabSlug($tab));
        }
    }

    private function setProfessionalMetaTags()
    {
        $seo = $this->person->seo;
        $person = $this->person;

        // Base meta data
        $baseTitle = $seo?->meta_title ?: $this->generateProfessionalTitle();
        $baseDescription = $seo?->meta_description ?: $this->generateProfessionalDescription();
        $keywords = $seo?->meta_keywords ?: $this->generateKeywords();

        // Tab-specific optimizations
        $tabData = $this->getTabSpecificMetaData($baseTitle, $baseDescription);

        $currentTitle = $tabData['title'];
        $currentDescription = $tabData['description'];
        $canonicalUrl = $this->getCanonicalUrl();

        // Set comprehensive meta tags
        $this->setBasicMetaTags($currentTitle, $currentDescription, $keywords, $canonicalUrl);
        $this->setOpenGraphTags($currentTitle, $currentDescription, $canonicalUrl);
        $this->setTwitterTags($currentTitle, $currentDescription);
        $this->setStructuredData();
        $this->setAdditionalMetaTags();
        $this->setBreadcrumbStructuredData();
    }

    private function generateProfessionalTitle()
    {
        $person = $this->person;
        $profession = $person->profession ?: 'Personality';
        $year = date('Y');

        $titles = [
            "overview" => "{$person->name} - {$profession} | Biography, Career & Facts {$year}",
            "biography" => "{$person->name} Biography - Early Life, Education & Personal Story",
            "career" => "{$person->name} Career Timeline - Professional Achievements & Work History",
            "personal_life" => "{$person->name} Personal Life - Family, Relationships & Lifestyle",
            "awards" => "{$person->name} Awards & Achievements - Honors and Recognition",
            "gallery" => "{$person->name} Photo Gallery - Images, Pictures & Photos",
            "controversies" => "{$person->name} Controversies - Facts & Details",
        ];

        return $titles[$this->tab] ?? $titles['overview'];
    }

    private function generateProfessionalDescription()
    {
        $person = $this->person;
        $profession = $person->profession ?: 'prominent personality';
        $baseAbout = Str::limit(strip_tags($person->about), 120) ?: "Explore comprehensive information about {$person->name}";

        $descriptions = [
            "overview" => "Complete profile of {$person->name}, {$profession}. {$baseAbout}. Updated for " . date('Y'),
            "biography" => "Detailed biography of {$person->name} - from early life to current status. Learn about education, background, and personal journey of this {$profession}.",
            "career" => "Professional career timeline of {$person->name}. Explore work history, major projects, achievements and career milestones as a {$profession}.",
            "personal_life" => "Personal life details of {$person->name}. Discover family background, relationships, education, and personal interests of this {$profession}.",
            "awards" => "Complete list of awards, honors and achievements received by {$person->name}. Recognition and accolades throughout their career as a {$profession}.",
            "gallery" => "Exclusive photo gallery of {$person->name}. High-quality images, pictures and photos showcasing different phases of life and career.",
            "controversies" => "Controversies and public disputes involving {$person->name}. Facts, details and analysis of controversial moments in their career.",
        ];

        return $descriptions[$this->tab] ?? $descriptions['overview'];
    }

    private function generateKeywords()
    {
        $person = $this->person;
        $profession = $person->profession ?: 'personality';
        $baseKeywords = [
            $person->name,
            strtolower($profession),
            'biography',
            'career',
            'facts',
            'profile'
        ];

        $tabKeywords = [
            "overview" => ['details', 'information', 'bio', 'who is'],
            "biography" => ['early life', 'education', 'background', 'personal story'],
            "career" => ['work history', 'professional life', 'achievements', 'timeline'],
            "personal_life" => ['family', 'relationships', 'lifestyle', 'personal'],
            "awards" => ['honors', 'recognition', 'achievements', 'prizes'],
            "gallery" => ['photos', 'images', 'pictures', 'visuals'],
            "controversies" => ['scandals', 'issues', 'disputes', 'controversial'],
        ];

        $keywords = array_merge($baseKeywords, $tabKeywords[$this->tab] ?? []);
        return implode(', ', array_slice($keywords, 0, 15));
    }

    private function getTabSpecificMetaData($baseTitle, $baseDescription)
    {
        $person = $this->person;

        $tabTitles = [
            'overview' => $baseTitle,
            'biography' => "{$person->name} Biography - Complete Life Story & Early Life Details",
            'career' => "{$person->name} Career - Professional Timeline & Major Achievements",
            'personal_life' => "{$person->name} Personal Life - Family, Relationships & Personal Details",
            'awards' => "{$person->name} Awards - Complete List of Honors & Recognitions",
            'gallery' => "{$person->name} Photos - Exclusive Image Gallery & Pictures",
            'controversies' => "{$person->name} Controversies - Detailed Analysis & Facts",
        ];

        $tabDescriptions = [
            'overview' => $baseDescription,
            'biography' => "Explore the complete biography of {$person->name}. From childhood to becoming a renowned {$person->profession}. Includes early life, education, and personal journey.",
            'career' => "Detailed career timeline of {$person->name}. Major projects, professional milestones, and significant achievements as a {$person->profession}. Updated for " . date('Y'),
            'personal_life' => "Discover {$person->name}'s personal life including family background, relationships, education history, and personal interests beyond their professional career.",
            'awards' => "Complete awards list of {$person->name}. All honors, recognitions, and achievements received throughout their career as a {$person->profession}.",
            'gallery' => "Browse exclusive photos of {$person->name}. High-quality images from various stages of life and career. Official and candid pictures.",
            'controversies' => "In-depth look at controversies involving {$person->name}. Facts, timeline, and analysis of disputed moments in their public life.",
        ];

        return [
            'title' => $tabTitles[$this->tab] ?? $baseTitle,
            'description' => $tabDescriptions[$this->tab] ?? $baseDescription
        ];
    }

    private function setBasicMetaTags($title, $description, $keywords, $canonicalUrl)
    {
        meta()
            ->set('title', $title)
            ->set('description', $description)
            ->set('keywords', $keywords)
            ->set('canonical', $canonicalUrl)
            ->set('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')
            ->set('author', $this->person->name)
            ->set('language', 'en')
            ->set('revisit-after', '7 days')
            ->set('rating', 'general')
            ->set('distribution', 'global');
    }

    private function setOpenGraphTags($title, $description, $canonicalUrl)
    {
        $image = $this->person->profile_image ?: $this->getDefaultImage();

        meta()
            ->set('og:title', $title)
            ->set('og:description', $description)
            ->set('og:url', $canonicalUrl)
            ->set('og:type', 'profile')
            ->set('og:image', $image ?: default_image(1200, 600))
            ->set('og:image:width', '1200')
            ->set('og:image:height', '630')
            ->set('og:image:alt', "Profile image of {$this->person->name}")
            ->set('og:site_name', config('app.name'))
            ->set('og:locale', 'en_US')
            ->set('profile:first_name', explode(' ', $this->person->name)[0])
            ->set('profile:last_name', explode(' ', $this->person->name)[1] ?? '')
            ->set('profile:username', $this->person->slug);
    }

    private function setTwitterTags($title, $description)
    {
        $image = $this->person->profile_image ?: $this->getDefaultImage();

        meta()
            ->set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $title)
            ->set('twitter:description', $description)
            ->set('twitter:image', $image ?: default_image(1200, 600))
            ->set('twitter:site', '@' . config('app.name'))
            ->set('twitter:creator', '@' . config('app.name'))
            ->set('twitter:label1', 'Profession')
            ->set('twitter:data1', $this->person->profession ?: 'Personality')
            ->set('twitter:label2', 'Category')
            ->set('twitter:data2', 'Biography');
    }

    private function setStructuredData()
    {
        // Person structured data
        $personStructuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $this->person->name,
            'description' => Str::limit(strip_tags($this->person->about), 200),
            'url' => $this->getCanonicalUrl(),
            'image' => $this->person->profile_image ?: $this->getDefaultImage(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $this->getCanonicalUrl()
            ]
        ];

        // Add profession if available
        if ($this->person->profession) {
            $personStructuredData['hasOccupation'] = [
                '@type' => 'Occupation',
                'name' => $this->person->profession
            ];
        }

        // Add sameAs for social links
        $sameAs = [];
        foreach ($this->person->socialLinks as $socialLink) {
            $sameAs[] = $socialLink->url;
        }

        if (!empty($sameAs)) {
            $personStructuredData['sameAs'] = $sameAs;
        }

        // Article structured data for biography/content pages
        $articleStructuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $this->generateProfessionalTitle(),
            'description' => $this->generateProfessionalDescription(),
            'image' => $this->person->profile_image ?: $this->getDefaultImage(),
            'author' => [
                '@type' => 'Person',
                'name' => $this->person->name
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png') // Update with your logo path
                ]
            ],
            'datePublished' => $this->person->created_at?->toIso8601String(),
            'dateModified' => $this->person->updated_at?->toIso8601String(),
            'mainEntityOfPage' => $this->getCanonicalUrl(),
            'articleSection' => 'Biography'
        ];

        // WebPage structured data
        $webPageStructuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $this->generateProfessionalTitle(),
            'description' => $this->generateProfessionalDescription(),
            'url' => $this->getCanonicalUrl(),
            'primaryImageOfPage' => $this->person->profile_image ?: $this->getDefaultImage(),
            'datePublished' => $this->person->created_at?->toIso8601String(),
            'dateModified' => $this->person->updated_at?->toIso8601String(),
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
                'itemListElement' => $this->generateBreadcrumbItems()
            ]
        ];

        // Set all structured data
        meta()
            ->set('script:ld+json-person', json_encode($personStructuredData))
            ->set('script:ld+json-article', json_encode($articleStructuredData))
            ->set('script:ld+json-webpage', json_encode($webPageStructuredData));
    }

    private function setBreadcrumbStructuredData()
    {
        $breadcrumbStructuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $this->generateBreadcrumbItems()
        ];

        meta()->set('script:ld+json-breadcrumb', json_encode($breadcrumbStructuredData));
    }

    private function generateBreadcrumbItems()
    {
        $baseUrl = config('app.url');
        $position = 1;

        $breadcrumbs = [
            [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => 'Home',
                'item' => $baseUrl
            ],
            [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => 'People',
                'item' => $baseUrl . '/people'
            ],
            [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $this->person->name,
                'item' => $this->getCanonicalUrl()
            ]
        ];

        // Add current tab as breadcrumb if not overview
        if ($this->tab !== 'overview') {
            $tabTitles = [
                'biography' => 'Biography',
                'career' => 'Career',
                'personal_life' => 'Personal Life',
                'awards' => 'Awards',
                'gallery' => 'Gallery',
                'controversies' => 'Controversies',
            ];

            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $tabTitles[$this->tab] ?? ucfirst(str_replace('_', ' ', $this->tab)),
                'item' => $this->getCanonicalUrl()
            ];
        }

        return $breadcrumbs;
    }

    private function setAdditionalMetaTags()
    {
        // Article meta for fresh content
        meta()
            ->set('article:published_time', $this->person->created_at?->toIso8601String())
            ->set('article:modified_time', $this->person->updated_at?->toIso8601String())
            ->set('article:author', $this->person->name)
            ->set('article:section', 'Biography');

        // Mobile and app links
        meta()
            ->set('mobile-web-app-capable', 'yes')
            ->set('theme-color', '#ffffff')
            ->set('apple-mobile-web-app-capable', 'yes')
            ->set('apple-mobile-web-app-status-bar-style', 'black-translucent');

        // Prevent duplicate content
        if ($this->tab !== 'overview') {
            meta()->set('canonical', $this->getCanonicalUrl());
        }
    }

    private function getDefaultImage()
    {
        $defaultImage = default_image(1200, 630, 90);
        return $defaultImage;
    }

    private function getCanonicalUrl()
    {
        if ($this->tab === 'overview') {
            return route('people.people.show', ['slug' => $this->slug]);
        }

        return route('people.details.tab', [
            'slug' => $this->slug,
            'tab' => $this->getTabSlug($this->tab)
        ]);
    }

    private function getTabFromSlug($slug)
    {
        $tabMapping = [
            'overview' => 'overview',
            'biography' => 'biography',
            'career' => 'career',
            'personal-life' => 'personal_life',
            'awards' => 'awards',
            'gallery' => 'gallery',
            'controversies' => 'controversies',
        ];

        return $tabMapping[$slug] ?? 'overview';
    }

    private function getTabSlug($tab)
    {
        $tabSlugs = [
            'overview' => 'overview',
            'biography' => 'biography',
            'career' => 'career',
            'personal_life' => 'personal-life',
            'awards' => 'awards',
            'gallery' => 'gallery',
            'controversies' => 'controversies',
        ];

        return $tabSlugs[$tab] ?? $tab;
    }

    public function hydrate(){
        if($this->slug && (!$this->person || !$this->person->exists)){
            $this->loadPerson();
        }
    }

    public function getTabs()
    {
        return [
            'overview' => [
                'title' => 'Overview',
                'count' => 0,
                'slug' => 'overview'
            ],
            'biography' => [
                'title' => 'Biography',
                'count' => $this->person->about ? 1 : 0,
                'slug' => 'biography'
            ],
            'career' => [
                'title' => 'Career',
                'count' => $this->getCareerItemsCount(),
                'slug' => 'career'
            ],
            'personal_life' => [
                'title' => 'Personal Life',
                'count' => $this->getPersonalLifeItemsCount(),
                'slug' => 'personal-life'
            ],
            'awards' => [
                'title' => 'Awards',
                'count' => $this->person->awards->count(),
                'slug' => 'awards'
            ],
            'gallery' => [
                'title' => 'Gallery',
                'count' => $this->person->photos->count(),
                'slug' => 'gallery'
            ],
            'controversies' => [
                'title' => 'Controversies',
                'count' => $this->person->controversies->count(),
                'slug' => 'controversies'
            ],
        ];
    }

    public function getTabUrl($tab)
    {
        if ($tab === 'overview') {
            return route('people.people.show', ['slug' => $this->slug]);
        }

        return route('people.details.tab', [
            'slug' => $this->slug,
            'tab' => $this->getTabs()[$tab]['slug']
        ]);
    }

    public function isActiveTab($tab)
    {
        // Debug logging
        Log::info("isActiveTab check", [
            'requested_tab' => $tab,
            'current_tab' => $this->tab,
            'is_equal' => $this->tab === $tab
        ]);

        return $this->tab === $tab;
    }

    public function shouldShowTab($tab)
    {
        $counts = [
            'biography' => $this->person->about ? 1 : 0,
            'career' => $this->getCareerItemsCount(),
            'personal_life' => $this->getPersonalLifeItemsCount(),
            'awards' => $this->person->awards->count(),
            'gallery' => $this->person->photos->count(),
            'controversies' => $this->person->controversies->count(),
        ];

        return $tab === 'overview' || ($counts[$tab] ?? 0) > 0;
    }

    private function getCareerItemsCount()
    {
        $count = 0;
        $count += $this->person->filmography->count();
        $count += $this->person->politicalCareers->count();
        $count += $this->person->sportsCareers->count();
        $count += $this->person->entrepreneurs->count();
        $count += $this->person->literatureCareer->count();
        return $count;
    }

    private function getPersonalLifeItemsCount()
    {
        $count = 0;
        $count += $this->person->relations->count();
        $count += $this->person->educations->count();
        $count += ($this->person->physical_stats && count($this->person->physical_stats)) ? 1 : 0;
        return $count;
    }

    private function getYearsActive()
    {
        // TODO
        return null;
    }

    public function render()
    {
        // Get social icons from config
        $socialIcons = config('social-icons.icons', []);
        return view('livewire.front.person.details', compact('socialIcons'));
    }
}
