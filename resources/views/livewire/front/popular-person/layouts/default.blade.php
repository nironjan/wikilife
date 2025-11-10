<!-- Your existing complex layout here -->
<div class="space-y-8">
    <!-- First Row: 2-column layout -->
    @if($popularPersons->count() >= 3)
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <!-- Left: Featured Person Card -->
                    <article class="bg-white rounded-lg shadow-md overflow-hidden group h-full border border-gray-100">
                        @php $featuredPerson = $popularPersons[0]; @endphp
                        <a href="{{ route('people.people.show', $featuredPerson->slug) }}" class="block h-full"
                            aria-label="View profile of {{ $featuredPerson->name }}">
                            <div class="flex flex-col lg:flex-row h-full">
                                <!-- Image Section -->
                                <div class="lg:w-2/5 relative">
                                    @if($featuredPerson->profile_image_url)
                                    <img src="{{ $featuredPerson->imageSize(400, 500, 85) }}"
                                        alt="{{ $featuredPerson->name }} - {{ $featuredPerson->primary_profession ?? 'Personality' }}"
                                        class="w-full h-64 lg:h-full object-cover group-hover:scale-105 transition duration-500"
                                        loading="eager" width="400" height="500">
                                    @else
                                    <div class="w-full h-64 lg:h-full bg-linear-to-br from-blue-50 to-blue-100 flex items-center justify-center"
                                        role="img" aria-label="Profile image placeholder">
                                        <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    @endif

                                    <!-- Popular Badge -->
                                    <div class="absolute top-4 left-4" aria-label="Most Popular Personality">
                                        <div
                                            class="flex items-center bg-linear-to-r from-yellow-500 to-yellow-600 text-white px-3 py-2 rounded-full text-xs font-bold shadow-lg">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                                aria-hidden="true">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            #1 Popular
                                        </div>
                                    </div>
                                </div>

                                <!-- Content Section -->
                                <div class="lg:w-3/5 p-6 flex flex-col justify-between">
                                    <div>
                                        <header class="flex flex-row gap-1">
                                            <h2
                                                class="text-xl font-bold text-gray-900 group-hover:text-yellow-700 transition-colors duration-200 leading-tight line-clamp-2">
                                                {{ $featuredPerson->name }}
                                            </h2>

                                        </header>

                                        @if($featuredPerson->primary_profession)
                                        <div class="flex items-center text-gray-600 font-semibold text-sm mb-4">
                                            <span>{{ $featuredPerson->primary_profession }}</span>
                                        </div>
                                        @endif

                                        <!-- Meta Information -->
                                        <div class="space-y-3 mb-4">


                                            @if($featuredPerson->about)
                                            <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                                                {{ Str::limit(strip_tags($featuredPerson->about), 120) }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Stats & Verification -->
                                    <footer
                                        class="flex items-center justify-between pt-4 border-t border-gray-100 mt-4">
                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            @if($featuredPerson->age)
                                            <div class=" text-gray-700">
                                                <span class="font-medium">{{ $featuredPerson->age }} Years
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center text-green-700 font-semibold text-sm">
                                            Read Full Bio →
                                        </div>
                                    </footer>
                                </div>
                            </div>
                        </a>
                    </article>

                    <!-- Right Column: Secondary Personalities -->
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Top Right: Single Person -->
                        @if(isset($popularPersons[1]))
                        @php $topRightPerson = $popularPersons[1]; @endphp
                        <article class="bg-white rounded-lg shadow-md overflow-hidden group h-full border border-gray-100">
                            <a href="{{ route('people.people.show', $topRightPerson->slug) }}" class="block h-full"
                                aria-label="View profile of {{ $topRightPerson->name }}">
                                <div class="flex h-full">
                                    <!-- Image Section -->
                                    <div class="w-1/3 md:w-2/5 relative shrink-0">
                                        @if($topRightPerson->profile_image_url)
                                        <img src="{{ $topRightPerson->imageSize(280, 200, 85) }}"
                                            alt="{{ $topRightPerson->name }} - {{ $topRightPerson->primary_profession ?? 'Personality' }}"
                                            class="w-full h-full md:h-48 object-cover group-hover:scale-105 transition duration-500"
                                            loading="lazy" width="280" height="200">
                                        @else
                                        <div class="w-full h-full md:h-48 bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center"
                                            role="img" aria-label="Profile image placeholder">
                                            <svg class="w-12 h-12 text-purple-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        @endif

                                        <!-- Rank Badge -->
                                        <div
                                            class="absolute top-3 left-3 bg-purple-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                            #2
                                        </div>
                                    </div>

                                    <!-- Content Section -->
                                    <div class="w-2/3 md:w-3/5 p-4 flex flex-col justify-between">
                                        <div>
                                            <header>
                                                <h2
                                                    class="font-bold text-sm md:text-lg text-gray-900 group-hover:text-purple-600 transition-colors duration-200 line-clamp-2">
                                                    {{ $topRightPerson->name }}
                                                </h2>
                                            </header>

                                            <!-- Meta Information -->
                                            <div class="space-y-2">
                                                @if($topRightPerson->primary_profession)
                                                <p class="text-gray-600 text-xs mb-2 line-clamp-1 font-medium">
                                                    {{ $topRightPerson->primary_profession }}
                                                </p>
                                                @endif
                                                @if($featuredPerson->about)
                                                <p class="hidden md:block text-gray-600 text-xs leading-relaxed line-clamp-2">
                                                    {{ Str::limit(strip_tags($featuredPerson->about), 120) }}
                                                </p>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Stats -->
                                        <footer
                                            class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100 mt-3">
                                            @if($topRightPerson->age)
                                                <span class="flex items-center text-gray-600 text-sm font-semibold">
                                                    {{ $topRightPerson->age }} years
                                                </span>
                                                @endif
                                            <span class="text-green-700 font-semibold flex items-center">
                                                Read Full Bio →
                                            </span>

                                        </footer>
                                    </div>
                                </div>
                            </a>
                        </article>
                        @endif

                        <!-- Bottom Right: Two Persons Side by Side -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($popularPersons->slice(2, 2) as $index => $person)
                            <article class="bg-white rounded-lg shadow-md overflow-hidden group h-full border border-gray-100">
                                <a href="{{ route('people.people.show', $person->slug) }}" class="block"
                                    aria-label="View profile of {{ $person->name }}">
                                    <div class="flex">
                                        <!-- Image -->
                                        <div class="w-1/3 shrink-0 relative">
                                            @if($person->profile_image_url)
                                            <img src="{{ $person->imageSize(120, 120, 85) }}"
                                                alt="{{ $person->name }} - {{ $person->primary_profession ?? 'Personality' }}"
                                                class="w-full h-full md:h-24 object-cover group-hover:scale-105 transition duration-500"
                                                loading="lazy" width="120" height="120">
                                            @else
                                            <div class="w-full h-full md:h-24 bg-linear-to-br from-gray-50 to-gray-100 flex items-center justify-center"
                                                role="img" aria-label="Profile image placeholder">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            @endif

                                            <!-- Rank Badge -->
                                            <div
                                                class="absolute top-2 left-1 bg-gray-600 text-white px-2 py-1 rounded-lg text-xs">
                                                #{{ $index + 1 }}
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="w-2/3 p-4 flex flex-col justify-between">
                                            <div>
                                                <header>
                                                    <h3
                                                        class="font-bold text-gray-900 text-sm line-clamp-1 group-hover:text-blue-600 transition-colors duration-200">
                                                        {{ $person->name }}
                                                    </h3>
                                                </header>
                                                @if($person->primary_profession)
                                                <p class="text-gray-600 text-xs mb-2 line-clamp-1 font-medium">
                                                    {{ $person->primary_profession }}
                                                </p>
                                                @endif
                                                @if($person->age)
                                                <p class="text-gray-600 text-xs font-medium">
                                                    {{ $person->age }} Yrs
                                                </p>
                                                @endif
                                            </div>
                                            <!-- Stats -->
                                            <footer
                                                class="md:hidden text-right text-xs text-gray-500 pt-3 border-t border-gray-100 mt-3">
                                                <span class="text-green-700 font-semibold">
                                                    Read Full Bio →
                                                </span>

                                            </footer>
                                        </div>
                                    </div>

                                </a>
                            </article>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

    <!-- Second Row: 4-column grid -->
    @if($popularPersons->count() > 4)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                    @foreach($popularPersons->slice(4, 4) as $index => $person)
                    <article class="bg-white rounded-lg shadow overflow-hidden group h-full">
                        <a href="{{ route('people.people.show', $person->slug) }}" class="block h-full"
                            aria-label="View profile of {{ $person->name }}">
                            <!-- Image Section -->
                            <div class="flex">
                                <div class="w-1/3 shrink-0 relative">
                                    @if($person->profile_image_url)
                                    <img src="{{ $person->imageSize(300, 200, 85) }}"
                                        alt="{{ $person->name }} - {{ $person->primary_profession ?? 'Personality' }}"
                                        class="w-full md:w-24 h-full md:h-24 object-cover group-hover:scale-105 transition duration-500"
                                        loading="lazy" width="300" height="200">
                                    @else
                                    <div class="w-full h-full md:w-24 md:h-24 bg-linear-to-br from-green-50 to-green-100 flex items-center justify-center"
                                        role="img" aria-label="Profile image placeholder">
                                        <svg class="w-12 h-12 text-green-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <!-- Content Section -->
                                <div class="w-2/3 p-4 flex flex-col justify-between">
                                    <div>
                                    <header>
                                        <h3
                                            class="text-sm font-bold text-gray-900 group-hover:text-green-600 transition-colors duration-200 line-clamp-2">
                                            {{ $person->name }}
                                        </h3>
                                        @if($person->primary_profession)
                                        <span class="text-gray-600 text-xs font-semibold">
                                            {{ $person->primary_profession }}
                                        </span>
                                        @endif
                                    </header>

                                    <!-- Meta Information -->
                                    <div class="flex items-center justify-between">
                                        @if($person->age)
                                        <span class="flex items-center font-medium text-sm text-gray-600">
                                            {{ $person->age }} yrs
                                        </span>
                                        @endif
                                    </div>
                                    </div>
                                    <!-- Stats -->
                                            <footer
                                                class="md:hidden text-right text-xs text-gray-500 pt-3 border-t border-gray-100 mt-3">
                                                <span class="text-green-700 font-semibold">
                                                    Read Full Bio →
                                                </span>

                                            </footer>

                                </div>

                            </div>

                        </a>
                    </article>
                    @endforeach
                </div>
                @endif
</div>
