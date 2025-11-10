<div class="fixed inset-0 bg-white z-50 lg:hidden" x-data="{ open: @entangle('isOpen') }" x-show="open" x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">

    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Search WikiLife</h3>
            <button wire:click="close" class="p-2 text-gray-400 hover:text-gray-600 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Search Input -->
        <form wire:submit.prevent="performSearch" class="relative">
            <div class="relative">
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Search biographies, people, professions..."
                    class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 cursor-pointer"
                    autofocus
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

                <button
                    type="submit"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-600 transition duration-200 cursor-pointer"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Mobile Search Suggestions -->
    <div class="overflow-y-auto h-[calc(100vh-120px)]">
        @if($search && strlen($search) >= 2)
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Search Suggestions</h4>
                <div class="space-y-3">
                    <!-- People Suggestions -->
                    @if(isset($searchSuggestions['people']) && $searchSuggestions['people']->count() > 0)
                        @foreach($searchSuggestions['people'] as $person)
                            <button wire:click="searchBySuggestion('person', '{{ $person->slug }}', '{{ $person->name }}')"
                                class="w-full flex items-center p-3 bg-gray-50 rounded-lg hover:bg-red-50 transition duration-200 text-left">
                                @if($person->profile_image_url)
                                    <img src="{{ $person->imageSize(40, 40, 80) }}" alt="{{ $person->name }}"
                                        class="w-12 h-12 rounded-full object-cover mr-3 shrink-0">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-3 shrink-0">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $person->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $person->primary_profession ?? 'Not Specified' }}</p>
                                </div>

                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @endforeach
                    @endif

                    <!-- Profession Suggestions -->
                    @if(isset($searchSuggestions['professions']) && count($searchSuggestions['professions']) > 0)
                        @foreach($searchSuggestions['professions'] as $profession)
                            <button wire:click="searchBySuggestion('profession', '{{ $profession['url'] }}', '{{ $profession['name'] }}')"
                                class="w-full flex items-center p-3 bg-gray-50 rounded-lg hover:bg-red-50 transition duration-200 text-left">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3 shrink-0">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $profession['name'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Profession Category</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @endforeach
                    @endif

                    <!-- No Results Message -->
                    @if((!isset($searchSuggestions['people']) || $searchSuggestions['people']->count() == 0) && (!isset($searchSuggestions['professions']) || count($searchSuggestions['professions']) == 0))
                        <div class="text-center py-4">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-gray-500">No suggestions found for "{{ $search }}"</p>
                        </div>
                    @endif

                    <!-- View All Results Link -->
                    @if(isset($searchSuggestions['people']) && $searchSuggestions['people']->count() > 0)
                        <div class="pt-2 border-t border-gray-200">
                            <a href="{{ route('people.people.search', ['query' => $search]) }}"
                                class="flex items-center justify-center text-sm text-red-600 hover:text-red-700 font-medium py-2">
                                View all results for "{{ $search }}"
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Trending Suggestions when no search term -->
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Trending Biographies</h4>
                <div class="space-y-3">
                    @foreach($trendingPeople as $person)
                        <a href="{{ route('people.people.show', $person->slug) }}"
                            class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-red-50 transition duration-200">
                            @if($person->profile_image_url)
                                <img src="{{ $person->imageSize(40, 40, 80) }}" alt="{{ $person->name }}"
                                    class="w-12 h-12 rounded-full object-cover mr-3 shrink-0">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-3 shrink-0">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $person->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $person->primary_profession ?? 'Not Specified' }}</p>
                            </div>

                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
