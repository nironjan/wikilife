<div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Awards & Recognition</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['totalAwards'] ?? 0 }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $stats['verifiedAwards'] ?? 0 }} verified awards
            </p>
        </div>
        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
            </svg>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
        <div class="text-center">
            <div class="flex items-center justify-center space-x-1 text-green-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
                <span class="text-sm font-semibold">{{ $stats['awardsThisYear'] ?? 0 }}</span>
            </div>
            <p class="text-xs text-gray-500 mt-1">This Year</p>
        </div>
        <div class="text-center">
            <div class="flex items-center justify-center space-x-1 text-blue-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-semibold">{{ $stats['verifiedAwards'] ?? 0 }}</span>
            </div>
            <p class="text-xs text-gray-500 mt-1">Verified</p>
        </div>
    </div>
</div>

</div>
