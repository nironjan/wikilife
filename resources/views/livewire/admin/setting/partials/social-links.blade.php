<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Social Links</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Manage social media profiles and online presence.</p>
    </div>

    <!-- Existing Social Links -->
    <div class="mb-8">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Current Social Links</h3>

        @if (count($social_links) > 0)
            <div class="space-y-3">
                @foreach ($social_links as $index => $link)
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-4">
                            @php
                                $platformKey = strtolower($link['platform']);
                                $iconConfig = $socialIcons[$platformKey] ?? null;
                            @endphp

                            @if ($iconConfig)
                                <!-- Display SVG icon from config -->
                                <div class="w-8 h-8 flex items-center justify-center rounded-full"
                                     style="background-color: {{ $iconConfig['color'] }}20;">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24"
                                         style="fill: {{ $iconConfig['color'] }};">
                                        <path d="{{ $iconConfig['svg'] }}"/>
                                    </svg>
                                </div>
                            @elseif ($link['icon'] && array_key_exists($link['icon'], $socialIcons))
                                <!-- Display SVG icon using stored icon key -->
                                @php $iconConfig = $socialIcons[$link['icon']]; @endphp
                                <div class="w-8 h-8 flex items-center justify-center rounded-full"
                                     style="background-color: {{ $iconConfig['color'] }}20;">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24"
                                         style="fill: {{ $iconConfig['color'] }};">
                                        <path d="{{ $iconConfig['svg'] }}"/>
                                    </svg>
                                </div>
                            @else
                                <!-- Fallback to initial or custom icon -->
                                <div
                                    class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                        {{ strtoupper(substr($link['platform'], 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $link['platform'] }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 break-all">
                                    {{ $link['url'] }}
                                </div>
                                @if ($link['username'])
                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        @{{ $link['username'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <button type="button" wire:click="removeSocialLink({{ $index }})"
                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    No social links added yet. Add your first social link below.
                </p>
            </div>
        @endif
    </div>

    <!-- Add New Social Link -->
    <div class="border-t border-gray-200 dark:border-gray-600 pt-8">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add New Social Link</h3>

        <form wire:submit.prevent="addSocialLink">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Platform -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Platform *
                    </label>
                    <select wire:model="new_social_platform"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        <option value="">Select a platform</option>
                        @foreach ($socialIcons as $key => $icon)
                            <option value="{{ $key }}">{{ $icon['name'] }}</option>
                        @endforeach
                    </select>

                    <!-- Custom platform input for "Other" -->
                    @if ($new_social_platform === 'other')
                        <div class="mt-2">
                            <x-flux::input label="Custom Platform Name"
                                         wire:model="new_social_platform_custom"
                                         placeholder="Enter platform name"
                                         required />
                        </div>
                    @endif
                </div>

                <!-- URL -->
                <x-flux::input label="Profile URL *" wire:model="new_social_url"
                    placeholder="https://platform.com/username" type="url" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <!-- Username -->
                <x-flux::input label="Username" wire:model="new_social_username" placeholder="username"
                    helper="Username/handle without @ symbol" />

                <!-- Icon (auto-populated based on platform selection) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Icon
                    </label>
                    <div class="flex items-center space-x-3">
                        @if ($new_social_platform && array_key_exists($new_social_platform, $socialIcons))
                            <div class="w-8 h-8 flex items-center justify-center rounded-full"
                                 style="background-color: {{ $socialIcons[$new_social_platform]['color'] }}20;">
                                <svg class="w-4 h-4" viewBox="0 0 24 24"
                                     style="fill: {{ $socialIcons[$new_social_platform]['color'] }};">
                                    <path d="{{ $socialIcons[$new_social_platform]['svg'] }}"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $socialIcons[$new_social_platform]['name'] }} icon will be used automatically
                            </span>
                            <!-- Hidden field to store the icon key -->
                            <input type="hidden" wire:model="new_social_icon" value="{{ $new_social_platform }}" />
                        @else
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Select a platform to see the icon
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Add Button -->
            <div class="flex justify-end mt-6">
                <x-flux::button type="submit" class="cursor-pointer">
                    + Add Social Link
                </x-flux::button>
            </div>
        </form>
    </div>
</div>
