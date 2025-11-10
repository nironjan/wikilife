<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\People;
use App\Models\PersonAward;
use App\Models\Filmography;
use App\Models\Politician;
use App\Models\SportsCareer;
use App\Models\LiteratureCareer;
use App\Models\Entrepreneur;
use App\Models\LatestUpdate;
use App\Models\BlogPost;
use Livewire\Attributes\Lazy;

#[Lazy]
class Dashboard extends Component
{
    public $timeRange = 'week';
    public $stats = [];
    public $peopleGrowthData = [];
    public $careerDistributionData = [];

    protected $queryString = ['timeRange'];

    public function mount()
    {
        $this->loadData();
    }

    public function placeholder(){
        return view('livewire.admin.dashboard-skeleton');
    }



    public function refreshData()
    {
        $this->loadData();
        $this->dispatch('notify', message: 'Dashboard data refreshed successfully!', type: 'success');
    }

    public function updatedTimeRange()
    {
        $this->loadData();
    }


    private function loadData()
    {
        $this->stats = $this->getStatsProperty();
        $this->peopleGrowthData = $this->getPeopleGrowth();
        $this->careerDistributionData = $this->getCareerDistribution();

    }

    public function getStatsProperty()
    {
        $startDate = $this->getStartDate($this->timeRange);

        return [
            // People Statistics
            'totalPeople' => People::count(),
            'activePeople' => People::active()->count(),
            'verifiedPeople' => People::verified()->count(),
            'alivePeople' => People::alive()->count(),
            'newPeopleThisMonth' => People::where('created_at', '>=', now()->subDays(30))->count(),
            'recentPeople' => People::where('created_at', '>=', $startDate)->count(),

            // Career Statistics
            'careerStats' => $this->getCareerStats(),

            // Awards Statistics
            'totalAwards' => PersonAward::count(),
            'verifiedAwards' => PersonAward::verified()->count(),
            'awardsThisYear' => PersonAward::whereYear('awarded_at', now()->year)->count(),

            // System Statistics
            'totalUpdates' => LatestUpdate::count(),
            'publishedUpdates' => LatestUpdate::published()->count(),
            'totalBlogPosts' => BlogPost::count(),
            'publishedBlogPosts' => BlogPost::published()->count(),
            'totalViews' => People::sum('view_count'),
            'totalLikes' => People::sum('like_count'),
            'totalComments' => People::sum('comment_count'),

            // Recent Activity
            'recentPeopleList' => $this->getRecentPeople(),
            'recentAwards' => $this->getRecentAwards(),
            'recentUpdates' => $this->getRecentUpdates(),

            // Top Viewed
            'topViewedPeople' => $this->getTopViewedPeople(),

            // Career Distribution
            'careerDistribution' => $this->getCareerDistribution(),

            // Monthly People Growth
            'peopleGrowth' => $this->getPeopleGrowth(),
        ];
    }

    // Add this computed property for people growth chart data
    public function getPeopleGrowthDataProperty()
    {
        return $this->getPeopleGrowth();
    }

    // Add this computed property for career distribution data
    public function getCareerDistributionDataProperty()
    {
        return $this->getCareerDistribution();
    }

    private function getCareerStats()
    {
        return [
            'totalCareers' =>
                Filmography::count() +
                Politician::count() +
                SportsCareer::count() +
                LiteratureCareer::count() +
                Entrepreneur::count(),

            'uniquePeople' =>
                Filmography::distinct('person_id')->count('person_id') +
                Politician::distinct('person_id')->count('person_id') +
                SportsCareer::distinct('person_id')->count('person_id') +
                LiteratureCareer::distinct('person_id')->count('person_id') +
                Entrepreneur::distinct('person_id')->count('person_id'),

            'filmography' => Filmography::count(),
            'politics' => Politician::count(),
            'sports' => SportsCareer::count(),
            'literature' => LiteratureCareer::count(),
            'entrepreneur' => Entrepreneur::count(),
        ];
    }

    private function getRecentPeople($limit = 5)
    {
        return People::with(['creator'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(function($person) {
                return [
                    'id' => $person->id,
                    'name' => $person->display_name,
                    'profile_image' => $person->profile_image_url,
                    'professions' => $person->professions,
                    'created_at' => $person->created_at->diffForHumans(),
                    'created_by' => $person->creator->name ?? 'System',
                    'status' => $person->status,
                ];
            });
    }

    private function getRecentAwards($limit = 5)
    {
        return PersonAward::with(['person'])
            ->verified()
            ->latest('awarded_at')
            ->take($limit)
            ->get()
            ->map(function($award) {
                return [
                    'id' => $award->id,
                    'award_name' => $award->award_name,
                    'person_name' => $award->person->display_name ?? 'Unknown',
                    'category' => $award->category,
                    'awarded_at' => $award->awarded_at?->format('M d, Y'),
                    'year' => $award->year,
                ];
            });
    }

    private function getRecentUpdates($limit = 5)
    {
        return LatestUpdate::with(['person', 'user'])
            ->published()
            ->approved()
            ->latest()
            ->take($limit)
            ->get()
            ->map(function($update) {
                return [
                    'id' => $update->id,
                    'title' => $update->title,
                    'person_name' => $update->person->display_name ?? 'General',
                    'update_type' => $update->update_type,
                    'created_at' => $update->created_at->diffForHumans(),
                    'user_name' => $update->user->name ?? 'System',
                ];
            });
    }

    private function getTopViewedPeople($limit = 5)
    {
        return People::active()
            ->orderBy('view_count', 'DESC')
            ->take($limit)
            ->get()
            ->map(function($person) {
                return [
                    'id' => $person->id,
                    'name' => $person->display_name,
                    'profile_image' => $person->profile_image_url,
                    'view_count' => $person->view_count,
                    'like_count' => $person->like_count,
                    'primary_profession' => $person->primary_profession,
                ];
            });
    }

    private function getCareerDistribution()
    {
        $people = People::active()->get();

        $distribution = [
            'Actor' => 0,
            'Politician' => 0,
            'Sports' => 0,
            'Writer' => 0,
            'Entrepreneur' => 0,
            'Other' => 0,
        ];

        foreach ($people as $person) {
            $professions = $person->professions ?? [];
            $categorized = false;

            foreach ($professions as $profession) {
                $professionLower = strtolower($profession);

                if (str_contains($professionLower, 'actor') || str_contains($professionLower, 'actress')) {
                    $distribution['Actor']++;
                    $categorized = true;
                    break;
                } elseif (str_contains($professionLower, 'politician') || str_contains($professionLower, 'minister')) {
                    $distribution['Politician']++;
                    $categorized = true;
                    break;
                } elseif (str_contains($professionLower, 'sport') || str_contains($professionLower, 'player') || str_contains($professionLower, 'athlete')) {
                    $distribution['Sports']++;
                    $categorized = true;
                    break;
                } elseif (str_contains($professionLower, 'writer') || str_contains($professionLower, 'author') || str_contains($professionLower, 'poet')) {
                    $distribution['Writer']++;
                    $categorized = true;
                    break;
                } elseif (str_contains($professionLower, 'entrepreneur') || str_contains($professionLower, 'business')) {
                    $distribution['Entrepreneur']++;
                    $categorized = true;
                    break;
                }
            }

            if (!$categorized && !empty($professions)) {
                $distribution['Other']++;
            }
        }

        return $distribution;
    }

    private function getPeopleGrowth()
    {
        $months = [];
        $counts = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->format('M Y');
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $count = People::whereBetween('created_at', [$monthStart, $monthEnd])->count();

            $months[] = $monthName;
            $counts[] = $count;
        }

        return [
            'months' => $months,
            'counts' => $counts,
        ];
    }

    private function getStartDate($range)
    {
        return match($range) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfWeek(),
        };
    }



    public function render()
    {
        return view('livewire.admin.dashboard', [
            'stats' => $this->getStatsProperty(),
            'peopleGrowthData' => $this->getPeopleGrowthDataProperty(),
            'careerDistributionData' => $this->getCareerDistributionDataProperty(),
        ]);
    }
}
