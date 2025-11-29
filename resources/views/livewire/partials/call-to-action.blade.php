<div>
    <div class="relative overflow-hidden">
        {{-- Background Gradient with Indian flag colors --}}
        <div class="absolute inset-0 bg-linear-to-br from-saffron-50 via-white to-green-50"></div>

        {{-- Decorative Elements --}}
        <div
            class="absolute top-0 left-0 w-72 h-72 bg-saffron-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
        </div>
        <div
            class="absolute top-0 right-0 w-72 h-72 bg-green-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-8 left-20 w-72 h-72 bg-white rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000">
        </div>

        <section class="relative py-6 lg:py-12 mb-12">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="text-center md:text-left">
                        {{-- Badge with Indian context --}}
                    <div
                        class="inline-flex items-center px-4 py-2 rounded-full bg-saffron-100 border border-saffron-200 mb-8">
                        <svg class="w-4 h-4 text-saffron-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-saffron-700">Trusted by millions of Indians 🇮🇳</span>
                    </div>

                    {{-- Main Heading with Indian focus --}}
                    <h2 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                        Discover the Stories Behind
                        <span class="text-transparent bg-clip-text bg-linear-to-r from-saffron-600 to-green-600">Great
                            Indian Minds</span>
                    </h2>

                    {{-- Subheading with Indian context --}}
                    <p class="text-base text-gray-600 mb-4 max-w-4xl mx-auto leading-relaxed">
                        Explore comprehensive biographies of remarkable Indians who shaped our nation and the world.
                        From freedom fighters and scientists to artists and entrepreneurs – their inspiring journeys
                        await you.
                    </p>
                     {{-- CTA Buttons --}}
                     <button wire:click="explorePeople" class="p-4 bg-white shadow border border-gray-300 cursor-pointer rounded-xl">

                         {{-- Text + Icon --}}
                         <span class="relative z-10 flex items-center">
                             Explore Indian Biographies
                             <svg class="w-5 h-5 ml-2 transition-transform duration-300 ease-out group-hover:translate-x-1.5"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                             </svg>
                         </span>

                     </button>

                    </div>

                    <div class="text-center">
                        {{-- Stats --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16 max-w-2xl mx-auto">
                            <div class="text-center">
                                <div class="text-xl lg:text-2xl font-bold text-gray-900 mb-2">{{ $stats['biographies'] }}
                                </div>
                                <div class="text-sm text-gray-600 font-medium">Indian Biographies</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl lg:text-2xl font-bold text-gray-900 mb-2">{{ $stats['professions'] }}
                                </div>
                                <div class="text-sm text-gray-600 font-medium">Professions</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl lg:text-2xl font-bold text-gray-900 mb-2">{{ $stats['updated'] }}</div>
                                <div class="text-sm text-gray-600 font-medium">Updated</div>
                            </div>
                        </div>



                        {{-- Trust Indicators with Indian context --}}
                        <div class="mt-16 pt-8 border-t border-gray-200">
                            <p class="text-sm text-gray-500 mb-6">Trusted by students, researchers, and curious minds across
                                India</p>
                            <div class="flex flex-wrap justify-center items-center gap-8 opacity-60">
                                <div class="text-gray-400 font-semibold">✓ Authentic Indian Sources</div>
                                <div class="text-gray-400 font-semibold">✓ Cultural Context</div>
                                <div class="text-gray-400 font-semibold">✓ Community Verified</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
