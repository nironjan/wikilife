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
                                {{ $editingId ? 'Edit Blog Post' : 'Add New Blog Post' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                {{ $editingId ? 'Update blog post information' : 'Create a new blog post entry' }}
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

                            <!-- Step 2: Content & Media -->
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
                                        Content & Media
                                    </span>
                                </div>
                            </div>

                            <!-- Step 3: SEO & Settings -->
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
                                        SEO & Settings
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
                                    ✓ {{ $editingId ? 'Update Blog Post' : 'Create Blog Post' }}
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
                                        <p class="text-gray-600 dark:text-gray-400">Enter the fundamental details about the blog post.</p>
                                    </div>

                                    <!-- Category Selection -->
                                    <!-- Category Selection -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Select Category *
                                        </label>
                                        <div class="space-y-2">
                                            <!-- Search Input -->
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                    </svg>
                                                </div>
                                                <input wire:model.live.debounce.300ms="category_search" type="text"
                                                       class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                       placeholder="Search for category..." />
                                                @if ($category_search || $blog_category_id)
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                                        <button type="button" wire:click="clearCategory"
                                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Results Dropdown -->
                                            @if ($category_search && !$blog_category_id)
                                                <div
                                                    class="border border-gray-200 dark:border-gray-600 rounded-md max-h-60 overflow-y-auto bg-white dark:bg-gray-700 shadow-lg">
                                                    @forelse($this->categories as $category)
                                                        <button type="button" wire:click="$set('blog_category_id', '{{ $category->id }}')"
                                                                class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center space-x-3">
                                                            <div
                                                                class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                                    {{ $category->name }}
                                                                </div>
                                                                @if ($category->description)
                                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                        {{ $category->description }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </button>
                                                    @empty
                                                        <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                                            No categories found matching "{{ $category_search }}"
                                                        </div>
                                                    @endforelse
                                                </div>
                                            @endif

                                            <!-- Selected Category Display -->
                                            @if ($blog_category_id)
                                                <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-3">
                                                            @php $selectedCategory = $this->categories->firstWhere('id', $blog_category_id) @endphp
                                                            @if ($selectedCategory)
                                                                <div
                                                                    class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <div class="text-sm font-medium text-green-800 dark:text-green-200">
                                                                        {{ $selectedCategory->name }}
                                                                    </div>
                                                                    @if ($selectedCategory->description)
                                                                        <div class="text-xs text-green-600 dark:text-green-400">
                                                                            {{ $selectedCategory->description }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <span class="text-green-600 dark:text-green-400">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </span>
                                                    </div>
                                                </div>
                                            @endif

                                            @error('blog_category_id')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Title -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Title *
                                        </label>
                                        <x-flux::input wire:model.live="title"
                                                       placeholder="Enter a compelling blog post title..." />
                                        @error('title')
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
                                                    /blog/{{ $slug }}
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

                            <!-- Step 2: Content & Media -->
                            @if ($currentStep == 2)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Content & Media</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Add your blog post content and media assets.</p>
                                    </div>

                                    <!-- Excerpt -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Excerpt
                                        </label>
                                        <x-flux::textarea wire:model="excerpt" rows="3"
                                                          placeholder="Write a brief excerpt that summarizes your blog post..." />
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            A short summary displayed in blog listings and search results.
                                        </p>
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
                                            placeholder="Write your blog post content here..."
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

                                    <!-- Featured Image -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Featured Image
                                        </label>

                                        @if ($existing_featured_image)
                                            <div class="mb-4">
                                                <div class="relative inline-block">
                                                    <img src="{{ $existing_featured_image }}" alt="Current featured image"
                                                         class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300">
                                                    @if ($existing_featured_image_file_id)
                                                        <div class="absolute top-2 right-2">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Optimized
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <x-flux::button type="button" wire:click="removeFeaturedImage"
                                                            variant="danger" size="sm" class="mb-2"
                                                            wire:confirm="Are you sure you want to remove the featured image? This action cannot be undone.">
                                                Remove Current Image
                                            </x-flux::button>
                                        @endif

                                        <x-flux::input type="file" wire:model="featured_image" accept="image/*" />

                                        @if ($featured_image)
                                            <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    New image selected: {{ $featured_image->getClientOriginalName() }}
                                                </p>
                                            </div>
                                        @endif

                                        @error('featured_image')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Tags -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Tags
                                        </label>
                                        <div class="space-y-2">
                                            <div class="flex space-x-2">
                                                <input wire:model="new_tag"
                                                       placeholder="Add tags (separate with commas)"
                                                       class="flex-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                                <button type="button" wire:click="addTag"
                                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                                                    Add
                                                </button>
                                            </div>

                                            @if(count($tags) > 0)
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    @foreach($tags as $index => $tag)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                            {{ $tag }}
                                                            <button type="button" wire:click="removeTag({{ $index }})"
                                                                    class="ml-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Common tags: {{ implode(', ', array_slice($commonTags, 0, 5)) }}...
                                        </p>
                                    </div>

                                    <!-- Image Optimization Settings -->
                                    <div class="space-y-3 p-4 bg-gray-50/50 dark:bg-gray-700/30 rounded-xl border border-gray-200/50 dark:border-gray-600/50">
                                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Image Optimization Settings</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <x-flux::input label="Width (px)" wire:model="imageWidth" type="number"
                                                           min="100" max="4000" />
                                            <x-flux::input label="Height (px)" wire:model="imageHeight"
                                                           type="number" min="100" max="4000" />
                                            <x-flux::input label="Quality (%)" wire:model="imageQuality"
                                                           type="number" min="10" max="100" />
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            These settings apply to newly uploaded featured images. Existing images will be replaced with optimized versions.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Step 3: SEO & Settings -->
                            @if ($currentStep == 3)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">SEO & Settings</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Configure SEO settings and publishing options.</p>
                                    </div>

                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <!-- Meta Title -->
                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                Meta Title
                                            </label>
                                            <x-flux::input wire:model="meta_title"
                                                           placeholder="Optional custom meta title for SEO..."
                                                           class="bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200" />
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                If empty, the blog post title will be used.
                                            </p>
                                        </div>

                                        <!-- Sort Order -->
                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                Sort Order
                                            </label>
                                            <x-flux::input type="number" wire:model="sort_order" min="0"
                                                           class="bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200" />
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Lower numbers appear first. Default is 0.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Meta Description -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Meta Description
                                        </label>
                                        <x-flux::textarea wire:model="meta_description" rows="3"
                                                          placeholder="Optional meta description for SEO..."
                                                          class="bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200" />
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
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Make this blog post publicly visible</p>
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
                                        ✓ {{ $editingId ? 'Update Blog Post' : 'Create Blog Post' }}
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
