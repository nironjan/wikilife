<div class="animate-pulse space-y-6">
    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3"></div>

    <div class="space-y-4 mt-6">
        @for ($i = 0; $i < 4; $i++)
            <div class="space-y-2">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/4"></div>
                <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
            </div>
        @endfor
    </div>

    <div class="flex justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
        <div class="h-10 w-32 bg-gray-200 dark:bg-gray-700 rounded"></div>
    </div>
</div>
