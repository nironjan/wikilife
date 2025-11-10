<div>
    <div class="min-h-screen dark:bg-gray-900 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Left Sidebar - Progress Steps -->
                    <div class="lg:w-1/3 bg-gray-50 dark:bg-gray-700 p-6 border-r border-gray-200 dark:border-gray-600">
                        <!-- Header -->
                        <div class="mb-8">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $editingId ? 'Edit Update' : 'Add New Update' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                {{ $editingId ? 'Update news information' : 'Create a new news update' }}
                            </p>
                        </div>

                        <!-- Progress Steps -->
                        <div class="space-y-4">
                            <!-- Step 1: Basic Info -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 1 ? 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800' : 'bg-gray-100 dark:bg-gray-600' }}">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-500 text-gray-600 dark:text-gray-300' }}">
                                    1
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium
                                    {{ $currentStep >= 1 ? 'text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300' }}">
                                        Basic Information
                                    </span>
                                </div>
                            </div>

                            <!-- Step 2: Content -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 2 ? 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800' : 'bg-gray-100 dark:bg-gray-600' }}">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-500 text-gray-600 dark:text-gray-300' }}">
                                    2
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium
                                    {{ $currentStep >= 2 ? 'text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300' }}">
                                        Update Content
                                    </span>
                                </div>
                            </div>

                            <!-- Step 3: Settings -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 3 ? 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800' : 'bg-gray-100 dark:bg-gray-600' }}">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-500 text-gray-600 dark:text-gray-300' }}">
                                    3
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium
                                    {{ $currentStep >= 3 ? 'text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300' }}">
                                        Settings
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-8">
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <span>Progress</span>
                                <span>{{ round(($currentStep / 3) * 100) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ ($currentStep / 3) * 100 }}%"></div>
                            </div>
                        </div>

                        <!-- Action Buttons for Mobile -->
                        <div class="lg:hidden mt-6 space-y-3">
                            @if ($currentStep > 1)
                                <x-flux::button type="button" wire:click="previousStep" class="cursor-pointer w-full">
                                    ← Previous
                                </x-flux::button>
                            @endif
                            @if ($currentStep < 3)
                                <x-flux::button type="button" wire:click="nextStep" class="w-full cursor-pointer">
                                    Next Step →
                                </x-flux::button>
                            @else
                                <x-flux::button type="button" wire:click="save" class="w-full cursor-pointer">
                                    ✓ {{ $editingId ? 'Update News' : 'Create News' }}
                                </x-flux::button>
                            @endif
                        </div>
                    </div>

                    <!-- Right Content - Form Steps -->
                    <div class="lg:w-2/3 p-6 lg:p-8">
                        <form wire:submit="save">
                            <!-- Step 1: Basic Information -->
                            @if ($currentStep == 1)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Basic Information</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Enter the fundamental details about the update.</p>
                                    </div>

                                    <!-- Person Selection -->
                                    @include('livewire.admin.award.partials.person-select')

                                    <!-- Title -->
                                    <x-flux::input label="Update Title *" wire:model.live="title"
                                        placeholder="e.g., New Movie Announcement, Award Win, etc." required />

                                    <!-- Slug with Auto-generation -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Slug *
                                            </label>
                                            <div class="flex items-center space-x-2">
                                                @if (!$autoSlug)
                                                    <x-flux::button type="button" wire:click="resetSlug"
                                                        class="cursor-pointer" size="sm">
                                                        ↻ Reset to Auto
                                                    </x-flux::button>
                                                @endif
                                                <span
                                                    class="text-xs px-2 py-1 rounded-full {{ $autoSlug ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' }}">
                                                    {{ $autoSlug ? 'Auto' : 'Manual' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="relative">
                                            <x-flux::input wire:model="slug" placeholder="URL-friendly identifier"
                                                required class="pr-10" />

                                            @if ($autoSlug && !empty($title) && empty($slug))
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <div
                                                        class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        @if (!empty($slug))
                                            <div class="mt-2 p-2 bg-gray-50 dark:bg-gray-700 rounded text-sm">
                                                <span class="text-gray-600 dark:text-gray-400">Preview URL:</span>
                                                <span class="font-mono text-blue-600 dark:text-blue-400 ml-2">
                                                    /updates/{{ $slug }}
                                                </span>
                                            </div>
                                        @endif

                                        @if ($autoSlug)
                                            <p class="mt-1 text-sm text-green-600 dark:text-green-400">
                                                ✓ Slug auto-generates as you type
                                            </p>
                                        @else
                                            <p class="mt-1 text-sm text-blue-600 dark:text-blue-400">
                                                ⚡ Manual mode - you can customize the slug
                                            </p>
                                        @endif

                                        @error('slug')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <!-- Step 2: Content -->
                            @if ($currentStep == 2)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Update Content</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Provide the detailed content and media for this update.</p>
                                    </div>

                                    <!-- Update Type -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Update Type
                                        </label>
                                        <input wire:model="update_type" list="updateTypes"
                                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Select or enter update type" />
                                        <datalist id="updateTypes">
                                            @foreach ($commonUpdateTypes as $type)
                                                <option value="{{ $type }}">
                                            @endforeach
                                        </datalist>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Common types: {{ implode(', ', array_slice($commonUpdateTypes, 0, 5)) }}...
                                        </p>
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <flux:textarea
                                            label="Short Description"
                                            wire:model="description"
                                            rows="3"
                                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Brief summary of the update (optional, will be auto-generated from content if empty)"
                                        />
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            A short description that appears in lists and previews. If empty, it will be auto-generated from the content.
                                        </p>
                                    </div>

                                    <!-- Update Content with Quill Editor -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Update Content *
                                        </label>

                                        <x-quill-editor
                                            wire:model="content"
                                            placeholder="Write your update content here..."
                                            height="400px"
                                            toolbar="basic"
                                        />

                                        <!-- Hidden field for validation -->
                                        <input type="hidden" wire:model="content" />

                                        @error('content')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Use the toolbar above to format your content with rich text editing.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Step 3: Settings -->
                            @if ($currentStep == 3)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Settings</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Configure visibility and display settings.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Status -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Status *
                                            </label>
                                            <select wire:model="status"
                                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                <option value="draft">Draft</option>
                                                <option value="published">Published</option>
                                            </select>
                                            @error('status')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Sort Order -->
                                        <x-flux::input label="Sort Order" wire:model="sort_order" type="number"
                                            min="0" helper="Lower numbers appear first in lists" />
                                    </div>

                                    <!-- Update Image -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Update Image
                                        </label>

                                        <!-- Existing Image Preview -->
                                        @if($existing_update_image)
                                            <div class="mb-4 p-4 border border-gray-300 rounded-lg dark:border-gray-600">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Image:</span>
                                                    <x-flux::button type="button" wire:click="removeUpdateImage"
                                                        class="cursor-pointer bg-red-600 hover:bg-red-700" size="sm">
                                                        Remove Image
                                                    </x-flux::button>
                                                </div>
                                                <img src="{{ $existing_update_image }}" alt="Current update image"
                                                     class="max-w-xs max-h-48 rounded-lg shadow-sm">
                                            </div>
                                        @endif

                                        <!-- Image Upload -->
                                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                                            @if($update_image)
                                                <div class="mb-4">
                                                    <img src="{{ $update_image->temporaryUrl() }}" alt="Preview"
                                                         class="max-w-xs max-h-48 mx-auto rounded-lg shadow-sm">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                        {{ $update_image->getClientOriginalName() }}
                                                    </p>
                                                </div>
                                            @else
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            @endif

                                            <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                                <label class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload an image</span>
                                                    <input type="file" wire:model="update_image" class="sr-only" accept="image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                PNG, JPG, GIF up to 5MB
                                            </p>
                                        </div>

                                        @error('update_image')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror

                                        <!-- Image Optimization Settings -->
                                        @if($update_image)
                                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Image Optimization</h4>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <x-flux::input label="Width (px)" wire:model="imageWidth" type="number"
                                                        min="100" max="4000" helper="Max width" />
                                                    <x-flux::input label="Height (px)" wire:model="imageHeight" type="number"
                                                        min="100" max="4000" helper="Max height" />
                                                    <x-flux::input label="Quality (%)" wire:model="imageQuality" type="number"
                                                        min="10" max="100" helper="Image quality" />
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Approval -->
                                    <x-flux::checkbox wire:model="is_approved" label="Approved Update"
                                        helper="Mark this update as approved for public viewing" />
                                </div>
                            @endif

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between pt-8 border-t border-gray-200 dark:border-gray-600 mt-8">
                                @if ($currentStep > 1)
                                    <x-flux::button type="button" wire:click="previousStep" class="cursor-pointer">
                                        ← Previous
                                    </x-flux::button>
                                @else
                                    <div></div>
                                @endif

                                @if ($currentStep < 3)
                                    <x-flux::button type="button" wire:click="nextStep" class="cursor-pointer">
                                        Next Step →
                                    </x-flux::button>
                                @else
                                    <x-flux::button type="submit" class="cursor-pointer">
                                        ✓ {{ $editingId ? 'Update News' : 'Create News' }}
                                    </x-flux::button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
