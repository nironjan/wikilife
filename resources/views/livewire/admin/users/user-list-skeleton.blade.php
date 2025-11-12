<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <!-- Header Skeleton -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-48 mb-2 animate-pulse"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-64 animate-pulse"></div>
        </div>
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded w-32 animate-pulse"></div>
    </div>

    <!-- Filters Skeleton -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
    </div>

    <!-- Table Skeleton -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-24 animate-pulse"></div>
                    </th>
                    <th class="px-6 py-3 text-left">
                        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-16 animate-pulse"></div>
                    </th>
                    <th class="px-6 py-3 text-left">
                        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-16 animate-pulse"></div>
                    </th>
                    <th class="px-6 py-3 text-left">
                        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-16 animate-pulse"></div>
                    </th>
                    <th class="px-6 py-3 text-left">
                        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-20 animate-pulse"></div>
                    </th>
                    <th class="px-6 py-3 text-right">
                        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-16 animate-pulse ml-auto"></div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <!-- Skeleton Rows -->
                @for ($i = 0; $i < 5; $i++)
                    <tr>
                        <!-- User Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded-full animate-pulse"></div>
                                </div>
                                <div class="ml-4">
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32 mb-2 animate-pulse"></div>
                                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-24 animate-pulse"></div>
                                </div>
                            </div>
                        </td>

                        <!-- Role Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-20 animate-pulse"></div>
                        </td>

                        <!-- Status Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-16 animate-pulse"></div>
                        </td>

                        <!-- Team Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-12 animate-pulse"></div>
                        </td>

                        <!-- Last Login Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24 animate-pulse"></div>
                        </td>

                        <!-- Actions Column -->
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-2">
                                <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-12 animate-pulse"></div>
                                <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-16 animate-pulse"></div>
                                <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-12 animate-pulse"></div>
                            </div>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <!-- Pagination Skeleton -->
    <div class="mt-6">
        <div class="flex items-center justify-between">
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32 animate-pulse"></div>
            <div class="flex space-x-2">
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-8 animate-pulse"></div>
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-8 animate-pulse"></div>
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-8 animate-pulse"></div>
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-8 animate-pulse"></div>
            </div>
        </div>
    </div>
</div>
