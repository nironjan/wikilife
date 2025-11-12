<?php

namespace App\Livewire\Admin\Menu;

use App\Models\Menu;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    public $menus = [];
    public $activeTab = 'header';
    public $search = '';
    public $confirmingDelete = null;
    public $expandedMenus = [];

    public function mount(): void
    {
        $this->loadMenus();
    }

    public function loadMenus(): void
    {
        $this->menus = Menu::with([
            'children' => function ($query) {
                $query->ordered();
            }
        ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('url', 'like', '%' . $this->search . '%');
                });
            })
            ->where('type', $this->activeTab)
            ->root()
            ->ordered()
            ->get()
            ->toArray();
    }

    public function updatedSearch(): void
    {
        $this->loadMenus();
    }

    public function updatedActiveTab(): void
    {
        $this->loadMenus();
        $this->expandedMenus = [];
    }

    // Toggle expand/collapse of parent menu
    public function toggleExpand($menuId): void
    {
        if (in_array($menuId, $this->expandedMenus)) {
            $this->expandedMenus = array_diff($this->expandedMenus, [$menuId]);
        } else {
            $this->expandedMenus[] = $menuId;
        }
    }

    // Check if a menu is expanded
    public function isExpanded($menuId): bool
    {
        return in_array($menuId, $this->expandedMenus);
    }

    public function moveUp($id): void
    {
        try {
            $currentMenu = Menu::findOrFail($id);

            // Determine if it's a parent or child menu
            if ($currentMenu->parent_id) {
                // Child menu - reorder within same parent
                $previousMenu = Menu::where('type', $this->activeTab)
                    ->where('parent_id', $currentMenu->parent_id)
                    ->where('order', '<', $currentMenu->order)
                    ->orderBy('order', 'desc')
                    ->first();
            } else {
                // Parent menu - reorder root menus
                $previousMenu = Menu::where('type', $this->activeTab)
                    ->where('parent_id', null)
                    ->where('order', '<', $currentMenu->order)
                    ->orderBy('order', 'desc')
                    ->first();
            }

            if ($previousMenu) {
                // Swap orders
                $currentOrder = $currentMenu->order;
                $previousOrder = $previousMenu->order;

                $currentMenu->update(['order' => $previousOrder]);
                $previousMenu->update(['order' => $currentOrder]);

                Toaster::success('Menu order updated successfully.');
                $this->loadMenus();
            }

        } catch (\Exception $e) {
            Toaster::error('Failed to update menu order: ' . $e->getMessage());
        }
    }

    public function moveDown($id): void
    {
        try {
            $currentMenu = Menu::findOrFail($id);

            // Determine if it's a parent or child menu
            if ($currentMenu->parent_id) {
                // Child menu - reorder within same parent
                $nextMenu = Menu::where('type', $this->activeTab)
                    ->where('parent_id', $currentMenu->parent_id)
                    ->where('order', '>', $currentMenu->order)
                    ->orderBy('order', 'asc')
                    ->first();
            } else {
                // Parent menu - reorder root menus
                $nextMenu = Menu::where('type', $this->activeTab)
                    ->where('parent_id', null)
                    ->where('order', '>', $currentMenu->order)
                    ->orderBy('order', 'asc')
                    ->first();
            }

            if ($nextMenu) {
                // Swap orders
                $currentOrder = $currentMenu->order;
                $nextOrder = $nextMenu->order;

                $currentMenu->update(['order' => $nextOrder]);
                $nextMenu->update(['order' => $currentOrder]);

                Toaster::success('Menu order updated successfully.');
                $this->loadMenus();
            }

        } catch (\Exception $e) {
            Toaster::error('Failed to update menu order: ' . $e->getMessage());
        }
    }

    public function deleteMenu($id): void
    {
        try {
            $menu = Menu::findOrFail($id);

            // Check if menu has children
            if ($menu->children()->exists()) {
                Toaster::error('Cannot delete menu with submenus. Please delete submenus first.');
                return;
            }

            $menu->delete();

            Toaster::success('Menu deleted successfully.');
            $this->loadMenus();

        } catch (\Exception $e) {
            Toaster::error('Failed to delete menu: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id): void
    {
        try {
            $menu = Menu::findOrFail($id);
            $menu->update(['is_active' => !$menu->is_active]);

            Toaster::success('Menu status updated successfully.');
            $this->loadMenus();

        } catch (\Exception $e) {
            Toaster::error('Failed to update menu status: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.menu.index');
    }
}
