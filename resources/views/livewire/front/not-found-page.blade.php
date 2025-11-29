<div class="min-h-screen bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Main Content --}}
        <div class="text-center">
            {{-- Animated 404 Illustration --}}
            <div class="relative mb-8">
                <div class="mx-auto w-64 h-64 relative">
                    {{-- Main SVG --}}
                    <svg class="w-full h-full text-gray-300 dark:text-gray-600" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        {{-- Book/Profile Silhouette --}}
                        <path d="M50 40C50 34.4772 54.4772 30 60 30H140C145.523 30 150 34.4772 150 40V160C150 165.523 145.523 170 140 170H60C54.4772 170 50 165.523 50 160V40Z"
                              fill="currentColor" fill-opacity="0.1" stroke="currentColor" stroke-width="2"/>

                        {{-- Magnifying Glass --}}
                        <circle cx="130" cy="70" r="25" stroke="currentColor" stroke-width="2" fill="none"/>
                        <path d="M145 85L160 100" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>

                        {{-- Question Mark --}}
                        <path d="M80 70C80 67.7909 81.7909 66 84 66C86.2091 66 88 67.7909 88 70C88 72.2091 86.2091 74 84 74C81.7909 74 80 72.2091 80 70Z"
                              fill="currentColor"/>
                        <path d="M84 50V60M84 78V90" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>

                    {{-- Floating Elements --}}
                    <div class="absolute top-8 left-12 animate-bounce">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="absolute top-16 right-16 animate-bounce" style="animation-delay: 0.2s;">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="absolute bottom-12 left-20 animate-bounce" style="animation-delay: 0.4s;">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Error Message --}}
            <div class="mb-12">
                <h1 class="text-6xl font-bold text-gray-900 dark:text-white mb-4">404</h1>
                <h2 class="text-3xl font-semibold text-gray-700 dark:text-gray-300 mb-4">
                    Not Found
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-8">
                    The page you're looking for doesn't exist or may have been moved.
                    Let's help you find what you're looking for.
                </p>
            </div>

            {{-- Quick Actions --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Back to Home
                </a>

                <a href="{{ route('people.people.index') }}"
                   class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    Browse All People
                </a>
            </div>
        </div>

        {{-- Suggested People --}}
        @if(count($suggestedPeople) > 0)
        <div class="max-w-4xl mx-auto mb-16">
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-8 text-center">
                Discover Notable Personalities
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($suggestedPeople as $person)
                <a href="{{ $person['url'] }}"
                   class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($person['profile_image'])
                                <img class="h-14 w-14 rounded-full object-cover border-2 border-blue-200 group-hover:border-blue-400 transition-colors duration-200"
                                     src="{{ $person['profile_image'] }}"
                                     alt="{{ $person['name'] }}"
                                     loading="lazy">
                            @else
                                <div class="h-14 w-14 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center border-2 border-blue-200 group-hover:border-blue-400 transition-colors duration-200">
                                    <svg class="h-7 w-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200 truncate">
                                {{ $person['name'] }}
                            </h4>
                            @if(!empty($person['professions']))
                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                    {{ implode(', ', array_slice($person['professions'], 0, 2)) }}
                                </p>
                            @endif
                            @if($person['age'])
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                    {{ $person['age'] }} years
                                </p>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Popular People --}}
        @if(count($popularPeople) > 0)
        <div class="max-w-4xl mx-auto">
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-8 text-center">
                Popular Biographies
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach(array_slice($popularPeople, 0, 4) as $person)
                <a href="{{ $person['url'] }}"
                   class="group bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($person['profile_image'])
                                <img class="h-12 w-12 rounded-full object-cover border-2 border-green-200 group-hover:border-green-400 transition-colors duration-200"
                                     src="{{ $person['profile_image'] }}"
                                     alt="{{ $person['name'] }}"
                                     loading="lazy">
                            @else
                                <div class="h-12 w-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center border-2 border-green-200 group-hover:border-green-400 transition-colors duration-200">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-200 truncate">
                                    {{ $person['name'] }}
                                </h4>
                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ number_format($person['view_count']) }}
                                </span>
                            </div>
                            @if(!empty($person['professions']))
                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                    {{ $person['professions'][0] ?? '' }}
                                </p>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Help Section --}}
        <div class="max-w-3xl mx-auto mt-16 text-center">
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-8 border border-blue-200 dark:border-blue-800">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Need More Help?
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Can't find what you're looking for? Try our advanced search or browse by categories.
                </p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('people.profession.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        Browse Categories
                    </a>
                    <a href="{{ route('people.profession.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        Browse Professions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
