<div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total People</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['totalPeople'] ?? 0 }}</p>
            <div class="flex items-center mt-2 text-sm text-green-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                {{ $stats['newPeopleThisMonth'] ?? 0 }} new this month
            </div>
        </div>
        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
        <div class="text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">Active</p>
            <p class="text-lg font-semibold text-green-600">{{ $stats['activePeople'] ?? 0 }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">Verified</p>
            <p class="text-lg font-semibold text-blue-600">{{ $stats['verifiedPeople'] ?? 0 }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">Alive</p>
            <p class="text-lg font-semibold text-purple-600">{{ $stats['alivePeople'] ?? 0 }}</p>
        </div>
    </div>
</div>

</div>
