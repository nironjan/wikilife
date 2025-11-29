<div>
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Contact Messages</h2>
                <p class="text-gray-600 dark:text-gray-400">Review and manage contact form submissions</p>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
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
                        placeholder="Search names, emails, subjects..." type="search">
                </div>
            </div>

            <!-- Type Filter -->
            <div>
                <select wire:model.live="type"
                    class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All Types</option>
                    @foreach ($types as $type)
                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <select wire:model.live="status"
                    class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sort -->
            <div>
                <select wire:model.live="sortField"
                    class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="created_at">Created Date</option>
                    <option value="name">Name</option>
                    <option value="email">Email</option>
                    <option value="status">Status</option>
                </select>
            </div>
        </div>

        <!-- Contact Messages Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Contact Information
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Subject & Message
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Verification
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Created
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($contactMessages as $message)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div
                                            class="h-10 w-10 rounded-full bg-blue-200 dark:bg-blue-600 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                {{ strtoupper(substr($message->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $message->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $message->email }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            <span @class([
                                                'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100' => $message->type === 'general',
                                                'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' => $message->type === 'support',
                                                'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' => $message->type === 'advertise',
                                                'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' => $message->type === 'other',
                                            ])>
                                                {{ ucfirst($message->type) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ Str::limit($message->subject, 50) }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                    {{ $this->getCleanExcerpt($message->message, 80) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if ($message->isVerified())
                                    <span class="inline-flex items-center text-green-600 dark:text-green-400">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified
                                    </span>
                                    <div class="text-xs text-gray-400">
                                        {{ $message->email_verified_at?->format('M d, Y') }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center text-red-600 dark:text-red-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Not Verified
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $message->created_at->format('M d, Y') }}
                                <div class="text-xs text-gray-400">
                                    {{ $message->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' => $message->status === 'pending',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' => $message->status === 'in_progress',
                                    'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' => $message->status === 'responded',
                                    'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100' => $message->status === 'closed',
                                ])>
                                    {{ ucfirst(str_replace('_', ' ', $message->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('webmaster.contact.review', $message->id) }}"
                                        class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-600 bg-blue-50 hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-400 dark:hover:bg-blue-800/30 text-xs font-medium transition-colors duration-150 cursor-pointer">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Review
                                    </a>

                                    @if ($message->status === 'responded')
                                        <button wire:click="resendResponseEmail({{ $message->id }})"
                                            class="inline-flex items-center px-3 py-1 border border-green-300 rounded-md text-green-600 bg-green-50 hover:bg-green-100 hover:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 dark:bg-green-900/20 dark:border-green-700 dark:text-green-400 dark:hover:bg-green-800/30 text-xs font-medium transition-colors duration-150 cursor-pointer">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Resend Email
                                        </button>
                                    @endif

                                    <button wire:click="deleteContactMessage({{ $message->id }})"
                                        wire:confirm="Are you sure you want to delete contact message from {{ $message->name }}?"
                                        class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-600 bg-red-50 hover:bg-red-100 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 dark:bg-red-900/20 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-800/30 text-xs font-medium transition-colors duration-150 cursor-pointer">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </div>

                                <!-- Quick Status Actions -->
                                <div class="flex justify-end space-x-1 mt-2">
                                    @foreach (['pending', 'in_progress', 'responded', 'closed'] as $actionStatus)
                                        @if ($message->status !== $actionStatus)
                                            <button wire:click="updateStatus({{ $message->id }}, '{{ $actionStatus }}')"
                                                class="inline-flex items-center px-2 py-1 text-xs rounded border cursor-pointer
                                                {{ $actionStatus === 'responded' ? 'border-green-300 text-green-600 bg-green-50 hover:bg-green-100' : '' }}
                                                {{ $actionStatus === 'closed' ? 'border-gray-300 text-gray-600 bg-gray-50 hover:bg-gray-100' : '' }}
                                                {{ $actionStatus === 'pending' ? 'border-yellow-300 text-yellow-600 bg-yellow-50 hover:bg-yellow-100' : '' }}
                                                {{ $actionStatus === 'in_progress' ? 'border-blue-300 text-blue-600 bg-blue-50 hover:bg-blue-100' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $actionStatus)) }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No contact messages found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $contactMessages->links() }}
        </div>
    </div>
</div>
