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
                                {{ $editingId ? 'Edit Page' : 'Add New Page' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                {{ $editingId ? 'Update page information' : 'Create a new page entry' }}
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

                            <!-- Step 2: Content & Template -->
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
                                        Content & Template
                                    </span>
                                </div>
                            </div>

                            <!-- Step 3: Publishing -->
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
                                        Publishing
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
                                    ✓ {{ $editingId ? 'Update Page' : 'Create Page' }}
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
                                        <p class="text-gray-600 dark:text-gray-400">Enter the fundamental details about the page.</p>
                                    </div>

                                    <!-- Title -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Title *
                                        </label>
                                        <x-flux::input wire:model.live="title"
                                                       placeholder="Enter a compelling page title..." />
                                        @error('title')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- description -->
                                    <div class="space-y-2">

                                        <x-flux::textarea wire:model="description" label="Description"
                                                       placeholder="Optional description for the page..." />
                                        @error('description')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

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
                                                    /{{ $slug }}
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

                            <!-- Step 2: Content & Template -->
                            @if ($currentStep == 2)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Content & Template</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Add your page content and select a template.</p>
                                    </div>

                                    <!-- Content with Quill Editor -->
                                    <div class="space-y-2" wire:ignore>
                                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Content *
                                        </label>

                                        <x-quill-editor
                                            wire:model="content"
                                            placeholder="Write your page content here..."
                                            height="400px"
                                            toolbar="full"
                                        />

                                        <!-- Hidden field for validation -->
                                        <input type="hidden" wire:model="content" />

                                        @error('content')
                                        <p class="text-sm text-red-600 dark:text-red-400 flex items-center mt-1">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <!-- Template Selection -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Template
                                        </label>
                                        <select wire:model="template"
                                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                                            <option value="">Default Template</option>
                                            <optgroup label="Common Templates">
                                                @foreach($commonTemplates as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </optgroup>
                                            @if(count($existingTemplates) > 0)
                                                <optgroup label="Existing Templates">
                                                    @foreach($existingTemplates as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        </select>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Select a template to control the page layout and styling.
                                        </p>
                                        @error('template')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <!-- Step 3: Publishing -->
                            @if ($currentStep == 3)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Publishing</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Configure publishing options for the page.</p>
                                    </div>

                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <!-- Published Toggle -->
                                        <div
                                            class="flex items-center justify-between p-4 bg-gray-50/50 dark:bg-gray-700/30 rounded-xl border border-gray-200/50 dark:border-gray-600/50">
                                            <div class="flex items-center space-x-3">
                                                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                                         stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Published</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Make this page publicly visible</p>
                                                </div>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" wire:model="is_published" class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                </div>
                                            </label>
                                        </div>

                                        <!-- Publish Date -->
                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                Publish Date & Time
                                            </label>
                                            <div class="relative">
                                                <x-flux::input type="datetime-local" wire:model="published_at"
                                                               class="pl-10 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200" />
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Page Information -->
                                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                                        <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Page Information</h3>
                                        <div class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
                                            <div class="flex justify-between">
                                                <span>URL:</span>
                                                <span class="font-mono">/{{ $slug ?: 'your-slug' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Template:</span>
                                                <span>{{ $template ? ($commonTemplates[$template] ?? ucfirst($template)) : 'Default' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Status:</span>
                                                <span class="font-medium {{ $is_published ? 'text-green-600 dark:text-green-400' : 'text-gray-600 dark:text-gray-400' }}">
                                                    {{ $is_published ? 'Will be published' : 'Draft' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
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
                                        ✓ {{ $editingId ? 'Update Page' : 'Create Page' }}
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
