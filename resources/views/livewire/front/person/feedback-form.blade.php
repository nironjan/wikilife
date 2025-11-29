<div class="min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
            <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Home</a>
            <span class="text-gray-400">›</span>
            <a href="{{ route('people.people.index') }}" class="hover:text-blue-600 transition-colors">People</a>
            <span class="text-gray-400">›</span>
            <a href="{{ route('people.people.show', $person->slug) }}" class="hover:text-blue-600 transition-colors">{{ $person->name }}</a>
            <span class="text-gray-400">›</span>
            <span class="text-gray-900 font-medium">Suggest Edit</span>
        </nav>

        {{-- Main Card --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            {{-- Header with Gradient --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-8 text-white">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Suggest an Edit for {{ $person->name }}</h1>
                        <p class="text-blue-100 text-md">Help us keep this profile accurate and up-to-date</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row">
                {{-- Left Sidebar - Progress Steps --}}
                <div class="lg:w-1/4 bg-gray-50 p-6 border-r border-gray-200">
                    {{-- Progress Steps --}}
                    <div class="space-y-4">
                        {{-- Step 1: Email Verification --}}
                        <div class="flex items-center space-x-3 p-3 rounded-lg {{ $currentStep >= 1 ? 'bg-blue-50 border border-dashed border-blue-200' : 'bg-gray-100' }}">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                                @if($emailVerified)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    1
                                @endif
                            </div>
                            <div>
                                <span class="text-sm font-medium {{ $currentStep >= 1 ? 'text-blue-900' : 'text-gray-600' }}">
                                    Email Verification
                                </span>
                            </div>
                        </div>

                        {{-- Step 2: Basic Info --}}
                        <div class="flex items-center space-x-3 p-3 rounded-lg {{ $currentStep >= 2 ? 'bg-blue-50 border border-dashed border-blue-200' : 'bg-gray-100' }}">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                                2
                            </div>
                            <div>
                                <span class="text-sm font-medium {{ $currentStep >= 2 ? 'text-blue-900' : 'text-gray-600' }}">
                                    Your Information
                                </span>
                            </div>
                        </div>

                        {{-- Step 3: Feedback --}}
                        <div class="flex items-center space-x-3 p-3 rounded-lg {{ $currentStep >= 3 ? 'bg-blue-50 border border-dashed border-blue-200' : 'bg-gray-100' }}">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                                3
                            </div>
                            <div>
                                <span class="text-sm font-medium {{ $currentStep >= 3 ? 'text-blue-900' : 'text-gray-600' }}">
                                    Your Feedback
                                </span>
                            </div>
                        </div>

                        {{-- Step 4: Suggestions --}}
                        <div class="flex items-center space-x-3 p-3 rounded-lg {{ $currentStep >= 4 ? 'bg-blue-50 border border-dashed border-blue-200' : 'bg-gray-100' }}">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep >= 4 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                                4
                            </div>
                            <div>
                                <span class="text-sm font-medium {{ $currentStep >= 4 ? 'text-blue-900' : 'text-gray-600' }}">
                                    Suggested Changes
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="mt-8">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Progress</span>
                            <span>{{ round(($currentStep / 4) * 100) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                 style="width: {{ ($currentStep / 4) * 100 }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Right Content - Form Steps --}}
                <div class="lg:w-3/4 p-6 lg:p-8">
                    {{-- Success Message --}}
                    @if (session()->has('success_message'))
                        <div class="mb-8 p-6 bg-green-50 border border-green-200 rounded-xl">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-green-800">Message Sent!</h3>
                                    <p class="text-green-700 mt-1">{{ session('success_message') }}</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ url('/') }}"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                    Back to Home
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if (session()->has('error'))
                        <div class="mb-8 p-6 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-red-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-red-800">Error</h3>
                                    <p class="text-red-700 mt-1">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- OTP Message --}}
                    @if (session()->has('otp_message'))
                        <div class="mb-8 p-6 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-blue-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-blue-700">{{ session('otp_message') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="submitFeedback">

                        {{-- Step 1: Email Verification --}}
                        @if ($currentStep == 1)
                            <div class="space-y-4">
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900 mb-1">Verify Your Email</h2>
                                    <p class="text-gray-600 text-sm">We'll send a verification code to ensure you're a real person.</p>

                                    {{-- Session Status --}}
                                    @if ($hasValidSession)
                                        <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-green-700 text-xs">Your email is verified for 1 hour. Please refresh the page.</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if (!$otpSent)
                                    <div>
                                        <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Email Address *</label>
                                        <input type="email" id="email" wire:model="email" wire:change="updateOTPRateLimitInfo"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="your.email@example.com"
                                            {{ $sendingOtp ? 'disabled' : '' }}> {{-- Removed $isEmailBlocked from disabled --}}
                                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                                        {{-- Blocked Email Warning --}}
                                        @if ($isEmailBlocked && $email)
                                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                    </svg>
                                                    <div>
                                                        <p class="text-red-700 font-medium text-sm">Too many verification attempts</p>
                                                        <p class="text-red-600 text-xs mt-1">
                                                            {{ \App\Helpers\FeedbackHelper::getRateLimitMessage($email) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($email && $remainingOTPRequests < 3)
                                            {{-- Rate Limit Info --}}
                                            <div class="mt-2 text-xs text-{{ $remainingOTPRequests > 0 ? 'blue' : 'orange' }}-600">
                                                {{ \App\Helpers\FeedbackHelper::getRateLimitMessage($email) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex justify-between items-center">
                                        @if ($hasValidSession)
                                            <button type="button" wire:click="clearSession"
                                                class="px-3 py-1.5 text-xs cursor-pointer text-gray-600 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 transition-colors">
                                                Use Different Email
                                            </button>
                                        @else
                                            <div></div>
                                        @endif

                                        <button type="button"
                                            wire:click="sendOTP"
                                            wire:loading.attr="disabled"
                                            wire:target="sendOTP"
                                            class="px-4 py-2 text-xs font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-blue-500 transition-colors flex items-center space-x-1 disabled:opacity-50 disabled:cursor-not-allowed"
                                            {{ $sendingOtp || !$email ? 'disabled' : '' }}>

                                            <span wire:loading.remove wire:target="sendOTP" class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                <span>Send Verification Code</span>
                                            </span>

                                            <span wire:loading wire:target="sendOTP" class="flex items-center gap-2"><div class="animate-spin rounded-full h-3 w-3 border-b-2 border-white"></div><span>Sending...</span></span>
                                        </button>

                                        </button>
                                    </div>
                                @else
                                    {{-- OTP Verification --}}
                                    <div class="max-w-md mx-auto text-center">
                                        <div class="mb-8">
                                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">Check Your Email</h3>
                                            <p class="text-gray-600">
                                                We've sent a 6-digit verification code to
                                                <strong class="text-blue-600">{{ $email }}</strong>
                                            </p>
                                            @if ($isEmailBlocked)
                                                <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded">
                                                    <p class="text-yellow-700 text-xs">Note: This email has reached its OTP limit. You can still verify the code that was sent.</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mb-8">
                                            <label for="otp" class="block text-sm font-semibold text-gray-700 mb-4">Enter Verification Code</label>
                                            <input type="text" id="otp" wire:model="otp" maxlength="6"
                                                class="w-full px-6 py-4 text-center text-xl font-bold border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                placeholder="000000"
                                                autofocus
                                                {{ $verifyingOtp ? 'disabled' : '' }}>
                                            @error('otp') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="flex justify-center space-x-6 mb-8">
                                            @if (!$isEmailBlocked)
                                                <button type="button" wire:click="resendOTP"
                                                    class="text-blue-600 hover:text-blue-500 cursor-pointer font-medium text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                    {{ $sendingOtp ? 'disabled' : '' }}>
                                                    @if($sendingOtp)
                                                        <div class="animate-spin rounded-full h-3 w-3 cursor-pointer border-b-2 border-blue-600 inline-block mr-1"></div>
                                                        Sending...
                                                    @else
                                                        Resend Code
                                                    @endif
                                                </button>
                                            @endif
                                            <button type="button" wire:click="$set('otpSent', false)"
                                                class="text-gray-600 hover:text-gray-500 font-medium cursor-pointer text-sm transition-colors">
                                                Change Email
                                            </button>
                                        </div>

                                        <div class="flex justify-center">
                                            <button type="button" wire:click="verifyOTP"
                                                class="px-8 py-3 text-sm font-medium text-white cursor-pointer bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                                {{ $verifyingOtp ? 'disabled' : '' }}>
                                                @if($verifyingOtp)
                                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                                                    <span>Verifying...</span>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span>Verify & Continue</span>
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Step 2: Basic Information --}}
                        @if ($currentStep == 2)
                            <div class="space-y-6">
                                <div class="flex justify-end">
                                    @if ($hasValidSession)
                                            <button type="button" wire:click="clearSession"
                                                class="px-3 py-1.5 text-xs cursor-pointer text-gray-600 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 transition-colors">
                                                Use Different Email
                                            </button>
                                        @else
                                            <div></div>
                                        @endif
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 mb-2">Your Information</h2>
                                    <p class="text-gray-600">Tell us a bit about yourself.</p>
                                </div>

                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Your Name *</label>
                                    <input type="text" id="name" wire:model="name"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter your full name">
                                    @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Type of Feedback *</label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        @foreach(['correction' => 'Correction', 'addition' => 'Addition', 'update' => 'Update', 'suggestion' => 'Suggestion'] as $value => $label)
                                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-colors {{ $type === $value ? 'border-blue-500 bg-blue-50' : '' }}">
                                                <input type="radio" wire:model="type" value="{{ $value }}" class="text-blue-600 focus:ring-blue-500">
                                                <span class="ml-3 text-sm font-medium text-gray-700">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif

                        {{-- Step 3: Feedback Details --}}
                        @if ($currentStep == 3)
                            <div class="space-y-6">
                                <div class="flex justify-end">
                                    @if ($hasValidSession)
                                            <button type="button" wire:click="clearSession"
                                                class="px-3 py-1.5 text-xs cursor-pointer text-gray-600 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 transition-colors">
                                                Use Different Email
                                            </button>
                                        @else
                                            <div></div>
                                        @endif
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 mb-2">Your Feedback</h2>
                                    <p class="text-gray-600">Provide detailed feedback about what needs to be changed or added.</p>
                                </div>

                                {{-- Content with Quill Editor --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Feedback *
                                        </label>

                                        <x-quill-editor
                                            wire:model="message"
                                            placeholder="Write your feedback here..."
                                            height="400px"
                                            toolbar="basic"
                                        />

                                        {{-- Hidden field for validation --}}
                                        <input type="hidden" wire:model="content" />

                                        @error('content')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Use the toolbar above to format your content with rich text editing.
                                        </p>
                                    </div>
                            </div>
                        @endif

                        {{-- Step 4: Suggested Changes --}}
                        @if ($currentStep == 4)
                            <div class="space-y-6">
                                <div class="flex justify-end">
                                    @if ($hasValidSession)
                                            <button type="button" wire:click="clearSession"
                                                class="px-3 py-1.5 text-xs cursor-pointer text-gray-600 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 transition-colors">
                                                Use Different Email
                                            </button>
                                        @else
                                            <div></div>
                                        @endif
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 mb-2">Suggested Changes (Optional)</h2>
                                    <p class="text-gray-600">Provide specific corrections or additions to help us improve this profile.</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="suggestedName" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                            <input type="text" id="suggestedName" wire:model="suggestedName"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                placeholder="Correct name if different">
                                        </div>
                                        <div>
                                            <label for="suggestedProfession" class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                                            <input type="text" id="suggestedProfession" wire:model="suggestedProfession"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                placeholder="Correct profession">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="suggestedBirthDate" class="block text-sm font-medium text-gray-700 mb-2">Birth Date</label>
                                            <input type="date" id="suggestedBirthDate" wire:model="suggestedBirthDate"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label for="suggestedDeathDate" class="block text-sm font-medium text-gray-700 mb-2">Death Date</label>
                                            <input type="date" id="suggestedDeathDate" wire:model="suggestedDeathDate"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="suggestedNationality" class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
                                        <input type="text" id="suggestedNationality" wire:model="suggestedNationality"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
                                            placeholder="Correct nationality">
                                    </div>
                                    <div>
                                        <label for="suggestedAbout" class="block text-sm font-medium text-gray-700 mb-2">Additional Information</label>
                                        <textarea id="suggestedAbout" wire:model="suggestedAbout" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
                                            placeholder="Any other corrections or additional information..."></textarea>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($currentStep > 1 && $currentStep < 5)
                            <div class="flex justify-between pt-8 border-t border-gray-200 mt-8">
                                @if ($currentStep > 1)
                                    <button type="button" wire:click="previousStep"
                                        class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        ← Previous
                                    </button>
                                @else
                                    <div></div>
                                @endif

                                @if ($currentStep < 3)
                                    <button type="button" wire:click="nextStep"
                                        class="px-6 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        Next Step →
                                    </button>
                                @else
                                    <button type="submit"
                                        class="px-6 py-3 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $submittingFeedback ? 'disabled' : '' }}>
                                        @if($submittingFeedback)
                                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                                            <span>Submitting...</span>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>Send Message</span>
                                        @endif
                                    </button>
                                @endif
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- Help Text --}}
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                Your feedback helps us maintain accurate and up-to-date information.
                We review all suggestions carefully.
            </p>
        </div>
    </div>
</div>

         {{-- ✅ Quill Editor Global --}}
    @assets
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    @endassets

    <script>
        document.addEventListener('alpine:init', () => {
    if (!Alpine.data('quillEditor')) {
        Alpine.data('quillEditor', (livewireContent, placeholderText, editorHeight, toolbarType) => ({
            quill: null,
            initialized: false,
            placeholder: placeholderText,
            height: editorHeight,
            toolbar: toolbarType,
            livewireContent,

            init() {
                // 🧠 Prevent double initialization
                if (this.initialized) return;
                this.initialized = true;
                this.$nextTick(() => this.initializeQuill());
            },

            initializeQuill() {
                // 🧱 Create editor DOM node
                const editorElement = document.createElement('div');
                editorElement.style.height = this.height;
                this.$refs.editorContainer.appendChild(editorElement);

                // 🧰 Toolbar config
                const toolbars = {
                    full: [
                        [{ 'font': [] }, { 'size': [] }],
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        [{ 'direction': 'rtl' }],
                        [{ 'align': [] }],
                        ['blockquote'],
                        ['link',],
                        ['clean']
                    ],
                    basic: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        ['link', 'image'],
                        ['clean']
                    ],
                    minimal: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link'],
                        ['clean']
                    ]
                };
                const toolbarConfig = toolbars[this.toolbar] || toolbars.full;

                // 🪶 Initialize Quill
                this.quill = new Quill(editorElement, {
                    theme: 'snow',
                    modules: {
                        toolbar: toolbarConfig,
                        clipboard: { matchVisual: false },
                    },
                    placeholder: this.placeholder,
                });

                // 🔄 Set initial content
                if (this.livewireContent) {
                    this.quill.root.innerHTML = this.livewireContent;
                }

                // 🧩 Livewire sync
                this.quill.on('text-change', () => {
                    const html = this.quill.root.innerHTML;
                    this.livewireContent = html;
                    this.$refs.hiddenTextarea.value = html;
                    this.$refs.hiddenTextarea.dispatchEvent(new Event('input'));
                });


                // 🪄 React to external Livewire changes
                this.$watch('livewireContent', value => {
                    if (value && this.quill && value !== this.quill.root.innerHTML) {
                        this.quill.root.innerHTML = value;
                    }
                });
            },
        }));
    }
});
    </script>
