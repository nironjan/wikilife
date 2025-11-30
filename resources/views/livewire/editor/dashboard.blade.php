<div>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Editor Dashboard</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Welcome back! Here's your content overview.</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-100 dark:bg-blue-900 px-3 py-1 rounded-full text-sm text-blue-800 dark:text-blue-200">
                            Editor Mode
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ Auth::user()->name }}
                        </span>
                    </div>
                </div>
            </div>

            @if($loading)
                <!-- Skeleton Loading -->
                <div class="animate-pulse">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        @foreach(range(1, 4) as $i)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/3 mb-4"></div>
                                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Main Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Persons -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Persons</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_persons'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Approved -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['approved_count'] }}</p>
                                <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                                    {{ $stats['approval_rate'] }}% approval rate
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Pending -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Review</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_count'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Views -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Views</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_views']) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $stats['average_views'] }} avg per person
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Stats & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Approval Status Breakdown -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Approval Status</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Approved</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                    {{ $stats['approved_count'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Pending</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                    {{ $stats['pending_count'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Rejected</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                    {{ $stats['rejected_count'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Breakdown -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Overview</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Active</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                    {{ $stats['active_count'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Inactive</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                    {{ $stats['inactive_count'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Deceased</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $stats['deceased_count'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            @foreach($quickActions as $action)
                                <a href="{{ $action['route'] }}"
                                   class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-{{ $action['color'] }}-600 dark:text-{{ $action['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($action['icon'] === 'plus')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                @elseif($action['icon'] === 'clock')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                @elseif($action['icon'] === 'check')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                @elseif($action['icon'] === 'x')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                @endif
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $action['title'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $action['description'] }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recently Created -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recently Created</h3>
                        </div>
                        <div class="p-6">
                            @if($recentPersons['created']->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentPersons['created'] as $person)
                                        <div class="flex items-center justify-between p-3 border border-gray-100 dark:border-gray-700 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                @if($person->profile_image_url)
                                                    <img class="w-10 h-10 rounded-full object-cover"
                                                         src="{{ $person->imageSize(40, 40, 100) }}"
                                                         alt="{{ $person->display_name }}">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                            {{ strtoupper(substr($person->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $person->display_name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $person->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            <span @class([
                                                'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                                'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' => $person->approval_status === 'approved',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' => $person->approval_status === 'pending',
                                                'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' => $person->approval_status === 'rejected',
                                            ])>
                                                {{ ucfirst($person->approval_status) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No recently created persons</p>
                            @endif
                        </div>
                    </div>

                    <!-- Recently Updated -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recently Updated</h3>
                        </div>
                        <div class="p-6">
                            @if($recentPersons['updated']->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentPersons['updated'] as $person)
                                        <div class="flex items-center justify-between p-3 border border-gray-100 dark:border-gray-700 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                @if($person->profile_image_url)
                                                    <img class="w-10 h-10 rounded-full object-cover"
                                                         src="{{ $person->imageSize(40, 40, 100) }}"
                                                         alt="{{ $person->display_name }}">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                            {{ strtoupper(substr($person->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $person->display_name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $person->updated_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            <span @class([
                                                'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                                'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' => $person->status === 'active',
                                                'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' => $person->status === 'inactive',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => $person->status === 'deceased',
                                            ])>
                                                {{ ucfirst($person->status) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No recently updated persons</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
