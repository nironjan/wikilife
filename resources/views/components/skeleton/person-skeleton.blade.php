<div class="animate-pulse">
    <!-- Table Header Skeleton -->
    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-3">
                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-20"></div>
            </div>
            <div class="col-span-2">
                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-16"></div>
            </div>
            <div class="col-span-2">
                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-12"></div>
            </div>
            <div class="col-span-2">
                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-10"></div>
            </div>
            <div class="col-span-2">
                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-14"></div>
            </div>
            <div class="col-span-1">
                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-16"></div>
            </div>
        </div>
    </div>

    <!-- Table Rows Skeleton -->
    @for ($i = 0; $i < 8; $i++)
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-3">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-32"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-500 rounded w-20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-span-2">
                    <div class="space-y-2">
                        <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-24"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-500 rounded w-16"></div>
                    </div>
                </div>
                <div class="col-span-2">
                    <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded-full w-20"></div>
                </div>
                <div class="col-span-2">
                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-12"></div>
                </div>
                <div class="col-span-2">
                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-16"></div>
                </div>
                <div class="col-span-1">
                    <div class="flex justify-end space-x-2">
                        <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-10"></div>
                        <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-16"></div>
                        <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-10"></div>
                    </div>
                </div>
            </div>
        </div>
    @endfor
</div>
