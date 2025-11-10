<?php

namespace App\Livewire\Admin\Blog\Categories;

use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Manage extends Component
{
    public BlogCategory $category;
    public bool $isEditing = false;
    public bool$isSlugManuallyEdited = false;

    // Individual form properties for binding
    public $name;
    public $slug;
    public $description;
    public $is_active = true;
    public $sort_order = 0;

    protected function rules() {
       $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];

       if($this->isEditing) {
           $rules['slug'] = 'required|string|max:255|unique:blog_categories,slug,' . $this->category->id;
       } else{
           $rules['slug'] = 'required|string|max:255|unique:blog_categories,slug';
       }
       return $rules;
    }


    public function mount($id = null): void
    {
        if ($id) {
            $this->category = BlogCategory::findOrFail($id);
            $this->isEditing = true;

            // Populate form properties from the model
            $this->name = $this->category->name;
            $this->slug = $this->category->slug;
            $this->description = $this->category->description;
            $this->is_active = $this->category->is_active;
            $this->sort_order = $this->category->sort_order;
        } else {
            $this->category = new BlogCategory();
        }
    }


    public function updatedName($value): void
    {
        if (!$this->isEditing && !$this->isSlugManuallyEdited) {
            $this->slug = str()->slug($value);
        }
    }

    public function updatedSlug($value): void
    {
        // If the user manually edits the slug, mark it as manually edited
        if (!$this->isSlugManuallyEdited) {
            $this->isSlugManuallyEdited = true;
        }

        // Auto-format the slug as user types
        $this->slug = Str::slug($value);
    }

    public function generateSlug(): void
    {
        if ($this->name) {
            $this->slug = Str::slug($this->name);
            $this->isSlugManuallyEdited = false;
        }
    }

    public function save(): void
    {
        $this->validate();

        try {
            // Update the category model with form data
            $this->category->name = $this->name;
            $this->category->slug = $this->slug;
            $this->category->description = $this->description;
            $this->category->is_active = $this->is_active;
            $this->category->sort_order = $this->sort_order;

            $this->category->save();

            $message = $this->isEditing ? 'Category updated successfully.' : 'Category created successfully.';
            Toaster::success($message);

            $this->redirectIntended(route('webmaster.blog.categories.index'));

        } catch (\Exception $e) {
            Toaster::error('Failed to save category. Please try again.' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('livewire.admin.blog.categories.manage');
    }
}
