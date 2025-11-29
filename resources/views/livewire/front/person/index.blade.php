<div>
<div class="bg-white min-h-screen pb-20"> {{-- Added bottom padding for mobile tab --}}
    {{-- SEO-Friendly Header Section --}}
    <div class="bg-gray-50 border-b">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Famous Personalities</h1>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Discover comprehensive profiles of notable figures from various fields including entertainment,
                    sports, politics, business, and more.
                </p>
            </div>

            {{-- Desktop Category Quick Links --}}
            <div class="mt-8 max-w-6xl mx-auto hidden lg:block">
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-4">Browse by Category:</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3">
                        @foreach($categories as $key => $name)
                        @php
                        $categoryConfig = config("professions.categories.{$key}");
                        $isActive = $category === $key;
                        @endphp
                        <a href="#" wire:click.prevent="$set('category', '{{ $key }}')"
                            class="flex flex-col items-center p-3 rounded-lg border-2 transition-all duration-200 {{ $isActive ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300 hover:bg-gray-50' }}">
                            <div class="text-blue-600 mb-2">
                                {!! $categoryConfig['icon'] ?? '' !!}
                            </div>
                            <span class="text-xs font-medium text-gray-700 text-center leading-tight">{{ $name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="max-w-6xl mx-auto px-4 py-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-700">Home</a>
                </li>
                <li>
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li>
                    <span class="text-gray-900 font-medium">Personalities</span>
                </li>
                @if($category && isset($categories[$category]))
                <li>
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li>
                    <span class="text-gray-600">{{ $categories[$category] }}</span>
                </li>
                @endif
                @if($profession)
                <li>
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li>
                    <span class="text-gray-600">{{ ucwords($profession) }}</span>
                </li>
                @endif
            </ol>
        </nav>
    </div>

    {{-- Main Content Grid --}}
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Left Sidebar - Search & Filters --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-4">
                    {{-- Search --}}
                    <div class="mb-6">
                        <label for="search" class="block text-sm font-semibold text-gray-900 mb-3">Search Personalities</label>
                        <div class="relative">
                            <input type="text" id="search" wire:model.live="search"
                                placeholder="Name, nickname..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Category Filter --}}
                    <div class="mb-6">
                        <label for="category" class="block text-sm font-semibold text-gray-900 mb-3">Category</label>
                        <select id="category" wire:model.live="category"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                            <option value="">All Categories</option>
                            @foreach($categories as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Profession Filter --}}
                    <div class="mb-6">
                        <label for="profession" class="block text-sm font-semibold text-gray-900 mb-3">Profession</label>
                        <select id="profession" wire:model.live="profession"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                            <option value="">All Professions</option>
                            @if($category)
                            @php
                            $categoryProfessions = $this->getProfessionsForCategory($category);
                            @endphp
                            @foreach($categoryProfessions as $prof)
                            <option value="{{ $prof }}">{{ ucwords($prof) }}</option>
                            @endforeach
                            @else
                            @php
                            $allProfessions = $this->getAllProfessions();
                            @endphp
                            @foreach($allProfessions as $prof)
                            <option value="{{ $prof }}">{{ ucwords($prof) }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Active Filters --}}
                    @if($search || $profession || $category)
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Active Filters</h4>
                        <div class="space-y-2">
                            @if($search)
                            <div class="flex items-center justify-between bg-blue-50 px-3 py-2 rounded">
                                <span class="text-sm text-blue-800">Search: "{{ $search }}"</span>
                                <button wire:click="$set('search', '')" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            @endif
                            @if($category && isset($categories[$category]))
                            <div class="flex items-center justify-between bg-blue-50 px-3 py-2 rounded">
                                <span class="text-sm text-blue-800">Category: {{ $categories[$category] }}</span>
                                <button wire:click="$set('category', '')" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            @endif
                            @if($profession)
                            <div class="flex items-center justify-between bg-blue-50 px-3 py-2 rounded">
                                <span class="text-sm text-blue-800">Profession: {{ ucwords($profession) }}</span>
                                <button wire:click="$set('profession', '')" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            @endif
                        </div>
                        <button wire:click="clearFilters"
                            class="w-full mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                            Clear All Filters
                        </button>
                    </div>
                    @endif

                    {{-- Results Count --}}
                    @if($people->count() > 0)
                    <div class="border-t border-gray-200 pt-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $people->total() }}</div>
                            <div class="text-sm text-gray-600">Personalities</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right Content - People Grid --}}
            <div class="lg:col-span-3">
                @if($people->count() > 0)
                {{-- Mobile Results Info --}}
                <div class="lg:hidden mb-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        Showing <span class="font-semibold">{{ $people->firstItem() }}-{{ $people->lastItem() }}</span>
                        of <span class="font-semibold">{{ $people->total() }}</span> personalities
                    </p>
                </div>

                {{-- People Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($people as $person)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-all duration-300">
                        <a href="{{ route('people.people.show', $person->slug) }}" class="block">
                            {{-- Profile Image --}}
                            <div class="aspect-w-3 aspect-h-4 bg-gray-100 overflow-hidden">
                                @if($person->profile_image_url)
                                <img src="{{ $person->imageSize(300, 400, 80) }}"
                                    alt="{{ $person->display_name }} - {{ $person->primary_profession ?? 'Personality' }}"
                                    loading="lazy"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                @endif
                            </div>

                            {{-- Person Info --}}
                            <div class="p-4">
                                <h2 class="font-bold text-gray-900 text-lg mb-2 line-clamp-1 group-hover:text-blue-600 transition-colors">
                                    {{ $person->display_name }}
                                </h2>

                                @if($person->primary_profession)
                                <p class="text-gray-600 text-sm mb-3 capitalize">
                                    {{ $person->primary_profession }}
                                </p>
                                @endif

                                {{-- Age and Status --}}
                                <div class="flex items-center justify-between text-xs">
                                    @if($person->age)
                                    <span class="text-gray-500">{{ $person->age }} years</span>
                                    @endif
                                    <span class="{{ $person->is_alive ? 'text-green-600 bg-green-50 px-2 py-1 rounded-full' : 'text-red-600 bg-red-50 px-2 py-1 rounded-full' }} text-xs font-medium">
                                        {{ $person->is_alive ? 'Alive' : 'Deceased' }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $people->links('components.pagination') }}
                </div>

                @else
                {{-- No Results --}}
                <div class="text-center py-16 bg-white rounded-xl border-2 border-dashed border-gray-200">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">No personalities found</h3>
                    <p class="mt-3 text-gray-500 max-w-md mx-auto">
                        @if($search || $profession || $category)
                        Try adjusting your search criteria or filters to find more results.
                        @else
                        No personalities are currently available. Please check back later.
                        @endif
                    </p>
                    @if($search || $profession || $category)
                    <button wire:click="clearFilters"
                        class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Clear All Filters
                    </button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Mobile Bottom Category Tabs --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-40">
        <div class="px-4 py-3">
            <h4 class="text-sm font-semibold text-gray-900 mb-3 px-2">Browse Categories</h4>
            <div class="flex space-x-3 overflow-x-auto pb-2 scrollbar-hide">
                @foreach($categories as $key => $name)
                @php
                $categoryConfig = config("professions.categories.{$key}");
                $isActive = $category === $key;
                @endphp
                <button wire:click="$set('category', '{{ $key }}')"
                    class="flex flex-col items-center p-3 rounded-xl border-2 transition-all duration-200 flex-shrink-0 min-w-[80px] {{ $isActive ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 bg-gray-50 text-gray-700 hover:border-blue-300' }}">
                    <div class="text-current mb-1">
                        {!! $categoryConfig['icon'] ?? '' !!}
                    </div>
                    <span class="text-xs font-medium text-center leading-tight whitespace-nowrap">{{ $name }}</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Add this CSS for hiding scrollbar on mobile --}}
<style>
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>



</div>

{{-- Structured Data for SEO --}}
<script type="application/ld+json">
    @json($structuredData['itemList'])
</script>

<script type="application/ld+json">
    @json($structuredData['breadcrumb'])
</script>

<script type="application/ld+json">
    @json($structuredData['website'])
</script>
