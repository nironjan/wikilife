<?php

namespace App\Livewire\Front\Person;

use App\Models\SpeechesInterview;
use F9Web\Meta\Meta;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\URL as LaravelURL;

#[Layout('components.layouts.front')]
#[Title('Speech & Interview Details')]
class Speeches extends Component
{
    public $person;
    public $speech;
    public $personSlug;
    public $speechSlug;

    public function mount($personSlug, $slug)
    {
        $this->personSlug = $personSlug;
        $this->speechSlug = $slug;
        $this->loadData();
        $this->setMetaTags();
    }

    public function loadData()
    {
        // Load the person first
        $this->person = \App\Models\People::active()
            ->verified()
            ->where('slug', $this->personSlug)
            ->firstOrFail();

        // Load the speech
        $this->speech = SpeechesInterview::where('slug', $this->speechSlug)
            ->where('person_id', $this->person->id)
            ->firstOrFail();
    }

    protected function setMetaTags()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;
        $speechTitle = $this->speech->title;
        $speechType = $this->speech->type;
        $location = $this->speech->location;
        $date = $this->speech->date?->format('F j, Y');

        // Base title and description
        $baseTitle = "{$speechTitle} - {$personName}";
        $baseDescription = $this->speech->description ?:
            "{$speechType} by {$personName}" .
            ($location ? " at {$location}" : "") .
            ($date ? " on {$date}" : "") .
            ". " . Str::limit(strip_tags($this->speech->description), 150);

        // Enhanced title with context
        $currentTitle = $baseTitle;
        $currentDescription = $baseDescription;

        // Add profession and type context
        if ($primaryProfession) {
            $currentTitle = "{$speechTitle} - {$personName} ({$primaryProfession})";
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
            ->set('og:site_name', config('app.name', 'Wiki Life'))
            ->set('og:locale', app()->getLocale())
            ->set('article:published_time', $this->speech->created_at->toISOString())
            ->set('article:modified_time', $this->speech->updated_at->toISOString())
            ->set('article:author', $personName)
            ->set('article:section', ucfirst($speechType));

        // Add speech-specific Open Graph tags
        if ($location) {
            Meta::set('og:locale:country', 'IN')
                ->set('business:contact_data:locality', $location);
        }

        // Twitter Card Tags
        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $currentTitle)
            ->set('twitter:description', $currentDescription)
            ->set('twitter:site', '@' . config('app.name', 'Wiki Life'))
            ->set('twitter:creator', '@' . config('app.name', 'Wiki Life'))
            ->set('twitter:label1', 'Speaker')
            ->set('twitter:data1', $personName)
            ->set('twitter:label2', 'Type')
            ->set('twitter:data2', ucfirst($speechType));

        // Additional meta tags
        Meta::set('author', $personName)
            ->set('publisher', config('app.name', 'Wiki Life'))
            ->set('content-type', 'article')
            ->set('content-language', app()->getLocale())
            ->set('audience', 'all')
            ->set('rating', 'general')
            ->set('distribution', 'global')
            ->set('revisit-after', '7 days')
            ->set('googlebot', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')
            ->set('bingbot', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1');

        // Location specific tags
        if ($location) {
            Meta::set('geo.placename', $location)
                ->set('geo.position', '0;0')
                ->set('ICBM', '0, 0');
        }

        // Set meta images
        $this->setMetaImages();

        // Additional schema-specific meta
        $this->setSchemaMetaTags();
    }

    protected function setMetaImages()
    {
        // Priority: Speech thumbnail > Person profile image > Default image
        $image = $this->speech->thumbnail_url ??
                $this->person->profile_image_url ??
                default_image(1200, 630);

        $imageWidth = 1200;
        $imageHeight = 630;

        Meta::set('og:image', $image)
            ->set('og:image:width', $imageWidth)
            ->set('og:image:height', $imageHeight)
            ->set('og:image:alt', "{$this->speech->title} - {$this->person->name}")
            ->set('og:image:type', 'image/jpeg')
            ->set('twitter:image', $image)
            ->set('twitter:image:alt', "{$this->speech->title} - {$this->person->name}");

        // Additional image meta for search engines
        Meta::set('image', $image)
            ->set('thumbnail', $image);
    }

    protected function setSchemaMetaTags()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;
        $speechType = $this->speech->type;

        // Additional schema-specific meta tags
        Meta::set('news_keywords', $this->getNewsKeywords())
            ->set('standout', $this->getCanonicalUrl())
            ->set('original-source', $this->speech->url ?? $this->getCanonicalUrl());

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

        // Speech type specific
        Meta::set('speech:type', $speechType)
            ->set('speech:date', $this->speech->date?->format('Y-m-d'));
    }

    private function getKeywords()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;
        $professions = $this->person->professions ?? [];
        $speechTitle = $this->speech->title;
        $speechType = $this->speech->type;
        $location = $this->speech->location;
        $nationality = $this->person->nationality;

        $baseKeywords = [
            $personName,
            $speechTitle,
            strtolower($speechType),
            $speechType === 'speech' ? 'public speaking' : 'media interview',
            'speeches and interviews',
            'public appearances',
            'media coverage',
            'public statements'
        ];

        // Add profession-based keywords
        if ($primaryProfession) {
            $baseKeywords[] = $primaryProfession;
            $baseKeywords[] = "{$primaryProfession} {$speechType}";
            $baseKeywords[] = "famous {$primaryProfession} speeches";
            $baseKeywords[] = "{$primaryProfession} interviews";
        }

        // Add all professions as keywords
        foreach ($professions as $profession) {
            if ($profession !== $primaryProfession) {
                $baseKeywords[] = $profession;
                $baseKeywords[] = "{$profession} {$speechType}";
            }
        }

        // Add location keywords
        if ($location) {
            $baseKeywords[] = $location;
            $baseKeywords[] = "{$location} {$speechType}";
            $baseKeywords[] = "events in {$location}";
        }

        // Add nationality keywords
        if ($nationality) {
            $baseKeywords[] = $nationality;
            $baseKeywords[] = "{$nationality} {$primaryProfession}";
        }

        // Add type specific keywords
        if ($speechType === 'speech') {
            $baseKeywords = array_merge($baseKeywords, [
                'keynote address',
                'public address',
                'speaking engagement',
                'oratory',
                'public speaking'
            ]);
        } else {
            $baseKeywords = array_merge($baseKeywords, [
                'media appearance',
                'press interview',
                'media conversation',
                'exclusive interview',
                'press coverage'
            ]);
        }

        // Add title words as keywords
        $titleWords = explode(' ', $speechTitle);
        $baseKeywords = array_merge($baseKeywords, array_slice($titleWords, 0, 5));

        // Add industry-specific keywords based on profession
        $industryKeywords = $this->getIndustryKeywords($primaryProfession);
        $baseKeywords = array_merge($baseKeywords, $industryKeywords);

        // Add year for timeliness
        if ($this->speech->date) {
            $year = $this->speech->date->format('Y');
            $baseKeywords[] = $year;
            $baseKeywords[] = "{$year} {$speechType}";
        }

        return implode(', ', array_unique(array_filter($baseKeywords)));
    }

    private function getNewsKeywords()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;
        $speechType = $this->speech->type;
        $location = $this->speech->location;

        $newsKeywords = [
            $personName,
            $primaryProfession,
            $speechType,
            $location,
            'breaking news',
            'latest',
            'media coverage'
        ];

        return implode(', ', array_unique(array_filter($newsKeywords)));
    }

    private function getIndustryKeywords($profession)
    {
        $industryMap = [
            'actor' => ['film industry', 'entertainment', 'cinema', 'movies', 'hollywood', 'bollywood'],
            'singer' => ['music industry', 'concert', 'music awards', 'performance', 'recording artist'],
            'politician' => ['government', 'politics', 'public service', 'policy', 'leadership', 'democracy'],
            'athlete' => ['sports', 'championship', 'tournament', 'sports news', 'athletics'],
            'writer' => ['literature', 'books', 'author events', 'literary talks', 'publishing'],
            'director' => ['film director', 'cinema', 'filmmaking', 'movie industry'],
            'model' => ['fashion', 'modeling', 'fashion events', 'beauty industry'],
            'scientist' => ['research', 'academia', 'science talks', 'innovation', 'technology'],
            'business' => ['entrepreneurship', 'corporate events', 'business talks', 'industry leader'],
            'doctor' => ['medical', 'healthcare', 'medical conferences', 'health talks'],
            'artist' => ['art', 'exhibition', 'art talks', 'creative industry'],
            'chef' => ['culinary', 'cooking', 'food events', 'gastronomy'],
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
        return LaravelURL::route('people.speeches.show', [
            'personSlug' => $this->personSlug,
            'slug' => $this->speechSlug
        ]);
    }

    public function getRelatedSpeeches()
    {
        return SpeechesInterview::where('person_id', $this->person->id)
            ->where('id', '!=', $this->speech->id)
            ->where(function($query) {
                $query->where('type', $this->speech->type)
                      ->orWhere('date', '>=', now()->subMonths(6));
            })
            ->orderBy('date', 'desc')
            ->limit(4)
            ->get();
    }

    public function getStructuredData()
    {
        $schemas = [
            $this->getPersonStructuredData(),
            $this->getBreadcrumbStructuredData(),
            $this->getSpeechStructuredData(),
            $this->getWebPageStructuredData()
        ];

        // Remove empty schemas and decode/re-encode to ensure valid JSON
        $validSchemas = [];
        foreach ($schemas as $schema) {
            if (!empty($schema)) {
                $decoded = json_decode($schema, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $validSchemas[] = $decoded;
                }
            }
        }

        // If multiple schemas, output as array, otherwise as single object
        if (count($validSchemas) > 1) {
            return json_encode($validSchemas, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        } elseif (count($validSchemas) === 1) {
            return json_encode($validSchemas[0], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }

        return '';
    }

    private function getPersonStructuredData()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;

        $personSchema = [
            "@context" => "https://schema.org",
            "@type" => "Person",
            "@id" => LaravelURL::route('people.people.show', $this->person->slug) . "#person",
            "name" => $personName,
            "description" => Str::limit(strip_tags($this->person->about ?? ''), 200),
            "url" => LaravelURL::route('people.people.show', $this->person->slug),
            "mainEntityOfPage" => LaravelURL::route('people.people.show', $this->person->slug),
            "image" => $this->person->profile_image_url ?: default_image(400, 400),
            "sameAs" => $this->getPersonSameAsLinks()
        ];

        // Add profession/occupation
        if ($primaryProfession) {
            $personSchema["hasOccupation"] = [
                "@type" => "Occupation",
                "name" => $primaryProfession,
                "description" => "Professional {$primaryProfession}"
            ];

            $personSchema["jobTitle"] = $primaryProfession;
        }

        // Add birth details
        if ($this->person->birth_date) {
            $personSchema["birthDate"] = $this->person->birth_date->format('Y-m-d');
        }

        if ($this->person->birth_place) {
            $personSchema["birthPlace"] = [
                "@type" => "Place",
                "name" => $this->person->birth_place
            ];
        }

        // Add nationality
        if ($this->person->nationality) {
            $personSchema["nationality"] = [
                "@type" => "Country",
                "name" => $this->person->nationality
            ];
        }

        // Add gender
        if ($this->person->gender) {
            $personSchema["gender"] = $this->person->gender;
        }

        return json_encode($personSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    private function getBreadcrumbStructuredData()
    {
        $breadcrumbSchema = [
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
                    "name" => "People",
                    "item" => url('/people')
                ],
                [
                    "@type" => "ListItem",
                    "position" => 3,
                    "name" => $this->person->name,
                    "item" => LaravelURL::route('people.people.show', $this->person->slug)
                ],
                [
                    "@type" => "ListItem",
                    "position" => 4,
                    "name" => "Speeches & Interviews",
                    "item" => route('people.details.tab', [
                        'slug' => $this->person->slug,
                        'tab' => 'speeches-interviews'
                        ])
                ],
                [
                    "@type" => "ListItem",
                    "position" => 5,
                    "name" => $this->speech->title,
                    "item" => $this->getCanonicalUrl()
                ]
            ]
        ];

        return json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    private function getSpeechStructuredData()
    {
        $personName = $this->person->name;
        $primaryProfession = $this->person->primary_profession;
        $speechType = $this->speech->type;

        // Determine schema type based on speech type
        $schemaType = $speechType === 'speech' ? 'PublicSpeakingEvent' : 'Conversation';

        $speechSchema = [
            "@context" => "https://schema.org",
            "@type" => $schemaType,
            "name" => $this->speech->title,
            "description" => $this->speech->description ?: "{$speechType} by {$personName}",
            "startDate" => $this->speech->date?->toIso8601String(),
            "performer" => [
                "@type" => "Person",
                "@id" => LaravelURL::route('people.people.show', $this->person->slug) . "#person",
                "name" => $personName,
                "url" => LaravelURL::route('people.people.show', $this->person->slug)
            ],
            "organizer" => [
                "@type" => "Organization",
                "name" => config('app.name', 'Wiki Life')
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => config('app.name', 'Wiki Life'),
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => site_logo(180, 60),
                    "width" => "180",
                    "height" => "60"
                ]
            ],
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => $this->getCanonicalUrl()
            ],
            "inLanguage" => app()->getLocale(),
            "isAccessibleForFree" => true
        ];

        // Add profession to performer if available
        if ($primaryProfession) {
            $speechSchema["performer"]["jobTitle"] = $primaryProfession;
        }

        // Add location if available
        if ($this->speech->location) {
            $speechSchema["location"] = [
                "@type" => "Place",
                "name" => $this->speech->location
            ];
        }

        // Add URL if available
        if ($this->speech->url) {
            $speechSchema["url"] = $this->speech->url;
        }

        // Add image if available
        $image = $this->speech->thumbnail_url ?? $this->person->profile_image_url;
        if ($image) {
            $speechSchema["image"] = [
                "@type" => "ImageObject",
                "url" => $image,
                "width" => "800",
                "height" => "600",
                "caption" => "{$this->speech->title} - {$personName}"
            ];
        }

        // For interviews, add additional properties
        if ($speechType === 'interview') {
            $speechSchema["about"] = $this->speech->description;
            $speechSchema["transcript"] = $this->speech->description;
        }

        return json_encode($speechSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    private function getWebPageStructuredData()
    {
        $webPageSchema = [
            "@context" => "https://schema.org",
            "@type" => "WebPage",
            "@id" => $this->getCanonicalUrl() . "#webpage",
            "name" => $this->speech->title . " - " . $this->person->name,
            "description" => $this->speech->description ?: "{$this->speech->type} by {$this->person->name}",
            "url" => $this->getCanonicalUrl(),
            "primaryImageOfPage" => $this->speech->thumbnail_url ?? $this->person->profile_image_url,
            "datePublished" => $this->speech->created_at->toIso8601String(),
            "dateModified" => $this->speech->updated_at->toIso8601String(),
            "lastReviewed" => $this->speech->updated_at->toIso8601String(),
            "author" => [
                "@type" => "Person",
                "@id" => LaravelURL::route('people.people.show', $this->person->slug) . "#person",
                "name" => $this->person->name
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => config('app.name', 'Wiki Life')
            ],
            "inLanguage" => app()->getLocale(),
            "breadcrumb" => [
                "@type" => "BreadcrumbList",
                "itemListElement" => $this->getBreadcrumbItemsForWebPage()
            ]
        ];

        return json_encode($webPageSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    private function getBreadcrumbItemsForWebPage()
    {
        return [
            [
                "@type" => "ListItem",
                "position" => 1,
                "name" => "Home",
                "item" => url('/')
            ],
            [
                "@type" => "ListItem",
                "position" => 2,
                "name" => $this->person->name,
                "item" => LaravelURL::route('people.people.show', $this->person->slug)
            ],
            [
                "@type" => "ListItem",
                "position" => 3,
                "name" => "Speeches & Interviews",
                "item" => route('people.details.tab', [
                        'slug' => $this->person->slug,
                        'tab' => 'speeches-interviews'
                        ])
            ],
            [
                "@type" => "ListItem",
                "position" => 4,
                "name" => $this->speech->title,
                "item" => $this->getCanonicalUrl()
            ]
        ];
    }

    private function getPersonSameAsLinks()
    {
        $sameAs = [];

        // Check if person has socialLinks relationship
        if (method_exists($this->person, 'socialLinks') && $this->person->socialLinks) {
            foreach ($this->person->socialLinks as $socialLink) {
                $sameAs[] = $socialLink->url;
            }
        }

        return $sameAs;
    }

    public function render()
    {
        // Ensure meta tags are set during render
        $this->setMetaTags();

        return view('livewire.front.person.speeches', [
            'relatedSpeeches' => $this->getRelatedSpeeches(),
            'structuredData' => $this->getStructuredData()
        ]);
    }
}
