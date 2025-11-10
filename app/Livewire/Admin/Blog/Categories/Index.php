<?php

namespace App\Livewire\Admin\Blog\Categories;

use App\Models\BlogCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function deleteCategory($id): void
    {
        $category = BlogCategory::findOrFail($id);

        if ($category->blogPosts()->exists()) {
            session()->flash('error', 'Cannot delete category with associated blog posts.');
            return;
        }

        $category->delete();
        Toaster::success('Category has been deleted.');
    }

    public function toggleStatus($id): void
    {
        $category = BlogCategory::findOrFail($id);
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $message = $category->is_active ? 'Category activated successfully.' : 'Category deactivated successfully.';
        Toaster::success($message);
    }

    public function render()
    {
        $categories = BlogCategory::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== '', function ($query) {
                if ($this->statusFilter === 'active') {
                    $query->where('is_active', true);
                } elseif ($this->statusFilter === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->withCount('blogPosts')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.blog.categories.index', compact('categories'));
    }
}
