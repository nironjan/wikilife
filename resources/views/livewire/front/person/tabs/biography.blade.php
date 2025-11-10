<div class="p-2 md:p-6">
    <!-- SEO-Optimized Header -->
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $person->display_name }} - Biography</h1>
        <div class="flex items-center text-sm text-gray-600">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                    clip-rule="evenodd" />
            </svg>
            Last updated: {{ $person->updated_at->format('F j, Y') }}
        </div>
    </header>

    <!-- Main Biography Content -->
    @if($person->about)
    <section class="mb-12">
        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">

            <div class="prose prose-gray prose-headings:font-semibold prose-headings:text-gray-900
                       prose-p:leading-relaxed prose-p:text-gray-700 prose-strong:text-gray-900
                       prose-ul:mt-4 prose-ul:space-y-2 prose-li:marker:text-gray-400
                       prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline">
                {!! $person->about !!}
            </div>
        </div>
    </section>
    @else
    <!-- No Biography Available State -->
    <section class="text-center py-16 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
        <div class="max-w-md mx-auto">
            <div
                class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Biography Coming Soon</h3>
            <p class="text-gray-600 mb-6">We're currently compiling the complete biography of {{ $person->display_name
                }}. Check back soon for detailed information about their life and achievements.</p>
            <div class="flex justify-center space-x-4">
                <a href="{{ $this->getTabUrl('career') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    View Career
                </a>
                <a href="{{ $this->getTabUrl('personal_life') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Personal Life
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Life Timeline Section -->
    @if($person->birth_date || $person->educations->count() > 0 || $person->awards->count() > 0)
    <section class="mt-16 pt-8 border-t border-gray-200">
        <div class="flex items-center mb-8">
            <div class="flex-shrink-0 w-1 h-8 bg-gradient-to-b from-blue-500 to-purple-600 rounded-full mr-4"></div>
            <h2 class="text-2xl font-bold text-gray-900">Life Timeline</h2>
        </div>

        <div class="relative">
            <!-- Main Timeline Line -->
            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-200 via-gray-200 to-gray-200">
            </div>

            <div class="space-y-8 ml-8">
                <!-- Birth Event -->
                @if($person->birth_date)
                <div class="relative group">
                    <div
                        class="absolute -left-11 top-4 w-5 h-5 bg-blue-500 rounded-full border-4 border-white shadow-lg group-hover:scale-110 transition-transform duration-200 z-10">
                    </div>
                    <div
                        class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all duration-200 group-hover:border-blue-200">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span
                                class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 text-sm font-semibold rounded-full border border-blue-200">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $person->birth_date->format('Y') }}
                            </span>
                            <span
                                class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">Birth</span>
                        </div>
                        <p class="text-gray-700 font-medium">
                            Born on <time datetime="{{ $person->birth_date->format('Y-m-d') }}"
                                class="font-semibold text-gray-900">{{ $person->birth_date->format('F j, Y') }}</time>
                            @if($person->place_of_birth)
                            in <span class="text-blue-600 font-medium">{{ $person->place_of_birth }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                @endif

                <!-- Education Milestones -->
                @foreach($person->educations->take(3) as $index => $education)
                <div class="relative group">
                    <div
                        class="absolute -left-11 top-6 w-4 h-4 bg-green-500 rounded-full border-3 border-white shadow-lg group-hover:scale-110 transition-transform duration-200 z-10">
                    </div>
                    <div
                        class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all duration-200 group-hover:border-green-200">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span
                                class="inline-flex items-center px-3 py-1 bg-green-50 text-green-700 text-sm font-semibold rounded-full border border-green-200">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                </svg>
                                {{ $education->year }}
                            </span>
                            <span
                                class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">Education</span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight">
                            {{ $education->degree }}
                        </h3>

                        <div class="space-y-2 text-gray-700">
                            @if($education->institution)
                            <div class="flex items-center font-medium">
                                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ $education->institution }}
                            </div>
                            @endif

                            @if($education->field_of_study)
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                {{ $education->field_of_study }}
                            </div>
                            @endif
                        </div>

                        @if($education->details)
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="prose prose-sm max-w-none text-gray-600">
                                {!! $education->details !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                <!-- View More Educations -->
                @if($person->educations->count() > 3)
                <div class="relative">
                    <div
                        class="absolute -left-11 top-1/2 transform -translate-y-1/2 w-4 h-4 bg-gray-400 rounded-full border-3 border-white shadow z-10">
                    </div>
                    <div class="text-center">
                        <a href="{{ $this->getTabUrl('personal_life') }}"
                            class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                            View {{ $person->educations->count() - 3 }} More Education Milestones
                        </a>
                    </div>
                </div>
                @endif

                <!-- Death Event -->
                @if($person->death_date)
                <div class="relative group">
                    <div
                        class="absolute -left-11 top-4 w-5 h-5 bg-red-500 rounded-full border-4 border-white shadow-lg group-hover:scale-110 transition-transform duration-200 z-10">
                    </div>
                    <div
                        class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all duration-200 group-hover:border-red-200">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span
                                class="inline-flex items-center px-3 py-1 bg-red-50 text-red-700 text-sm font-semibold rounded-full border border-red-200">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $person->death_date->format('Y') }}
                            </span>
                            <span
                                class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">Passing</span>
                        </div>
                        <p class="text-gray-700 font-medium">
                            Passed away on <time datetime="{{ $person->death_date->format('Y-m-d') }}"
                                class="font-semibold text-gray-900">{{ $person->death_date->format('F j, Y') }}</time>
                            @if($person->place_of_death)
                            in <span class="text-red-600 font-medium">{{ $person->place_of_death }}</span>
                            @endif
                            @if($person->death_cause)
                            <span class="block mt-1 text-gray-600">Cause: {{ $person->death_cause }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    <!-- SEO Structured Data Hint -->
    <div class="mt-12 pt-8 border-t border-gray-200 text-center">
        <p class="text-sm text-gray-500">
            This biography page provides comprehensive information about {{ $person->display_name }}'s life, education,
            and key milestones.
        </p>
    </div>
</div>
