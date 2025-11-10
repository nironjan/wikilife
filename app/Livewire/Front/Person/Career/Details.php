<?php

namespace App\Livewire\Front\Person\Career;

use App\Models\People;
use App\Models\Politician;
use App\Models\Filmography;
use App\Models\SportsCareer;
use App\Models\Entrepreneur;
use App\Models\LiteratureCareer;
use F9Web\Meta\Meta;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\URL as LaravelURL;

#[Layout('components.layouts.front')]
#[Title('Career Details')]
class Details extends Component
{
    public People $person;
    public $slug;
    public $careerSlug;
    public $careerType;
    public $careerData;

    public function mount($personSlug, $slug)
    {
        $this->slug = $personSlug;
        $this->careerSlug = $slug;
        $this->loadPerson();
        $this->loadCareerData();
        $this->setMetaTags();

        $this->dispatch('page-loaded', slug: $personSlug);
    }

    public function loadPerson()
    {
        $this->person = People::active()
            ->verified()
            ->where('slug', $this->slug)
            ->firstOrFail();
    }

    public function loadCareerData()
    {
        // Try to find career in each model
        $career = $this->findCareerInAllTypes();

        if (!$career) {
            abort(404, 'Career not found');
        }

        $this->careerData = $career['data'];
        $this->careerType = $career['type'];
    }

    private function findCareerInAllTypes()
    {
        $models = [
            'politics' => Politician::class,
            'film' => Filmography::class,
            'sports' => SportsCareer::class,
            'business' => Entrepreneur::class,
            'literature' => LiteratureCareer::class,
        ];

        foreach ($models as $type => $modelClass) {
            $career = $modelClass::where('slug', $this->careerSlug)
                ->where('person_id', $this->person->id)
                ->first();

            if ($career) {
                return [
                    'type' => $type,
                    'data' => $career
                ];
            }
        }

        return null;
    }

    protected function setMetaTags()
    {
        $personName = $this->person->display_name;
        $primaryProfession = $this->person->primary_profession;
        $professions = $this->person->professions ?? [];
        $careerTitle = $this->getCareerTitle();
        $careerMeta = $this->getCareerMetaInfo();
        $dateInfo = $this->getDateInfo();

        // Base title and description
        $baseTitle = "{$careerTitle} - {$personName} Career Details";
        $baseDescription = "Comprehensive career information about {$personName}'s role as {$careerTitle}" .
            ($primaryProfession ? ", the renowned {$primaryProfession}" : "") .
            ". " . $this->getCareerDescription();

        // Enhanced title with profession context
        $currentTitle = $baseTitle;
        $currentDescription = $baseDescription;

        // Add profession and career type context
        if ($primaryProfession) {
            $currentTitle = "{$careerTitle} - {$personName} ({$primaryProfession}) Career";
            $currentDescription = "Detailed career profile of {$primaryProfession} {$personName} as {$careerTitle}. " .
                $this->getCareerDescription();
        }

        // Add date context if available
        if ($dateInfo['duration']) {
            $currentDescription .= " Career duration: {$dateInfo['duration']}.";
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
            ->set('article:published_time', $this->careerData->created_at->toISOString())
            ->set('article:modified_time', $this->careerData->updated_at->toISOString())
            ->set('article:author', $personName)
            ->set('article:section', $careerMeta['type_name'])
            ->set('article:tag', $this->getCareerTags());

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
            ->set('twitter:label1', 'Career Type')
            ->set('twitter:data1', $careerMeta['type_name'])
            ->set('twitter:label2', 'Duration')
            ->set('twitter:data2', $dateInfo['duration'] ?? 'Ongoing');

        // Additional professional meta tags
        Meta::set('author', $personName)
            ->set('publisher', config('app.name', 'WikiLife'))
            ->set('content-type', 'article')
            ->set('content-language', app()->getLocale())
            ->set('audience', 'all')
            ->set('rating', 'general')
            ->set('distribution', 'global')
            ->set('revisit-after', '30 days')
            ->set('googlebot', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')
            ->set('bingbot', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1');

        // Career-specific meta
        Meta::set('career:type', $this->careerType)
            ->set('career:status', $dateInfo['is_current'] ? 'current' : 'past')
            ->set('career:start_date', $dateInfo['start']?->format('Y-m-d'))
            ->set('career:end_date', $dateInfo['end']?->format('Y-m-d'))
            ->set('content_category', 'career');

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
        // Use person's profile image for career
        $image = $this->person->profile_image_url ?? asset('images/career-og.jpg');

        $imageWidth = 1200;
        $imageHeight = 630;

        Meta::set('og:image', $image)
            ->set('og:image:width', $imageWidth)
            ->set('og:image:height', $imageHeight)
            ->set('og:image:alt', "{$this->getCareerTitle()} - {$this->person->display_name}")
            ->set('og:image:type', 'image/jpeg')
            ->set('twitter:image', $image)
            ->set('twitter:image:alt', "{$this->getCareerTitle()} - {$this->person->display_name}");

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

        // Career-specific meta
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
        $personName = $this->person->display_name;
        $primaryProfession = $this->person->primary_profession;
        $professions = $this->person->professions ?? [];
        $careerTitle = $this->getCareerTitle();
        $careerMeta = $this->getCareerMetaInfo();
        $dateInfo = $this->getDateInfo();
        $nationality = $this->person->nationality;

        $baseKeywords = [
            $personName,
            $careerTitle,
            strtolower($careerMeta['type_name']),
            'career',
            'professional',
            'occupation',
            'work history',
            'career details',
            'professional life'
        ];

        // Add profession-based keywords
        if ($primaryProfession) {
            $baseKeywords[] = $primaryProfession;
            $baseKeywords[] = "{$primaryProfession} career";
            $baseKeywords[] = "professional {$primaryProfession}";
            $baseKeywords[] = "famous {$primaryProfession}";
            $baseKeywords[] = "celebrity {$primaryProfession}";
        }

        // Add all professions as keywords
        foreach ($professions as $profession) {
            if ($profession !== $primaryProfession) {
                $baseKeywords[] = $profession;
                $baseKeywords[] = "{$profession} career";
            }
        }

        // Add career-specific fields as keywords
        if ($careerMeta['primary_field']) {
            $baseKeywords[] = $careerMeta['primary_field'];
        }
        if ($careerMeta['secondary_field']) {
            $baseKeywords[] = $careerMeta['secondary_field'];
        }

        // Add nationality keywords
        if ($nationality) {
            $baseKeywords[] = $nationality;
            $baseKeywords[] = "{$nationality} {$primaryProfession}";
        }

        // Add career status keywords
        if ($dateInfo['is_current']) {
            $baseKeywords[] = 'current position';
            $baseKeywords[] = 'present role';
            $baseKeywords[] = 'ongoing career';
        } else {
            $baseKeywords[] = 'past career';
            $baseKeywords[] = 'former position';
            $baseKeywords[] = 'previous role';
        }

        // Add duration keywords
        if ($dateInfo['duration']) {
            $baseKeywords[] = $dateInfo['duration'];
            $baseKeywords[] = 'career timeline';
        }

        // Add title words as keywords
        $titleWords = explode(' ', $careerTitle);
        $baseKeywords = array_merge($baseKeywords, array_slice($titleWords, 0, 5));

        // Add industry-specific keywords based on career type
        $industryKeywords = $this->getIndustryKeywords($this->careerType);
        $baseKeywords = array_merge($baseKeywords, $industryKeywords);

        // Add current year for timeliness
        $baseKeywords[] = date('Y');
        $baseKeywords[] = date('Y') . ' career';

        return implode(', ', array_unique(array_filter($baseKeywords)));
    }

    private function getNewsKeywords()
    {
        $personName = $this->person->display_name;
        $primaryProfession = $this->person->primary_profession;
        $careerTitle = $this->getCareerTitle();
        $careerMeta = $this->getCareerMetaInfo();

        $newsKeywords = [
            $personName,
            $primaryProfession,
            $careerTitle,
            $careerMeta['type_name'],
            'career news',
            'professional update',
            'career profile'
        ];

        return implode(', ', array_unique(array_filter($newsKeywords)));
    }

    private function getCareerTags()
    {
        $careerMeta = $this->getCareerMetaInfo();
        $tags = [
            'career',
            strtolower($careerMeta['type_name']),
            $this->person->display_name
        ];

        if ($this->person->primary_profession) {
            $tags[] = $this->person->primary_profession;
        }

        if ($careerMeta['primary_field']) {
            $tags[] = $careerMeta['primary_field'];
        }

        return implode(', ', $tags);
    }

    private function getIndustryKeywords($careerType)
    {
        $industryMap = [
            'politics' => ['government', 'politics', 'public service', 'election', 'legislation', 'political career', 'public office'],
            'film' => ['entertainment', 'movies', 'cinema', 'hollywood', 'bollywood', 'film industry', 'acting', 'directing', 'production'],
            'sports' => ['athletics', 'sports', 'competition', 'team', 'championship', 'athlete', 'sports career', 'professional sports'],
            'business' => ['corporate', 'business', 'entrepreneurship', 'company', 'executive', 'corporate career', 'business leadership'],
            'literature' => ['writing', 'literature', 'books', 'author', 'publishing', 'literary career', 'writing career', 'authorship'],
        ];

        return $industryMap[$careerType] ?? [];
    }

    private function getCareerDescription()
    {
        $careerMeta = $this->getCareerMetaInfo();
        $dateInfo = $this->getDateInfo();

        $description = "Explore detailed information about this {$careerMeta['type_name']} role";

        if ($careerMeta['primary_field']) {
            $description .= " in {$careerMeta['primary_field']}";
        }

        if ($careerMeta['secondary_field']) {
            $description .= " focusing on {$careerMeta['secondary_field']}";
        }

        if ($dateInfo['duration']) {
            $description .= ". Career spanned {$dateInfo['duration']}";
        }

        if ($dateInfo['is_current']) {
            $description .= ". Currently active position";
        }

        return $description . ".";
    }

    private function getCanonicalUrl()
    {
        return LaravelURL::route('people.career.show', [
            'personSlug' => $this->slug,
            'slug' => $this->careerSlug
        ]);
    }

    public function getStructuredData()
    {
        $personName = $this->person->display_name;
        $primaryProfession = $this->person->primary_profession;
        $careerTitle = $this->getCareerTitle();
        $careerMeta = $this->getCareerMetaInfo();
        $dateInfo = $this->getDateInfo();

        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Article",
            "headline" => $careerTitle,
            "description" => $this->getCareerDescription(),
            "datePublished" => $this->careerData->created_at->toIso8601String(),
            "dateModified" => $this->careerData->updated_at->toIso8601String(),
            "author" => [
                "@type" => "Person",
                "name" => $personName,
                "url" => LaravelURL::route('people.people.show', $this->slug)
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
            "articleSection" => $careerMeta['type_name'],
            "keywords" => $this->getKeywords(),
            "inLanguage" => app()->getLocale(),
            "copyrightYear" => $this->careerData->created_at->year,
            "isAccessibleForFree" => true,
            "isFamilyFriendly" => true
        ];

        // Add profession to author if available
        if ($primaryProfession) {
            $schema["author"]["jobTitle"] = $primaryProfession;
        }

        // Add career-specific properties
        $schema["about"] = [
            "@type" => "Person",
            "name" => $personName,
            "hasOccupation" => [
                "@type" => "Occupation",
                "name" => $careerTitle,
                "description" => $this->getCareerDescription()
            ]
        ];

        // Add duration information
        if ($dateInfo['start']) {
            $schema["about"]["hasOccupation"]["startDate"] = $dateInfo['start']->format('Y-m-d');
        }
        if ($dateInfo['end']) {
            $schema["about"]["hasOccupation"]["endDate"] = $dateInfo['end']->format('Y-m-d');
        }

        // Add image if available
        $image = $this->person->profile_image_url;
        if ($image) {
            $schema["image"] = [
                "@type" => "ImageObject",
                "url" => $image,
                "width" => "800",
                "height" => "600",
                "caption" => "{$careerTitle} - {$personName}"
            ];
        }

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    // ============ ORIGINAL METHODS FROM YOUR COMPONENT ============

    private function getRelatedCareers()
    {
        $models = [
            'politics' => Politician::class,
            'film' => Filmography::class,
            'sports' => SportsCareer::class,
            'business' => Entrepreneur::class,
            'literature' => LiteratureCareer::class,
        ];

        $modelClass = $models[$this->careerType] ?? null;

        if (!$modelClass) {
            return collect();
        }

        return $modelClass::where('person_id', $this->person->id)
            ->where('slug', '!=', $this->careerSlug)
            ->where(function($query) use ($modelClass) {
                // Handle active status based on model
                if (method_exists($modelClass, 'scopeActive')) {
                    $query->active();
                } else if (method_exists($modelClass, 'scopeVerified')) {
                    $query->verified();
                }
            })
            ->orderBy('sort_order')
            ->take(3)
            ->get();
    }

    private function getCareerTitle()
    {
        return match($this->careerType) {
            'politics' => $this->careerData->position,
            'film' => $this->careerData->movie_title,
            'sports' => $this->careerData->position . ' - ' . $this->careerData->sport,
            'business' => $this->careerData->role . ' at ' . $this->careerData->company_name,
            'literature' => $this->careerData->title,
            default => 'Career Details'
        };
    }

    private function getCareerMetaInfo()
    {
        return match($this->careerType) {
            'politics' => [
                'icon' => '🏛️',
                'color' => 'blue',
                'type_name' => 'Political Career',
                'primary_field' => $this->careerData->political_party,
                'secondary_field' => $this->careerData->constituency,
            ],
            'film' => [
                'icon' => '🎬',
                'color' => 'purple',
                'type_name' => 'Film Career',
                'primary_field' => $this->careerData->profession_type,
                'secondary_field' => $this->careerData->industry,
            ],
            'sports' => [
                'icon' => '⚽',
                'color' => 'green',
                'type_name' => 'Sports Career',
                'primary_field' => $this->careerData->sport,
                'secondary_field' => $this->careerData->team,
            ],
            'business' => [
                'icon' => '💼',
                'color' => 'indigo',
                'type_name' => 'Business Career',
                'primary_field' => $this->careerData->company_name,
                'secondary_field' => $this->careerData->industry,
            ],
            'literature' => [
                'icon' => '📚',
                'color' => 'yellow',
                'type_name' => 'Literary Career',
                'primary_field' => $this->careerData->work_type,
                'secondary_field' => $this->careerData->genre,
            ],
            default => [
                'icon' => '💼',
                'color' => 'gray',
                'type_name' => 'Career',
                'primary_field' => '',
                'secondary_field' => '',
            ]
        };
    }

    private function getRelatedCareerTitle($relatedCareer)
    {
        return match($this->careerType) {
            'politics' => $relatedCareer->position,
            'film' => $relatedCareer->movie_title,
            'sports' => $relatedCareer->position . ' - ' . $relatedCareer->sport,
            'business' => $relatedCareer->role . ' at ' . $relatedCareer->company_name,
            'literature' => $relatedCareer->title,
            default => 'Career'
        };
    }

    private function getRelatedCareerDate($relatedCareer)
    {
        return match($this->careerType) {
            'politics' => $relatedCareer->tenure_start ? $relatedCareer->tenure_start->format('Y') : 'N/A',
            'film' => $relatedCareer->release_year ?? 'N/A',
            'sports' => $relatedCareer->debut_date ? $relatedCareer->debut_date->format('Y') : 'N/A',
            'business' => $relatedCareer->founding_date ? $relatedCareer->founding_date->format('Y') : 'N/A',
            'literature' => $relatedCareer->publishing_year ?? 'N/A',
            default => 'N/A'
        };
    }

    private function getDateInfo()
    {
        return match($this->careerType) {
            'politics' => [
                'start' => $this->careerData->tenure_start,
                'end' => $this->careerData->tenure_end,
                'duration' => $this->careerData->tenureDuration,
                'is_current' => $this->careerData->is_current,
            ],
            'film' => [
                'start' => $this->careerData->release_date,
                'end' => null,
                'duration' => $this->careerData->release_year,
                'is_current' => false,
            ],
            'sports' => [
                'start' => $this->careerData->debut_date,
                'end' => $this->careerData->retirement_date,
                'duration' => $this->careerData->careerDuration,
                'is_current' => $this->careerData->is_active,
            ],
            'business' => [
                'start' => $this->careerData->founding_date ?? $this->careerData->joining_date,
                'end' => $this->careerData->exit_date,
                'duration' => $this->careerData->company_age ? $this->careerData->company_age . ' years' : null,
                'is_current' => !$this->careerData->exit_date,
            ],
            'literature' => [
                'start' => $this->careerData->start_date,
                'end' => $this->careerData->end_date,
                'duration' => $this->careerData->careerDuration,
                'is_current' => $this->careerData->is_active,
            ],
            default => [
                'start' => null,
                'end' => null,
                'duration' => null,
                'is_current' => false,
            ]
        };
    }

    public function render()
    {
        // Ensure meta tags are set during render
        $this->setMetaTags();

        $meta = $this->getCareerMetaInfo();
        $dateInfo = $this->getDateInfo();
        $relatedCareers = $this->getRelatedCareers();

        return view('livewire.front.person.career.details', compact('meta', 'dateInfo', 'relatedCareers'))->with([
            'structuredData' => $this->getStructuredData(),
        ]);
    }
}
