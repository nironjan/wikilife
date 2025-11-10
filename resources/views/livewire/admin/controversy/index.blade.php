<div>
    <div class="min-h-screen dark:from-gray-900 dark:to-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div class="mb-8">
                <div
                    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-xl">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl font-bold text-gray-900 dark:text-white bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                                    Controversies Management
                                </h1>
                                <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">
                                    Manage controversial topics and public discussions
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('webmaster.persons.controversies.manage') }}"
                            class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-orange-600 border border-transparent rounded-lg hover:from-red-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add New Controversy
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input wire:model.live="search" id="search"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200"
                            placeholder="Search controversies..." type="search">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <select wire:model.live="status"
                        class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                        <option value="">All Status</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Person Filter -->
                <div>
                    <select wire:model.live="person"
                        class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                        <option value="">All Persons</option>
                        @foreach ($people as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Year Filter -->
                <div>
                    <select wire:model.live="year"
                        class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                        <option value="">All Years</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Controversies Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200/60 dark:border-gray-700/60 overflow-hidden">
                <!-- Skeleton Loading -->
                <div wire:loading.delay>
                    @include('components.skeleton.controversy-table')
                </div>

                <!-- Actual Content -->
                <div wire:loading.remove>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                                        wire:click="sortBy('title')">
                                        <div class="flex items-center space-x-1">
                                            <span>Controversy</span>
                                            @if ($sortField === 'title')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Person
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($controversies as $controversy)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0 p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                        {{ $controversy->title }}
                                                    </div>
                                                    <div
                                                        class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2 prose prose-sm dark:prose-invert max-w-none">
                                                        {!! Str::limit($controversy->html_content ?: Str::markdown($controversy->content), 120) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white font-medium">
                                                {{ $controversy->person->display_name }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $controversy->date?->format('M j, Y') ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-1">
                                                <span @class([
                                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                    'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' =>
                                                        $controversy->is_published,
                                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => !$controversy->is_published,
                                                ])>
                                                    {{ $controversy->is_published ? 'Published' : 'Draft' }}
                                                </span>
                                                <span @class([
                                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                    'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' =>
                                                        $controversy->is_resolved,
                                                    'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100' => !$controversy->is_resolved,
                                                ])>
                                                    {{ $controversy->is_resolved ? 'Resolved' : 'Unresolved' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end items-center space-x-2">
                                                <button wire:click="togglePublish({{ $controversy->id }})"
                                                    @class([
                                                        'inline-flex items-center px-3 cursor-pointer py-1 rounded-full text-xs font-medium transition-colors duration-200',
                                                        'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-800 dark:text-green-100 dark:hover:bg-green-700' => !$controversy->is_published,
                                                        'bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' =>
                                                            $controversy->is_published,
                                                    ])>
                                                    {{ $controversy->is_published ? 'Unpublish' : 'Publish' }}
                                                </button>
                                                <button wire:click="toggleResolve({{ $controversy->id }})"
                                                    @class([
                                                        'inline-flex items-center cursor-pointer px-3 py-1 rounded-full text-xs font-medium transition-colors duration-200',
                                                        'bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-800 dark:text-blue-100 dark:hover:bg-blue-700' => !$controversy->is_resolved,
                                                        'bg-orange-100 text-orange-800 hover:bg-orange-200 dark:bg-orange-800 dark:text-orange-100 dark:hover:bg-orange-700' =>
                                                            $controversy->is_resolved,
                                                    ])>
                                                    {{ $controversy->is_resolved ? 'Unresolve' : 'Resolve' }}
                                                </button>
                                                <a href="{{ route('webmaster.persons.controversies.manage', ['id' => $controversy->id]) }}"
                                                    class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium hover:bg-blue-200 dark:bg-blue-800 dark:text-blue-100 dark:hover:bg-blue-700 transition-colors duration-200">
                                                    Edit
                                                </a>
                                                <button wire:click="deleteControversy({{ $controversy->id }})"
                                                    wire:confirm="Are you sure you want to delete this controversy?"
                                                    class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium hover:bg-red-200 dark:bg-red-800 dark:text-red-100 dark:hover:bg-red-700 transition-colors duration-200 cursor-pointer">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center">
                                            <div
                                                class="flex flex-col items-center justify-center space-y-3 text-gray-500 dark:text-gray-400">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                                <p class="text-lg font-medium">No controversies found</p>
                                                <p class="text-sm">Get started by creating your first controversy</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6" wire:loading.remove>
                {{ $controversies->links() }}
            </div>
        </div>
    </div>
</div>
