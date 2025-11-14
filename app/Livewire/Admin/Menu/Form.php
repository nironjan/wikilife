<?php

namespace App\Livewire\Admin\Menu;

use App\Models\Menu;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Form extends Component
{
    public ?int $editingId = null;
    public array $menuTypes = ['header', 'footer', 'sidebar', 'top_header', 'footer_bar'];
    public array $icons;

    // Form fields
    public $name = '';
    public $slug = '';
    public $url = '';
    public $icon = '';
    public $type = 'header';
    public $parent_id = null;
    public $description = '';
    public $meta_title = '';
    public $meta_description = '';
    public $is_active = true;
    public $open_in_new_tab = false;

    public $parents = [];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => [
            'required',
            'string',
            'max:255',
            Rule::unique('menus', 'slug')->ignore($this->editingId ?: null)
        ],
            'url' => 'required|string|max:500',
            'icon' => 'nullable|string|max:50',
            'type' => 'required|in:header,footer,sidebar,top_header,footer_bar',
            'parent_id' => 'nullable|integer|exists:menus,id',
            'description' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
        ];
    }

    public function mount(?int $id = null): void
    {
        $this->icons = config('menu-icons.icons', []);

        if ($id) {
            $this->editingId = $id;
            $this->loadMenu($id);
        }

        $this->loadParents();
    }

    public function loadMenu(int $id): void
    {
        $menu = Menu::findOrFail($id);

        $this->name = $menu->name;
        $this->slug = $menu->slug;
        $this->url = $menu->url;
        $this->icon = $menu->icon;
        $this->type = $menu->type;
        $this->parent_id = $menu->parent_id;
        $this->description = $menu->description;
        $this->meta_title = $menu->meta_title;
        $this->meta_description = $menu->meta_description;
        $this->is_active = $menu->is_active;
        $this->open_in_new_tab = $menu->open_in_new_tab;
    }

    public function loadParents(): void
    {
        $query = Menu::where('type', $this->type)
            ->whereNull('parent_id')
            ->active()
            ->ordered();

        // If editing, exclude the current menu and its children from parent options
        if ($this->editingId) {
            $query->where('id', '!=', $this->editingId);

            // Also exclude any children of the current menu to prevent circular references
            $childIds = Menu::where('parent_id', $this->editingId)
                ->pluck('id')
                ->toArray();

            if (!empty($childIds)) {
                $query->whereNotIn('id', $childIds);
            }
        }

        $this->parents = $query->get()
            ->map(function ($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                ];
            })
            ->toArray();
    }

    public function onTypeChange(): void
    {
        $this->parent_id = null;
        $this->loadParents();
    }

    public function updatedType($value): void
    {
        $this->parent_id = null;
        $this->loadParents();
    }

    public function generateSlugFromName(): void
    {
        if (!$this->editingId && empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function generateSlug(): void
    {
        $this->slug = Str::slug($this->name);
    }

   public function save(): void
{
    $this->validate();

    // Fixed: Only check for self-referencing when editing AND parent_id is not null
    if ($this->editingId && $this->parent_id == $this->editingId) {
        Toaster::error('A menu cannot be its own parent.');
        return;
    }

    try {
        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'url' => $this->url,
            'icon' => $this->icon,
            'type' => $this->type,
            'parent_id' => $this->parent_id ? (int) $this->parent_id : null,
            'description' => $this->description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'is_active' => $this->is_active,
            'open_in_new_tab' => $this->open_in_new_tab,
        ];

        // Calculate depth
        if ($this->parent_id) {
            $parentMenu = Menu::find($this->parent_id);
            $data['depth'] = $parentMenu ? $parentMenu->depth + 1 : 1;
        } else {
            $data['depth'] = 0;
        }

        if ($this->editingId) {
            Menu::findOrFail($this->editingId)->update($data);
            Toaster::success('Menu updated successfully.');
        } else {
            // Set order for new menu
            $lastOrder = Menu::where('type', $this->type)
                ->where('parent_id', $this->parent_id)
                ->max('order');

            $data['order'] = $lastOrder ? $lastOrder + 1 : 1;

            Menu::create($data);
            Toaster::success('Menu created successfully.');
        }

        $this->redirect(route('webmaster.menus'));

    } catch (\Exception $e) {
        Toaster::error('Failed to save menu: ' . $e->getMessage());
    }
}

    public function render()
    {
        return view('livewire.admin.menu.form');
    }
}
