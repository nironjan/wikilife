<?php

namespace App\Livewire\Admin\Controversy;

use App\Models\Controversy;
use App\Models\People;
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

    // Form Fields
    public $person_id = '';
    public $title = '';
    public $slug = '';
    public $content = '';
    public $html_content = '';
    public $date = '';
    public $source_url = '';
    public $is_resolved = false;
    public $is_published = false;

    // Search fields
    public $person_search = '';

    protected function rules()
    {
        return [
            'person_id' => 'required|exists:people,id',
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', Rule::unique('controversies', 'slug')->ignore($this->editingId)],
            'content' => 'required|string',
            'date' => 'nullable|date',
            'source_url' => 'nullable|url|max:500',
            'is_resolved' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadControversy($id);
            $this->autoSlug = false;
        } else {
            $this->autoSlug = true;
        }
    }

    public function loadControversy($id)
    {
        $controversy = Controversy::findOrFail($id);

        $this->person_id = $controversy->person_id;
        $this->title = $controversy->title;
        $this->slug = $controversy->slug;
        $this->content = $controversy->content;
        $this->html_content = $controversy->html_content;
        $this->date = optional($controversy->date)->format('Y-m-d');
        $this->source_url = $controversy->source_url;
        $this->is_resolved = $controversy->is_resolved;
        $this->is_published = $controversy->is_published;

        // Pre-fill search field
        if ($controversy->person) {
            $this->person_search = $controversy->person->display_name;
        }
    }

    /**
     * Get filtered people based on search
     */
    public function getPeopleProperty()
    {
        // Get all active people first
        $allPeople = People::active()
            ->orderBy('name')
            ->limit(50)
            ->get();

        // If no search term, return all people
        if (empty($this->person_search)) {
            return $allPeople;
        }

        $searchTerm = strtolower(trim($this->person_search));

        // Apply the same PHP filtering logic as search suggestions
        return $allPeople->filter(function($person) use ($searchTerm) {
            // Check name (case insensitive)
            if (stripos($person->name, $searchTerm) !== false) {
                return true;
            }

            // Check full_name (case insensitive)
            if ($person->full_name && stripos($person->full_name, $searchTerm) !== false) {
                return true;
            }

            // Check nicknames (case insensitive)
            $nicknames = $person->nicknames ?? [];
            foreach ($nicknames as $nickname) {
                if (stripos($nickname, $searchTerm) !== false) {
                    return true;
                }
            }

            // Check professions (case insensitive)
            $professions = $person->professions ?? [];
            foreach ($professions as $profession) {
                if (stripos($profession, $searchTerm) !== false) {
                    return true;
                }
            }

            return false;
        });
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
            Controversy::where('slug', $this->slug)
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
     * When person is selected, update search field
     */
    public function updatedPersonId($value)
    {
        if ($value) {
            $person = People::find($value);
            if ($person) {
                $this->person_search = $person->display_name;
            }
        } else {
            $this->person_search = '';
        }
    }

    /**
     * Clear person selection
     */
    public function clearPerson()
    {
        $this->person_id = '';
        $this->person_search = '';
    }

    /**
     * Convert markdown to HTML when saving
     */
    protected function convertToHtml($markdown)
    {
        try {
            // Use Laravel's built-in Markdown parser
            $this->html_content = Str::markdown($markdown);
        } catch (Exception $e) {
            // Fallback: use the markdown as-is
            $this->html_content = $markdown;
        }
    }

    /**
     * Step Navigation Methods
     */
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
                'person_id' => 'required|exists:people,id',
                'title' => 'required|string|max:255',
                'slug' => ['required', 'string', Rule::unique('controversies', 'slug')->ignore($this->editingId)],
            ],
            2 => [
                'content' => 'required|string',
                'date' => 'nullable|date',
                'source_url' => 'nullable|url|max:500',
            ],
            3 => [
                'is_resolved' => 'boolean',
                'is_published' => 'boolean',
            ]
        ];

        $this->validate($stepRules[$this->currentStep]);
    }

    public function save()
    {
        $rules = $this->rules();

        if ($this->editingId) {
            $rules['slug'] = ['required', 'string', Rule::unique('controversies', 'slug')->ignore($this->editingId)];
        }

        $this->validate($rules);

        try {
            // Convert markdown to HTML before saving
            $this->convertToHtml($this->content);

            $data = [
                'person_id' => $this->person_id,
                'title' => $this->title,
                'slug' => Str::slug($this->slug),
                'content' => $this->content,
                'html_content' => $this->html_content,
                'date' => $this->date ?: null,
                'source_url' => $this->source_url,
                'is_resolved' => $this->is_resolved,
                'is_published' => $this->is_published,
            ];

            DB::transaction(function () use ($data) {
                if ($this->editingId) {
                    Controversy::findOrFail($this->editingId)->update($data);
                } else {
                    Controversy::create($data);
                }
            });

            Toaster::success(
                $this->editingId ? 'Controversy updated successfully.' : 'Controversy created successfully.'
            );

            return redirect()->route('webmaster.persons.controversies.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save controversy: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.controversy.manage');
    }
}
