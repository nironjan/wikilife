<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $template = '';
    public string $year = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'template' => ['except' => ''],
        'year' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingTemplate()
    {
        $this->resetPage();
    }

    public function updatingYear()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'desc';
        }
        $this->sortField = $field;
    }

    public function togglePublish($id)
    {
        try {
            $page = Page::findOrFail($id);
            $page->update([
                'is_published' => !$page->is_published,
                'published_at' => $page->is_published ? null : now()
            ]);

            Toaster::success(
                $page->is_published ? 'Page published successfully.' : 'Page unpublished successfully.'
            );
        } catch (\Exception $e) {
            Toaster::error('Failed to update page status.');
        }
    }

    public function deletePage($id)
    {
        try {
            $page = Page::findOrFail($id);
            $page->delete();

            Toaster::success('Page deleted successfully.');
        } catch (\Exception $e) {
            Toaster::error('Failed to delete page.');
        }
    }

    public function render()
    {
        $pages = Page::with(['user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%")
                        ->orWhere('content', 'like', "%{$this->search}%")
                        ->orWhere('slug', 'like', "%{$this->search}%")
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->status === 'published', function ($query) {
                $query->where('is_published', true)
                    ->where(function ($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    });
            })
            ->when($this->status === 'unpublished', function ($query) {
                $query->where('is_published', false);
            })
            ->when($this->status === 'scheduled', function ($query) {
                $query->where('is_published', true)
                    ->where('published_at', '>', now());
            })
            ->when($this->template, function ($query) {
                $query->where('template', $this->template);
            })
            ->when($this->year, function ($query) {
                $query->whereYear('published_at', $this->year);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

        $templates = Page::select('template')
            ->whereNotNull('template')
            ->where('template', '!=', '')
            ->distinct()
            ->orderBy('template')
            ->get()
            ->pluck('template')
            ->mapWithKeys(fn($template) => [$template => ucfirst($template)])
            ->toArray();

        $years = Page::selectRaw('YEAR(published_at) as year')
            ->whereNotNull('published_at')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get()
            ->pluck('year')
            ->toArray();

        $statuses = [
            'published' => 'Published',
            'unpublished' => 'Unpublished',
            'scheduled' => 'Scheduled',
        ];

        return view('livewire.admin.pages.index', compact('pages', 'templates', 'years', 'statuses'));
    }
}
