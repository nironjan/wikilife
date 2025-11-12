<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <!-- Card Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Latest updates across your platform</p>
        </div>
        <div class="relative" x-data="{ dropdownOpen: false }">
            <button type="button"
                class="inline-flex items-center p-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                @click="dropdownOpen = !dropdownOpen" @click.outside="dropdownOpen = false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                    </path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none border border-gray-200 dark:border-gray-600"
                style="display: none;" role="menu" aria-orientation="vertical">
                <a href="{{ route('webmaster.persons.index') }}"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                    role="menuitem">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                        <span>View All People</span>
                    </div>
                </a>
                <a href="{{ route('webmaster.persons.award.index') }}"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                    role="menuitem">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4">
                            </path>
                        </svg>
                        <span>View All Awards</span>
                    </div>
                </a>
                <a href="{{ route('webmaster.persons.latest-update.index') }}"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                    role="menuitem">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                        <span>View All Updates</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Combined Table Layout -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th
                        class="pb-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Activity</th>
                    <th
                        class="pb-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Details</th>
                    <th
                        class="pb-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Date</th>
                    <th
                        class="pb-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <!-- Recent People -->
                @forelse($stats['recentPeopleList'] ?? [] as $person)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($person['profile_image'])
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $person['profile_image'] }}"
                                    alt="{{ $person['name'] }}">
                                @else
                                <div
                                    class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $person['name'] }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    New Person Added
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ implode(', ', array_slice($person['professions'] ?? [], 0, 2)) }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            by {{ $person['created_by'] }}
                        </div>
                    </td>
                    <td class="py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">{{ $person['created_at'] }}</div>
                    </td>
                    <td class="py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $person['status'] == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                            {{ $person['status'] }}
                        </span>
                    </td>
                </tr>
                @empty
                @endforelse

                <!-- Recent Awards -->
                @forelse($stats['recentAwards'] ?? [] as $award)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 h-10 w-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $award['award_name'] }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Award Received
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $award['person_name'] }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $award['category'] }}
                        </div>
                    </td>
                    <td class="py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">{{ $award['awarded_at'] }}</div>
                    </td>
                    <td class="py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Awarded
                        </span>
                    </td>
                </tr>
                @empty
                @endforelse

                <!-- Recent Updates -->
                @forelse($stats['recentUpdates'] ?? [] as $update)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ \Illuminate\Support\Str::limit($update['title'], 40) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $update['update_type'] }} Update
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $update['person_name'] }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            by {{ $update['user_name'] }}
                        </div>
                    </td>
                    <td class="py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">{{ $update['created_at'] }}</div>
                    </td>
                    <td class="py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            Published
                        </span>
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>

        <!-- Empty State -->
        @if(empty($stats['recentPeopleList'] ?? []) && empty($stats['recentAwards'] ?? []) &&
        empty($stats['recentUpdates'] ?? []))
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <p class="text-gray-500 dark:text-gray-400 text-sm">No recent activity</p>
        </div>
        @endif
    </div>

    <!-- Activity Summary -->
    @if(!empty($stats['recentPeopleList'] ?? []) || !empty($stats['recentAwards'] ?? []) ||
    !empty($stats['recentUpdates'] ?? []))
    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ count($stats['recentPeopleList'] ??
                    []) }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">New People</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ count($stats['recentAwards'] ??
                    []) }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Awards</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ count($stats['recentUpdates'] ??
                    []) }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Updates</div>
            </div>
        </div>
    </div>
    @endif
    </div>
