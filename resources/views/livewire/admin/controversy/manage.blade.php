<div>
    <div class="min-h-screen dark:bg-gray-900 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Left Sidebar - Progress Steps -->
                    <div class="lg:w-1/3 bg-red-50 dark:bg-red-900/20 p-6 border-r border-red-200 dark:border-red-800">
                        <!-- Header -->
                        <div class="mb-8">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $editingId ? 'Edit Controversy' : 'Add New Controversy' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                {{ $editingId ? 'Update controversial topic details' : 'Create a new controversial topic entry' }}
                            </p>
                        </div>

                        <!-- Progress Steps -->
                        <div class="space-y-4">
                            <!-- Step 1: Basic Info -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 1 ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' : 'bg-gray-100 dark:bg-gray-600' }}">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $currentStep >= 1 ? 'bg-red-600 text-white' : 'bg-gray-300 dark:bg-gray-500 text-gray-600 dark:text-gray-300' }}">
                                    1
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium
                                    {{ $currentStep >= 1 ? 'text-red-900 dark:text-red-100' : 'text-gray-600 dark:text-gray-300' }}">
                                        Basic Information
                                    </span>
                                </div>
                            </div>

                            <!-- Step 2: Content -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 2 ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' : 'bg-gray-100 dark:bg-gray-600' }}">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $currentStep >= 2 ? 'bg-red-600 text-white' : 'bg-gray-300 dark:bg-gray-500 text-gray-600 dark:text-gray-300' }}">
                                    2
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium
                                    {{ $currentStep >= 2 ? 'text-red-900 dark:text-red-100' : 'text-gray-600 dark:text-gray-300' }}">
                                        Controversy Details
                                    </span>
                                </div>
                            </div>

                            <!-- Step 3: Settings -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 3 ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' : 'bg-gray-100 dark:bg-gray-600' }}">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $currentStep >= 3 ? 'bg-red-600 text-white' : 'bg-gray-300 dark:bg-gray-500 text-gray-600 dark:text-gray-300' }}">
                                    3
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium
                                    {{ $currentStep >= 3 ? 'text-red-900 dark:text-red-100' : 'text-gray-600 dark:text-gray-300' }}">
                                        Status & Settings
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
                                <div class="bg-red-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ ($currentStep / 3) * 100 }}%"></div>
                            </div>
                        </div>

                        <!-- Warning Message -->
                        <div class="mt-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                                <div class="text-sm text-red-800 dark:text-red-300">
                                    <p class="font-medium">Responsible Content Guidelines</p>
                                    <p class="mt-1 opacity-90">Ensure factual accuracy and maintain neutral language.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons for Mobile -->
                        <div class="lg:hidden mt-6 space-y-3">
                            @if ($currentStep > 1)
                                <x-flux::button type="button" wire:click="previousStep" class="cursor-pointer w-full bg-red-600 hover:bg-red-700">
                                    ← Previous
                                </x-flux::button>
                            @endif
                            @if ($currentStep < 3)
                                <x-flux::button type="button" wire:click="nextStep" class="w-full cursor-pointer bg-red-600 hover:bg-red-700">
                                    Next Step →
                                </x-flux::button>
                            @else
                                <x-flux::button type="button" wire:click="save" class="w-full cursor-pointer bg-red-600 hover:bg-red-700">
                                    ✓ {{ $editingId ? 'Update Controversy' : 'Create Controversy' }}
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
                                        <p class="text-gray-600 dark:text-gray-400">Enter the fundamental details about the controversy.</p>
                                    </div>

                                    <!-- Person Selection -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Select Person *
                                        </label>
                                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                            @include('livewire.admin.award.partials.person-select')
                                        </div>
                                    </div>

                                    <!-- Title -->
                                    <x-flux::input label="Controversy Title *" wire:model.live="title"
                                        placeholder="e.g., Legal Issue, Public Statement, Scandal, etc." required />

                                    <!-- Slug with Auto-generation -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Slug *
                                            </label>
                                            <div class="flex items-center space-x-2">
                                                @if (!$autoSlug)
                                                    <x-flux::button type="button" wire:click="resetSlug"
                                                        class="cursor-pointer bg-red-600 hover:bg-red-700" size="sm">
                                                        ↻ Reset to Auto
                                                    </x-flux::button>
                                                @endif
                                                <span
                                                    class="text-xs px-2 py-1 rounded-full {{ $autoSlug ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
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
                                                        class="animate-spin rounded-full h-4 w-4 border-b-2 border-red-600">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        @if (!empty($slug))
                                            <div class="mt-2 p-2 bg-gray-50 dark:bg-gray-700 rounded text-sm">
                                                <span class="text-gray-600 dark:text-gray-400">Preview URL:</span>
                                                <span class="font-mono text-red-600 dark:text-red-400 ml-2">
                                                    /controversies/{{ $slug }}
                                                </span>
                                            </div>
                                        @endif

                                        @if ($autoSlug)
                                            <p class="mt-1 text-sm text-green-600 dark:text-green-400">
                                                ✓ Slug auto-generates as you type
                                            </p>
                                        @else
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">
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
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Controversy Details</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Provide the detailed content and sources for this controversy.</p>
                                    </div>

                                    <!-- Content with Quill Editor -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Controversy Content *
                                        </label>

                                        <x-quill-editor
                                            wire:model="content"
                                            placeholder="Write the detailed description of the controversy here..."
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

                                    <!-- Date & Source URL -->
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <!-- Date -->
                                        <x-flux::input type="date" label="Controversy Date" wire:model="date"
                                            helper="When did this controversy occur?" />

                                        <!-- Source URL -->
                                        <x-flux::input type="url" label="Source URL" wire:model="source_url"
                                            placeholder="https://example.com/source"
                                            helper="Link to reliable source for verification" />
                                    </div>
                                </div>
                            @endif

                            <!-- Step 3: Settings -->
                            @if ($currentStep == 3)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Status & Settings</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Configure visibility and resolution status.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Published Status -->
                                        <div class="space-y-4">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Visibility</h3>
                                            <x-flux::checkbox wire:model="is_published" label="Published"
                                                helper="Make this controversy publicly visible" />
                                        </div>

                                        <!-- Resolution Status -->
                                        <div class="space-y-4">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Resolution</h3>
                                            <x-flux::checkbox wire:model="is_resolved" label="Resolved"
                                                helper="Mark this controversy as resolved" />
                                        </div>
                                    </div>

                                    <!-- Status Summary -->
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Current Status</h4>
                                        <div class="flex items-center space-x-4 text-sm">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-3 h-3 rounded-full {{ $is_published ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                                                <span class="text-gray-600 dark:text-gray-400">
                                                    {{ $is_published ? 'Public' : 'Private' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-3 h-3 rounded-full {{ $is_resolved ? 'bg-blue-500' : 'bg-orange-500' }}"></div>
                                                <span class="text-gray-600 dark:text-gray-400">
                                                    {{ $is_resolved ? 'Resolved' : 'Unresolved' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between pt-8 border-t border-gray-200 dark:border-gray-600 mt-8">
                                @if ($currentStep > 1)
                                    <x-flux::button type="button" wire:click="previousStep" class="cursor-pointer bg-gray-600 hover:bg-gray-700">
                                        ← Previous
                                    </x-flux::button>
                                @else
                                    <div></div>
                                @endif

                                @if ($currentStep < 3)
                                    <x-flux::button type="button" wire:click="nextStep" class="cursor-pointer bg-red-600 hover:bg-red-700">
                                        Next Step →
                                    </x-flux::button>
                                @else
                                    <x-flux::button type="submit" class="cursor-pointer bg-red-600 hover:bg-red-700">
                                        ✓ {{ $editingId ? 'Update Controversy' : 'Create Controversy' }}
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
