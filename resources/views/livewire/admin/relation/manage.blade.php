<div>
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Left Sidebar - Progress Steps -->
                    <div class="lg:w-1/3 bg-gray-50 dark:bg-gray-700 p-6 border-r border-gray-200 dark:border-gray-600">
                        <!-- Header -->
                        <div class="mb-8">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $editingId ? 'Edit Relation' : 'Create New Relation' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                Connect people and define their relationships
                            </p>
                        </div>

                        <!-- Progress Steps -->
                        <div class="space-y-4">
                            <!-- Step 1: People -->
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
                                        People
                                    </span>
                                </div>
                            </div>

                            <!-- Step 2: Relation Type -->
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
                                        Relation Details
                                    </span>
                                </div>
                            </div>

                            <!-- Step 3: Timeline & Notes -->
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
                                        Timeline & Notes
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
                                    ✓ {{ $editingId ? 'Update Relation' : 'Create Relation' }}
                                </x-flux::button>
                            @endif
                        </div>
                    </div>

                    <!-- Right Content - Form Steps -->
                    <div class="lg:w-2/3 p-6 lg:p-8">
                        <form wire:submit="save">
                            <!-- Step 1: People -->
                            <!-- Step 1: People -->
                            @if ($currentStep == 1)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">People
                                            Information</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Select the people involved in this
                                            relationship.</p>
                                    </div>

                                    <!-- Primary Person -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Person *
                                        </label>
                                        <div class="space-y-2">
                                            <!-- Search Input -->
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                    </svg>
                                                </div>
                                                <input wire:model.live.debounce.300ms="person_search" type="text"
                                                    class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    placeholder="Search for a person..." />
                                                @if ($person_search || $person_id)
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                                        <button type="button" wire:click="clearPerson"
                                                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Results Dropdown -->
                                            @if ($person_search && !$person_id)
                                                <div
                                                    class="border border-gray-200 dark:border-gray-600 rounded-md max-h-60 overflow-y-auto bg-white dark:bg-gray-700 shadow-lg">
                                                    @forelse($this->people as $person)
                                                        <button type="button"
                                                            wire:click="$set('person_id', '{{ $person->id }}')"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center space-x-3">
                                                            @if ($person->profile_image_url)
                                                                <img class="h-8 w-8 rounded-full object-cover"
                                                                    src="{{ $person->profile_image_url }}"
                                                                    alt="{{ $person->display_name }}">
                                                            @else
                                                                <div
                                                                    class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                                    <span
                                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                                        {{ strtoupper(substr($person->name, 0, 1)) }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <div
                                                                    class="text-sm font-medium text-gray-900 dark:text-white">
                                                                    {{ $person->display_name }}
                                                                </div>
                                                                @if ($person->primary_profession)
                                                                    <div
                                                                        class="text-xs text-gray-500 dark:text-gray-400">
                                                                        {{ $person->primary_profession }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </button>
                                                    @empty
                                                        <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                                            No people found matching "{{ $person_search }}"
                                                        </div>
                                                    @endforelse
                                                </div>
                                            @endif

                                            <!-- Selected Person Display -->
                                            @if ($person_id)
                                                <div
                                                    class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-3">
                                                            @php $selectedPerson = $this->people->firstWhere('id', $person_id) @endphp
                                                            @if ($selectedPerson)
                                                                @if ($selectedPerson->profile_image_url)
                                                                    <img class="h-10 w-10 rounded-full object-cover"
                                                                        src="{{ $selectedPerson->profile_image_url }}"
                                                                        alt="{{ $selectedPerson->display_name }}">
                                                                @else
                                                                    <div
                                                                        class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                                        <span
                                                                            class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                                            {{ strtoupper(substr($selectedPerson->name, 0, 1)) }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <div
                                                                        class="text-sm font-medium text-green-800 dark:text-green-200">
                                                                        {{ $selectedPerson->display_name }}
                                                                    </div>
                                                                    @if ($selectedPerson->primary_profession)
                                                                        <div
                                                                            class="text-xs text-green-600 dark:text-green-400">
                                                                            {{ $selectedPerson->primary_profession }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <span class="text-green-600 dark:text-green-400">
                                                            <svg class="h-5 w-5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                            @error('person_id')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Related Person Selection -->
                                    <div class="space-y-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Related Person
                                            </label>
                                            <div class="space-y-2">
                                                <!-- Search Input for Related Person -->
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <input wire:model.live.debounce.300ms="related_person_search"
                                                        type="text"
                                                        class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                        placeholder="Search for related person..." />
                                                    @if ($related_person_search || $related_person_id)
                                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                                            <button type="button" wire:click="clearRelatedPerson"
                                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                                <svg class="h-5 w-5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Results Dropdown -->
                                                @if ($related_person_search && !$related_person_id && empty($related_person_name))
                                                    <div
                                                        class="border border-gray-200 dark:border-gray-600 rounded-md max-h-60 overflow-y-auto bg-white dark:bg-gray-700 shadow-lg">
                                                        @forelse($this->relatedPeople as $person)
                                                            <button type="button"
                                                                wire:click="$set('related_person_id', '{{ $person->id }}')"
                                                                class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center space-x-3">
                                                                @if ($person->profile_image_url)
                                                                    <img class="h-8 w-8 rounded-full object-cover"
                                                                        src="{{ $person->profile_image_url }}"
                                                                        alt="{{ $person->display_name }}">
                                                                @else
                                                                    <div
                                                                        class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                                        <span
                                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                                            {{ strtoupper(substr($person->name, 0, 1)) }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <div
                                                                        class="text-sm font-medium text-gray-900 dark:text-white">
                                                                        {{ $person->display_name }}
                                                                    </div>
                                                                    @if ($person->primary_profession)
                                                                        <div
                                                                            class="text-xs text-gray-500 dark:text-gray-400">
                                                                            {{ $person->primary_profession }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </button>
                                                        @empty
                                                            <div
                                                                class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                                                No people found matching "{{ $related_person_search }}"
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                @endif

                                                <!-- Selected Related Person Display -->
                                                @if ($related_person_id)
                                                    <div
                                                        class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center space-x-3">
                                                                @php $selectedRelatedPerson = $this->relatedPeople->firstWhere('id', $related_person_id) @endphp
                                                                @if ($selectedRelatedPerson)
                                                                    @if ($selectedRelatedPerson->profile_image_url)
                                                                        <img class="h-10 w-10 rounded-full object-cover"
                                                                            src="{{ $selectedRelatedPerson->profile_image_url }}"
                                                                            alt="{{ $selectedRelatedPerson->display_name }}">
                                                                    @else
                                                                        <div
                                                                            class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                                            <span
                                                                                class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                                                {{ strtoupper(substr($selectedRelatedPerson->name, 0, 1)) }}
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                    <div>
                                                                        <div
                                                                            class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                                                            {{ $selectedRelatedPerson->display_name }}
                                                                        </div>
                                                                        @if ($selectedRelatedPerson->primary_profession)
                                                                            <div
                                                                                class="text-xs text-blue-600 dark:text-blue-400">
                                                                                {{ $selectedRelatedPerson->primary_profession }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <span class="text-blue-600 dark:text-blue-400">
                                                                <svg class="h-5 w-5" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="text-center text-gray-500 dark:text-gray-400">OR</div>

                                        <!-- Manual Name Input -->
                                        <x-flux::input label="Related Person Name" wire:model="related_person_name"
                                            placeholder="Enter name if not in database"
                                            helper="Required if related person is not selected from database above" />
                                    </div>
                                </div>
                            @endif

                            <!-- Step 2: Relation Details -->
                            @if ($currentStep == 2)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Relation
                                            Details</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Define the type and nature of the
                                            relationship.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Relation Type -->
                                        <x-flux::select label="Relation Type *" wire:model="relation_type" required>
                                            <option value="">Select Relation Type</option>
                                            @foreach ($relationTypes as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </x-flux::select>

                                        <!-- Marital Status -->
                                        <x-flux::select label="Marital Status" wire:model="marital_status">
                                            <option value="">Select Marital Status</option>
                                            @foreach ($maritalStatuses as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </x-flux::select>
                                    </div>

                                    <!-- Reciprocal -->
                                    <x-flux::checkbox wire:model="is_reciprocal" label="Reciprocal Relationship"
                                        helper="Check if this relationship works both ways (e.g., if A is parent of B, then B is child of A)" />
                                </div>
                            @endif

                            <!-- Step 3: Timeline & Notes -->
                            @if ($currentStep == 3)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Timeline &
                                            Additional Information</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Add timeline details and any
                                            additional notes.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- Since Year -->
                                        <x-flux::input label="Start Year" wire:model="since" type="number"
                                            min="1900" max="{{ date('Y') + 1 }}" placeholder="e.g., 1990" />

                                        <!-- Until Year -->
                                        <x-flux::input label="End Year" wire:model="until" type="number"
                                            min="1900" max="{{ date('Y') + 1 }}" placeholder="e.g., 2020" />

                                        <!-- Death Year -->
                                        <x-flux::input label="Death Year (Related Person)"
                                            wire:model="related_person_death_year" type="number" min="1900"
                                            max="{{ date('Y') + 1 }}" placeholder="If applicable" />
                                    </div>

                                    <!-- Notes -->
                                    <x-flux::textarea label="Additional Notes" wire:model="notes"
                                        placeholder="Any additional information about this relationship..."
                                        rows="4" />
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
                                        ✓ {{ $editingId ? 'Update Relation' : 'Create Relation' }}
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
