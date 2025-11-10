<div>
    <div class="bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="mb-8">
                @if($category)
                    <div class="flex items-center mb-4">
                        <div class="text-blue-600 mr-3">
                            {!! $category['icon'] !!}
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $category['name'] }}</h1>
                    </div>
                    <p class="text-gray-600 mb-4">
                        Browse famous personalities in {{ strtolower($category['name']) }} field
                    </p>
                @else
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">
                        {{ ucwords(str_replace('-', ' ', $professionName)) }}s
                    </h1>
                @endif
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <input
                            type="text"
                            wire:model.live="search"
                            placeholder="Search by name..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>

                    <!-- Filters -->
                    <div class="flex flex-wrap gap-4">
                        <!-- Sort By -->
                        <select wire:model.live="sortBy" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="name">Sort by Name</option>
                            <option value="popular">Sort by Popularity</option>
                            <option value="latest">Sort by Latest</option>
                        </select>

                        <!-- Status -->
                        <select wire:model.live="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="all">All Status</option>
                            <option value="alive">Alive</option>
                            <option value="deceased">Deceased</option>
                        </select>

                        <!-- Clear Filters -->
                        <button
                            wire:click="clearFilters"
                            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50"
                        >
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Profession Tags (for categories) -->
            @if(count($professionList) > 1)
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Related Professions</h3>
                            <p class="text-sm text-gray-600">Click on any profession to filter results</p>
                        </div>

                        <!-- Sort Options for Tags -->
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600">Sort by:</span>
                            <select
                                wire:change="sortProfessions($event.target.value)"
                                class="text-sm border border-gray-300 rounded-md px-2 py-1 focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="name">Name A-Z</option>
                                <option value="name-desc">Name Z-A</option>
                                <option value="count">Most People</option>
                                <option value="count-asc">Fewest People</option>
                            </select>
                        </div>
                    </div>

                    <!-- Profession Tags Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                        @foreach($displayProfessions as $profession)
                            @php
                                $count = $professionCounts[$profession] ?? 0;
                                $professionSlug = \Illuminate\Support\Str::slug($profession);
                                $isCurrentProfession = $professionSlug === $this->professionName;
                            @endphp

                            @if($isCurrentProfession)
                                <!-- Current profession - show as selected -->
                                <div class="text-left p-2 border rounded-lg bg-blue-100 border-blue-300 ring-2 ring-blue-200">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-medium text-xs text-blue-800 line-clamp-1">
                                            {{ ucwords($profession) }}
                                        </span>
                                        <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-blue-600">
                                            {{ $count }} {{ Str::plural('person', $count) }}
                                        </span>
                                        <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            @else
                                <!-- Other professions - show as clickable links -->
                                <a
                                    href="{{ route('people.profession.details', ['professionName' => $professionSlug]) }}"
                                    class="text-left p-2 border rounded-lg transition-all duration-200 hover:shadow-md bg-white border-gray-200 hover:border-gray-300 block"
                                >
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-medium text-xs text-gray-900 line-clamp-1">
                                            {{ ucwords($profession) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">
                                            {{ $count }} {{ Str::plural('person', $count) }}
                                        </span>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>

                    <!-- Show More/Less Toggle -->
                    @if(count($professionList) > 10)
                        <div class="mt-4 text-center">
                            <button
                                wire:click="toggleShowAllProfessions"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            >
                                @if($showAllProfessions)
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                    Show Less
                                    <span class="text-gray-500 ml-1">(First 10)</span>
                                @else
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                    Show All {{ count($professionList) }} Professions
                                @endif
                            </button>

                            @if(!$showAllProfessions)
                                <p class="text-xs text-gray-500 mt-2">
                                    Showing 10 of {{ count($professionList) }} professions
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <!-- People Grid -->
            @if($people->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-6 mb-8">
                    @foreach($people as $person)
                        <a
                            href="{{ route('people.people.show', ['slug' => $person->slug]) }}"
                            class="bg-white rounded-lg shadow hover:shadow-md transition-shadow duration-300 overflow-hidden"
                        >
                            <!-- Profile Image -->
                            <div class="aspect-w-3 aspect-h-4 bg-gray-200">
                                @if($person->profile_image_url)
                                    <img
                                        src="{{ $person->imageSize(300, 400) }}"
                                        alt="{{ $person->display_name }}"
                                        class="w-full h-48 object-cover"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">
                                    {{ $person->display_name }}
                                </h3>

                                <!-- Primary Profession -->
                                @if($person->primary_profession)
                                    <p class="text-sm text-blue-600 mb-2">
                                        {{ $person->primary_profession }}
                                    </p>
                                @endif

                                <!-- Age/Status -->
                                <div class="flex items-center text-xs text-gray-500 mb-2">
                                    @if($person->is_alive)
                                        <span class="inline-flex items-center">
                                            <svg class="w-3 h-3 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Alive
                                            @if($person->age)
                                                · {{ $person->age }} years
                                            @endif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-red-600">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            Deceased
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $people->links('components.pagination') }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No personalities found</h3>
                    <p class="text-gray-600 mb-4">
                        @if(!empty($search))
                            No results found for "{{ $search }}". Try adjusting your search or filters.
                        @else
                            No personalities found in this category with the current filters.
                        @endif
                    </p>
                    <button
                        wire:click="clearFilters"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                    >
                        Clear Filters
                    </button>
                </div>
            @endif
        </div>
    </div>
    <livewire:front.popular-person.index lazy="on-load" />
</div>
