<div>
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Left Sidebar - Progress Steps -->
                    <div class="lg:w-1/4 bg-gray-50 dark:bg-gray-700 p-6 border-r border-gray-200 dark:border-gray-600">
                        <!-- Header -->
                        <div class="mb-8">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $editingId ? 'Edit Filmography' : 'Add Filmography' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                Add movie credits and film information
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

                            <!-- Step 2: Movie Details -->
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
                                        Movie Details
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
                                <x-flux::button type="button" wire:click="nextStep" class="w-full">
                                    Next Step →
                                </x-flux::button>
                            @else
                                <x-flux::button type="button" wire:click="save" class="w-full">
                                    ✓ {{ $editingId ? 'Update Filmography' : 'Add Filmography' }}
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
                                            the film credit.</p>
                                    </div>

                                    <!-- Person Selection -->
                                    @include('livewire.admin.filmography.partials.person-select')

                                    <!-- Movie Title -->
                                    <x-flux::input label="Movie Title *" wire:model="movie_title"
                                        placeholder="Enter movie title" required />

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Release Date -->
                                        <x-flux::input label="Release Date" wire:model="release_date" type="date" />

                                        <!-- Profession Type -->
                                        <x-flux::select label="Profession Type *" wire:model="profession_type" required>
                                            @foreach ($professionTypes as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </x-flux::select>
                                    </div>

                                    <!-- Role (for actors) -->
                                    <x-flux::input label="Role / Character" wire:model="role"
                                        placeholder="e.g., John Wick, Tony Stark"
                                        helper="For actors - enter the character name. For other roles, describe the contribution." />
                                </div>
                            @endif

                            <!-- Step 2: Movie Details -->
                            @if ($currentStep == 2)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Movie Details
                                        </h2>
                                        <p class="text-gray-600 dark:text-gray-400">Provide additional information about
                                            the movie.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Industry -->
                                        <x-flux::input label="Industry" wire:model="industry"
                                            placeholder="e.g., Hollywood, Bollywood, Tollywood" />

                                        <!-- Production Company -->
                                        <x-flux::input label="Production Company" wire:model="production_company"
                                            placeholder="e.g., Warner Bros., Marvel Studios" />
                                    </div>

                                    <!-- Director Selection -->
                                    @include('livewire.admin.filmography.partials.director-select')

                                    <!-- Genres -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Genres
                                        </label>
                                        <div class="space-y-2">
                                            @foreach ($genres as $index => $genre)
                                                <div class="flex gap-2">
                                                    <x-flux::input wire:model="genres.{{ $index }}"
                                                        placeholder="Enter genre" class="flex-1" />
                                                    @if (count($genres) > 1)
                                                        <x-flux::button type="button"
                                                            wire:click="removeGenre({{ $index }})"
                                                            variant="danger" size="sm">
                                                            Remove
                                                        </x-flux::button>
                                                    @endif
                                                </div>
                                            @endforeach
                                            <x-flux::button type="button" wire:click="addGenre" class="cursor-pointer"
                                                size="sm">
                                                Add Genre
                                            </x-flux::button>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Common genres: {{ implode(', ', array_slice($commonGenres, 0, 5)) }}...
                                        </p>
                                    </div>

                                    <!-- Description -->
                                    <x-flux::textarea label="Description / Plot Summary" wire:model="description"
                                        placeholder="Brief description or plot summary of the movie..."
                                        rows="4" />
                                </div>
                            @endif

                            <!-- Step 3: Additional Information -->
                            @if ($currentStep == 3)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Additional
                                            Information</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Add financial data, awards, and
                                            verification status.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Box Office Collection -->
                                        <x-flux::input label="Box Office Collection" wire:model="box_office_collection"
                                            placeholder="e.g., $100M, ₹500 crore" helper="Total box office revenue" />

                                        <!-- Sort Order -->
                                        <x-flux::input label="Sort Order" wire:model="sort_order" type="number"
                                            min="0" helper="Lower numbers appear first" />
                                    </div>

                                    <!-- Award Selection -->
                                    @include('livewire.admin.filmography.partials.award-select')

                                    <!-- Verification -->
                                    <x-flux::checkbox wire:model="is_verified" label="Verified Information"
                                        helper="Mark this filmography entry as verified" />
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
                                    <x-flux::button type="button" wire:click="nextStep">
                                        Next Step →
                                    </x-flux::button>
                                @else
                                    <x-flux::button type="submit">
                                        ✓ {{ $editingId ? 'Update Filmography' : 'Add Filmography' }}
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
