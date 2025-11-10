<div>
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">People Management</h2>
                <p class="text-gray-600 dark:text-gray-400">Manage biographies and profiles</p>
            </div>
            <a href="{{ route('webmaster.persons.manage') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Person
            </a>
        </div>

        <!-- Filters and Search -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Search -->
            <div>
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
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Search by name..." type="search">
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <select wire:model.live="status"
                        class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="deceased">Deceased</option>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <select wire:model.live="sortField"
                        class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="created_at">Created Date</option>
                    <option value="name">Name</option>
                    <option value="birth_date">Birth Date</option>
                    <option value="view_count">Views</option>
                </select>
            </div>
        </div>

        <!-- People Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            @if($loading)
                <!-- Skeleton Loading -->
                @include('components.skeleton.person-skeleton')
            @else
                <!-- Content -->
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Person
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Profession
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Views
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Created
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($people as $person)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($person->profile_image_url)
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                 src="{{ $person->imageSize(40, 40, 100) }}"
                                                 alt="{{ $person->display_name }}">
                                        @else
                                            <div
                                                class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        {{ strtoupper(substr($person->name, 0, 1)) }}
                                                    </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $person->display_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $person->age ? $person->age . ' years' : 'Age unknown' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $person->primary_profession ?? 'N/A' }}
                                </div>
                                @if ($person->professions && count($person->professions) > 1)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        +{{ count($person->professions) - 1 }} more
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span @class([
    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
    'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' =>
        $person->status === 'active',
    'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' =>
        $person->status === 'inactive',
    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' =>
        $person->status === 'deceased',
])>
                                        {{ ucfirst($person->status) }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ number_format($person->view_count) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $person->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('webmaster.persons.manage', ['id' => $person->id]) }}"
                                       class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-600 bg-blue-50 hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-400 dark:hover:bg-blue-800/30 text-xs font-medium transition-colors duration-150">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <button wire:click="toggleStatus({{ $person->id }})"
                                            class="inline-flex items-center px-3 py-1 border border-orange-300 rounded-md text-orange-600 bg-orange-50 hover:bg-orange-100 hover:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1 dark:bg-orange-900/20 dark:border-orange-700 dark:text-orange-400 dark:hover:bg-blue-800/30 text-xs font-medium transition-colors duration-150 cursor-pointer">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                        {{ $person->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    <button wire:click="deletePerson({{ $person->id }})"
                                            wire:confirm="Are you sure you want to delete {{ $person->display_name }}?"
                                            class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-600 bg-red-50 hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 dark:bg-red-900/20 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-800/30 text-xs font-medium transition-colors duration-150 cursor-pointer">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No people found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Pagination -->
        @if(!$loading)
            <div class="mt-6">
                {{ $people->links() }}
            </div>
        @endif
    </div>
</div>
