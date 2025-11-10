<div class="space-y-6">
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-cyan-600 rounded-full"></div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            Branding & Images
        </h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Logo -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Logo
            </label>
            @if ($existing_logo)
                <div class="mb-4">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $existing_logo }}" alt="Current logo" class="h-16 w-16 object-contain rounded">
                        <button type="button" wire:click="removeLogo"
                            class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm transition-colors duration-200">
                            Remove Logo
                        </button>
                    </div>
                </div>
            @endif
            @if (!$existing_logo)
                <div
                    class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50/50 dark:bg-gray-700/30 hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-300">
                    <label class="flex flex-col items-center justify-center w-full h-32 cursor-pointer p-4">
                        <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                            <span class="text-blue-600 dark:text-blue-400">Click to upload</span> your logo
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">PNG, JPG up to 2MB</p>
                        <input type="file" class="hidden" wire:model="logo" accept="image/*">
                    </label>
                </div>
            @endif
            @error('logo')
                <p class="text-sm text-red-600 dark:text-red-400 flex items-center mt-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Favicon -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                    </path>
                </svg>
                Favicon
            </label>
            @if ($existing_favicon)
                <div class="mb-4">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $existing_favicon }}" alt="Current favicon" class="h-8 w-8 object-contain rounded">
                        <button type="button" wire:click="removeFavicon"
                            class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm transition-colors duration-200">
                            Remove Favicon
                        </button>
                    </div>
                </div>
            @endif
            @if (!$existing_favicon)
                <div
                    class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50/50 dark:bg-gray-700/30 hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-300">
                    <label class="flex flex-col items-center justify-center w-full h-32 cursor-pointer p-4">
                        <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                            </path>
                        </svg>
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                            <span class="text-blue-600 dark:text-blue-400">Click to upload</span> favicon
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">ICO, PNG up to 1MB</p>
                        <input type="file" class="hidden" wire:model="favicon" accept="image/*">
                    </label>
                </div>
            @endif
            @error('favicon')
                <p class="text-sm text-red-600 dark:text-red-400 flex items-center mt-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <!-- Default Image -->
    <div class="space-y-2">
        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
            </svg>
            Default Image
        </label>
        @if ($existing_default_image)
            <div class="mb-4">
                <div class="flex items-center space-x-4">
                    <img src="{{ $existing_default_image }}" alt="Current default image"
                        class="h-16 w-16 object-cover rounded">
                    <button type="button" wire:click="removeDefaultImage"
                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm transition-colors duration-200">
                        Remove Image
                    </button>
                </div>
            </div>
        @endif
        @if (!$existing_default_image)
            <div
                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50/50 dark:bg-gray-700/30 hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-300">
                <label class="flex flex-col items-center justify-center w-full h-32 cursor-pointer p-4">
                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                        <span class="text-blue-600 dark:text-blue-400">Click to upload</span> default image
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">PNG, JPG up to 5MB</p>
                    <input type="file" class="hidden" wire:model="default_image" accept="image/*">
                </label>
            </div>
        @endif
        @error('default_image')
            <p class="text-sm text-red-600 dark:text-red-400 flex items-center mt-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>
</div>
