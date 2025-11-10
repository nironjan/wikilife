<?php

namespace App\Livewire\Front\Person\LatestUpdate;

use App\Models\LatestUpdate;
use App\Models\People;
use F9Web\Meta\Meta;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\URL as LaravelURL;

#[Layout('components.layouts.front')]
#[Title('Latest Update Details')]
class Details extends Component
{
    public $person;
    public $personSlug;
    public $updateSlug;
    public $update;

    public function mount($personSlug, $slug)
    {
        $this->personSlug = $personSlug;
        $this->updateSlug = $slug;
        $this->loadData();
        $this->setMetaTags();
    }

    public function loadData()
    {
        $this->person = People::active()
            ->verified()
            ->where('slug', $this->personSlug)
            ->with(['seo'])
            ->firstOrFail();

        $this->update = LatestUpdate::where('slug', $this->updateSlug)
            ->where('person_id', $this->person->id)
            ->published()
            ->approved()
            ->firstOrFail();
    }

    protected function setMetaTags()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;
        $updateTitle = $this->update->title;
        $updateType = $this->update->update_type;

        // Base title and description
        $baseTitle = "{$updateTitle} - {$personName}";
       $baseDescription = $this->update->description ?:
        ($this->update->excerpt ?:
            "Latest {$updateType} update about {$personName}" .
            ($primaryProfession ? ", the renowned {$primaryProfession}" : "") .
            ". " . Str::limit(strip_tags($this->update->content), 150)
        );

        // Enhanced title with profession context
        $currentTitle = $baseTitle;
        $currentDescription = $baseDescription;

        // Add profession and update type context
        if ($primaryProfession) {
            $currentTitle = "{$updateTitle} - {$personName} ({$primaryProfession})";
        }

        // Set basic meta tags using Meta facade
        Meta::set('title', $currentTitle)
            ->set('description', $currentDescription)
            ->set('keywords', $this->getKeywords())
            ->set('canonical', $this->getCanonicalUrl())
            ->set('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1');

        // Open Graph Tags
        Meta::set('og:title', $currentTitle)
            ->set('og:description', $currentDescription)
            ->set('og:type', 'article')
            ->set('og:url', $this->getCanonicalUrl())
            ->set('og:site_name', config('app.name', 'WikiLife'))
            ->set('og:locale', app()->getLocale())
            ->set('article:published_time', $this->update->created_at->toISOString())
            ->set('article:modified_time', $this->update->updated_at->toISOString())
            ->set('article:author', $personName)
            ->set('article:section', $updateType);

        // Add profession-specific Open Graph tags
        if ($primaryProfession) {
            Meta::set('article:tag', $primaryProfession)
                ->set('profile:username', $personName)
                ->set('profile:gender', $this->person->gender ?? '')
                ->set('job_title', $primaryProfession);
        }

        // Twitter Card Tags
        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $currentTitle)
            ->set('twitter:description', $currentDescription)
            ->set('twitter:site', '@' . config('app.name', 'WikiLife'))
            ->set('twitter:creator', '@' . config('app.name', 'WikiLife'))
            ->set('twitter:label1', 'Written by')
            ->set('twitter:data1', $personName)
            ->set('twitter:label2', 'Category')
            ->set('twitter:data2', $updateType);

        // Additional professional meta tags
        Meta::set('author', $personName)
            ->set('publisher', config('app.name', 'WikiLife'))
            ->set('content-type', 'article')
            ->set('content-language', app()->getLocale())
            ->set('audience', 'all')
            ->set('rating', 'general')
            ->set('distribution', 'global')
            ->set('revisit-after', '7 days')
            ->set('googlebot', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')
            ->set('bingbot', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1');

        // Location and profession specific tags
        if ($this->person->nationality) {
            Meta::set('country_name', $this->person->nationality)
                ->set('geo.region', $this->person->nationality)
                ->set('geo.placename', $this->person->nationality);
        }

        // Set meta images
        $this->setMetaImages();

        // Additional schema-specific meta
        $this->setSchemaMetaTags();
    }

    protected function setMetaImages()
    {
        // Priority: Update image > Person profile image > Default image
        $image = $this->update->image_url ??
                $this->person->profile_image_url ??
                asset('images/update-details-og.jpg');

        $imageWidth = 1200;
        $imageHeight = 630;

        Meta::set('og:image', $image)
            ->set('og:image:width', $imageWidth)
            ->set('og:image:height', $imageHeight)
            ->set('og:image:alt', "{$this->update->title} - {$this->person->name}")
            ->set('og:image:type', 'image/jpeg')
            ->set('twitter:image', $image)
            ->set('twitter:image:alt', "{$this->update->title} - {$this->person->name}");

        // Additional image meta for search engines
        Meta::set('image', $image)
            ->set('thumbnail', $image);
    }

    protected function setSchemaMetaTags()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;

        // Additional schema-specific meta tags
        Meta::set('news_keywords', $this->getNewsKeywords())
            ->set('standout', $this->getCanonicalUrl())
            ->set('original-source', $this->getCanonicalUrl());

        // Article-specific meta
        Meta::set('article:content_tier', 'free')
            ->set('article:opinion', 'false')
            ->set('article:family_friendly', 'yes');

        // Person and profession specific
        if ($primaryProfession) {
            Meta::set('person:profession', $primaryProfession)
                ->set('person:name', $personName);
        }

        if ($this->person->birth_date) {
            Meta::set('person:birth_date', $this->person->birth_date->format('Y-m-d'));
        }
    }

    private function getKeywords()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;
        $professions = $this->person->professions ?? [];
        $updateTitle = $this->update->title;
        $updateType = $this->update->update_type;
        $nationality = $this->person->nationality;

        $baseKeywords = [
            $personName,
            $updateTitle,
            strtolower($updateType),
            'latest update',
            'news update',
            'recent development',
            'career news',
            'professional update'
        ];

        // Add profession-based keywords
        if ($primaryProfession) {
            $baseKeywords[] = $primaryProfession;
            $baseKeywords[] = "{$primaryProfession} news";
            $baseKeywords[] = "{$primaryProfession} update";
            $baseKeywords[] = "famous {$primaryProfession}";
            $baseKeywords[] = "{$primaryProfession} {$updateType}";
        }

        // Add all professions as keywords
        foreach ($professions as $profession) {
            if ($profession !== $primaryProfession) {
                $baseKeywords[] = $profession;
                $baseKeywords[] = "{$profession} {$updateType}";
            }
        }

        // Add nationality keywords
        if ($nationality) {
            $baseKeywords[] = $nationality;
            $baseKeywords[] = "{$nationality} {$primaryProfession}";
        }

        // Add update type specific keywords
        $baseKeywords[] = "{$updateType} news";
        $baseKeywords[] = "latest {$updateType}";
        $baseKeywords[] = "recent {$updateType}";

        // Add title words as keywords
        $titleWords = explode(' ', $updateTitle);
        $baseKeywords = array_merge($baseKeywords, array_slice($titleWords, 0, 5));

        // Add industry-specific keywords based on profession
        $industryKeywords = $this->getIndustryKeywords($primaryProfession);
        $baseKeywords = array_merge($baseKeywords, $industryKeywords);

        // Add current year for timeliness
        $baseKeywords[] = date('Y');
        $baseKeywords[] = date('Y') . ' update';

        return implode(', ', array_unique(array_filter($baseKeywords)));
    }

    private function getNewsKeywords()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;
        $updateType = $this->update->update_type;

        $newsKeywords = [
            $personName,
            $primaryProfession,
            $updateType,
            'breaking news',
            'latest',
            'update'
        ];

        return implode(', ', array_unique(array_filter($newsKeywords)));
    }

    private function getIndustryKeywords($profession)
    {
        $industryMap = [
            'actor' => ['hollywood', 'bollywood', 'film industry', 'movies', 'cinema', 'entertainment', 'acting', 'film', 'television'],
            'singer' => ['music industry', 'album', 'song', 'concert', 'music awards', 'recording artist', 'musician', 'vocalist', 'performance'],
            'politician' => ['government', 'politics', 'election', 'public service', 'legislation', 'policy', 'democracy', 'leadership', 'public figure'],
            'athlete' => ['sports', 'championship', 'tournament', 'team', 'competition', 'athletics', 'player', 'game', 'sports news'],
            'writer' => ['literature', 'books', 'author', 'publishing', 'novel', 'bestseller', 'writing', 'literary', 'publication'],
            'director' => ['film director', 'movie director', 'cinema', 'filmmaking', 'production', 'directing', 'film making', 'movie making'],
            'model' => ['fashion', 'modeling', 'runway', 'photoshoot', 'fashion industry', 'style', 'beauty', 'fashion week'],
            'scientist' => ['research', 'academia', 'discovery', 'innovation', 'technology', 'study', 'science', 'research paper', 'academic'],
            'business' => ['entrepreneur', 'CEO', 'executive', 'corporation', 'industry leader', 'business news', 'corporate', 'enterprise', 'startup'],
            'doctor' => ['medical', 'healthcare', 'physician', 'hospital', 'medical research', 'health', 'medicine', 'healthcare news'],
            'artist' => ['art', 'painting', 'exhibition', 'gallery', 'creative', 'visual arts', 'artwork', 'contemporary art'],
            'chef' => ['culinary', 'cooking', 'restaurant', 'cuisine', 'food', 'gastronomy', 'culinary arts', 'recipe'],
        ];

        if (!$profession) return [];

        $professionLower = strtolower($profession);
        foreach ($industryMap as $key => $keywords) {
            if (str_contains($professionLower, $key)) {
                return $keywords;
            }
        }

        return [];
    }

    private function getCanonicalUrl()
    {
        return LaravelURL::route('people.updates.show', [
            'personSlug' => $this->personSlug,
            'slug' => $this->updateSlug
        ]);
    }

    public function getRelatedUpdates()
    {
        return LatestUpdate::where('person_id', $this->person->id)
            ->where('id', '!=', $this->update->id)
            ->published()
            ->approved()
            ->latestFirst()
            ->limit(4)
            ->get();
    }

    public function getStructuredData()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;

        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Article",
            "headline" => $this->update->title,
            "description" => $this->update->excerpt ?: Str::limit(strip_tags($this->update->content), 160),
            "datePublished" => $this->update->created_at->toIso8601String(),
            "dateModified" => $this->update->updated_at->toIso8601String(),
            "author" => [
                "@type" => "Person",
                "name" => $personName,
                "url" => LaravelURL::route('people.people.show', $this->personSlug)
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => config('app.name', 'WikiLife'),
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => asset('images/logo.png'),
                    "width" => "180",
                    "height" => "60"
                ]
            ],
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => $this->getCanonicalUrl()
            ],
            "articleSection" => $this->update->update_type,
            "keywords" => $this->getKeywords(),
            "inLanguage" => app()->getLocale(),
            "copyrightYear" => $this->update->created_at->year,
            "accessMode" => "visual",
            "accessModeSufficient" => "visual",
            "isAccessibleForFree" => true,
            "isFamilyFriendly" => true
        ];

        // Add profession to author if available
        if ($primaryProfession) {
            $schema["author"]["jobTitle"] = $primaryProfession;
        }

        // Add image if available
        $image = $this->update->image_url ?? $this->person->profile_image_url;
        if ($image) {
            $schema["image"] = [
                "@type" => "ImageObject",
                "url" => $image,
                "width" => "800",
                "height" => "600",
                "caption" => "{$this->update->title} - {$personName}"
            ];
            $schema["thumbnailUrl"] = $image;
        }

        // Add article body if content is available
        if ($this->update->content) {
            $schema["articleBody"] = Str::limit(strip_tags($this->update->content), 5000);
        }

        // Add breadcrumb schema
        $schema["breadcrumb"] = [
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
                    "name" => $personName,
                    "item" => LaravelURL::route('people.people.show', $this->personSlug)
                ],
                [
                    "@type" => "ListItem",
                    "position" => 3,
                    "name" => "Latest Updates",
                    "item" => LaravelURL::route('people.updates.index', ['personSlug' => $this->personSlug])
                ],
                [
                    "@type" => "ListItem",
                    "position" => 4,
                    "name" => $this->update->title,
                    "item" => $this->getCanonicalUrl()
                ]
            ]
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function render()
    {
        // Ensure meta tags are set during render
        $this->setMetaTags();

        return view('livewire.front.person.latest-update.details', [
            'relatedUpdates' => $this->getRelatedUpdates(),
        ]);
    }
}
