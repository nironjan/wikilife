<div class="p-2 md:p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Career</h2>

    @php
    $hasCareer = $person->filmography->count() > 0 ||
    $person->politicalCareers->count() > 0 ||
    $person->sportsCareers->count() > 0 ||
    $person->entrepreneurs->count() > 0 ||
    $person->literatureCareer->count() > 0;
    @endphp

    @if($hasCareer)
    <!-- Filmography -->
    @if($person->filmography->count() > 0)
    <div class="space-y-4 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Filmography</h3>
        @foreach($person->filmography->groupBy('category') as $category => $items)
        <div class="mb-6">
            <h4 class="text-lg font-medium text-gray-900 mb-3 border-b pb-2">{{ $category }}</h4>
            <div class="space-y-3">
                @foreach($items as $work)
                <div class="flex justify-between items-start p-3 bg-gray-50 rounded-lg">
                    <div>
                        <h5 class="font-medium text-gray-900">{{ $work->title }}</h5>
                        @if($work->role)
                        <p class="text-sm text-gray-600">as {{ $work->role }}</p>
                        @endif
                        @if($work->year)
                        <p class="text-sm text-gray-500">{{ $work->year }}</p>
                        @endif
                    </div>
                    @if($work->description)
                    <button
                        @click="$dispatch('open-modal', { content: '{{ addslashes($work->description) }}', title: '{{ addslashes($work->title) }}' })"
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Details
                    </button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Political Career -->
    @if($person->politicalCareers->count() > 0)
    <div class="space-y-4 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Political Career</h3>
        <div class="space-y-4">
            @foreach($person->politicalCareers as $career)
            <a href="{{ route('people.career.show', ['personSlug' => $person->slug, 'slug' => $career->slug]) }}"
                class="block border-l-4 border-blue-500 pl-4 py-2 hover:bg-blue-50 rounded-r-lg transition-colors duration-200 group">
                <div>
                    <!-- Position as clickable heading -->
                    <h4
                        class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-200 mb-1">
                        {{ $career->position }}
                        <svg class="w-4 h-4 inline-block ml-1 text-gray-400 group-hover:text-blue-500 transition-colors duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </h4>

                    @if($career->political_party)
                    <p class="text-gray-600">{{ $career->political_party }}</p>
                    @endif

                    @if($career->constituency)
                    <p class="text-sm text-gray-500">Constituency: {{ $career->constituency }}</p>
                    @endif

                    <p class="text-sm text-gray-500">
                        @if($career->tenure_start && $career->tenure_end)
                        {{ $career->tenure_start->format('Y') }} - {{ $career->tenure_end->format('Y') }}
                        @elseif($career->tenure_start)
                        Since {{ $career->tenure_start->format('Y') }}
                        @endif
                        @if($career->is_current)
                        <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Current</span>
                        @endif
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Sports Career -->
    @if($person->sportsCareers->count() > 0)
    <div class="space-y-4 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Sports Career</h3>
        <div class="space-y-4">
            @foreach($person->sportsCareers as $career)
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $career->sport }} - {{ $career->position }}
                        </h4>
                        <p class="text-gray-600">{{ $career->team ?? '' }}</p>
                        <p class="text-sm text-gray-500">
                            @if($career->years_active)
                            Years Active: {{ $career->years_active }}
                            @endif
                        </p>
                    </div>
                    @if($career->achievements)
                    <button
                        @click="$dispatch('open-modal', { content: '{{ addslashes($career->achievements) }}', title: '{{ addslashes($career->sport) }} Achievements' })"
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Achievements
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Business Career -->
    @if($person->entrepreneurs->count() > 0)
    <div class="space-y-4 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Business Ventures</h3>
        <div class="space-y-4">
            @foreach($person->entrepreneurs as $venture)
            <div class="border rounded-lg p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $venture->company_name }}</h4>
                        <p class="text-gray-600">{{ $venture->role }}</p>
                        <p class="text-sm text-gray-500">
                            @if($venture->founded_year)
                            Founded: {{ $venture->founded_year }}
                            @endif
                            @if($venture->industry)
                            • {{ $venture->industry }}
                            @endif
                        </p>
                    </div>
                    @if($venture->description)
                    <button
                        @click="$dispatch('open-modal', { content: '{{ addslashes($venture->description) }}', title: '{{ addslashes($venture->company_name) }}' })"
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Details
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Literature Career -->
    @if($person->literatureCareer->count() > 0)
    <div class="space-y-4 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Literary Works</h3>
        <div class="space-y-4">
            @foreach($person->literatureCareer as $work)
            <a href="{{ route('people.career.show', ['personSlug' => $person->slug, 'slug' => $work->slug]) }}"
                class="block border-l-4 border-purple-500 pl-4 py-3 hover:bg-purple-50 rounded-r-lg transition-colors duration-200 group">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <!-- Title as clickable heading -->
                        <h4
                            class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors duration-200 text-lg mb-1">
                            {{ $work->display_title }}
                            <svg class="w-4 h-4 inline-block ml-1 text-gray-400 group-hover:text-purple-500 transition-colors duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                </path>
                            </svg>
                        </h4>

                        <!-- Work Details -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-2">
                            @if($work->work_type)
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $work->work_type }}
                            </span>
                            @endif

                            @if($work->role)
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $work->role }}
                            </span>
                            @endif

                            @if($work->publishing_year)
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ $work->publishing_year }}
                            </span>
                            @endif
                        </div>

                        <!-- Additional Information -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-2">
                            @if($work->genre)
                            <span>{{ $work->genre }}</span>
                            @endif

                            @if($work->language)
                            <span>{{ $work->language }}</span>
                            @endif

                            @if($work->isbn)
                            <span class="font-mono text-xs">ISBN: {{ $work->isbn }}</span>
                            @endif
                        </div>

                        <!-- Awards -->
                        @if($work->awards_count > 0)
                        <div class="mt-2">
                            <span
                                class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4"></path>
                                </svg>
                                {{ $work->awards_count }} award{{ $work->awards_count > 1 ? 's' : '' }}
                            </span>
                        </div>
                        @endif

                        <!-- Status -->
                        <div class="mt-2">
                            @if($work->career_status === 'Active')
                            <span
                                class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">
                                {{ $work->career_status }}
                            </span>
                            @elseif($work->career_status === 'Completed')
                            <span
                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">
                                {{ $work->career_status }}
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full font-medium">
                                {{ $work->career_status }}
                            </span>
                            @endif

                            @if($work->career_duration !== 'N/A')
                            <span class="ml-2 text-xs text-gray-500">{{ $work->career_duration }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Cover Image -->
                    @if($work->cover_image_url)
                    <div class="ml-4 flex-shrink-0">
                        <img src="{{ $work->cover_image_url }}" alt="Cover of {{ $work->display_title }}"
                            class="w-16 h-20 object-cover rounded-lg shadow-sm border group-hover:shadow-md transition-shadow duration-200">
                    </div>
                    @endif
                </div>

                <!-- Description Preview -->
                @if($work->description)
                <div class="mt-3">
                    <p class="text-sm text-gray-600 line-clamp-2">
                        {{ Str::limit(strip_tags($work->description), 120) }}
                    </p>
                    <span class="text-purple-600 text-sm font-medium mt-1 inline-block">
                        Read full description →
                    </span>
                </div>
                @endif
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @else
    <!-- No Career Information -->
    <div class="text-center py-12">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
            </path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No Career Information</h3>
        <p class="text-gray-500">Career details for {{ $person->display_name }} are not available yet.</p>
    </div>
    @endif
</div>
