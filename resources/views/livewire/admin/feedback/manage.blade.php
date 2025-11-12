<div>
    <div class="min-h-screen dark:bg-gray-900 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Review Feedback
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        Review user feedback and update status
                    </p>
                </div>

                <form wire:submit="save" class="p-6 space-y-6">
                    <!-- Basic Information (Read-only) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Person -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Person
                            </label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center">
                                    @if ($this->person?->profile_image_url)
                                        <img class="h-8 w-8 rounded-full object-cover mr-3"
                                            src="{{ $this->person->imageSize(32, 32, 100) ?? $this->person->profile_image_url }}"
                                            alt="{{ $this->person->display_name }}">
                                    @endif
                                    <span class="text-gray-900 dark:text-white font-medium">
                                        {{ $this->person?->display_name ?? 'Unknown Person' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Type
                            </label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' => $type === 'suggestion',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' => $type === 'correction',
                                    'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' => $type === 'addition',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' => $type === 'update',
                                ])>
                                    {{ ucfirst($type) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- User Information (Read-only) -->
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
                            <div class="text-gray-900 dark:text-white quill-content">
                                {!! $message !!}
                            </div>
                        </div>
                    </div>

                    <!-- Suggested Changes (Read-only) -->
                    @if ($suggested_name || $suggested_profession || $suggested_birth_date || $suggested_death_date || $suggested_nationality || $suggested_about)
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Suggested Changes</h3>
                            
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if ($suggested_name)
                                        <div>
                                            <label class="block text-sm font-medium text-blue-700 dark:text-blue-300">Name</label>
                                            <div class="mt-1 text-blue-900 dark:text-blue-100">{{ $suggested_name }}</div>
                                        </div>
                                    @endif
                                    @if ($suggested_profession)
                                        <div>
                                            <label class="block text-sm font-medium text-blue-700 dark:text-blue-300">Profession</label>
                                            <div class="mt-1 text-blue-900 dark:text-blue-100">{{ $suggested_profession }}</div>
                                        </div>
                                    @endif
                                    @if ($suggested_birth_date)
                                        <div>
                                            <label class="block text-sm font-medium text-blue-700 dark:text-blue-300">Birth Date</label>
                                            <div class="mt-1 text-blue-900 dark:text-blue-100">{{ $suggested_birth_date }}</div>
                                        </div>
                                    @endif
                                    @if ($suggested_death_date)
                                        <div>
                                            <label class="block text-sm font-medium text-blue-700 dark:text-blue-300">Death Date</label>
                                            <div class="mt-1 text-blue-900 dark:text-blue-100">{{ $suggested_death_date }}</div>
                                        </div>
                                    @endif
                                    @if ($suggested_nationality)
                                        <div>
                                            <label class="block text-sm font-medium text-blue-700 dark:text-blue-300">Nationality</label>
                                            <div class="mt-1 text-blue-900 dark:text-blue-100">{{ $suggested_nationality }}</div>
                                        </div>
                                    @endif
                                </div>
                                
                                @if ($suggested_about)
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-blue-700 dark:text-blue-300">About/Bio</label>
                                        <div class="mt-1 text-blue-900 dark:text-blue-100 whitespace-pre-wrap">{{ $suggested_about }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-between pt-6 border-t border-gray-200 dark:border-gray-600">
                        <a href="{{ route('webmaster.feedback.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                            ← Back to List
                        </a>
                        
                        <div class="flex space-x-3">
                            <button type="button" wire:click="$set('status', 'rejected')"
                                class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20 cursor-pointer">
                                Reject
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