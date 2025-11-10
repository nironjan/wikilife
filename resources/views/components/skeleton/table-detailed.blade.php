<div class="animate-pulse">
    <!-- Table Header -->
    <div class="bg-gray-50 dark:bg-gray-700">
        <div class="px-6 py-3 grid grid-cols-12 gap-4">
            <div class="col-span-4">
                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/4"></div>
            </div>
            <div class="col-span-2">
                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/3"></div>
            </div>
            <div class="col-span-2">
                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/4"></div>
            </div>
            <div class="col-span-2">
                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/3"></div>
            </div>
            <div class="col-span-2">
                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/4"></div>
            </div>
        </div>
    </div>

    <!-- Table Rows -->
    @for ($i = 0; $i < 8; $i++)
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="grid grid-cols-12 gap-4 items-center">
                <!-- Title with Thumbnail -->
                <div class="col-span-4">
                    <div class="flex items-center space-x-3">
                        <!-- Thumbnail (random size for variation) -->
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 bg-gray-300 dark:bg-gray-600 rounded"></div>
                        </div>
                        <!-- Title & Description -->
                        <div class="flex-1 space-y-2">
                            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-500 rounded w-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Person -->
                <div class="col-span-2">
                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-2/3"></div>
                </div>

                <!-- Type Badge -->
                <div class="col-span-2">
                    <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded-full w-20"></div>
                </div>

                <!-- Date -->
                <div class="col-span-2">
                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-24"></div>
                </div>

                <!-- Actions -->
                <div class="col-span-2">
                    <div class="flex justify-end space-x-3">
                        <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-10"></div>
                        <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-12"></div>
                    </div>
                </div>
            </div>
        </div>
    @endfor
</div>
