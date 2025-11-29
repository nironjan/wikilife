<div class="max-w-6xl mx-auto bg-gray-50">
    {{-- Header --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6">
            {{-- Breadcrumb --}}
            <nav class="flex items-center space-x-1.5 sm:space-x-2 text-xs text-gray-600 mb-3 sm:mb-4">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors duration-200">Home</a>
                <span class="text-gray-400">›</span>
                <a href="{{ route('people.people.index') }}" class="hover:text-blue-600 transition-colors duration-200">People</a>
                <span class="text-gray-400">›</span>
                <a href="{{ route('people.states.index') }}" class="hover:text-blue-600 transition-colors duration-200">States</a>
                <span class="text-gray-400">›</span>
                <span class="text-gray-900 font-medium">{{ $stateName }}</span>
            </nav>

            {{-- Page Header --}}
            <div class="text-center lg:text-left">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
                    Famous People from {{ $stateName }}
                </h1>
                <p class="text-sm sm:text-base text-gray-600 mb-4">
                    Discover notable personalities, celebrities, and famous people from {{ $stateName }}
                </p>

                {{-- Stats --}}
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 text-sm text-gray-700">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="font-semibold">{{ $totalCount }}</span> People
                    </div>

                    @if($professions->count() > 0)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-semibold">{{ $professions->count() }}</span> Professions
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Filters and Controls --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 py-4">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                {{-- Search and Filters --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    {{-- Search --}}
                    <div class="relative">
                        <input type="text"
                               wire:model.live="search"
                               placeholder="Search people..."
                               class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64 text-sm">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    {{-- Profession Filter --}}
                    @if($professions->count() > 0)
                    <select wire:model.live="professionFilter"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-48 text-sm">
                        <option value="">All Professions</option>
                        @foreach($professions as $profession)
                            <option value="{{ $profession }}">{{ $profession }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>

                {{-- Sort and Reset --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    {{-- Sort --}}
                    <select wire:model.live="sortBy"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-40 text-sm">
                        <option value="name">Sort by Name</option>
                        <option value="view_count">Sort by Popularity</option>
                        <option value="birth_date">Sort by Birth Date</option>
                    </select>

                    {{-- Reset --}}
                    <button wire:click="resetFilters"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-sm font-medium">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 py-6 sm:py-8">
        @if($people->count() > 0)
            {{-- People Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @foreach($people as $person)
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group">
                        <a href="{{ route('people.people.show', $person->slug) }}" class="block">
                            {{-- Profile Image --}}
                            <div class="aspect-w-3 aspect-h-4 bg-gray-100 overflow-hidden">
                                @if($person->profile_image_url)
                                    <img src="{{ $person->imageSize(300, 400) ?? $person->profile_image_url }}"
                                         alt="{{ $person->display_name }}"
                                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-3 sm:p-4">
                                <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-1 line-clamp-1 group-hover:text-blue-600 transition-colors duration-200">
                                    {{ $person->name }}
                                </h3>

                                @if($person->full_name && $person->full_name !== $person->name)
                                    <p class="text-xs text-gray-600 mb-2 line-clamp-1">{{ $person->full_name }}</p>
                                @endif

                                {{-- Professions --}}
                                @if($person->professions && count($person->professions) > 0)
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach(array_slice($person->professions, 0, 2) as $profession)
                                            <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">
                                                {{ $profession }}
                                            </span>
                                        @endforeach
                                        @if(count($person->professions) > 2)
                                            <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">
                                                +{{ count($person->professions) - 2 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                {{-- Meta Info --}}
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    @if($person->birth_date)
                                        <span>Born {{ $person->birth_date->format('Y') }}</span>
                                    @endif

                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $people->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No People Found</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">
                    @if($search || $professionFilter)
                        No people found matching your search criteria in {{ $stateName }}. Try adjusting your filters.
                    @else
                        No people are currently listed for {{ $stateName }}. Check back later for updates.
                    @endif
                </p>
                @if($search || $professionFilter)
                    <button wire:click="resetFilters"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                        Clear Filters
                    </button>
                @endif
            </div>
        @endif
    </div>

    {{-- Structured Data --}}
    <script type="application/ld+json">
        {!! $structuredData !!}
    </script>
</div>
