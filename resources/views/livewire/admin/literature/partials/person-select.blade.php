<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Author/Writer *
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
            <input wire:model.live.debounce.300ms="person_search" type="text"
                class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Search for author or writer..." />
            @if ($person_search || $person_id)
                <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                    <button type="button" wire:click="clearPerson"
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
        @if ($person_search && !$person_id)
            <div
                class="border border-gray-200 dark:border-gray-600 rounded-md max-h-60 overflow-y-auto bg-white dark:bg-gray-700 shadow-lg">
                @forelse($this->people as $person)
                    <button type="button" wire:click="$set('person_id', '{{ $person->id }}')"
                        class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center space-x-3">
                        @if ($person->profile_image_url)
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ $person->profile_image_url }}"
                                alt="{{ $person->display_name }}">
                        @else
                            <div
                                class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ strtoupper(substr($person->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $person->display_name }}
                            </div>
                            @if ($person->primary_profession)
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $person->primary_profession }}
                                </div>
                            @endif
                        </div>
                    </button>
                @empty
                    <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                        No authors/writers found matching "{{ $person_search }}"
                    </div>
                @endforelse
            </div>
        @endif

        <!-- Selected Person Display -->
        @if ($person_id)
            <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md">
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
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ strtoupper(substr($selectedPerson->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-green-800 dark:text-green-200">
                                    {{ $selectedPerson->display_name }}
                                </div>
                                @if ($selectedPerson->primary_profession)
                                    <div class="text-xs text-green-600 dark:text-green-400">
                                        {{ $selectedPerson->primary_profession }}
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

        @error('person_id')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>
</div>
