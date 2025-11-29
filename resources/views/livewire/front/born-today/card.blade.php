@assets
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endassets

<div>
    @if(!$loading && $peopleBornToday->count() > 0)
    {{-- Add Swiper CSS and JS CDN --}}


    <section class="py-12 bg-white border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Born Today</h2>
                        <p class="text-gray-600 text-sm">Celebrating birthdays of remarkable people born on this day</p>
                    </div>
                </div>

                {{-- View All Link --}}
                <a href="{{ route('people.born-today') }}"
                    class="hidden sm:flex items-center text-red-600 hover:text-red-700 font-medium text-sm">
                    View All
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            {{-- Swiper Slider --}}
            <div class="relative born-today-slider">
                {{-- Slider Container --}}
                <div class="swiper born-today-swiper">
                    <div class="swiper-wrapper">
                        @foreach($peopleBornToday as $person)
                        <div class="swiper-slide">
                            <div
                                class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group h-full">
                                {{-- Image Section --}}
                                <div class="relative overflow-hidden">
                                    <a href="{{ route('people.people.show', $person->slug) }}" class="block">
                                        @if($person->profile_image)
                                        <img src="{{ $person->profile_image }}" alt="{{ $person->name }}"
                                            class="w-full h-full md:h-48 object-cover group-hover:scale-105 transition duration-500"
                                            loading="lazy">
                                        @else
                                        <div
                                            class="w-full h-full md:h-48 bg-linear-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        @endif
                                    </a>

                                    {{-- Birthday Badge --}}
                                    <div class="absolute top-3 left-3">
                                        <div
                                            class="flex items-center px-3 py-1.5 rounded-full text-xs font-semibold text-white
                                                bg-gradient-to-r from-rose-500 to-pink-600
                                                shadow-md backdrop-blur-sm ring-1 ring-white/20
                                                transition-transform duration-200 hover:scale-105">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Birthday
                                        </div>
                                    </div>

                                </div>

                                {{-- Content Section --}}
                                <div class="p-4">
                                    <a href="{{ route('people.people.show', $person->slug) }}" class="block">
                                        <h3
                                            class="font-bold text-gray-900 text-base line-clamp-1 group-hover:text-red-600 transition duration-200">
                                            {{ $person->name }}
                                        </h3>
                                    </a>
                                    <div class="flex items-center justify-between text-gray-600 text-sm mt-2">
                                        @if($person->primary_profession)
                                        <p class="font-medium flex items-center truncate">
                                            {{ $person->primary_profession }}
                                        </p>
                                        @endif

                                        @if($person->birth_date && $person->age)
                                        <span
                                            class="ml-2 inline-flex items-center px-2 py-0.5 text-gray-700 text-xs font-semibold bg-gray-100 rounded">
                                            {{ $person->age }} yrs
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div
                    class="born-today-swiper-button-prev absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-4 z-10 bg-white border border-gray-200 rounded-full p-2 shadow-lg hover:shadow-xl transition duration-200 hover:bg-gray-50 cursor-pointer hidden sm:flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>

                <div
                    class="born-today-swiper-button-next absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-4 z-10 bg-white border border-gray-200 rounded-full p-2 shadow-lg hover:shadow-xl transition duration-200 hover:bg-gray-50 cursor-pointer hidden sm:flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>

                {{-- Pagination Dots --}}
                <div class="born-today-swiper-pagination flex justify-center space-x-2"></div>
            </div>

            {{-- Mobile View All Link --}}
            <div class="sm:hidden text-center mt-6">
                <a href="{{ route('people.born-today') }}"
                    class="inline-flex items-center text-red-600 hover:text-red-700 font-medium text-sm">
                    View All Born Today
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <style>
        /* Custom styles for Swiper */
        .born-today-swiper {
            width: 100%;
            height: 100%;
            padding: 10px 0;
        }

        .swiper-slide {
            width: auto;
            height: auto;
        }

        /* Centered Pagination dots styling */
        .born-today-swiper-pagination {
            position: absolute !important;
            /* Position below the slider */
            left: 50% !important;
            transform: translateX(-50%) !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 8px !important;
            width: auto !important;
            z-index: 10 !important;
        }

        .born-today-swiper-pagination .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            background: #d1d5db;
            opacity: 1;
            transition: all 0.3s ease;
            margin: 0 4px !important;
        }

        .born-today-swiper-pagination .swiper-pagination-bullet-active {
            background: #dc2626;
            width: 24px;
            border-radius: 4px;
        }

        /* Navigation buttons - Always visible on desktop */
        .born-today-swiper-button-prev,
        .born-today-swiper-button-next {
            opacity: 0.8;
            transition: all 0.3s ease;
            display: flex !important;
            /* Force display */
        }

        .born-today-swiper-button-prev:hover,
        .born-today-swiper-button-next:hover {
            opacity: 1;
            transform: scale(1.1);
            background: #f9fafb;
        }

        /* Hide navigation on mobile */
        @media (max-width: 640px) {

            .born-today-swiper-button-prev,
            .born-today-swiper-button-next {
                display: none !important;
            }
        }

        /* Ensure buttons are properly positioned */
        .born-today-swiper-button-prev {
            left: 0;
        }

        .born-today-swiper-button-next {
            right: 0;
        }

        /* Swiper slide width for better responsiveness */
        .swiper-slide {
            width: 280px;
            /* Fixed width for consistent cards */
        }

        @media (max-width: 640px) {
            .swiper-slide {
                width: 85%;
                /* Wider on mobile */
            }
        }

    </style>

    @endif
</div>

@script
<script>
    console.log('Swiper script loaded');

    // Simple Swiper initialization
    function initSwiper() {
        console.log('Initializing Swiper...');

        // Wait for Swiper to be available
        if (typeof Swiper === 'undefined') {
            console.log('Swiper not loaded yet, retrying...');
            setTimeout(initSwiper, 100);
            return;
        }

        const swiperEl = document.querySelector('.born-today-swiper');
        if (!swiperEl) {
            console.log('Swiper element not found');
            return;
        }

        try {
            // Destroy existing instance if any
            if (swiperEl.swiper) {
                swiperEl.swiper.destroy(true, true);
            }

            // Initialize new Swiper
            const swiper = new Swiper('.born-today-swiper', {
                slidesPerView: 1,
                spaceBetween: 16,
                loop: true, // Changed to true for autoplay
                grabCursor: true,

                // Autoplay configuration
                autoplay: {
                    delay: 3000, // 3 seconds
                    disableOnInteraction: false, // Continue autoplay after user interaction
                    pauseOnMouseEnter: true, // Pause when mouse hovers
                },

                // Responsive breakpoints
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 16
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 16
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 16
                    },
                    1280: {
                        slidesPerView: 5,
                        spaceBetween: 16
                    }
                },

                // Navigation
                navigation: {
                    nextEl: '.born-today-swiper-button-next',
                    prevEl: '.born-today-swiper-button-prev',
                },

                // Pagination
                pagination: {
                    el: '.born-today-swiper-pagination',
                    clickable: true,
                    dynamicBullets: true, // Better looking dots
                },

                // Touch
                touchRatio: 1,
                simulateTouch: true,

                // Speed
                speed: 600, // Smooth transition
            });

            console.log('✅ Swiper initialized successfully with autoplay');

            // Manual controls for autoplay (optional)
            const pauseAutoplay = () => {
                if (swiper.autoplay.running) {
                    swiper.autoplay.stop();
                    console.log('Autoplay paused');
                }
            };

            const resumeAutoplay = () => {
                if (!swiper.autoplay.running) {
                    swiper.autoplay.start();
                    console.log('Autoplay resumed');
                }
            };

            // Pause autoplay when interacting with navigation
            const nextBtn = document.querySelector('.born-today-swiper-button-next');
            const prevBtn = document.querySelector('.born-today-swiper-button-prev');

            if (nextBtn) {
                nextBtn.addEventListener('mouseenter', pauseAutoplay);
                nextBtn.addEventListener('mouseleave', resumeAutoplay);
                nextBtn.addEventListener('click', pauseAutoplay);
            }

            if (prevBtn) {
                prevBtn.addEventListener('mouseenter', pauseAutoplay);
                prevBtn.addEventListener('mouseleave', resumeAutoplay);
                prevBtn.addEventListener('click', pauseAutoplay);
            }

        } catch (error) {
            console.error('Error initializing Swiper:', error);
        }
    }

    // Initialize on load and when Livewire updates
    document.addEventListener('DOMContentLoaded', initSwiper);
    document.addEventListener('livewire:load', initSwiper);
    document.addEventListener('livewire:update', initSwiper);

    // Also try initializing after a short delay
    setTimeout(initSwiper, 500);
</script>
@endscript
