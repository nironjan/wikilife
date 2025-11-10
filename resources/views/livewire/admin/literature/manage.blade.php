<div>
    <div class="min-h-screen  dark:bg-gray-900 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Left Sidebar - Progress Steps -->
                    <div class="lg:w-1/4 bg-gray-50 dark:bg-gray-700 p-6 border-r border-gray-200 dark:border-gray-600">
                        <!-- Header -->
                        <div class="mb-8">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $editingId ? 'Edit Literary Work' : 'Add Literary Work' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                {{ $editingId ? 'Update literature career information' : 'Create a new literary work entry' }}
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

                            <!-- Step 2: Publication Details -->
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
                                        Publication Details
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
                                    Previous
                                </x-flux::button>
                            @endif
                            @if ($currentStep < 3)
                                <x-flux::button type="button" wire:click="nextStep" class="w-full">
                                    Next Step
                                </x-flux::button>
                            @else
                                <x-flux::button type="button" wire:click="save" class="w-full">
                                    {{ $editingId ? 'Update Work' : 'Create Work' }}
                                </x-flux::button>
                            @endif
                        </div>
                    </div>

                    <!-- Right Content - Form Steps -->
                    <div class="lg:w-3/4 p-6 lg:p-8">
                        <form wire:submit="save">
                            <!-- Step 1: Basic Information -->
                            @if ($currentStep == 1)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Basic
                                            Information</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Enter the fundamental details about
                                            the literary work.</p>
                                    </div>

                                    <!-- Person Selection -->
                                    @include('livewire.admin.literature.partials.person-select')

                                    <!-- Role -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Role *
                                        </label>
                                        <input wire:model="role" list="roles"
                                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Select or enter role" required />
                                        <datalist id="roles">
                                            @foreach ($commonRoles as $role)
                                                <option value="{{ $role }}">
                                            @endforeach
                                        </datalist>
                                        @error('role')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Media Type -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Media Type *
                                        </label>
                                        <input wire:model="media_type" list="mediaTypes"
                                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Select or enter media type" required />
                                        <datalist id="mediaTypes">
                                            @foreach ($commonMediaTypes as $type)
                                                <option value="{{ $type }}">
                                            @endforeach
                                        </datalist>
                                        @error('media_type')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Title -->
                                    <x-flux::input label="Work Title *" wire:model="title"
                                        placeholder="e.g., The Great Gatsby, War and Peace" required
                                        helper="Title of the literary work" />

                                    <!-- Work Type -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Work Type *
                                        </label>
                                        <input wire:model="work_type" list="workTypes"
                                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Select or enter work type" required />
                                        <datalist id="workTypes">
                                            @foreach ($commonWorkTypes as $type)
                                                <option value="{{ $type }}">
                                            @endforeach
                                        </datalist>
                                        @error('work_type')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <!-- Step 2: Publication Details -->
                            @if ($currentStep == 2)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Publication
                                            Details</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Provide specific details about the
                                            publication.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Start Date -->
                                        <x-flux::input label="Career Start Date" wire:model="start_date" type="date"
                                            helper="When the literary career started" />

                                        <!-- End Date -->
                                        <x-flux::input label="Career End Date" wire:model="end_date" type="date"
                                            helper="When the literary career ended (leave empty if ongoing)" />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Publishing Year -->
                                        <x-flux::input label="Publishing Year" wire:model="publishing_year"
                                            type="number" min="1900" max="{{ date('Y') + 5 }}"
                                            placeholder="e.g., 2023" helper="Year the work was published" />

                                        <!-- Language -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Language *
                                            </label>
                                            <input wire:model="language" list="languages"
                                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                placeholder="Select or enter language" required />
                                            <datalist id="languages">
                                                @foreach ($commonLanguages as $lang)
                                                    <option value="{{ $lang }}">
                                                @endforeach
                                            </datalist>
                                            @error('language')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Genre -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Genre *
                                        </label>
                                        <input wire:model="genre" list="genres"
                                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Select or enter genre" required />
                                        <datalist id="genres">
                                            @foreach ($commonGenres as $genre)
                                                <option value="{{ $genre }}">
                                            @endforeach
                                        </datalist>
                                        @error('genre')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- ISBN -->
                                    <x-flux::input label="ISBN" wire:model="isbn"
                                        placeholder="e.g., 978-3-16-148410-0"
                                        helper="International Standard Book Number" />

                                    <!-- Cover Image Upload -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Cover Image
                                        </label>

                                        @if ($existing_cover_image)
                                            <div class="mb-4">
                                                <img src="{{ $existing_cover_image }}" alt="Current cover image"
                                                    class="w-full h-32 object-cover rounded-lg border-2 border-gray-300">
                                            </div>
                                            <x-flux::button type="button" wire:click="removeCoverImage"
                                                variant="danger" size="sm" class="mb-2">
                                                Remove Current Image
                                            </x-flux::button>
                                        @endif

                                        <x-flux::input type="file" wire:model="cover_image" accept="image/*" />

                                        @if ($cover_image)
                                            <div class="mt-2">
                                                <p class="text-sm text-green-600 dark:text-green-400">New image
                                                    selected: {{ $cover_image->getClientOriginalName() }}</p>
                                                <div
                                                    class="w-32 h-48 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden mt-2 shadow-sm">
                                                    <img src="{{ $cover_image->temporaryUrl() }}" alt="Preview"
                                                        class="w-full h-full object-cover">
                                                </div>
                                            </div>
                                        @endif

                                        @error('cover_image')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Link -->
                                    <x-flux::input label="Reference Link" wire:model="link"
                                        placeholder="https://example.com/book-details" type="url"
                                        helper="Link to publisher, store, or reference" />
                                </div>
                            @endif

                            <!-- Step 3: Additional Information -->
                            @if ($currentStep == 3)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Additional
                                            Information</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Set status, awards, and additional
                                            details.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Verification Status -->
                                        <div>
                                            <label class="flex items-center">
                                                <input type="checkbox" wire:model="is_verified"
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Verified
                                                    Work</span>
                                            </label>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Check if this work has been verified for accuracy
                                            </p>
                                        </div>

                                        <!-- Sort Order -->
                                        <x-flux::input label="Sort Order" wire:model="sort_order" type="number"
                                            min="0" helper="Lower numbers appear first in lists" />
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Description & Notes
                                        </label>
                                        <textarea wire:model="description" rows="4"
                                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Describe the literary work, its significance, themes, or any additional notes..."></textarea>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Brief description, summary, or important notes about the work
                                        </p>
                                    </div>

                                    <!-- Award Selection -->
                                    @include('livewire.admin.literature.partials.award-select')
                                </div>
                            @endif

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between pt-8 border-t border-gray-200 dark:border-gray-600 mt-8">
                                @if ($currentStep > 1)
                                    <x-flux::button type="button" wire:click="previousStep" class="cursor-pointer">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Previous
                                    </x-flux::button>
                                @else
                                    <div></div>
                                @endif

                                @if ($currentStep < 3)
                                    <x-flux::button type="button" wire:click="nextStep">
                                        Next Step
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </x-flux::button>
                                @else
                                    <x-flux::button type="submit">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ $editingId ? 'Update Literary Work' : 'Create Literary Work' }}
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
