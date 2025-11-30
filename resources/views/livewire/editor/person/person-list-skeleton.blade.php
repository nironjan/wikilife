<div class="animate-pulse">
    <div class="space-y-4">
        <!-- Header Skeleton -->
        <div class="flex justify-between items-center">
            <div>
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-48 mb-2"></div>
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-64"></div>
            </div>
            <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded w-32"></div>
        </div>

        <!-- Stats Skeleton -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach(range(1, 4) as $i)
                <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                        <div class="ml-3">
                            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-16 mb-2"></div>
                            <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-12"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Filters Skeleton -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach(range(1, 3) as $i)
                <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
            @endforeach
        </div>

        <!-- Table Skeleton -->
        <div class="space-y-3">
            @foreach(range(1, 5) as $i)
                <div class="flex items-center space-x-4 p-4 bg-gray-200 dark:bg-gray-700 rounded">
                    <div class="h-10 w-10 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/4"></div>
                        <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-1/6"></div>
                    </div>
                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/6"></div>
                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/6"></div>
                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/6"></div>
                    <div class="flex space-x-2">
                        <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-12"></div>
                        <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-16"></div>
                        <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-12"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
