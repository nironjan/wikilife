<div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                Recent Personalities
            </h2>
            <p class="text-sm text-gray-600 mt-1">Latest added verified profiles</p>
        </div>

        {{-- Persons Grid --}}
        <div class="p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($persons as $person)
                <a href="{{ route('people.people.show', $person['slug']) }}"
                    class="group block bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 overflow-hidden"
                    wire:key="person-{{ $person['id'] }}">
                    {{-- Image Container --}}
                    <div class="relative overflow-hidden bg-gray-100">
                        @if($person['profile_image_small'])
                        <img src="{{ $person['profile_image_small'] }}" alt="{{ $person['name'] }}"
                            class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy">
                        @else
                        <div
                            class="w-full h-48 bg-linear-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        @endif

                        {{-- Status Badge --}}
                        <div class="absolute top-2 right-2">
                            @if($person['is_alive'])
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                Alive
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <span class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1"></span>
                                Deceased
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-4">
                        {{-- Title --}}
                        <h3
                            class="font-semibold text-gray-900 group-hover:text-blue-600 line-clamp-2 transition-colors duration-200 text-sm leading-tight mb-2">
                            {{ $person['seo_title'] }}
                        </h3>

                        {{-- Profession & Age --}}
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            @if($person['primary_profession'])
                            <span class="font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                {{ $person['primary_profession'] }}
                            </span>
                            @endif

                            @if($person['age'])
                            <span class="text-gray-500">
                                {{ $person['age'] }} years
                            </span>
                            @endif
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <p class="text-gray-500 text-lg">No recent persons found</p>
                    <p class="text-gray-400 text-sm mt-1">Check back later for new additions</p>
                </div>
                @endforelse
            </div>

            {{-- View All Button --}}
            @if(count($persons) > 0)
            <div class="mt-6 text-center">
                <a href="{{ route('people.people.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200">
                    View All Personalities
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
