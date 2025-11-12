<?php

namespace App\Livewire\Front\Person;

use App\Models\Controversy;
use App\Models\People;
use F9Web\Meta\Meta;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\URL as LaravelURL;

#[Layout('components.layouts.front')]
#[Title('Controversy Details')]
class ControversyDetails extends Component
{
    public Controversy $controversy;
    public People $person;
    public $personSlug;
    public $slug;

    public function mount($personSlug, $slug)
    {
        $this->personSlug = $personSlug;
        $this->slug = $slug;
        $this->loadControversy();
        $this->setMetaTags();
    }

    public function loadControversy()
    {
        // First, get the person to ensure they exist
        $this->person = People::where('slug', $this->personSlug)
            ->active()
            ->verified()
            ->firstOrFail();

        // Then get the controversy for this specific person
        $this->controversy = Controversy::with(['person'])
            ->where('slug', $this->slug)
            ->where('person_id', $this->person->id)
            ->where('is_published', true)
            ->firstOrFail();
    }

    protected function setMetaTags()
    {
        $personName = $this->person->display_name;
        $primaryProfession = $this->person->primary_profession;
        $professions = $this->person->professions ?? [];
        $controversyTitle = $this->controversy->title;
        $isResolved = $this->controversy->is_resolved;

        // Base title and description
        $baseTitle = "{$controversyTitle} - {$personName} Controversy";
        $baseDescription = $this->controversy->excerpt ?:
            "Details about the {$controversyTitle} controversy involving {$personName}" .
            ($primaryProfession ? ", the {$primaryProfession}" : "") .
            ". " . ($isResolved ? "This controversy has been resolved." : "Ongoing controversy details and updates.");

        // Enhanced title with profession context
        $currentTitle = $baseTitle;
        $currentDescription = $baseDescription;

        // Add profession and resolution status context
        if ($primaryProfession) {
            $currentTitle = "{$controversyTitle} - {$personName} ({$primaryProfession}) Controversy";
            $currentDescription = "{$primaryProfession} {$personName} controversy: {$controversyTitle}. " .
                ($isResolved ? "Resolved issue details and facts." : "Current controversy developments and information.");
        }

        // Add resolution status to title
        if ($isResolved) {
            $currentTitle .= " [Resolved]";
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
            ->set('article:published_time', $this->controversy->created_at->toISOString())
            ->set('article:modified_time', $this->controversy->updated_at->toISOString())
            ->set('article:author', $personName)
            ->set('article:section', 'Controversy')
            ->set('article:tag', $this->getControversyTags());

        // Add profession-specific Open Graph tags
        if ($primaryProfession) {
            Meta::set('profile:username', $personName)
                ->set('profile:gender', $this->person->gender ?? '')
                ->set('job_title', $primaryProfession)
                ->set('business:contact_data:job_title', $primaryProfession);
        }

        // Twitter Card Tags
        Meta::set('twitter:card', 'summary_large_image')
            ->set('twitter:title', $currentTitle)
            ->set('twitter:description', $currentDescription)
            ->set('twitter:site', '@' . config('app.name', 'WikiLife'))
            ->set('twitter:creator', '@' . config('app.name', 'WikiLife'))
            ->set('twitter:label1', 'Person')
            ->set('twitter:data1', $personName)
            ->set('twitter:label2', 'Status')
            ->set('twitter:data2', $isResolved ? 'Resolved' : 'Ongoing');

        // Additional professional meta tags
        Meta::set('author', $personName)
            ->set('publisher', config('app.name', 'WikiLife'))
            ->set('content-type', 'article')
            ->set('content-language', app()->getLocale())
            ->set('audience', 'all')
            ->set('rating', 'mature') // Controversial content
            ->set('distribution', 'global')
            ->set('revisit-after', '30 days')
            ->set('googlebot', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')
            ->set('bingbot', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1');

        // Controversy-specific meta
        Meta::set('controversy:status', $isResolved ? 'resolved' : 'ongoing')
            ->set('controversy:date', $this->controversy->date?->format('Y-m-d'))
            ->set('content_category', 'controversy');

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
        // Use person's profile image for controversy
        $image = $this->person->profile_image_url ?? default_image(1200, 630);

        $imageWidth = 1200;
        $imageHeight = 630;

        Meta::set('og:image', $image)
            ->set('og:image:width', $imageWidth)
            ->set('og:image:height', $imageHeight)
            ->set('og:image:alt', "{$this->controversy->title} - {$this->person->display_name}")
            ->set('og:image:type', 'image/jpeg')
            ->set('twitter:image', $image)
            ->set('twitter:image:alt', "{$this->controversy->title} - {$this->person->display_name}");

        // Additional image meta for search engines
        Meta::set('image', $image)
            ->set('thumbnail', $image);
    }

    protected function setSchemaMetaTags()
    {
        $personName = $this->person->display_name;
        $primaryProfession = $this->person->primary_profession;

        // Additional schema-specific meta tags
        Meta::set('news_keywords', $this->getNewsKeywords())
            ->set('standout', $this->getCanonicalUrl());

        // Controversy-specific meta
        Meta::set('article:content_tier', 'free')
            ->set('article:opinion', 'false')
            ->set('article:family_friendly', 'no') // Controversial content
            ->set('content_warning', 'controversy');

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
        $personName = $this->person->display_name;
        $primaryProfession = $this->person->primary_profession;
        $professions = $this->person->professions ?? [];
        $controversyTitle = $this->controversy->title;
        $isResolved = $this->controversy->is_resolved;
        $nationality = $this->person->nationality;

        $baseKeywords = [
            $personName,
            $controversyTitle,
            'controversy',
            'scandal',
            'issue',
            'dispute',
            'controversial',
            'allegations',
            'facts',
            'truth'
        ];

        // Add profession-based keywords
        if ($primaryProfession) {
            $baseKeywords[] = $primaryProfession;
            $baseKeywords[] = "{$primaryProfession} controversy";
            $baseKeywords[] = "{$primaryProfession} scandal";
            $baseKeywords[] = "famous {$primaryProfession}";
            $baseKeywords[] = "{$primaryProfession} news";
            $baseKeywords[] = "celebrity {$primaryProfession}";
        }

        // Add all professions as keywords
        foreach ($professions as $profession) {
            if ($profession !== $primaryProfession) {
                $baseKeywords[] = $profession;
                $baseKeywords[] = "{$profession} controversy";
            }
        }

        // Add nationality keywords
        if ($nationality) {
            $baseKeywords[] = $nationality;
            $baseKeywords[] = "{$nationality} {$primaryProfession}";
        }

        // Add resolution status keywords
        if ($isResolved) {
            $baseKeywords[] = 'resolved controversy';
            $baseKeywords[] = 'settled dispute';
            $baseKeywords[] = 'closed case';
        } else {
            $baseKeywords[] = 'ongoing controversy';
            $baseKeywords[] = 'current scandal';
            $baseKeywords[] = 'active dispute';
        }

        // Add title words as keywords
        $titleWords = explode(' ', $controversyTitle);
        $baseKeywords = array_merge($baseKeywords, array_slice($titleWords, 0, 5));

        // Add industry-specific keywords based on profession
        $industryKeywords = $this->getIndustryKeywords($primaryProfession);
        $baseKeywords = array_merge($baseKeywords, $industryKeywords);

        // Add controversy-specific keywords
        $controversyKeywords = $this->getControversyKeywords();
        $baseKeywords = array_merge($baseKeywords, $controversyKeywords);

        // Add current year for timeliness
        $baseKeywords[] = date('Y');
        $baseKeywords[] = date('Y') . ' controversy';

        return implode(', ', array_unique(array_filter($baseKeywords)));
    }

    private function getNewsKeywords()
    {
        $personName = $this->person->display_name;
        $primaryProfession = $this->person->primary_profession;
        $controversyTitle = $this->controversy->title;

        $newsKeywords = [
            $personName,
            $primaryProfession,
            $controversyTitle,
            'breaking news',
            'controversy',
            'scandal',
            'exclusive'
        ];

        return implode(', ', array_unique(array_filter($newsKeywords)));
    }

    private function getControversyKeywords()
    {
        return [
            'allegations',
            'accusations',
            'investigation',
            'legal issues',
            'public opinion',
            'media coverage',
            'social media',
            'backlash',
            'criticism',
            'defense',
            'response',
            'statement',
            'evidence',
            'proof',
            'denial',
            'admission'
        ];
    }

    private function getControversyTags()
    {
        $tags = [
            'controversy',
            $this->controversy->is_resolved ? 'resolved' : 'ongoing',
            $this->person->display_name
        ];

        if ($this->person->primary_profession) {
            $tags[] = $this->person->primary_profession;
        }

        return implode(', ', $tags);
    }

    private function getIndustryKeywords($profession)
    {
        $industryMap = [
            'actor' => ['hollywood scandal', 'film industry controversy', 'celebrity gossip', 'entertainment news'],
            'singer' => ['music industry scandal', 'celebrity musician', 'music controversy', 'concert incident'],
            'politician' => ['political scandal', 'government controversy', 'public figure', 'election issue'],
            'athlete' => ['sports scandal', 'athlete controversy', 'sports news', 'team dispute'],
            'business' => ['corporate scandal', 'business controversy', 'executive dispute', 'company issue'],
            'doctor' => ['medical scandal', 'healthcare controversy', 'doctor dispute', 'medical ethics'],
            'scientist' => ['research scandal', 'academic controversy', 'scientific dispute', 'research ethics'],
            'artist' => ['art world scandal', 'creative controversy', 'artist dispute', 'exhibition issue'],
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
        return LaravelURL::route('people.controversies.show', [
            'personSlug' => $this->personSlug,
            'slug' => $this->slug
        ]);
    }

    public function getStructuredData()
    {
        $schemas = [
            $this->getPersonStructuredData(),
            $this->getBreadcrumbStructuredData(),
            $this->getArticleStructuredData()
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
        $personName = $this->person->display_name;
        $primaryProfession = $this->person->primary_profession;

        $personSchema = [
            "@context" => "https://schema.org",
            "@type" => "Person",
            "@id" => LaravelURL::route('people.people.show', $this->personSlug) . "#person",
            "name" => $personName,
            "description" => $this->person->about ? strip_tags($this->person->about) : "Professional {$primaryProfession}",
            "url" => LaravelURL::route('people.people.show', $this->personSlug),
            "image" => $this->person->profile_image_url ?: default_image(400, 400),
            "mainEntityOfPage" => LaravelURL::route('people.people.show', $this->personSlug)
        ];

        // Add profession/occupation
        if ($primaryProfession) {
            $personSchema["hasOccupation"] = [
                "@type" => "Occupation",
                "name" => $primaryProfession
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
                    "name" => $this->person->display_name,
                    "item" => LaravelURL::route('people.people.show', $this->personSlug)
                ],
                [
                    "@type" => "ListItem",
                    "position" => 4,
                    "name" => "Controversies",
                    "item" => LaravelURL::route('people.details.tab', [
                        'slug' => $this->personSlug,
                        'tab'  => 'controversies'
                    ])
                ],
                [
                    "@type" => "ListItem",
                    "position" => 5,
                    "name" => $this->controversy->title,
                    "item" => $this->getCanonicalUrl()
                ]
            ]
        ];

        return json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    private function getArticleStructuredData()
    {
        $personName = $this->person->display_name;
        $primaryProfession = $this->person->primary_profession;

        $articleSchema = [
            "@context" => "https://schema.org",
            "@type" => "Article",
            "headline" => $this->controversy->title,
            "description" => $this->controversy->excerpt,
            "datePublished" => $this->controversy->created_at->toIso8601String(),
            "dateModified" => $this->controversy->updated_at->toIso8601String(),
            "author" => [
                "@type" => "Organization",
                "name" => config('app.name', 'WikiLife')
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => config('app.name', 'WikiLife'),
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
            "articleSection" => "Controversy",
            "keywords" => $this->getKeywords(),
            "inLanguage" => app()->getLocale(),
            "copyrightYear" => $this->controversy->created_at->year,
            "contentRating" => "R",
            "isFamilyFriendly" => false
        ];

        // Add about property referencing the person
        $articleSchema["about"] = [
            "@type" => "Person",
            "@id" => LaravelURL::route('people.people.show', $this->personSlug) . "#person",
            "name" => $personName,
            "url" => LaravelURL::route('people.people.show', $this->personSlug)
        ];

        // Add profession to about if available
        if ($primaryProfession) {
            $articleSchema["about"]["jobTitle"] = $primaryProfession;
        }

        // Add image if available
        $image = $this->person->profile_image_url;
        if ($image) {
            $articleSchema["image"] = [
                "@type" => "ImageObject",
                "url" => $image,
                "width" => "800",
                "height" => "600",
                "caption" => "{$this->controversy->title} - {$personName}"
            ];
        }

        // Add controversy status
        if ($this->controversy->is_resolved) {
            $articleSchema["creativeWorkStatus"] = "Resolved";
        }

        return json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function render()
    {
        // Ensure meta tags are set during render
        $this->setMetaTags();

        return view('livewire.front.person.controversy-details', [
            'structuredData' => $this->getStructuredData(),
        ]);
    }
}
