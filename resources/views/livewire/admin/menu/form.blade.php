<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $editingId ? 'Edit Menu' : 'Create New Menu' }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ $editingId ? 'Update menu item details' : 'Add a new menu item to the navigation' }}
                        </p>
                    </div>

                    <form wire:submit.prevent="save" class="space-y-6">
                        <!-- Basic Information Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <flux:input type="text" id="name" wire:model="name"
                                    wire:blur="generateSlugFromName" placeholder="Enter menu name" label="Name*" />
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <flux:input type="text" id="slug" wire:model="slug" placeholder="menu-slug"
                                        label="Slug*" />
                                </div>
                                <div class="mt-2">
                                    <flux:button type="button" wire:click="generateSlug" variant="outline"
                                        size="sm">
                                        Generate from name
                                    </flux:button>
                                </div>
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- URL and Type Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- URL -->
                            <div>
                                <flux:input type="text" id="url" wire:model="url" label="URL*"
                                    placeholder="/p/page or https://example.com" />
                                @error('url')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div>
                                <flux:select id="type" label="Menu Type*" wire:model="type"
                                    wire:change="onTypeChange" placeholder="Choose Menu Type">
                                    @foreach ($menuTypes as $menuType)
                                        <flux:select.option value="{{ $menuType }}">
                                            {{ match($menuType) {
                                                'header' => 'Header Menu',
                                                'footer' => 'Footer Menu',
                                                'sidebar' => 'Sidebar Menu',
                                                'top_header' => 'Top Header',
                                                'footer_bar' => 'Footer Bar',
                                                default => ucfirst($menuType)
                                            } }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Icon and Parent Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:select
                                wire:model="icon"
                                label="Menu Icon"
                            >
                                <flux:select.option value="">Select an icon</flux:select.option>
                                @foreach ($icons as $key => $value)
                                    <flux:select.option value="{{ $key }}">{{ ucfirst($key) }}</flux:select.option>
                                @endforeach
                            </flux:select>


                            <!-- Parent Menu -->
                            <div>
                                <flux:select id="parent_id" wire:model="parent_id" label="Parent Menu">
                                    <flux:select.option value="">No Parent (Root Menu)</flux:select.option>
                                    @forelse ($parents as $parent)
                                        <flux:select.option value="{{ $parent['id'] }}">{{ $parent['name'] }}</flux:select.option>
                                    @empty
                                        <flux:select.option value="" disabled>No parent menus available for this type</flux:select.option>
                                    @endforelse
                                </flux:select>
                                @error('parent_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror

                                <!-- Help text -->
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    @if($editingId)
                                        Note: This menu and its submenus are excluded from parent options to prevent circular references.
                                    @else
                                        Select a parent menu to create a submenu, or leave empty for a root menu.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <flux:textarea id="description" wire:model="description" rows="3" label="Description"
                                placeholder="Optional description for this menu item" />
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SEO Section -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">SEO Information</h3>

                            <div class="space-y-4">
                                <!-- Meta Title -->
                                <div>
                                    <flux:input type="text" id="meta_title" wire:model="meta_title"
                                        label="Meta Title" placeholder="Meta title for SEO" />
                                    @error('meta_title')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Meta Description -->
                                <div>
                                    <flux:textarea id="meta_description" wire:model="meta_description" rows="3"
                                        label="Meta Description" placeholder="Meta description for SEO" />
                                    @error('meta_description')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Settings Section -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Settings</h3>

                            <div class="space-y-4">
                                <!-- Status -->
                                <div class="flex items-center">
                                    <flux:checkbox id="is_active" wire:model="is_active" />
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                        Active (Visible on website)
                                    </label>
                                </div>

                                <!-- Open in New Tab -->
                                <div class="flex items-center">
                                    <flux:checkbox id="open_in_new_tab" wire:model="open_in_new_tab" />
                                    <label for="open_in_new_tab"
                                        class="ml-2 block text-sm text-gray-900 dark:text-white">
                                        Open in new tab
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('webmaster.menus') }}"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ $editingId ? 'Update Menu' : 'Create Menu' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
