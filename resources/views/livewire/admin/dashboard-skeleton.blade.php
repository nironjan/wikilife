<div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header Skeleton -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <div class="h-8 bg-gray-300 dark:bg-gray-600 rounded w-64 mb-2 animate-pulse"></div>
            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-96 animate-pulse"></div>
        </div>
        <div class="flex items-center space-x-3">
            <div class="h-10 bg-gray-300 dark:bg-gray-600 rounded w-32 animate-pulse"></div>
            <div class="h-10 bg-gray-300 dark:bg-gray-600 rounded w-24 animate-pulse"></div>
        </div>
    </div>

    <!-- Statistics Cards Skeleton -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @for($i = 0; $i < 4; $i++)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-pulse">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/3"></div>
                    <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                </div>
                <div class="h-7 bg-gray-300 dark:bg-gray-600 rounded w-1/2 mb-3"></div>
                <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-3/4 mb-4"></div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
                    <div class="bg-gray-300 dark:bg-gray-600 h-2 rounded-full w-1/2"></div>
                </div>
                <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-1/4"></div>
            </div>
        @endfor
    </div>

    <!-- Detailed Stats Grid Skeleton -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Chart Skeleton -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-pulse">
                <div class="h-5 bg-gray-300 dark:bg-gray-600 rounded w-1/4 mb-6"></div>
                <div class="flex justify-between items-center mb-6">
                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/6"></div>
                    <div class="h-8 bg-gray-300 dark:bg-gray-600 rounded w-24"></div>
                </div>
               <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3">
                        <div class="h-3 w-16 bg-gray-200 dark:bg-gray-700 rounded"></div>
                    </th>
                    <th class="pb-3">
                        <div class="h-3 w-16 bg-gray-200 dark:bg-gray-700 rounded"></div>
                    </th>
                    <th class="pb-3">
                        <div class="h-3 w-16 bg-gray-200 dark:bg-gray-700 rounded"></div>
                    </th>
                    <th class="pb-3">
                        <div class="h-3 w-16 bg-gray-200 dark:bg-gray-700 rounded"></div>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <!-- Repeat skeleton rows -->
                @for ($i = 0; $i < 4; $i++)
                <tr>
                    <td class="py-4">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                            <div>
                                <div class="h-3 w-32 bg-gray-200 dark:bg-gray-700 rounded mb-2"></div>
                                <div class="h-2.5 w-20 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="h-3 w-28 bg-gray-200 dark:bg-gray-700 rounded mb-2"></div>
                        <div class="h-2.5 w-16 bg-gray-200 dark:bg-gray-700 rounded"></div>
                    </td>
                    <td class="py-4">
                        <div class="h-3 w-20 bg-gray-200 dark:bg-gray-700 rounded"></div>
                    </td>
                    <td class="py-4">
                        <div class="h-5 w-16 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
            </div>
        </div>

        <!-- List Skeleton -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-pulse">
                <div class="h-5 bg-gray-300 dark:bg-gray-600 rounded w-1/3 mb-6"></div>
                <div class="space-y-4">
                    @for($i = 0; $i < 5; $i++)
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div>
                                <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-1/2"></div>
                            </div>
                            <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-12"></div>
                        </div>
                    @endfor
                </div>
                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="h-9 bg-gray-300 dark:bg-gray-600 rounded w-full"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Skeleton -->
    <div class="mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-pulse">
            <div class="h-5 bg-gray-300 dark:bg-gray-600 rounded w-1/4 mb-6"></div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @for($i = 0; $i < 8; $i++)
                    <div class="text-center">
                        <div class="h-12 w-12 bg-gray-300 dark:bg-gray-600 rounded-lg mx-auto mb-3"></div>
                        <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4 mx-auto"></div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>
</div>
