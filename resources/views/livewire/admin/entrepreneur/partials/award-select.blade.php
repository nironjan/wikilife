<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Sports Awards & Recognition
        @if (count($award_ids) > 0)
            <span
                class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                {{ count($award_ids) }} selected
            </span>
        @endif
    </label>

    <div class="space-y-3">
        <!-- Search Input -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="award_search" type="text"
                class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Search for sports awards..." />
            @if ($award_search)
                <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                    <button type="button" wire:click="$set('award_search', '')"
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
        @if ($award_search)
            <div
                class="border border-gray-200 dark:border-gray-600 rounded-md max-h-60 overflow-y-auto bg-white dark:bg-gray-700 shadow-lg">
                @forelse($this->awards as $award)
                    @if (!in_array($award->id, $award_ids))
                        <button type="button" wire:click="addAward({{ $award->id }})"
                            class="w-full text-left px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 border-b border-gray-100 dark:border-gray-600 last:border-b-0">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $award->award_name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $award->organization }}
                                        @if ($award->awarded_at)
                                            • {{ $award->awarded_at->format('Y') }}
                                        @endif
                                    </div>
                                    @if ($award->person)
                                        <div class="text-xs text-gray-400 mt-1">
                                            Awarded to: {{ $award->person->display_name }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            </div>
                        </button>
                    @endif
                @empty
                    <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                        No sports awards found matching "{{ $award_search }}"
                    </div>
                @endforelse
            </div>
        @endif

        <!-- Selected Awards Display -->
        @if (count($award_ids) > 0)
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Selected Awards ({{ count($award_ids) }})
                    </span>
                    <button type="button" wire:click="clearAwards"
                        class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        Clear All
                    </button>
                </div>

                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach ($this->selectedAwards as $award)
                        <div
                            class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    {{ $award->award_name }}
                                </div>
                                <div class="text-xs text-blue-600 dark:text-blue-400">
                                    {{ $award->organization }}
                                    @if ($award->awarded_at)
                                        • {{ $award->awarded_at->format('Y') }}
                                    @endif
                                </div>
                            </div>
                            <button type="button" wire:click="removeAward({{ $award->id }})"
                                class="ml-4 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-md">
                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    No awards selected. Search and select awards above.
                </p>
            </div>
        @endif

        @error('award_ids')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
        @error('award_ids.*')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>
</div>
