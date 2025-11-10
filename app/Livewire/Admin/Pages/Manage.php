<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Page;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Manage extends Component
{
    public ?int $editingId = null;
    public int $currentStep = 1;
    public bool $autoSlug = true;

    // Page fields
    public $title = '';
    public $slug = '';
    public $description = '';
    public $content = '';
    public $template = '';
    public $is_published = false;
    public $published_at = '';

    // Search fields
    public $creator_search = '';

    /**
     * Get validation rules
     */
    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', Rule::unique('pages', 'slug')->ignore($this->editingId)],
            'description' => 'nullable|string|max:255',
            'content' => 'required|string',
            'template' => 'nullable|string|max:50',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ];
    }


    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadPage($id);
            $this->autoSlug = false;
        } else {
            $this->autoSlug = true;
            $this->published_at = now()->format('Y-m-d\TH:i');
        }
    }

    public function loadPage($id)
    {
        $page = Page::with(['user'])->findOrFail($id);

        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->description = $page->description;
        $this->content = $page->content;
        $this->template = $page->template;
        $this->is_published = $page->is_published;
        $this->published_at = optional($page->published_at)->format('Y-m-d\TH:i');

        // Pre-fill search field
        if ($page->creator) {
            $this->creator_search = $page->creator->name;
        }
    }

    /**
     * Get filtered creators based on search
     */
    public function getCreatorsProperty()
    {
        return User::when($this->creator_search, function ($query) {
            $query->where('name', 'like', "%{$this->creator_search}%")
                  ->orWhere('email', 'like', "%{$this->creator_search}%");
        })
            ->orderBy('name')
            ->limit(50)
            ->get();
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
        Page::where('slug', $this->slug)
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
                'title' => 'required|string|max:255',
                'slug' => ['required', 'string', Rule::unique('pages', 'slug')->ignore($this->editingId)],
                'description' => 'nullable|string|max:255',
            ],
            2 => [
                'content' => 'required|string',
                'template' => 'nullable|string|max:50',
            ],
            3 => [
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
            $rules['slug'] = ['required', 'string', Rule::unique('pages', 'slug')->ignore($this->editingId)];
        }

        $this->validate($rules);

        try {
            $data = [
                'title' => $this->title,
                'slug' => Str::slug($this->slug),
                'description' => $this->description, // Changed from subtitle
                'content' => $this->content,
                'template' => $this->template ?: null,
                'is_published' => $this->is_published,
                'published_at' => $this->is_published ? $this->published_at : null,
            ];

            $page = DB::transaction(function () use ($data) {
                if ($this->editingId) {
                    $page = Page::findOrFail($this->editingId);
                    $page->update($data);
                    return $page;
                } else {
                    // Set user_id for new pages to current authenticated user
                    $data['user_id'] = auth()->id();
                    return Page::create($data);
                }
            });

            Toaster::success($this->editingId ? 'Page updated successfully.' : 'Page created successfully.');

            return redirect()->route('webmaster.pages.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save page: ' . $e->getMessage());
        }


    }

    public function render()
    {
        $commonTemplates = [
            'about' => 'About Page',
            'contact' => 'Contact Page',
            'privacy' => 'Privacy Policy',
            'terms' => 'Terms of Service',
            'home' => 'Home Page',
            'services' => 'Services Page',
            'pricing' => 'Pricing Page',
            'faq' => 'FAQ Page',
            'custom' => 'Custom Template',
        ];

        // Get existing templates from database
        $existingTemplates = Page::select('template')
            ->whereNotNull('template')
            ->where('template', '!=', '')
            ->distinct()
            ->orderBy('template')
            ->get()
            ->pluck('template')
            ->mapWithKeys(fn($template) => [$template => $commonTemplates[$template] ?? ucfirst($template)])
            ->toArray();

        return view('livewire.admin.pages.manage', compact('commonTemplates', 'existingTemplates'));
    }
}
