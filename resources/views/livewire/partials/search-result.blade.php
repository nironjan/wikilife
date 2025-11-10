<div>
    <div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Search Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Search Results</h1>

                    @if($searchPerformed && $query)
                        <p class="text-gray-600">
                            @if($totalResults > 0)
                                Found <span class="font-semibold text-red-600">{{ $totalResults }}</span>
                                result{{ $totalResults > 1 ? 's' : '' }} for
                                "<span class="font-semibold">{{ $query }}</span>"
                            @else
                                No results found for "<span class="font-semibold">{{ $query }}</span>"
                            @endif
                        </p>
                    @else
                        <p class="text-gray-600">Enter a search term to find biographies</p>
                    @endif
                </div>

                <!-- Search Box -->
                <div class="w-80">
                    <form wire:submit.prevent="performSearch" class="relative">
                        <input
                            type="text"
                            wire:model="query"
                            placeholder="Search biographies..."
                            class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200"
                        >
                        <button
                            type="submit"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-600 transition duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        @if($searchPerformed)
            @if($totalResults > 0)
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($searchResults as $person)
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition duration-200 overflow-hidden">
                            <a href="{{ route('people.people.show', $person->slug) }}" class="block">
                                @if($person->profile_image_url)
                                    <img
                                        src="{{ $person->imageSize(400, 300, 80) }}"
                                        alt="{{ $person->name }}"
                                        class="w-full h-48 object-cover"
                                    >
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            <div class="p-4">
                                <a href="{{ route('people.people.show', $person->slug) }}" class="block">
                                    <h3 class="text-lg font-semibold text-gray-900 hover:text-red-600 transition duration-200 mb-2">
                                        {{ $person->name }}
                                    </h3>
                                </a>

                                @if($person->primary_profession)
                                    <p class="text-sm text-gray-600 mb-3">
                                        {{ $person->primary_profession }}
                                    </p>
                                @endif

                                @if($person->about)
                                    <p class="text-sm text-gray-500 line-clamp-2">
                                        {{ Str::limit(strip_tags($person->about), 120) }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                    <span class="text-xs text-gray-500">
                                        {{ number_format($person->view_count) }} views
                                    </span>
                                    <span class="text-xs text-green-600 font-medium">
                                        Verified
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($searchResults->hasPages())
                    <div class="mt-8">
                        {{ $searchResults->links() }}
                    </div>
                @endif

            @else
                <!-- No Results -->
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No results found</h3>
                    <p class="text-gray-600 mb-6">We couldn't find any biographies matching "{{ $query }}"</p>
                    <div class="space-y-4">
                        <p class="text-sm text-gray-500">Suggestions:</p>
                        <ul class="text-sm text-gray-500 list-disc list-inside space-y-1">
                            <li>Try different keywords</li>
                            <li>Check the spelling</li>
                            <li>Search by profession (e.g., "scientist", "actor")</li>
                            <li>Browse by <a href="{{ route('people.index') }}" class="text-red-600 hover:text-red-700">categories</a></li>
                        </ul>
                    </div>
                </div>
            @endif
        @else
            <!-- Search Prompt -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Search WikiLife Biographies</h3>
                <p class="text-gray-600">Enter a name, profession, or keyword to find biographies</p>
            </div>
        @endif
    </div>
</div>
</div>
