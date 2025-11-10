<div class="min-h-screen bg-gray-50" x-data="{ mobileMenuOpen: false }">
    <!-- Wikipedia-style Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
                <a href="{{ url('/') }}" class="hover:text-blue-600">Home</a>
                <span class="text-gray-400">›</span>
                <a href="{{ route('people.people.index') }}" class="hover:text-blue-600">People</a>
                <span class="text-gray-400">›</span>
                <span class="text-gray-900 font-medium">{{ $person->display_name }}</span>
            </nav>

            <!-- Main Header -->
            <div class="flex flex-col lg:flex-row gap-8 items-center lg:items-start text-center lg:text-left">
                <!-- Profile Image -->
                <div class="shrink-0">
                    <div class="w-48 h-48 rounded-lg border-4 border-white shadow-lg overflow-hidden mx-auto lg:mx-0">
                        @if($person->profile_image_url)
                        <img src="{{ $person->imageSize(300, 300) }}" alt="{{ $person->display_name }}"
                            class="w-full h-full object-cover">
                        @else
                        <div
                            class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Header Content -->
                <div class="flex-1 w-full">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $person->seo->meta_title ??
                        $person->display_name }}</h1>

                    @if($person->nicknames)
                    <p class="text-lg text-gray-600 mb-4">
                        Also known as: <span class="font-medium">{{ implode(', ', $person->nicknames) }}</span>
                    </p>
                    @endif

                    <!-- Quick Info Bar -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 mb-4 text-sm">
                        @foreach($person->professions as $profession)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-medium">
                            {{ $profession }}
                        </span>
                        @endforeach

                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full">
                            {{ $person->is_alive ? 'Alive' : 'Deceased' }}
                        </span>

                        @if($person->age)
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full">
                            {{ $person->age }} years
                        </span>
                        @endif
                    </div>

                    <!-- Vital Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 lg:text-left">
                        @if($person->birth_date)
                        <div class="flex items-start justify-center lg:justify-start">
                            <strong class="w-20 flex-shrink-0 text-left">Born:</strong>
                            <span class="text-left">
                                {{ $person->birth_date->format('F j, Y') }}
                                @if($person->place_of_birth)
                                <br><span class="text-gray-600">in {{ $person->place_of_birth }}</span>
                                @endif
                            </span>
                        </div>
                        @endif

                        @if($person->death_date)
                        <div class="flex items-start justify-center lg:justify-start">
                            <strong class="w-20 flex-shrink-0 text-left">Died:</strong>
                            <span class="text-left">
                                {{ $person->death_date->format('F j, Y') }}
                                @if($person->place_of_death)
                                <br><span class="text-gray-600">in {{ $person->place_of_death }}</span>
                                @endif
                                @if($person->death_cause)
                                <br><span class="text-gray-600">({{ $person->death_cause }})</span>
                                @endif
                            </span>
                        </div>
                        @endif

                        @if($person->nationality)
                        <div class="flex items-center justify-center lg:justify-start">
                            <strong class="w-20 flex-shrink-0 text-left">Nationality:</strong>
                            <span class="text-left">{{ $person->nationality }}</span>
                        </div>
                        @endif

                        @if($person->years_active = $this->getYearsActive())
                        <div class="flex items-center justify-center lg:justify-start">
                            <strong class="w-20 shrink-0 text-left">Years active:</strong>
                            <span class="text-left">{{ $person->years_active }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Tabs Navigation -->
    <div class="bg-white border-b border-gray-200 hidden lg:block" wire:key="desktop-tabs-{{ $tab }}">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex space-x-8 overflow-x-auto">
                @foreach($this->getTabs() as $tabKey => $config)
                @if($this->shouldShowTab($tabKey))
                <a href="{{ $this->getTabUrl($tabKey) }}" wire:key="tab-{{ $tabKey }}"
                    class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-colors duration-200 {{ $this->isActiveTab($tabKey) ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $config['title'] }}
                    @if($config['count'] > 0)
                    <span class="ml-1 bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">
                        {{ $config['count'] }}
                    </span>
                    @endif
                </a>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar (Desktop) -->
            <div class="lg:col-span-1 hidden lg:block">
                @include('livewire.front.person.partials.details-sidebar')
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">

                    {{-- Visible active Tab --}}
                    <div>
                        @switch($tab)
                        @case('overview')
                        <div class="p-2 md:p-6" wire:key="tab-overview">
                            @include('livewire.front.person.tabs.overview')
                        </div>
                        @break
                        @case('biography')
                        <div class="p-2 md:p-6" wire:key="tab-biography">
                            @include('livewire.front.person.tabs.biography')
                        </div>
                        @break
                        @case('career')
                        <div class="p-2 md:p-6" wire:key="tab-career">
                            @include('livewire.front.person.tabs.career')
                        </div>
                        @break
                        @case('personal_life')
                        <div class="p-2 md:p-6" wire:key="tab-personal-life">
                            @include('livewire.front.person.tabs.personal-life')
                        </div>
                        @break
                        @case('awards')
                        <div class="p2 md:p-6" wire:key="tab-awards">
                            @include('livewire.front.person.tabs.awards')
                        </div>
                        @break
                        @case('gallery')
                        <div class="p-2 md:p-6" wire:key="tab-gallery">
                            @include('livewire.front.person.tabs.gallery')
                        </div>
                        @break
                        @case('controversies')
                        <div class="p-2 md:p-6" wire:key="tab-controversies">
                            @include('livewire.front.person.tabs.controversies')
                        </div>
                        @break
                        @default
                        <div class="p-2 md:p-6" wire:key="tab-default">
                            @include('livewire.front.person.tabs.overview')
                        </div>
                        @endswitch
                    </div>
                    <div class="sr-only">
                        <section aria-hidden="true">
                            @include('livewire.front.person.tabs.biography')
                            @include('livewire.front.person.tabs.career')
                            @include('livewire.front.person.tabs.personal-life')
                            @include('livewire.front.person.tabs.awards')
                            @include('livewire.front.person.tabs.gallery')
                            @include('livewire.front.person.tabs.controversies')
                        </section>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scrollable Mobile Floating Menu -->
    <div class="lg:hidden fixed bottom-0 left-4 right-4 z-50">
        <div class="bg-white/95 backdrop-blur-lg border border-gray-200/80 rounded-2xl shadow-2xl shadow-black/20">
            <!-- Scrollable Container -->
            <div class="flex overflow-x-auto scrollbar-hide px-4 py-3 space-x-3" x-data="{ scrollPosition: 0 }"
                @scroll.debounce="scrollPosition = $event.target.scrollLeft">

                <!-- Left Scroll Indicator -->
                <div x-show="scrollPosition > 0" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 w-6 h-full bg-gradient-to-r from-white to-transparent pointer-events-none z-10">
                    <div class="w-2 h-2 bg-gray-400 rounded-full absolute top-1/2 left-1 transform -translate-y-1/2">
                    </div>
                </div>

                <!-- Right Scroll Indicator -->
                <div x-show="scrollPosition < $el.scrollWidth - $el.clientWidth - 10"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 w-6 h-full bg-gradient-to-l from-white to-transparent pointer-events-none z-10">
                    <div class="w-2 h-2 bg-gray-400 rounded-full absolute top-1/2 right-1 transform -translate-y-1/2">
                    </div>
                </div>

                @foreach($this->getTabs() as $tab => $config)
                @if($this->shouldShowTab($tab))
                <a href="{{ $this->getTabUrl($tab) }}" class="relative flex flex-col items-center p-3 min-w-16 flex-shrink-0 rounded-xl transition-all duration-300 ease-out
                       {{ $this->isActiveTab($tab)
                           ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/25 transform -translate-y-0.5'
                           : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/80' }}" x-data="{ tooltip: false }"
                    @mouseenter="tooltip = true" @mouseleave="tooltip = false" @touchstart="tooltip = true"
                    @touchend="setTimeout(() => tooltip = false, 1500)">

                    <!-- Icon based on tab type -->
                    <div class="relative">
                        @switch($tab)
                        @case('overview')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        @break
                        @case('biography')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        @break
                        @case('career')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        @break
                        @case('personal_life')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        @break
                        @case('awards')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                        @break
                        @case('gallery')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        @break
                        @case('controversies')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        @break
                        @default
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @endswitch

                        <!-- Notification badge for counts -->
                        @if($config['count'] > 0)
                        <span class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 text-xs
                                {{ $this->isActiveTab($tab)
                                    ? 'bg-white text-blue-600'
                                    : 'bg-blue-500 text-white' }}
                                rounded-full font-semibold shadow-sm">
                            {{ $config['count'] > 9 ? '9+' : $config['count'] }}
                        </span>
                        @endif
                    </div>

                    <!-- Tab label -->
                    <span class="text-xs text-center font-semibold mt-1 leading-tight whitespace-nowrap">
                        {{ $config['title'] }}
                    </span>
                </a>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Add CSS to hide scrollbar -->
    <style>
        .scrollbar-hide {
            -ms-overflow-style: none;
            /* Internet Explorer 10+ */
            scrollbar-width: none;
            /* Firefox */
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
            /* Safari and Chrome */
        }

        /* Smooth scrolling */
        .scrollbar-hide {
            scroll-behavior: smooth;
        }
    </style>

    <!-- Add padding for mobile floating menu -->
    <div class="pb-28 lg:pb-0"></div>

@script
    <script>
        window.addEventListener('scroll-to-top', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    </script>
    @endscript

    <!-- Modal for Details -->
    <div x-data="{ showModal: false, modalContent: '', modalTitle: '' }" x-show="showModal"
        class="fixed inset-0 z-50 overflow-y-auto"
        x-on:open-modal.window="showModal = true; modalContent = $event.detail.content; modalTitle = $event.detail.title"
        x-on:keydown.escape.window="showModal = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75">
            </div>

            <div x-show="showModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" x-text="modalTitle"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="prose max-w-none text-gray-700" x-html="modalContent.replace(/\n/g, '<br>')"></div>
            </div>
        </div>
    </div>
</div>
