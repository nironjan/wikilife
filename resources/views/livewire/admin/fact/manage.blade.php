<div class="min-h-screen dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="mb-8">
            <div
                class="bg-white/80 dark:bg-gray-800/80 rounded-2xl shadow border border-gray-200/50 dark:border-gray-700/50 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h1
                                class="text-2xl font-bold text-gray-900 dark:text-white bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                                {{ $factId ? 'Edit Fact' : 'Add New Fact' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">
                                {{ $factId ? 'Update this interesting fact' : 'Share a fascinating piece of information' }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('webmaster.persons.facts.index') }}"
                        class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Facts
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200/60 dark:border-gray-700/60 overflow-hidden">
            <!-- Form Header -->
            <div
                class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 px-6 py-4 border-b border-gray-200/50 dark:border-gray-600/50">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-purple-600 rounded-full"></div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Fact Information
                    </h2>
                </div>
            </div>

            <form wire:submit="saveFact">
                <div class="p-6 lg:p-8 space-y-8">
                    <!-- Person Selection -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Select Person *
                        </label>
                        <div
                            class="bg-gray-50/50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200/50 dark:border-gray-600/50">
                            @include('livewire.admin.partials.person-select')
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Fact Title *
                        </label>
                        <div class="relative">
                            <x-flux::input wire:model="title" placeholder="Enter a captivating fact title..."
                                class="bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200" />
                        </div>
                        @error('title')
                            <p class="text-sm text-red-600 dark:text-red-400 flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            Category
                        </label>
                        <div class="relative">
                            <x-flux::input wire:model="category" placeholder="Choose or enter a category..."
                                list="categories"
                                class="bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200" />
                        </div>
                        <datalist id="categories">
                            @foreach ($categories as $category)
                                <option value="{{ $category }}">
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Fact Text -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Fact Details *
                        </label>
                        <div>


                            <x-quill-editor wire:model="fact_text" placeholder="Write the detailed facts of the person here..."
                                height="400px" toolbar="basic" />

                            <!-- Hidden field for validation -->
                            <input type="hidden" wire:model="fact_text" />

                            @error('fact_text')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Use the toolbar above to format your content with rich text editing.
                            </p>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="pt-8 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                All changes are saved securely
                            </div>
                            <x-flux::button type="submit" class="cursor-pointer">

                                {{ $factId ? 'Update Fact' : 'Create Fact' }}
                            </x-flux::button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div
            class="mt-6 bg-blue-50/50 dark:bg-blue-900/20 border border-blue-200/50 dark:border-blue-800/50 rounded-2xl p-6">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-800 dark:text-blue-300">
                    <p class="font-medium">Tips for great facts:</p>
                    <ul class="mt-2 space-y-1 list-disc list-inside opacity-90">
                        <li>Keep it interesting and surprising</li>
                        <li>Include specific details and numbers</li>
                        <li>Make it memorable and shareable</li>
                        <li>Verify accuracy before publishing</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
