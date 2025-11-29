<div class="relative" x-data="{
    suggestionsOpen: false,
    openSuggestions() {
        this.suggestionsOpen = true;
    },
    closeSuggestions() {
        this.suggestionsOpen = false;
    }
}" x-on:keydown.escape="closeSuggestions()">

    <form wire:submit.prevent="performSearch" class="relative">
        <div class="relative">
            <input
                type="text"
                wire:model.live="search"
                x-on:focus="openSuggestions()"
                x-on:click.away="closeSuggestions()"
                placeholder="{{ $placeholder }}"
                class="w-full bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 placeholder-gray-400 {{ $variant === 'hero' ? 'pl-5 pr-12 py-3 text-base rounded-xl' : ($variant === 'compact' ? 'pl-3 pr-10 py-2 text-sm' : 'pl-4 pr-11 py-2.5') }} {{ $inputClass }}"
            >

            @if($search)
                <button
                    type="button"
                    wire:click="clearSearch"
                    class="absolute right-8 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1 rounded-lg transition duration-150"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif

            @if($showButton)
                <button
                    type="submit"
                    aria-label="Search"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-red-600 p-1 rounded-lg transition duration-150 {{ $buttonClass }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            @endif
        </div>
    </form>


    <div wire:loading class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-gray-200 z-50 p-4">
        <div class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm text-gray-600">Searching...</span>
        </div>
    </div>


    <div
        x-show="suggestionsOpen"
        class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-gray-200 z-50 max-h-80 overflow-y-auto"
        x-cloak
        wire:loading.remove
    >
        @if($search && strlen($search) >= 2)

            @if(count($searchSuggestions['people'] ?? []) > 0)
                <div class="p-3 border-b border-gray-100">
                    <h3 class="text-xs font-semibold text-gray-900 mb-2 uppercase tracking-wide">People ({{ count($searchSuggestions['people']) }})</h3>
                    <div class="space-y-1">
                        @foreach($searchSuggestions['people'] as $person)
                            <button
                                wire:click="searchBySuggestion('{{ $person->slug }}')"
                                class="w-full flex items-center p-2 hover:bg-red-50 rounded-lg transition duration-150 text-left group"
                            >
                                @if($person->profile_image_url)
                                    <img
                                        src="{{ $person->imageSize(32, 32, 80) }}"
                                        alt="{{ $person->name }}"
                                        class="w-8 h-8 rounded-full object-cover mr-3 flex-shrink-0"
                                    >
                                @else
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $person->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">
                                        @if(!empty($person->professions))
                                            {{ implode(', ', array_slice($person->professions, 0, 2)) }}
                                        @else
                                            No profession specified
                                        @endif
                                    </p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif


            @if(count($searchSuggestions['professions'] ?? []) > 0)
                <div class="p-3 border-b border-gray-100">
                    <h3 class="text-xs font-semibold text-gray-900 mb-2 uppercase tracking-wide">Professions ({{ count($searchSuggestions['professions']) }})</h3>
                    <div class="space-y-1">
                        @foreach($searchSuggestions['professions'] as $profession)
                            <a
                                href="{{ $profession['url'] }}"
                                class="w-full flex items-center justify-between p-2 hover:bg-red-50 rounded-lg transition duration-150 group"
                            >
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $profession['name'] }}</p>
                                        <p class="text-xs text-gray-500">Profession Category</p>
                                    </div>
                                </div>
                                <span class="text-xs text-blue-600 font-medium">Browse</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif


            @if(count($searchSuggestions['people'] ?? []) === 0 && count($searchSuggestions['professions'] ?? []) === 0)
                <div class="p-4 text-center text-gray-500">
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm">No results found for "{{ $searchSuggestions['query'] ?? $search }}"</p>
                </div>
            @endif

        @elseif((!$search || strlen($search) < 2) && $showTrending && count($trendingPeople) > 0)

            <div class="p-3">
                <h3 class="text-xs font-semibold text-gray-900 mb-2 uppercase tracking-wide text-left">Trending Biographies</h3>
                <div class="space-y-1">
                    @foreach($trendingPeople as $person)
                        <a
                            href="{{ route('people.people.show', $person->slug) }}"
                            class="flex items-center p-2 hover:bg-red-50 rounded-lg transition duration-150 group"
                        >
                            @if($person->profile_image_url)
                                <img
                                    src="{{ $person->imageSize(32, 32, 80) }}"
                                    alt="{{ $person->name }}"
                                    class="w-8 h-8 rounded-full object-cover mr-3 flex-shrink-0"
                                >
                            @else
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-start min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $person->name }}</p>
                                <p class="text-xs text-gray-500 truncate">
                                    @if(!empty($person->professions))
                                        {{ implode(', ', array_slice($person->professions, 0, 2)) }}
                                    @else
                                        No profession specified
                                    @endif
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
