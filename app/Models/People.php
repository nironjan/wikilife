<?php

namespace App\Models;

use App\Jobs\GenerateSitemapJob;
use App\Services\ImageKitService;
use App\Services\SitemapService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class People extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'full_name',
        'nicknames',
        'cover_image',
        'cover_img_caption',
        'cover_file_id',
        'profile_image',
        'profile_image_caption',
        'profile_img_file_id',
        'about',
        'early_life',
        'gender',
        'birth_date',
        'death_date',
        'place_of_birth',
        'place_of_death',
        'death_cause',
        'age',
        'hometown',
        'address',
        'state_code',
        'nationality',
        'religion',
        'caste',
        'ethnicity',
        'zodiac_sign',
        'blood_group',
        'professions',
        'physical_stats',
        'favourite_things',
        'references',
        'view_count',
        'like_count',
        'comment_count',
        'follower_count',
        'status',
        'created_by',
        'verified',
    ];

    protected $casts = [
        'nicknames' => 'array',
        'professions' => 'array',
        'physical_stats' => 'array',
        'favourite_things' => 'array',
        'references' => 'array',
        'birth_date' => 'date',
        'death_date' => 'date',
        'verified'  => 'boolean',
    ];



    // ==========SITEMAP====================

    /**
     * The "booted" method of the model
     */

    protected static function booted(){
        //Generate sitemap when a person is created
        static::created(function ($person){
            if($person->status === 'active' && $person->verified){
                app(SitemapService::class)->generateSitemap();
            }
        });

        // Generate sitemap when a person is updated
        static::updated(function ($person){
            if($person->isDirty(['status', 'verified', 'slug']) && $person->status === 'active' && $person->verified){
                app(SitemapService::class)->generateSitemap();
            }
        });

        // generate sitemap when a person is deleted
        static::deleted(function(){
            app(SitemapService::class)->generateSitemap();
        });
    }

    // ====================== ATTRIBUTES =====================

    public function getStateNameAttribute()
    {
        return config('indian_states.all.' . $this->state_code);
    }


    public function getDisplayNameAttribute()
    {
        return $this->full_name ?: $this->name;
    }

    public function getAgeAttribute(): ?int
    {

        if (!$this->birth_date)
            return null;

        if ($this->death_date) {
            return $this->birth_date->diffInYears($this->death_date);
        }
        return $this->birth_date->age;
    }

    public function getIsAliveAttribute()
    {
        return is_null($this->death_date);
    }

    public function getPrimaryProfessionAttribute(): ?string
    {
        return $this->professions ? $this->professions[0] ?? null : null;
    }

    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image ?? null;
    }

    public function setProfessionsAttribute($value)
    {
        if (is_array($value)) {
            // convert all profession value to lowercase
            $value = array_map('strtolower', $value);
            $value = array_unique(array_filter($value));
        }
        $this->attributes['professions'] = json_encode($value);
    }

    public function getProfessionsAttribute($value)
    {
        $professions = json_decode($value, true) ?? [];
        // Return with proper capitalization for display
        return array_map('ucwords', $professions);
    }

    public function setNicknamesAttribute($value)
    {
        if (is_array($value)) {
            // convert all nicknames value to lowercase
            $value = array_map('strtolower', $value);
        }
        $this->attributes['nicknames'] = json_encode($value);
    }


        public function getNicknamesAttribute($value)
    {
        $nicknames = json_decode($value, true) ?? [];
        // Return with proper capitalization for display
        return array_map('ucwords', $nicknames);
    }


    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image ?? null;
    }


    /**
     * Get image with custom size using ImageKitService
     * Usage: $product->imageSize(400, 300) or $product->imageSize(800)
     */
    public function imageSize($width = null, $height = null, $quality = null): ?string
    {
        if (!$this->profile_image_url) {
            return null;
        }

        // If no custom size requested, return original optimized image
        if (!$width && !$height && !$quality) {
            return $this->profile_image_url;
        }

        // Use ImageKitService to generate custom size
        return app(ImageKitService::class)->getUrlWithTransformations(
            $this->extractBaseFilePath($this->profile_image_url),
            $width,
            $height,
            $quality
        );
    }

    /**
     * Extract base file path from ImageKit URL
     */
    protected function extractBaseFilePath($imageKitUrl): ?string
    {
        try {
            $parsedUrl = parse_url($imageKitUrl);

            if (!isset($parsedUrl['path'])) {
                return null;
            }

            $path = $parsedUrl['path'];

            // Remove transformation part to get base path
            if (str_contains($path, '/tr:')) {
                $parts = explode('/tr:', $path, 2);
                if (isset($parts[1])) {
                    $transformationAndPath = $parts[1];
                    $firstSlash = strpos($transformationAndPath, '/');
                    if ($firstSlash !== false) {
                        return substr($transformationAndPath, $firstSlash);
                    }
                }
            }

            return $path;

        } catch (\Exception $e) {
            return null;
        }
    }


    // ===================== SCOPES ========================

    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVerified(Builder $query)
    {
        return $query->where('verified', true);
    }

    public function scopeAlive(Builder $query)
    {
        return $query->whereNull('death_date');
    }

    public function scopeDeceased(Builder $query)
    {
        $query->whereNull('death_date');
    }

    public function scopeByProfession(Builder $query, string $profession)
    {
        return $query->whereJsonContains('professions', strtolower($profession));
    }

    public function scopePopular(Builder $query, int $minViews = 1000)
    {
        return $query->where('view_count', '>=', $minViews);
    }

    public function scopeBornOn(Builder $query, Carbon $date)
    {
        return $query->whereDay('birth_date', $date->day)
            ->whereMonth('birth_date', $date->month);
    }

    public function scopeSearch(Builder $query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
                ->orWhere('full_name', 'LIKE', "%{$term}%")
                ->orWhereJsonContains('nicknames', $term);
        });
    }

    public function scopeByProfessionCategory(Builder $query, string $category)
    {
        $professions = config("professions.categories.{$category}.professions", []);

        if (empty($professions)) {
            return $query;
        }

        return $query->where(function ($q) use ($professions) {
            foreach ($professions as $profession) {
                $q->orWhereJsonContains('professions', $profession)
                    ->orWhereJsonContains('professions', strtolower($profession))
                    ->orWhereJsonContains('professions', ucwords($profession));
            }
        });
    }

    /**
     * NEW: Scope for multiple profession categories
     * Usage: People::byProfessionCategories(['entrepreneur', 'engineer'])->get()
     */
    public function scopeByProfessionCategories($query, array $categories)
    {
        return $query->where(function ($q) use ($categories) {
            foreach ($categories as $category) {
                $professions = config("professions.{$category}", []);

                if (!empty($professions)) {
                    $q->orWhere(function ($subQuery) use ($professions) {
                        foreach ($professions as $profession) {
                            $subQuery->orWhereJsonContains('professions', $profession);
                        }
                    });
                }
            }
        });
    }

    /**
     * NEW: Scope to exclude profession categories
     * Usage: People::excludeProfessionCategory('engineer')->get()
     */
    public function scopeExcludeProfessionCategory($query, $category)
    {
        $professions = config("professions.{$category}", []);

        if (empty($professions)) {
            return $query;
        }

        return $query->where(function ($q) use ($professions) {
            foreach ($professions as $profession) {
                $q->whereJsonDoesntContain('professions', $profession);
            }
        });
    }

    // ================== RELATIONSHIPS ======================

    public function latestUpdates(){
        return $this->hasMany(LatestUpdate::class, 'person_id')
            ->published()
            ->approved()
            ->latestFirst()
            ->orderBy('sort_order');
    }

    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function assets()
    {
        return $this->hasOne(Assets::class, 'person_id');
    }

    public function mediaProfile()
    {
        return $this->hasOne(MediaProfile::class, 'person_id');
    }

    public function socialLinks()
    {
        return $this->hasMany(SocialLinks::class, 'person_id');
    }

    public function speechesInterviews()
    {
        return $this->hasMany(SpeechesInterview::class, 'person_id');
    }

    public function controversies()
    {
        return $this->hasMany(Controversy::class, 'person_id');
    }

    public function lesserKnownFacts()
    {
        return $this->hasMany(LesserKnownFact::class, 'person_id');
    }

    public function photos()
    {
        return $this->hasMany(PersonPhoto::class,'person_id');
    }

    public function relations()
    {
        return $this->hasMany(PersonRelations::class, 'person_id');
    }

    public function educations()
    {
        return $this->hasMany(PersonEducation::class, 'person_id')->orderBy('sort_order');
    }

    public function awards()
    {
        return $this->hasMany(PersonAward::class, 'person_id')->orderBy('sort_order');
    }

    public function filmography()
    {
        return $this->hasMany(Filmography::class, 'person_id')->orderBy('sort_order');
    }

    public function politicalCareers()
    {
        return $this->hasMany(Politician::class, 'person_id')->orderBy('sort_order');
    }

    public function sportsCareers()
    {
        return $this->hasMany(SportsCareer::class, 'person_id' )->orderBy('sort_order');
    }

    public function entrepreneurs()
    {
        return $this->hasMany(Entrepreneur::class, 'person_id')->orderBy('sort_order');
    }

    public function literatureCareer()
    {
        return $this->hasMany(LiteratureCareer::class, 'person_id')->orderBy('sort_order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'people_id');
    }


    // ============== CUSTOM METHODS =====================

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function getSpouse()
    {
        return $this->relations()
            ->where('relation_type', 'spouse')
            ->where('is_reciprocal', true)
            ->first();
    }

    public function getChildren()
    {
        return $this->relations()->where('relation_type', 'child');
    }

    public function hasProfession(string $profession)
    {
        return in_array($profession, $this->professions ?? []);
    }

    public function getActiveSocialLinks()
    {
        return $this->socialLinks()->whereNotNull('url')->get();
    }


}
