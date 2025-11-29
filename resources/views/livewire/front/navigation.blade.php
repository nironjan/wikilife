<nav class="bg-white border-b border-gray-200 sticky top-0 z-50" x-data="{
    mobileMenuOpen: false,
    searchOpen: false,
    categoriesOpen: false
 }" @keydown.escape="searchOpen = false; mobileMenuOpen = false; categoriesOpen = false">

    {{-- Top Bar - WikiHow Style --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">W</span>
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-2xl font-bold text-gray-900">Wiki Life</span>
                            <span class="text-xs text-gray-500 block -mt-1">The Biography Encyclopedia</span>
                        </div>
                    </a>
                </div>

                {{-- Desktop Search Bar --}}
                <div class="hidden lg:block w-full mx-8">
                    <livewire:front.search-box
                        variant="default"
                        placeholder="Search biographies, people, professions..."
                        :show-trending="true"
                    />
                </div>

                {{-- Right Actions --}}
                <div class="flex items-center space-x-4">
                    {{-- Professions Dropdown --}}
                    <div class="hidden lg:block relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center cursor-pointer space-x-1 text-gray-700 hover:text-red-600 px-3 py-2 text-sm font-medium transition duration-200">
                            <span>Professions</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute left-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50"
                            x-cloak>
                            @foreach($popularCategories as $name => $slug)
                            <a href="{{ route('people.profession.details', $slug) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-200">
                                {{ $name }}
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Mobile Search Button --}}
                    <button @click="searchOpen = true" aria-label="Search"
                        class="lg:hidden p-2 cursor-pointer text-gray-600 hover:text-red-600 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    {{-- Today's Birthdays --}}
                    <a href="{{ route('people.born-today') }}"
                        class="hidden sm:flex items-center px-3 py-2 bg-green-700 text-white text-sm font-medium rounded-lg hover:bg-green-800 transition duration-200 whitespace-nowrap shrink-0">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 01118 0z"/>
                        </svg>
                        <span class="sr-only sm:not-sr-only sm:ml-2">Born Today</span>
                    </a>

                    {{-- Mobile menu button --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden p-2 text-gray-600 hover:text-red-600 transition duration-200 cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Search Overlay --}}
    <div x-show="searchOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-white z-50 lg:hidden flex flex-col" x-cloak>

        {{-- Header with Search - Fixed --}}
        <div class="shrink-0 bg-white border-b border-gray-200">
            <div class="p-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Search</h2>
                        <p class="text-sm text-gray-500 mt-1">Find biographies and professions</p>
                    </div>
                    <button @click="searchOpen = false"
                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition duration-200 cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Search Box --}}
                <div class="relative">
                    <livewire:front.search-box
                        variant="compact"
                        placeholder="Search people, professions..."
                        :show-trending="true"
                    />
                </div>
            </div>
        </div>

        {{-- Scrollable Content Area --}}
        <div class="flex-1 overflow-y-auto">
            {{-- Quick Categories Section --}}
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-900">Popular Categories</h3>
                    <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-full">{{ count($popularCategories) }}+</span>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    @foreach(array_slice($popularCategories, 0, 6) as $name => $slug)
                    <a href="{{ route('people.profession.details', $slug) }}"
                        class="group flex items-center justify-between p-3 bg-white text-gray-700 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all duration-200 border border-gray-200 hover:border-red-200 hover:shadow-sm">
                        <span class="text-sm font-medium">{{ $name }}</span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-red-500 transition duration-200 opacity-0 group-hover:opacity-100"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    @endforeach
                </div>

                {{-- View All Categories --}}
                <div class="mt-4 pt-3 border-t border-gray-200">
                    <a href="{{ route('people.profession.index') }}"
                        class="flex items-center justify-center w-full py-3 bg-white text-red-600 hover:text-red-700 hover:bg-red-50 font-medium rounded-xl border border-gray-200 transition duration-200 group">
                        <span class="text-sm font-semibold">Browse All Categories</span>
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="p-4">
                <h3 class="text-base font-semibold text-gray-900 mb-3">Quick Access</h3>
                <div class="space-y-2">
                    {{-- Born Today --}}
                    <a href="{{ route('people.born-today') }}"
                        class="flex items-center justify-between p-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:shadow-lg transition-all duration-200 group">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold">Born Today</p>
                                <p class="text-xs text-green-100">Celebrating birthdays</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-white opacity-80 group-hover:translate-x-1 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    {{-- Recent Updates --}}
                    <a href="{{ route('people.people.index') }}"
                        class="flex items-center justify-between p-3 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition duration-200 group border border-blue-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold">Recent Updates</p>
                                <p class="text-xs text-blue-600">New biographies added</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-blue-600 opacity-70 group-hover:translate-x-1 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    {{-- Contribute --}}
                    <a href="#"
                        class="flex items-center justify-between p-3 bg-red-50 text-red-700 rounded-xl hover:bg-red-100 transition duration-200 group border border-red-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold">Contribute</p>
                                <p class="text-xs text-red-600">Add to WikiLife</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-red-600 opacity-70 group-hover:translate-x-1 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Footer Stats --}}
            <div class="p-4 bg-gray-900 text-white">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-lg font-bold text-white">10K+</div>
                        <div class="text-xs text-gray-300">Biographies</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-white">50+</div>
                        <div class="text-xs text-gray-300">Professions</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-white">1M+</div>
                        <div class="text-xs text-gray-300">Readers</div>
                    </div>
                </div>
                <div class="text-center mt-3 pt-3 border-t border-gray-700">
                    <p class="text-xs text-gray-400">WikiLife - India's Biography Encyclopedia</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="lg:hidden bg-white border-t border-gray-200 shadow-lg fixed inset-0 z-40 mt-16 overflow-y-auto"
        x-cloak>
        <div class="p-4 space-y-6">
            {{-- Professions Section --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Professions</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($popularCategories as $name => $slug)
                    <a href="{{ route('people.profession.details', $slug) }}"
                        class="text-center px-3 py-3 bg-gray-50 text-sm text-gray-700 rounded-lg hover:bg-red-50 hover:text-red-600 transition duration-200 border border-gray-200">
                        {{ $name }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="space-y-3">
                <a href="#"
                    class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Contribute to WikiLife
                </a>
            </div>
        </div>
    </div>
</nav>
