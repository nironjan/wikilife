<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center px-4 py-8">
    <div class="max-w-sm w-full text-center">
        <!-- Animated Robot -->
        <div class="mb-6 relative">
            <div class="w-24 h-24 mx-auto mb-3 relative">
                <!-- Robot Head -->
                <div class="w-24 h-24 bg-white rounded-xl border-4 border-blue-500 relative shadow-lg">
                    <!-- Eyes -->
                    <div class="flex justify-center space-x-6 pt-6">
                        <div class="w-4 h-4 bg-blue-500 rounded-full animate-pulse"></div>
                        <div class="w-4 h-4 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                    </div>
                    <!-- Mouth -->
                    <div class="w-12 h-1 bg-blue-500 rounded-full mx-auto mt-4 animate-pulse" style="animation-delay: 1s;"></div>
                </div>

                <!-- Antenna -->
                <div class="w-1.5 h-4 bg-gray-400 absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <div class="w-2 h-2 bg-red-400 rounded-full absolute -top-0.5 -left-0.25 animate-ping"></div>
                </div>
            </div>

            <!-- Pulsing Ring -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-28 h-28 border-4 border-blue-400 rounded-full animate-ping opacity-20"></div>
            </div>
        </div>

        <!-- Error Code -->
        <div class="text-6xl font-bold text-blue-600 mb-1 font-mono tracking-wide">
            {{ $errorCode }}
        </div>

        <!-- Error Message -->
        <h1 class="text-xl font-bold text-gray-800 mb-4 uppercase tracking-wide">
            {{ $errorMessage }}
        </h1>

        <!-- Robot Messages -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 mb-6 border border-blue-200 shadow-sm">
            @foreach($robotMessage as $message)
                <p class="text-gray-700 mb-2 text-base leading-relaxed">
                    {{ $message }}
                </p>
            @endforeach
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <button
                onclick="window.history.back()"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 px-5 rounded-lg transition-all duration-300 transform hover:scale-105 uppercase tracking-wide flex items-center justify-center space-x-2 text-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Go Back</span>
            </button>

            <a
                href="{{ url('/') }}"
                class="w-full bg-transparent hover:bg-blue-500/10 text-blue-600 border border-blue-500 font-semibold py-2.5 px-5 rounded-lg transition-all duration-300 transform hover:scale-105 uppercase tracking-wide flex items-center justify-center space-x-2 text-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Return Home</span>
            </a>
        </div>

        <!-- Technical Info (only show in local/dev) -->
        @if(app()->environment('local'))
        <div class="mt-6 p-3 bg-white/50 rounded-lg border border-gray-300">
            <p class="text-gray-600 text-xs font-mono">
                Path: {{ request()->path() }}
            </p>
        </div>
        @endif
    </div>
</div>
