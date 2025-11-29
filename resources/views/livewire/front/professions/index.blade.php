<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Professions & Categories</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-6">
                Explore our comprehensive directory of {{ $totalCategories }} professional categories and discover famous personalities from various fields
            </p>
        </div>

        {{-- Search and Filter Section --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4">
                {{-- Search Input --}}
                <div class="flex-1">
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Search professions or categories..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                {{-- Category Filter --}}
                <div class="flex-1">
                    <select
                        wire:model.live="selectedCategory"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">All Categories</option>
                        @foreach(config('professions.categories') as $key => $category)
                            <option value="{{ $key }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Clear Filters --}}
                @if($search || $selectedCategory)
                    <button
                        wire:click="clearFilters"
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200"
                    >
                        Clear
                    </button>
                @endif
            </div>

            {{-- Active Filters --}}
            @if($search || $selectedCategory)
                <div class="mt-4 text-sm text-gray-600">
                    Showing {{ $filteredCount }} of {{ $totalCategories }} categories
                    @if($search)
                        matching "{{ $search }}"
                    @endif
                    @if($selectedCategory)
                        in {{ config('professions.categories')[$selectedCategory]['name'] ?? '' }}
                    @endif
                </div>
            @endif
        </div>

        {{-- Popular Professions --}}
        @if(count($popularProfessions) > 0 && empty($search) && empty($selectedCategory))
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Popular Professions</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($popularProfessions as $profession => $count)
                        @php
                            $professionSlug = \Illuminate\Support\Str::slug($profession);
                        @endphp
                        <a
                            href="{{ route('people.profession.details', ['professionName' => $professionSlug]) }}"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200"
                        >
                            {{ $profession }}
                            <span class="ml-2 bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">
                                {{ $count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Categories Grid --}}
        @if(count($categories) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($categories as $key => $category)
                @php
                    $categorySlug = \Illuminate\Support\Str::slug($key);
                @endphp
                    <a
                        href="{{ route('people.profession.details', ['professionName' => $categorySlug]) }}"
                        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-200 group"
                    >
                        <div class="flex items-start space-x-4">
                            {{-- Icon --}}
                            <div class="flex-shrink-0 text-blue-600 group-hover:text-blue-700 transition-colors duration-200">
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                    {!! $category['icon'] !!}
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors duration-200 line-clamp-1">
                                    {{ $category['name'] }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-3">
                                    {{ count($category['professions']) }} professions
                                </p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($category['professions'], 0, 4) as $profession)
                                        <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                            {{ $profession }}
                                        </span>
                                    @endforeach
                                    @if(count($category['professions']) > 4)
                                        <span class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded font-medium">
                                            +{{ count($category['professions']) - 4 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Arrow --}}
                            <div class="flex-shrink-0 text-gray-400 group-hover:text-blue-600 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No categories found</h3>
                <p class="text-gray-600 mb-4">Try adjusting your search or filter criteria</p>
                <button
                    wire:click="clearFilters"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
                >
                    Clear Filters
                </button>
            </div>
        @endif
                {{-- Pagination --}}
        <div class="mt-10">
            {{ $categories->links('components.pagination') }}
        </div>

        <livewire:front.popular-person.index />
    </div>

    {{-- JSON-LD Structured Data --}}
    @isset($structuredData)
        @foreach($structuredData as $data)
            <script type="application/ld+json">
                {!! json_encode($data) !!}
            </script>
        @endforeach
    @endisset
</div>
