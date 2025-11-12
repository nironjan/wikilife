<div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <!-- Card Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upcoming Birthdays</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Birthdays in the next 30 days</p>
            </div>
            <a href="{{ route('webmaster.persons.manage') }}"
               class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                View All
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <!-- Birthday List -->
        <div class="space-y-4">
            @forelse($upcomingBirthdays as $person)
                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <!-- Profile Image -->
                        <div class="flex-shrink-0">
                            @if($person['profile_image'])
                                <img class="h-12 w-12 rounded-full object-cover border-2 border-blue-200 dark:border-blue-800"
                                     src="{{ $person['profile_image'] }}"
                                     alt="{{ $person['name'] }}"
                                     loading="lazy">
                            @else
                                <div class="h-12 w-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center border-2 border-blue-200 dark:border-blue-800">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Person Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $person['name'] }}
                                </h4>
                                @if($person['days_until_birthday'] == 0)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Today!
                                    </span>
                                @elseif($person['days_until_birthday'] <= 7)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                        Soon
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2 mt-1">
                                @if(!empty($person['professions']))
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ implode(', ', array_slice($person['professions'], 0, 2)) }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center space-x-4 mt-1">
                                <div class="flex items-center space-x-1 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $person['formatted_birth_date'] }}</span>
                                </div>

                                <div class="flex items-center space-x-1 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>
                                        @if($person['days_until_birthday'] == 0)
                                            Today
                                        @elseif($person['days_until_birthday'] == 1)
                                            Tomorrow
                                        @else
                                            in {{ $person['days_until_birthday'] }} days
                                        @endif
                                    </span>
                                </div>

                                <div class="flex items-center space-x-1 text-xs text-blue-600 dark:text-blue-400 font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Turning {{ $person['next_age'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex-shrink-0 ml-4">
                        <a href="{{ $person['manage_url'] }}"
                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Manage
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="text-center py-8">
                    <div class="flex justify-center mb-3">
                        <div class="h-16 w-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">No upcoming birthdays</p>
                    <p class="text-gray-400 dark:text-gray-500 text-xs">Birthdays in the next 30 days will appear here</p>
                </div>
            @endforelse
        </div>

        <!-- Summary Stats -->
        @if(!empty($upcomingBirthdays))
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-xl font-bold text-blue-600 dark:text-blue-400">
                            {{ count($upcomingBirthdays) }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Upcoming</div>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-green-600 dark:text-green-400">
                            {{ collect($upcomingBirthdays)->where('days_until_birthday', 0)->count() }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Today</div>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-purple-600 dark:text-purple-400">
                            {{ collect($upcomingBirthdays)->where('days_until_birthday', '<=', 7)->count() }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">This Week</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
