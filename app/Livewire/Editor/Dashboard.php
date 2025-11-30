<?php

namespace App\Livewire\Editor;

use App\Models\People;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Editor Dashboard")]
#[Layout('components.layouts.editor')]
class Dashboard extends Component
{
    public $stats = [];
    public $recentPersons = [];
    public $loading = true;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $userId = Auth::id();

        // Total counts
        $totalPersons = People::where('created_by', $userId)->count();
        $approvedCount = People::where('created_by', $userId)->approved()->count();
        $pendingCount = People::where('created_by', $userId)->pending()->count();
        $rejectedCount = People::where('created_by', $userId)->rejected()->count();

        // Status counts
        $activeCount = People::where('created_by', $userId)->active()->count();
        $inactiveCount = People::where('created_by', $userId)->where('status', 'inactive')->count();
        $deceasedCount = People::where('created_by', $userId)->where('status', 'deceased')->count();

        // View statistics
        $totalViews = People::where('created_by', $userId)->sum('view_count');
        $averageViews = $totalPersons > 0 ? round($totalViews / $totalPersons) : 0;

        // Recent activity
        $recentlyCreated = People::where('created_by', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentlyUpdated = People::where('created_by', $userId)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $this->stats = [
            'total_persons' => $totalPersons,
            'approved_count' => $approvedCount,
            'pending_count' => $pendingCount,
            'rejected_count' => $rejectedCount,
            'active_count' => $activeCount,
            'inactive_count' => $inactiveCount,
            'deceased_count' => $deceasedCount,
            'total_views' => $totalViews,
            'average_views' => $averageViews,
            'approval_rate' => $totalPersons > 0 ? round(($approvedCount / $totalPersons) * 100) : 0,
        ];

        $this->recentPersons = [
            'created' => $recentlyCreated,
            'updated' => $recentlyUpdated,
        ];

        $this->loading = false;
    }

    public function getQuickActions()
    {
        return [
            [
                'title' => 'Create New Person',
                'description' => 'Add a new biography entry',
                'icon' => 'plus',
                'route' => route('editor.persons.create'),
                'color' => 'blue',
            ],
            [
                'title' => 'View Pending Entries',
                'description' => 'Check entries awaiting approval',
                'icon' => 'clock',
                'route' => route('editor.persons.index', ['approvalStatus' => 'pending']),
                'color' => 'yellow',
            ],
            [
                'title' => 'View Approved Entries',
                'description' => 'See your approved content',
                'icon' => 'check',
                'route' => route('editor.persons.index', ['approvalStatus' => 'approved']),
                'color' => 'green',
            ],
            [
                'title' => 'Manage Rejected Entries',
                'description' => 'Review and update rejected entries',
                'icon' => 'x',
                'route' => route('editor.persons.index', ['approvalStatus' => 'rejected']),
                'color' => 'red',
            ],
        ];
    }

    public function getPerformanceMetrics()
    {
        $userId = Auth::id();

        // Last 30 days performance
        $lastMonth = now()->subDays(30);

        $recentCreated = People::where('created_by', $userId)
            ->where('created_at', '>=', $lastMonth)
            ->count();

        $recentApproved = People::where('created_by', $userId)
            ->approved()
            ->where('verified_at', '>=', $lastMonth)
            ->count();

        $recentViews = People::where('created_by', $userId)
            ->where('updated_at', '>=', $lastMonth)
            ->sum('view_count');

        return [
            'recent_created' => $recentCreated,
            'recent_approved' => $recentApproved,
            'recent_views' => $recentViews,
        ];
    }

    public function render()
    {
        return view('livewire.editor.dashboard', [
            'quickActions' => $this->getQuickActions(),
            'performanceMetrics' => $this->getPerformanceMetrics(),
        ]);
    }
}
