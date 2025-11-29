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
                                {{ $editingId ? 'Edit Award' : 'Add New Award' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                {{ $editingId ? 'Update award information' : 'Create a new award entry' }}
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

                            <!-- Step 2: Award Details -->
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
                                        Award Details
                                    </span>
                                </div>
                            </div>

                            <!-- Step 3: Additional Info -->
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
                                        Additional Information
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
                                <x-flux::button type="button" wire:click="previousStep" class="cursor-pointer"
                                    class="w-full">
                                    ← Previous
                                </x-flux::button>
                            @endif
                            @if ($currentStep < 3)
                                <x-flux::button type="button" wire:click="nextStep" class="w-full cursor-pointer">
                                    Next Step →
                                </x-flux::button>
                            @else
                                <x-flux::button type="button" wire:click="save" class="w-full cursor-pointer">
                                    ✓ {{ $editingId ? 'Update Award' : 'Create Award' }}
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
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Basic
                                            Information</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Enter the fundamental details about
                                            the award.</p>
                                    </div>

                                    <!-- Person Selection -->
                                    @include('livewire.admin.award.partials.person-select')

                                    <!-- Award Name -->
                                    <x-flux::input label="Award Name *" wire:model.live="award_name"
                                        placeholder="e.g., Academy Award, Grammy Award" required />

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

                                            @if ($autoSlug && !empty($award_name) && empty($slug))
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
                                                    /awards/{{ $slug }}
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

                                    <!-- Organization -->
                                    <x-flux::input label="Organization *" wire:model="organization"
                                        placeholder="e.g., Academy of Motion Picture Arts and Sciences" required />
                                </div>
                            @endif

                            <!-- Step 2: Award Details -->
                            @if ($currentStep == 2)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Award Details
                                        </h2>
                                        <p class="text-gray-600 dark:text-gray-400">Provide specific details about the
                                            award.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Award Date -->
                                        <x-flux::input label="Award Date" wire:model="awarded_at" type="date" />

                                        <!-- Category -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Category
                                            </label>
                                            <input wire:model="category" list="categories"
                                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                placeholder="Select or enter category" />
                                            <datalist id="categories">
                                                @foreach ($commonCategories as $cat)
                                                    <option value="{{ $cat }}">
                                                @endforeach
                                            </datalist>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Common categories:
                                                {{ implode(', ', array_slice($commonCategories, 0, 5)) }}...
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Award Image -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Award Image
                                        </label>
                                        @if ($existing_award_image)
                                            <div class="mb-4">
                                                <img src="{{ $existing_award_image }}" alt="Current award image"
                                                    class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300">
                                            </div>
                                            <x-flux::button type="button" wire:click="removeAwardImage"
                                                variant="danger" size="sm" class="mb-2">
                                                Remove Current Image
                                            </x-flux::button>
                                        @endif
                                        <x-flux::input type="file" wire:model="award_image" accept="image/*" />
                                        @error('award_image')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <x-flux::input type="text" wire:model="image_caption" label="Image Caption" placeholder="Image source..." />
                                    </div>

                                    <!-- Description -->
                                    <x-flux::textarea label="Description" wire:model="description"
                                        placeholder="Describe the award, its significance, or the achievement..."
                                        rows="4" />
                                </div>
                            @endif

                            <!-- Step 3: Additional Information -->
                            @if ($currentStep == 3)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Additional
                                            Information</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Set display order and verification
                                            status.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Sort Order -->
                                        <x-flux::input label="Sort Order" wire:model="sort_order" type="number"
                                            min="0" helper="Lower numbers appear first in lists" />

                                        <!-- Image Optimization Settings -->
                                        <div class="space-y-3">
                                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Image
                                                Optimization</h3>
                                            <x-flux::input label="Width (px)" wire:model="imageWidth" type="number"
                                                min="100" max="4000" />
                                            <x-flux::input label="Height (px)" wire:model="imageHeight"
                                                type="number" min="100" max="4000" />
                                            <x-flux::input label="Quality (%)" wire:model="imageQuality"
                                                type="number" min="10" max="100" />
                                        </div>
                                    </div>

                                    <!-- Verification -->
                                    <x-flux::checkbox wire:model="is_verified" label="Verified Award"
                                        helper="Mark this award entry as verified and accurate" />
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
                                        ✓ {{ $editingId ? 'Update Award' : 'Create Award' }}
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
