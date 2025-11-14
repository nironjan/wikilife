<div>
    <section class="bg-linear-to-br from-white via-red-50/30 to-white flex items-center py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Column - Content & Search -->
                <div class="space-y-4 text-center md:text-left">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-red-100 text-red-700 text-xs font-medium border border-red-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        India's Biography Encyclopedia
                    </div>

                    <!-- Main Heading -->
                    <div class="space-y-2">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                            Discover
                            <span class="text-red-600">Remarkable</span>
                            Lives
                        </h1>

                        <p class="text-xs md:text-base text-gray-600 leading-relaxed max-w-lg">
                            Explore thousands of comprehensive biographies of influential people in India.
                            Their stories, achievements, and legacies await.
                        </p>
                    </div>

                    <!-- Search Box -->
                    <livewire:front.search-box
                        variant="hero"
                        input-class="bg-gray-900 text-black border-gray-700 placeholder-gray-400"
                        button-class="text-black hover:text-red-400"
                    />

                    <!-- Quick Stats -->
                    <div class="flex justify-center md:justify-start gap-6 pt-4">
                        <div class="text-center md:text-left">
                            <div class="text-lg font-bold text-red-600">10K+</div>
                            <div class="text-sm text-gray-600">Biographies</div>
                        </div>
                        <div class="text-center md:text-left">
                            <div class="text-lg font-bold text-red-600">50+</div>
                            <div class="text-sm text-gray-600">Professions</div>
                        </div>
                        <div class="text-center md:text-left">
                            <div class="text-lg font-bold text-red-600">1M+</div>
                            <div class="text-sm text-gray-600">Readers</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Illustration -->
                <div class="relative hidden md:block">
                    <!-- Main Illustration Container -->
                    <div class="relative p-4 mb-2">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <svg class="w-full h-full" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="200" cy="200" r="150" stroke="currentColor" stroke-width="1" fill="none"/>
                                <circle cx="200" cy="200" r="100" stroke="currentColor" stroke-width="1" fill="none"/>
                                <circle cx="200" cy="200" r="50" stroke="currentColor" stroke-width="1" fill="none"/>
                            </svg>
                        </div>

                        <!-- Main Illustration -->
                        <div class="relative z-10">
                            <!-- Book/Knowledge Theme -->
                            <div class="flex justify-center items-center space-x-8 mb-8">
                                <!-- Book Stack -->
                                <div class="relative">
                                    <div class="w-16 h-20 bg-red-500 rounded-lg transform rotate-6 shadow-lg"></div>
                                    <div class="w-16 h-20 bg-red-400 rounded-lg absolute top-1 left-1 transform -rotate-3 shadow-lg"></div>
                                    <div class="w-16 h-20 bg-red-300 rounded-lg absolute top-2 left-2 transform rotate-2 shadow-lg"></div>
                                </div>

                                <!-- Globe -->
                                <div class="relative">
                                    <div class="w-20 h-20 bg-blue-500 rounded-full shadow-lg"></div>
                                    <div class="absolute inset-2 bg-blue-400 rounded-full"></div>
                                    <div class="absolute top-4 left-6 w-8 h-1 bg-white/30 transform rotate-45"></div>
                                    <div class="absolute top-6 left-4 w-1 h-8 bg-white/30 transform rotate-45"></div>
                                </div>
                            </div>

                            <!-- People Silhouettes -->
                            <div class="flex justify-center space-x-6 mb-8">
                                <!-- Scientist -->
                                <div class="text-center">
                                    <div class="w-12 h-16 bg-purple-500 rounded-t-full mx-auto mb-2 relative">
                                        <div class="w-8 h-8 bg-purple-400 rounded-full absolute -top-2 left-2"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 font-medium">Scientists</span>
                                </div>

                                <!-- Artist -->
                                <div class="text-center">
                                    <div class="w-12 h-16 bg-green-500 rounded-t-full mx-auto mb-2 relative">
                                        <div class="w-3 h-3 bg-green-400 rounded-full absolute top-2 left-4"></div>
                                        <div class="w-6 h-4 bg-green-400 rounded-full absolute bottom-4 left-3"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 font-medium">Artists</span>
                                </div>

                                <!-- Leader -->
                                <div class="text-center">
                                    <div class="w-12 h-16 bg-yellow-500 rounded-t-full mx-auto mb-2 relative">
                                        <div class="w-8 h-8 bg-yellow-400 rounded-full absolute -top-2 left-2"></div>
                                        <div class="w-4 h-2 bg-yellow-300 absolute bottom-6 left-4"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 font-medium">Leaders</span>
                                </div>
                            </div>

                            <!-- Achievement Icons -->
                            <div class="flex justify-center space-x-8">
                                <!-- Trophy -->
                                <div class="text-center">
                                    <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-1 shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-gray-500">Achievements</span>
                                </div>

                                <!-- Lightbulb -->
                                <div class="text-center">
                                    <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-1 shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-gray-500">Innovations</span>
                                </div>

                                <!-- Heart -->
                                <div class="text-center">
                                    <div class="w-10 h-10 bg-pink-500 rounded-full flex items-center justify-center mx-auto mb-1 shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-gray-500">Legacy</span>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Elements -->
                        <div class="absolute -top-4 right-4 w-8 h-8 bg-red-400 rounded-full opacity-20 animate-pulse"></div>
                        <div class="absolute -bottom-4 -left-4 w-6 h-6 bg-blue-400 rounded-full opacity-20 animate-pulse delay-1000"></div>
                        <div class="absolute top-1/2 right-6 w-4 h-4 bg-green-400 rounded-full opacity-20 animate-pulse delay-500"></div>
                    </div>

                    <!-- Background Decorative Elements -->
                    <div class="absolute -z-10 top-10  w-32 h-32 bg-red-200 rounded-full opacity-20 blur-xl"></div>
                    <div class="absolute -z-10 bottom-10 -left-10 w-40 h-40 bg-blue-200 rounded-full opacity-20 blur-xl"></div>
                </div>
            </div>
        </div>
    </section>
</div>
