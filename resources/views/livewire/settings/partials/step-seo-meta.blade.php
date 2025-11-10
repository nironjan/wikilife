<div class="space-y-6">
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-2 h-8 bg-gradient-to-b from-green-500 to-emerald-600 rounded-full"></div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            SEO & Meta Information
        </h2>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <!-- Meta Title -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Meta Title
                <span class="ml-2 text-xs font-normal text-gray-500">(Recommended: 50-60 characters)</span>
            </label>
            <input type="text" wire:model="meta_title" placeholder="Enter meta title for SEO"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200 placeholder-gray-500 dark:placeholder-gray-400">
            <div class="flex justify-between text-xs text-gray-500">
                <span>Appears in search results</span>
                <span>{{ strlen($meta_title) }}/60</span>
            </div>
        </div>

        <!-- Meta Description -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
                Meta Description
                <span class="ml-2 text-xs font-normal text-gray-500">(Recommended: 150-160 characters)</span>
            </label>
            <textarea wire:model="meta_description" placeholder="Enter meta description for SEO" rows="3"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200 placeholder-gray-500 dark:placeholder-gray-400 resize-none"></textarea>
            <div class="flex justify-between text-xs text-gray-500">
                <span>Appears below title in search results</span>
                <span>{{ strlen($meta_description) }}/160</span>
            </div>
        </div>

        <!-- Meta Keywords -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                    </path>
                </svg>
                Meta Keywords
            </label>
            <input type="text" wire:model="meta_keywords" placeholder="keyword1, keyword2, keyword3"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200 placeholder-gray-500 dark:placeholder-gray-400">
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- OG Title -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                OG Title
            </label>
            <input type="text" wire:model="og_title" placeholder="Open Graph title for social sharing"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all duration-200 placeholder-gray-500 dark:placeholder-gray-400">
        </div>

        <!-- Twitter Title -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Twitter Title
            </label>
            <input type="text" wire:model="twitter_title" placeholder="Twitter card title for social sharing"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all duration-200 placeholder-gray-500 dark:placeholder-gray-400">
        </div>

        <!-- OG Description -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
                OG Description
            </label>
            <textarea wire:model="og_description" placeholder="Open Graph description for social sharing" rows="3"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all duration-200 placeholder-gray-500 dark:placeholder-gray-400 resize-none"></textarea>
        </div>

        <!-- Twitter Description -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
                Twitter Description
            </label>
            <textarea wire:model="twitter_description" placeholder="Twitter card description for social sharing" rows="3"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all duration-200 placeholder-gray-500 dark:placeholder-gray-400 resize-none"></textarea>
        </div>
    </div>
</div>
