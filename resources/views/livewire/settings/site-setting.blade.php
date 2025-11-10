<div>
    <div class="min-h-screen  dark:from-gray-900 dark:to-gray-800 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div class="mb-8">
                <div
                    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl font-bold text-gray-900 dark:text-white bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                                    Site Settings
                                </h1>
                                <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">
                                    Configure your website step by step
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200/50 dark:border-gray-700/50 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Step {{ $currentStep }} of {{ $totalSteps }}
                        </span>
                        <span class="text-sm font-medium text-purple-600 dark:text-purple-400">
                            {{ round(($currentStep / $totalSteps) * 100) }}% Complete
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full transition-all duration-500"
                            style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <span>Basic Info</span>
                        <span>Branding</span>
                        <span>SEO</span>
                        <span>Social & Scripts</span>
                        <span>Settings</span>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200/60 dark:border-gray-700/60 overflow-hidden">
                <form wire:submit.prevent="saveSettings">
                    <div class="p-6 lg:p-8">
                        <!-- Step 1: Basic Information -->
                        @if ($currentStep == 1)
                            @include('livewire.settings.partials.step-basic-info')
                        @endif

                        <!-- Step 2: Branding & Images -->
                        @if ($currentStep == 2)
                            @include('livewire.settings.partials.step-branding')
                        @endif

                        <!-- Step 3: SEO & Meta -->
                        @if ($currentStep == 3)
                            @include('livewire.settings.partials.step-seo-meta')
                        @endif

                        <!-- Step 4: Social Media & Scripts -->
                        @if ($currentStep == 4)
                            @include('livewire.settings.partials.step-social-scripts')
                        @endif

                        <!-- Step 5: Localization & Settings -->
                        @if ($currentStep == 5)
                            @include('livewire.settings.partials.step-localization')
                        @endif

                        <!-- Navigation Buttons -->
                        <div
                            class="flex items-center justify-between pt-8 border-t border-gray-200 dark:border-gray-600">
                            <div>
                                @if ($currentStep > 1)
                                    <button type="button" wire:click="previousStep"
                                        class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 font-medium cursor-pointer">
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Previous
                                    </button>
                                @endif
                            </div>

                            <div class="flex items-center space-x-4">
                                @if ($currentStep < $totalSteps)
                                    <button type="button" wire:click="nextStep"
                                        class="px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-lg font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl cursor-pointer">
                                        Next
                                        <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                @else
                                    <button type="submit"
                                        class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg font-semibold transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl cursor-pointer">
                                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Save All Settings
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Info Card -->
            <div
                class="mt-6 bg-purple-50/50 dark:bg-purple-900/20 border border-purple-200/50 dark:border-purple-800/50 rounded-2xl p-6">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mt-0.5 flex-shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-purple-800 dark:text-purple-300">
                        <p class="font-medium">Step {{ $currentStep }} Tips:</p>
                        <ul class="mt-2 space-y-1 list-disc list-inside opacity-90">
                            @if ($currentStep == 1)
                                <li>Use a clear and memorable site name</li>
                                <li>Provide accurate contact information</li>
                                <li>Keep tagline concise and descriptive</li>
                            @elseif($currentStep == 2)
                                <li>Use high-quality logo images</li>
                                <li>Favicon should be 32x32 pixels</li>
                                <li>Optimize images for web performance</li>
                            @elseif($currentStep == 3)
                                <li>Keep meta titles under 60 characters</li>
                                <li>Meta descriptions should be 150-160 characters</li>
                                <li>Use relevant keywords naturally</li>
                            @elseif($currentStep == 4)
                                <li>Use full URLs for social media links</li>
                                <li>Test scripts before adding them</li>
                                <li>Keep social media profiles updated</li>
                            @else
                                <li>Choose appropriate timezone for your audience</li>
                                <li>Set correct currency for your region</li>
                                <li>Test maintenance mode before going live</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
