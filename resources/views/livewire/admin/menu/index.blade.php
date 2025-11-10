<div>
    <div class="min-h-screen dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Menu Management</h1>
                                <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">
                                    Manage your website navigation menus
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('webmaster.menus.manage') }}"
                            class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add Menu
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabs & Search -->
            <div class="mb-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <!-- Tabs -->
                        <div class="flex space-x-1 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                            @foreach (['header' => 'Header', 'footer' => 'Footer', 'sidebar' => 'Sidebar'] as $type => $label)
                                <button wire:click="$set('activeTab', '{{ $type }}')"
                                    class="px-4 py-2 cursor-pointer text-sm font-medium rounded-md transition-colors duration-200 {{ $activeTab === $type ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Search -->
                        <div class="relative lg:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input wire:model.live="search" type="search" placeholder="Search menus..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu List -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ ucfirst($activeTab) }} Menus
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Use arrows to reorder menus • Click to expand/collapse
                        </p>
                    </div>
                </div>

                <!-- Menu Items -->
                <div class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($menus as $index => $menu)
                        <!-- Parent Menu -->
                        <div wire:key="parent-menu-{{ $menu['id'] }}"
                            class="border-b border-gray-100 dark:border-gray-600">
                            <div
                                class="flex items-center justify-between p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <!-- Content -->
                                <div class="flex items-center space-x-4 flex-1">
                                    <!-- Expand/Collapse & Order Controls -->
                                    <div class="flex flex-col space-y-1">
                                        @if (count($menu['children']) > 0)
                                            <button wire:click="toggleExpand({{ $menu['id'] }})"
                                                class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                                                title="{{ $this->isExpanded($menu['id']) ? 'Collapse' : 'Expand' }}">
                                                <svg class="w-4 h-4 transform {{ $this->isExpanded($menu['id']) ? 'rotate-90' : '' }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        @else
                                            <div class="p-1 opacity-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Order Controls -->
                                        <button wire:click="moveUp({{ $menu['id'] }})"
                                            class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 {{ $index === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            title="Move Up" {{ $index === 0 ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            </svg>
                                        </button>
                                        <button wire:click="moveDown({{ $menu['id'] }})"
                                            class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 {{ $index === count($menus) - 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            title="Move Down" {{ $index === count($menus) - 1 ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Icon -->
                                    @if ($menu['icon'] && array_key_exists($menu['icon'], config('menu-icon.icons', [])))
                                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ config('menu-icon.icons.' . $menu['icon']) }}" />
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Menu Details -->
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $menu['name'] }}
                                                @if (count($menu['children']) > 0)
                                                    <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                                        ({{ count($menu['children']) }}
                                                        submenu{{ count($menu['children']) > 1 ? 's' : '' }})
                                                    </span>
                                                @endif
                                            </h3>
                                            @if ($menu['is_active'])
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    Active
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                    Inactive
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $menu['url'] }}
                                            @if ($menu['open_in_new_tab'])
                                                <span class="ml-2 text-xs text-blue-600 dark:text-blue-400">(New
                                                    Tab)</span>
                                            @endif
                                        </p>
                                        @if ($menu['description'])
                                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">
                                                {{ $menu['description'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <!-- Toggle Status -->
                                    <button wire:click="toggleStatus({{ $menu['id'] }})"
                                        class="p-2 cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                                        title="{{ $menu['is_active'] ? 'Deactivate' : 'Activate' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            @if ($menu['is_active'])
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            @endif
                                        </svg>
                                    </button>

                                    <!-- Edit -->
                                    <a href="{{ route('webmaster.menus.manage', $menu['id']) }}"
                                        class="p-2 cursor-pointer text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <!-- Delete -->
                                    <button onclick="confirmDelete({{ $menu['id'] }})"
                                        class="p-2 cursor-pointer text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200"
                                        title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Child Menus -->
                            @if (count($menu['children']) > 0 && $this->isExpanded($menu['id']))
                                <div
                                    class="bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-600">
                                    @foreach ($menu['children'] as $childIndex => $child)
                                        <div wire:key="child-menu-{{ $child['id'] }}"
                                            class="flex items-center justify-between p-6 pl-16 hover:bg-gray-100 dark:hover:bg-gray-600/50 transition-colors duration-200 border-b border-gray-100 dark:border-gray-600 last:border-b-0">
                                            <!-- Content -->
                                            <div class="flex items-center space-x-4 flex-1">
                                                <!-- Order Controls for Child -->
                                                <div class="flex flex-col space-y-1">
                                                    <div class="p-1 opacity-0">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </div>
                                                    <button wire:click="moveUp({{ $child['id'] }})"
                                                        class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 {{ $childIndex === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                        title="Move Up" {{ $childIndex === 0 ? 'disabled' : '' }}>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 15l7-7 7 7" />
                                                        </svg>
                                                    </button>
                                                    <button wire:click="moveDown({{ $child['id'] }})"
                                                        class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 {{ $childIndex === count($menu['children']) - 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                        title="Move Down"
                                                        {{ $childIndex === count($menu['children']) - 1 ? 'disabled' : '' }}>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Child Menu Indicator -->
                                                <div class="p-2">
                                                    <div class="w-1 h-1 bg-gray-400 rounded-full"></div>
                                                </div>

                                                <!-- Child Menu Details -->
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-3">
                                                        <h3
                                                            class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            {{ $child['name'] }}
                                                        </h3>
                                                        @if ($child['is_active'])
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                                Active
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                                Inactive
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ $child['url'] }}
                                                        @if ($child['open_in_new_tab'])
                                                            <span
                                                                class="ml-2 text-xs text-blue-600 dark:text-blue-400">(New
                                                                Tab)</span>
                                                        @endif
                                                    </p>
                                                    @if ($child['description'])
                                                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                                                            {{ $child['description'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Actions for Child -->
                                            <div class="flex items-center space-x-2">
                                                <!-- Toggle Status -->
                                                <button wire:click="toggleStatus({{ $child['id'] }})"
                                                    class="p-2 cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                                                    title="{{ $child['is_active'] ? 'Deactivate' : 'Activate' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        @if ($child['is_active'])
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        @endif
                                                    </svg>
                                                </button>

                                                <!-- Edit -->
                                                <a href="{{ route('webmaster.menus.manage', $child['id']) }}"
                                                    class="p-2 cursor-pointer text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                <!-- Delete -->
                                                <button onclick="confirmDelete({{ $child['id'] }})"
                                                    class="p-2 cursor-pointer text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200"
                                                    title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No menus found</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                Get started by creating a new menu item.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('webmaster.menus.manage') }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Menu
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(menuId) {
            if (confirm('Are you sure you want to delete this menu? This action cannot be undone.')) {
                @this.deleteMenu(menuId);
            }
        }
    </script>
</div>
