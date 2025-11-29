<?php

namespace App\Livewire\Admin\Blog\Posts;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Services\ImageKitService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Mockery\Exception;

class Manage extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;
    public int $currentStep = 1;
    public bool $autoSlug = true;

    // Blog Post fields
    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $featured_image = null;
    public $tags = [];
    public $meta_title = '';
    public $meta_description = '';
    public $sort_order = 0;
    public $is_published = false;
    public $published_at = '';

    // Search fields
    public $category_display = '';
    public $category_search = '';
    public $blog_category_id = '';
    public $existing_featured_image = null;
    public $existing_featured_image_file_id = null;
    public $new_tag = '';

    // Image optimization
    public $imageWidth = 800;
    public $imageHeight = 600;
    public $imageQuality = 85;

    protected $imageKitService;

    /**
     * Get validation rules
     */
    protected function rules()
    {
        return [
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', Rule::unique('blog_posts', 'slug')->ignore($this->editingId)],
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:5120',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',

            'imageWidth' => 'nullable|integer|min:100|max:4000',
            'imageHeight' => 'nullable|integer|min:100|max:4000',
            'imageQuality' => 'nullable|integer|min:10|max:100',
        ];
    }

    public function boot()
    {
        $this->imageKitService = new ImageKitService();
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadBlogPost($id);
            $this->autoSlug = false;
        } else {
            $this->autoSlug = true;
            $this->published_at = now()->format('Y-m-d\TH:i');
        }
    }

    public function loadBlogPost($id)
    {
        $blogPost = BlogPost::findOrFail($id);

        $this->blog_category_id = $blogPost->blog_category_id;
        $this->title = $blogPost->title;
        $this->slug = $blogPost->slug;
        $this->excerpt = $blogPost->excerpt;
        $this->content = $blogPost->content;
        $this->existing_featured_image = $blogPost->featured_image_url;
        $this->existing_featured_image_file_id = $blogPost->featured_image_file_id;
        $this->tags = $blogPost->tags ?? [];
        $this->meta_title = $blogPost->meta_title;
        $this->meta_description = $blogPost->meta_description;
        $this->sort_order = $blogPost->sort_order ?? 0;
        $this->is_published = $blogPost->is_published;
        $this->published_at = optional($blogPost->published_at)->format('Y-m-d\TH:i');

        // Pre-fill search field
        if ($blogPost->blogCategory) {
            $this->category_search = $blogPost->blogCategory->name;
        }
    }

    public function getCategoriesProperty()
    {
        return BlogCategory::when($this->category_search, function ($query) {
                // Use ilike for case-insensitive search in PostgreSQL
                $query->where('name', 'ilike', "%{$this->category_search}%");
            })
            ->orderBy('name')
            ->limit(50)
            ->get();
    }

    public function getSelectedCategoryProperty()
    {
        if (!$this->blog_category_id) {
            return null;
        }

        return BlogCategory::find($this->blog_category_id);
    }

    public function updatedBlogCategoryId($value)
    {
        if ($value) {
            $category = BlogCategory::find($value);
            if ($category) {
                $this->category_display = $category->name;
                $this->category_search = '';
            }
        } else {
            $this->category_display = '';
            $this->category_search = '';
        }
    }

    public function clearCategory()
    {
        $this->blog_category_id = '';
        $this->category_display = '';
        $this->category_search = '';
    }

    /**
     * Auto-generate slug when title changes
     */
    public function updatedTitle($value)
    {
        if ($this->autoSlug && !empty($value)) {
            $this->generateSlug();
        }
    }

    /**
     * Generate slug from title
     */
    public function generateSlug()
    {
        if ($this->autoSlug && !empty($this->title)) {
            $baseSlug = Str::slug($this->title);
            $this->slug = $baseSlug;
            $this->ensureUniqueSlug();
        }
    }

    /**
     * Ensure the generated slug is unique
     */
    protected function ensureUniqueSlug()
    {
        $baseSlug = $this->slug;
        $counter = 1;

        while (
        BlogPost::where('slug', $this->slug)
            ->when($this->editingId, fn($q) => $q->where('id', '!=', $this->editingId))
            ->exists()
        ) {
            $this->slug = $baseSlug . '-' . $counter;
            $counter++;

            if ($counter > 100)
                break;
        }
    }

    /**
     * When user manually edits slug, disable auto-generation
     */
    public function updatedSlug($value)
    {
        if (!empty($value) && $value !== Str::slug($this->title)) {
            $this->autoSlug = false;
        }

        if (empty($value)) {
            $this->autoSlug = true;
            $this->generateSlug();
        }
    }

    /**
     * Reset to auto-generated slug
     */
    public function resetSlug()
    {
        $this->autoSlug = true;
        $this->generateSlug();
        Toaster::success('Slug reset to auto-generated value.');
    }



    /**
     * Add a new tag
     */
    public function addTag($tag = null)
    {
        $tag = $tag ?: $this->new_tag;

        if ($tag && !in_array($tag, $this->tags)) {
            $this->tags[] = trim($tag);
        }

        $this->new_tag = '';
    }

    /**
     * Remove a tag
     */
    public function removeTag($index)
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
    }

    /**
     * Handle tag input
     */
    public function updatedNewTag($value)
    {
        if (str_contains($value, ',')) {
            $tags = explode(',', $value);
            foreach ($tags as $tag) {
                $tag = trim($tag);
                if ($tag && !in_array($tag, $this->tags)) {
                    $this->tags[] = $tag;
                }
            }
            $this->new_tag = '';
        }
    }

    /**
     * Remove featured image from ImageKit and database
     */
    public function removeFeaturedImage()
    {
        try {
            if ($this->editingId && $this->existing_featured_image_file_id) {
                $blogPost = BlogPost::find($this->editingId);

                // Delete from ImageKit
                $this->imageKitService->deleteFile($this->existing_featured_image_file_id);

                // Update database
                $blogPost->update([
                    'featured_image' => null,
                    'featured_image_file_id' => null,
                ]);

                $this->existing_featured_image = null;
                $this->existing_featured_image_file_id = null;

                Toaster::success('Featured image removed successfully.');
            } else if ($this->editingId && $this->existing_featured_image) {
                // Fallback: if file ID is missing but image exists
                $blogPost = BlogPost::find($this->editingId);
                $blogPost->update([
                    'featured_image' => null,
                    'featured_image_file_id' => null,
                ]);
                $this->existing_featured_image = null;
                Toaster::success('Featured image removed successfully.');
            }
        } catch (Exception $e) {
            Toaster::error('Failed to remove featured image: ' . $e->getMessage());
        }

        $this->featured_image = null;
    }

    /**
     * Update published_at when is_published changes
     */
    public function updatedIsPublished($value)
    {
        if ($value && empty($this->published_at)) {
            $this->published_at = now()->format('Y-m-d\TH:i');
        }
    }

    public function nextStep()
    {
        // Ensure slug is generated before validation
        if ($this->currentStep === 1 && $this->autoSlug && empty($this->slug) && !empty($this->title)) {
            $this->generateSlug();
        }

        $this->validateCurrentStep();

        if ($this->currentStep < 3) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function validateCurrentStep()
    {
        $stepRules = [
            1 => [
                'blog_category_id' => 'required|exists:blog_categories,id',
                'title' => 'required|string|max:255',
                'slug' => ['required', 'string', Rule::unique('blog_posts', 'slug')->ignore($this->editingId)],
            ],
            2 => [
                'excerpt' => 'nullable|string|max:500',
                'content' => 'required|string',
                'featured_image' => 'nullable|image|max:5120',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
            ],
            3 => [
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'sort_order' => 'nullable|integer|min:0',
                'is_published' => 'boolean',
                'published_at' => 'nullable|date',
            ]
        ];

        $this->validate($stepRules[$this->currentStep]);
    }

    public function save()
    {
        $rules = $this->rules();

        if ($this->editingId) {
            $rules['slug'] = ['required', 'string', Rule::unique('blog_posts', 'slug')->ignore($this->editingId)];
        }

        $this->validate($rules);

        try {
            $data = [
                'blog_category_id' => $this->blog_category_id,
                'title' => $this->title,
                'slug' => Str::slug($this->slug),
                'excerpt' => $this->excerpt,
                'content' => $this->content,
                'tags' => !empty($this->tags) ? $this->tags : null,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'sort_order' => $this->sort_order ?: 0,
                'is_published' => $this->is_published,
                'published_at' => $this->is_published ? $this->published_at : null,
            ];

            $blogPost = DB::transaction(function () use ($data) {
                $blogPost = $this->editingId
                    ? tap(BlogPost::findOrFail($this->editingId))->update($data)
                    : BlogPost::create($data);

                // Upload featured image if provided
                if ($this->featured_image) {
                    $this->uploadFeaturedImage($blogPost);
                }

                return $blogPost;
            });

            Toaster::success($this->editingId ? 'Blog post updated successfully.' : 'Blog post created successfully.');

            return redirect()->route('webmaster.blog.posts.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save blog post: ' . $e->getMessage());
        }
    }

    protected function uploadFeaturedImage(BlogPost $blogPost)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->featured_image,
                'blog-posts/',
                $this->imageWidth ?: null,
                $this->imageHeight ?: null,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old featured image if exists
            if ($blogPost->featured_image_file_id) {
                $this->imageKitService->deleteFile($blogPost->featured_image_file_id);
            }

            $blogPost->update([
                'featured_image' => $upload->optimizedUrl,
                'featured_image_file_id' => $upload->fileId,
            ]);

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function render()
    {
        $commonTags = [
            'Technology',
            'Programming',
            'Web Development',
            'Laravel',
            'JavaScript',
            'PHP',
            'Vue.js',
            'React',
            'Tailwind CSS',
            'Database',
            'API',
            'Security',
            'Performance',
            'Tutorial',
            'Tips & Tricks',
        ];

        // Get categories for the view
        $categories = BlogCategory::orderBy('name')->get();

        return view('livewire.admin.blog.posts.manage',compact('commonTags', 'categories'));
    }
}
