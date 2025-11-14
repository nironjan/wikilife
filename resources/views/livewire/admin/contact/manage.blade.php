<div>
    <div class="min-h-screen dark:bg-gray-900 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Review Contact Message
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        Review contact form submission and update status
                    </p>
                </div>

                <form wire:submit="save" class="p-6 space-y-6">
                    <!-- Contact Information (Read-only) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Name
                            </label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white">
                                {{ $name }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email
                            </label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white">
                                {{ $email }}
                            </div>
                        </div>
                    </div>

                    <!-- Subject and Type -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Subject
                            </label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white">
                                {{ $subject }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Type
                            </label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100' => $type === 'general',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' => $type === 'support',
                                    'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' => $type === 'partnership',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' => $type === 'technical',
                                    'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' => $type === 'other',
                                ])>
                                    {{ ucfirst($type) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Status and Verification -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status (Editable) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status *
                            </label>
                            <select wire:model="status"
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Verification (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Verification
                            </label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                @if ($email_verified_at)
                                    <span class="inline-flex items-center text-green-600 dark:text-green-400">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified on {{ \Carbon\Carbon::parse($email_verified_at)->format('M d, Y g:i A') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-red-600 dark:text-red-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Not Verified
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Message (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Message
                        </label>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                            <div class="text-gray-900 dark:text-white whitespace-pre-wrap">
                                {{ $message }}
                            </div>
                        </div>
                    </div>

                    <!-- Technical Information -->
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Technical Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">IP Address</label>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ip_address ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">User Agent</label>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white break-words">{{ $user_agent ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between pt-6 border-t border-gray-200 dark:border-gray-600">
                        <a href="{{ route('webmaster.contact.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 cursor-pointer">
                            ← Back to List
                        </a>

                        <div class="flex space-x-3">
                            <button type="button" wire:click="$set('status', 'closed')"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 cursor-pointer">
                                Close
                            </button>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                                Update Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
