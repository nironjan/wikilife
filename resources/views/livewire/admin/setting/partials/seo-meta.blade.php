<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">SEO Meta Information</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Optimize search engine visibility and meta information.</p>
    </div>

    <form wire:submit="saveSeoMeta">
        <div class="space-y-6">
            <!-- Meta Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Meta Title
                    <span class="text-xs text-gray-500 dark:text-gray-400 float-right">
                        {{ strlen($seo_meta_title) }}/150
                    </span>
                </label>
                <x-flux::input wire:model="seo_meta_title"
                    placeholder="Optimized title for search engines (max 60 characters)"
                    helper="Appears in browser tabs and search results" />
            </div>

            <!-- Meta Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Meta Description
                    <span class="text-xs text-gray-500 dark:text-gray-400 float-right">
                        {{ strlen($seo_meta_description) }}/200
                    </span>
                </label>
                <flux:textarea wire:model="seo_meta_description" rows="2"
                    placeholder="Brief description for search results (max 160 characters)" />

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    This description appears in search engine results below the title.
                </p>
            </div>

            <!-- Meta Keywords -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Meta Keywords
                </label>
                <x-flux::input wire:model="seo_meta_keywords" placeholder="keyword1, keyword2, keyword3"
                    helper="Comma-separated keywords relevant to this person" />
            </div>

            <!-- Tags -->
            <div>

                <flux:textarea wire:model="seo_tags" rows="2" label="Content Tags"
                    placeholder="Additional tags for content categorization" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Internal tags for better content organization and filtering.
                </p>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-600 ">
                <x-flux::button type="submit" class="cursor-pointer">
                    ✓ Save SEO Meta
                </x-flux::button>
            </div>
        </div>
    </form>
</div>
