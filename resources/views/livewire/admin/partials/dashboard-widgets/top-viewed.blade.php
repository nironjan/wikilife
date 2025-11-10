<div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <!-- Card Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Viewed People</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Most popular biographies by view count</p>
        </div>
        <a href="{{ route('webmaster.persons.index', ['sort' => 'view_count']) }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            See All
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Person</th>
                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Views</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($stats['topViewedPeople'] ?? [] as $person)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($person['profile_image'])
                                    <img class="h-10 w-10 rounded-full object-cover"
                                         src="{{ $person['profile_image'] }}"
                                         alt="{{ $person['name'] }}">
                                @else
                                    <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $person['name'] }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $person['primary_profession'] ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                    {{ number_format($person['view_count']) }}
                                </span>
                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No view data available</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- View Distribution -->
    @php
        $topViewed = $stats['topViewedPeople'] ?? collect();
        $totalViews = $topViewed->sum('view_count');
    @endphp
    @if($totalViews > 0)
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center space-x-2 mb-4">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h4 class="text-sm font-semibold text-blue-600 uppercase tracking-wide">View Distribution</h4>
            </div>

            <div class="space-y-3">
                @foreach($topViewed as $index => $person)
                    @php
                        $percentage = $totalViews > 0 ? round(($person['view_count'] / $totalViews) * 100, 1) : 0;
                        $colorClass = match($index) {
                            0 => 'bg-blue-500',
                            1 => 'bg-green-500',
                            2 => 'bg-purple-500',
                            3 => 'bg-yellow-500',
                            default => 'bg-gray-500'
                        };
                        $textColorClass = match($index) {
                            0 => 'text-blue-600 dark:text-blue-400',
                            1 => 'text-green-600 dark:text-green-400',
                            2 => 'text-purple-600 dark:text-purple-400',
                            3 => 'text-yellow-600 dark:text-yellow-400',
                            default => 'text-gray-600 dark:text-gray-400'
                        };
                    @endphp
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                @if($index < 4)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold text-white {{ $colorClass }}">
                                    {{ $index + 1 }}
                                </span>
                                @else
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                    </svg>
                                @endif
                                <span class="text-sm font-medium {{ $textColorClass }}">{{ $person['name'] }}</span>
                            </div>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $colorClass }} transition-all duration-500 ease-out"
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total Views Summary -->
            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Views</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalViews) }}</span>
                </div>
            </div>
        </div>
    @endif
</div>

</div>
