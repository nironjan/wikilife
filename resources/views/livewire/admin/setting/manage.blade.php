<div>
    <div class="min-h-screen dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Person Settings</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            Manage SEO, assets, media profile, and social links for {{ $person->display_name }}
                        </p>
                    </div>
                    <a href="{{ route('webmaster.persons.settings.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Back to Persons
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Left Sidebar - Navigation -->
                    <div class="lg:w-1/4 bg-gray-50 dark:bg-gray-700 p-6 border-r border-gray-200 dark:border-gray-600">
                        <nav class="space-y-2">
                            <button wire:click="switchTab('seo')" @class([
                                'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200',
                                'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-200 border border-blue-200 dark:border-blue-800' =>
                                    $activeTab === 'seo',
                                'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-gray-100' =>
                                    $activeTab !== 'seo',
                            ])>
                                <div class="flex items-center space-x-3 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>SEO Meta</span>
                                </div>
                            </button>

                            <button wire:click="switchTab('assets')" @class([
                                'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200',
                                'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-200 border border-blue-200 dark:border-blue-800' =>
                                    $activeTab === 'assets',
                                'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-gray-100' =>
                                    $activeTab !== 'assets',
                            ])>
                                <div class="flex items-center space-x-3 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                    <span>Assets & Wealth</span>
                                </div>
                            </button>

                            <button wire:click="switchTab('media')" @class([
                                'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200',
                                'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-200 border border-blue-200 dark:border-blue-800' =>
                                    $activeTab === 'media',
                                'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-gray-100' =>
                                    $activeTab !== 'media',
                            ])>
                                <div class="flex items-center space-x-3 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                    <span>Media Profile</span>
                                </div>
                            </button>

                            <button wire:click="switchTab('social')" @class([
                                'w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200',
                                'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-200 border border-blue-200 dark:border-blue-800' =>
                                    $activeTab === 'social',
                                'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-gray-100' =>
                                    $activeTab !== 'social',
                            ])>
                                <div class="flex items-center space-x-3 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span>Social Links</span>
                                </div>
                            </button>
                        </nav>

                        <!-- Person Info Card -->
                        <div
                            class="mt-8 p-4 bg-white dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-500">
                            <div class="flex items-center space-x-3">
                                @if ($person->profile_image)
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ $person->profile_image }}"
                                        alt="{{ $person->display_name }}">
                                @else
                                    <div
                                        class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-500 flex items-center justify-center">
                                        <span class="text-lg font-medium text-gray-500 dark:text-gray-300">
                                            {{ strtoupper(substr($person->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $person->display_name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $person->primary_profession ?? 'Person' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content - Active Tab -->
                    <div class="lg:w-3/4 p-6 lg:p-8 relative">

                        <div>

                            <!-- SEO Meta Tab -->
                            @if ($activeTab == 'seo')
                                @include('livewire.admin.setting.partials.seo-meta')
                            @endif

                            <!-- Assets Tab -->
                            @if ($activeTab == 'assets')
                                @include('livewire.admin.setting.partials.assets')
                            @endif

                            <!-- Media Profile Tab -->
                            @if ($activeTab == 'media')
                                @include('livewire.admin.setting.partials.media-profile')
                            @endif

                            <!-- Social Links Tab -->
                            @if ($activeTab == 'social')
                                @include('livewire.admin.setting.partials.social-links')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
