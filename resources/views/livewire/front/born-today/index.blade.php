<div class="min-h-screen bg-white">
    {{-- SEO Meta Tags are automatically handled by the component --}}

    {{-- Breadcrumb --}}
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 transition-colors">Home</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-gray-400 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <a href="{{ route('people.people.index') }}" class="text-blue-600 hover:text-blue-800 transition-colors">Personalities</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-gray-400 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="text-gray-600 font-medium">Born Today</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Section Header --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Born Today</h1>
                    <p class="text-gray-600 text-sm">Celebrating famous personalities born on {{ $today->format('F j, Y') }}</p>
                </div>
            </div>

            {{-- Date Display --}}
            <div class="hidden sm:flex items-center px-4 py-2 bg-white rounded-lg border border-gray-200">
                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm font-semibold text-gray-700">{{ $today->format('F j, Y') }}</span>
            </div>
        </div>

        @if($people->count() > 0)
            {{-- People Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
                @foreach($people as $person)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group border border-gray-100">
                    <a href="{{ route('people.people.show', $person->slug) }}" class="block">
                        {{-- Profile Image with Age Badge --}}
                        <div class="relative overflow-hidden">
                            @if($person->profile_image_url)
                            <img src="{{ $person->imageSize(400, 500, 85) }}"
                                alt="{{ $person->name }} - {{ $person->primary_profession ?? 'Personality' }} born on {{ $person->birth_date?->format('F j, Y') }}"
                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy">
                            @else
                            <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            @endif

                            {{-- Status Badge --}}
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $person->is_alive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $person->is_alive ? 'Alive' : 'Remembered' }}
                                </span>
                            </div>
                        </div>

                        {{-- Person Info --}}
                        <div class="p-4">
                            <h2 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-green-600 transition-colors line-clamp-1">
                                {{ $person->name }}
                            </h2>

                            @if($person->primary_profession)
                            <p class="text-gray-600 text-sm mb-3 capitalize">
                                {{ $person->primary_profession }}
                            </p>
                            @endif

                            {{-- Birth Date --}}
                            <div class="flex items-center text-xs text-gray-500 mb-3">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Born {{ $person->birth_date?->format('M j, Y') }}</span>
                            </div>

                            {{-- View Count --}}
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div class="flex items-center text-xs text-gray-500">
                                    @if($person->age)
                                    {{ $person->age }} years
                                    @endif
                                </div>
                                <span class="text-green-600 font-semibold text-sm group-hover:translate-x-1 transition-transform duration-200">
                                    Read Bio →
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($people->hasPages())
            <div class="mb-12">
                {{ $people->links('components.pagination') }}
            </div>
            @endif

            {{-- Browse All Personalities Section --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Explore All Personalities</h2>
                            <p class="text-gray-600 text-sm">Discover thousands of inspiring biographies across various professions</p>
                        </div>
                    </div>

                    {{-- View All Link --}}
                    <a href="{{ route('people.people.index') }}"
                        class="hidden sm:flex items-center bg-white text-blue-600 hover:text-blue-700 font-medium px-6 py-3 rounded-lg border border-blue-200 hover:border-blue-300 transition-colors shadow-sm">
                        Browse All
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                {{-- Mobile View All Button --}}
                <div class="sm:hidden mt-4">
                    <a href="{{ route('people.people.index') }}"
                        class="w-full flex items-center justify-center bg-white text-blue-600 hover:text-blue-700 font-medium px-6 py-3 rounded-lg border border-blue-200 hover:border-blue-300 transition-colors shadow-sm">
                        Browse All Personalities
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

        @else
            {{-- Empty State --}}
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No Birthdays Today</h3>
                    <p class="text-gray-600 mb-8">
                        There are no notable personalities in our database born on {{ $today->format('F j') }}.
                        Check back tomorrow or explore our complete collection of biographies.
                    </p>
                    <a href="{{ route('people.people.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        Explore All Personalities
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- Popular Persons Section --}}
    <livewire:front.popular-person.index lazy="on-load" />
</div>

{{-- Multiple Structured Data Schemas --}}
<script type="application/ld+json">
    @json($structuredData['itemList'])
</script>

<script type="application/ld+json">
    @json($structuredData['webPage'])
</script>
