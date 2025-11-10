<?php

namespace App\Livewire\Admin\Blog\Posts;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $category = '';
    public string $year = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'category' => ['except' => ''],
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

    public function updatingCategory()
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
            $blogPost = BlogPost::findOrFail($id);
            $blogPost->update([
                'is_published' => !$blogPost->is_published,
                'published_at' => $blogPost->is_published ? null : now()
            ]);

            Toaster::success(
                $blogPost->is_published ? 'Blog post published successfully.' : 'Blog post unpublished successfully.'
            );
        } catch (\Exception $e) {
            Toaster::error('Failed to update blog post status.');
        }
    }

    public function deleteBlogPost($id)
    {
        try {
            $blogPost = BlogPost::findOrFail($id);
            $blogPost->delete();

            Toaster::success('Blog post deleted successfully.');
        } catch (\Exception $e) {
            Toaster::error('Failed to delete blog post.');
        }
    }

    public function render()
    {
        $blogPosts = BlogPost::with(['blogCategory'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                        ->orWhere('excerpt', 'like', "%{$this->search}%")
                        ->orWhere('content', 'like', "%{$this->search}%")
                        ->orWhereHas('blogCategory', function ($categoryQuery) {
                            $categoryQuery->where('name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->status === 'published', function ($query) {
                $query->where('is_published', true);
            })
            ->when($this->status === 'unpublished', function ($query) {
                $query->where('is_published', false);
            })
            ->when($this->category, function ($query) {
                $query->where('blog_category_id', $this->category);
            })
            ->when($this->year, function ($query) {
                $query->whereYear('published_at', $this->year);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

        $categories = BlogCategory::orderBy('name')
            ->get()
            ->mapWithKeys(fn($category) => [$category->id => $category->name])
            ->toArray();

        $years = BlogPost::selectRaw('YEAR(published_at) as year')
            ->whereNotNull('published_at')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get()
            ->pluck('year')
            ->toArray();

        $statuses = [
            'published' => 'Published',
            'unpublished' => 'Unpublished',
        ];



        return view('livewire.admin.blog.posts.index', compact('blogPosts', 'categories', 'years', 'statuses'));
    }
}
