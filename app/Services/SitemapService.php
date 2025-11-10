<?php

namespace App\Services;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Controversy;
use App\Models\Entrepreneur;
use App\Models\LatestUpdate;
use App\Models\LiteratureCareer;
use App\Models\Page;
use App\Models\People;
use App\Models\Politician;
use App\Models\SportsCareer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class SitemapService{

    public function generateSitemap(){
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Add static pages
        $sitemap .= $this->addStaticPages();

        // Add dynamic pages
        $sitemap .= $this->addDynamicPages();

        // Add profession pages
        $sitemap .= $this->addProfessionPages();


        // Add people pages
        $sitemap .= $this->addPeoplePages();

        // Add blog article and categories
        $sitemap .= $this->addBlogPages();

        // Add Latest updates, controversy, career...
        $sitemap .= $this->addDynamicContentPages();

        $sitemap .= '</urlset>';

        // Save the sitemap
        Storage::disk('public')->put('sitemap.xml', $sitemap);

        return true;
    }

    protected function addStaticPages(){
        $staticPages = [
            '/' => ['changefreq' => 'daily', 'priority' => '1.0'],
            '/born-today' => ['changefreq' => 'daily', 'priority' => '0.8'],
        ];

        $xml = '';
        foreach($staticPages as $page => $config){
            $xml .= $this->urlToXml(
                url($page),
                Carbon::now()->toDateString(),
                $config['changefreq'],
            $config['priority']
        );
        }
        return $xml;
    }


    protected function addDynamicPages(){
        $xml = '';

        // Get all published pages
        $pages = Page::published()
            ->select(['slug', 'updated_at', 'published_at', 'template'])
            ->orderBy('published_at', 'desc')
            ->get();

        foreach ($pages as $page) {
            // Determine priority based on template type
            $priority = $this->getPagePriority($page->template);

            // Determine change frequency based on last update
            $changeFreq = $this->getPageChangeFrequency($page->updated_at);

            $xml .= $this->urlToXml(
                route('page.details', $page->slug),
                $page->updated_at->toDateString(),
                $changeFreq,
                $priority
            );
        }

        return $xml;
    }

    protected function getPagePriority($template)
    {
        // Assign priorities based on template importance
        return match($template) {
            'home' => '1.0',
            'about' => '0.9',
            'services' => '0.8',
            'pricing' => '0.8',
            'contact' => '0.8',
            'privacy' => '0.3',
            'terms' => '0.3',
            'faq' => '0.5',
            default => '0.6' // custom and other templates
        };
    }

    protected function getPageChangeFrequency($lastUpdated)
    {
        $daysAgo = $lastUpdated->diffInDays(now());

        if ($daysAgo < 7) {
            return 'weekly';
        } elseif ($daysAgo < 30) {
            return 'monthly';
        } else {
            return 'yearly';
        }
    }

    protected function addBlogPages(){
        $xml = '';

        // Blog Index page
        $xml .= $this->urlToXml(
            route('articles.index'),
            Carbon::now()->toDateString(),
            'daily',
            '0.9'
        );

        // Blog Categories
        $categories = BlogCategory::where('is_active', true)
            ->select(['slug', 'updated_at'])
            ->orderBy('sort_order')
            ->get();

        foreach ($categories as $category) {
            $xml .= $this->urlToXml(
                route('articles.category', $category->slug),
                $category->updated_at->toDateString(),
                'weekly',
                '0.7'
            );
        }

        // Blog Posts

        $posts = BlogPost::published()
            ->select(['slug', 'updated_at', 'published_at'])
            ->with('blogCategory:id,slug')
            ->orderBy('published_at', 'desc')
            ->get();

        foreach ($posts as $post) {
            $priority = $post->published_at->gt(now()->subMonths(6)) ? '0.8' : '0.6';
            $changeFreq = $post->published_at->gt(now()->subMonths(1)) ? 'weekly' : 'monthly';

            $xml .= $this->urlToXml(
                route('articles.show', $post->slug),
                $post->updated_at->toDateString(),
                $changeFreq,
                $priority
            );
        }

        return $xml;
    }




    protected function addProfessionPages()
    {
        $categories = config('professions.categories', []);
        $xml = '';

        // Add profession categories index
        $xml .= $this->urlToXml(
            route('people.profession.index'),
            Carbon::now()->toDateString(),
            'weekly',
            '0.8'
        );

        // Add individual profession category pages
        foreach ($categories as $categoryKey => $category) {
            $categorySlug = \Illuminate\Support\Str::slug($categoryKey);

            $xml .= $this->urlToXml(
            route('people.profession.details', ['professionName' => $categorySlug]),
            Carbon::now()->toDateString(),
            'weekly',
            '0.7'
            );

            // Add individual profession pages within each category
            foreach ($category['professions'] as $profession) {
                $professionSlug = \Illuminate\Support\Str::slug($profession);
                $xml .= $this->urlToXml(
                    route('people.profession.details', ['professionName' => $professionSlug]),
                    Carbon::now()->toDateString(),
                    'weekly',
                    '0.6'
                );
            }
        }

        return $xml;
    }


    protected function addPeoplePages(){
        $people = People::active()
            ->verified()
            ->select(['slug', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->get();

            $xml = '';
            foreach($people as $person){
                // Main person profile page
                $xml .= $this->urlToXml(
                    route('people.people.show', $person->slug),
                    $person->updated_at->toDateString(),
                    'weekly',
                    '0.9'
                );

                // Person tabs (biography, career, etc)
                $tabs = ['biography', 'career', 'gallery', 'awards', 'updates', 'controversies'];
            foreach ($tabs as $tab) {
                $xml .= $this->urlToXml(
                    route('people.details.tab', ['slug' => $person->slug, 'tab' => $tab]),
                    $person->updated_at->toDateString(),
                    'monthly',
                    '0.7'
                );
            }

            }

            return $xml;
    }

    protected function addDynamicContentPages(){
        $xml = '';

        // Lates Updates
        $latestUpdates = LatestUpdate::published()
            ->approved()
            ->select(['slug', 'person_id', 'updated_at'])
            ->with('person:id,slug')
            ->get();

        foreach ($latestUpdates as $update) {
            if ($update->person) {
                $xml .= $this->urlToXml(
                    route('people.updates.show', [
                        'personSlug' => $update->person->slug,
                        'slug' => $update->slug
                    ]),
                    $update->updated_at->toDateString(),
                    'monthly',
                    '0.6'
                );
            }
        }

        // Controversies
        $controversies = Controversy::published()
            ->select(['slug', 'person_id', 'updated_at'])
            ->with('person:id,slug')
            ->get();

            foreach ($controversies as $controversy) {
            if ($controversy->person) {
                $xml .= $this->urlToXml(
                    route('people.controversies.show', [
                        'personSlug' => $controversy->person->slug,
                        'slug' => $controversy->slug
                    ]),
                    $controversy->updated_at->toDateString(),
                    'monthly',
                    '0.6'
                );
            }
        }

        // Career pages (various types)
        $xml .= $this->addCareerPages();

        return $xml;
    }

    protected function addCareerPages()
    {
        $xml = '';

        // Sports Careers
        $sportsCareers = SportsCareer::select(['slug', 'person_id', 'updated_at'])
            ->with('person:id,slug')
            ->get();

        foreach ($sportsCareers as $career) {
            if ($career->person) {
                $xml .= $this->urlToXml(
                    route('people.career.show', [
                        'personSlug' => $career->person->slug,
                        'slug' => $career->slug
                    ]),
                    $career->updated_at->toDateString(),
                    'monthly',
                    '0.5'
                );
            }
        }

        // Political Careers
        $politicalCareers = Politician::select(['slug', 'person_id', 'updated_at'])
            ->with('person:id,slug')
            ->get();

        foreach ($politicalCareers as $career) {
            if ($career->person) {
                $xml .= $this->urlToXml(
                    route('people.career.show', [
                        'personSlug' => $career->person->slug,
                        'slug' => $career->slug
                    ]),
                    $career->updated_at->toDateString(),
                    'monthly',
                    '0.5'
                );
            }
        }

        // Entrepreneur Careers
        $entrepreneurCareers = Entrepreneur::select(['slug', 'person_id', 'updated_at'])
            ->with('person:id,slug')
            ->get();

        foreach ($entrepreneurCareers as $career) {
            if ($career->person) {
                $xml .= $this->urlToXml(
                    route('people.career.show', [
                        'personSlug' => $career->person->slug,
                        'slug' => $career->slug
                    ]),
                    $career->updated_at->toDateString(),
                    'monthly',
                    '0.5'
                );
            }
        }

        // Literature Careers
        $literatureCareers = LiteratureCareer::select(['slug', 'person_id', 'updated_at'])
            ->with('person:id,slug')
            ->get();

        foreach ($literatureCareers as $career) {
            if ($career->person) {
                $xml .= $this->urlToXml(
                    route('people.career.show', [
                        'personSlug' => $career->person->slug,
                        'slug' => $career->slug
                    ]),
                    $career->updated_at->toDateString(),
                    'monthly',
                    '0.5'
                );
            }
        }

        return $xml;
    }


    protected function urlToXml($loc, $lastmod, $changefreq, $priority){
        return "
            <url>
                <loc>{$loc}</loc>
                <lastmod>{$lastmod}</lastmod>
                <changefreq>{$changefreq}</changefreq>
                <priority>{$priority}</priority>
            </url>
        ";
    }

    public function getSitemapUrl(){
        return url('/sitemap.xml');
    }

    /**
     * Generate sitemap index for large sites (if needed in future)
     */
    public function generateSitemapIndex()
    {
        // This can be implemented if you have multiple sitemap files
        $sitemapIndex = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemapIndex .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $sitemapIndex .= "
            <sitemap>
                <loc>" . $this->getSitemapUrl() . "</loc>
                <lastmod>" . Carbon::now()->toDateString() . "</lastmod>
            </sitemap>
        ";

        $sitemapIndex .= '</sitemapindex>';

        Storage::disk('public')->put('sitemap_index.xml', $sitemapIndex);

        return true;
    }

}
