<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Home
                </a>
                <span class="text-gray-300">›</span>
                <a href="{{ route('people.people.show', $person->slug) }}" class="hover:text-blue-600 transition-colors duration-200 truncate max-w-[120px]">
                    {{ $person->display_name }}
                </a>
                <span class="text-gray-300">›</span>
                <a href="{{ route('people.details.tab', ['slug' => $person->slug, 'tab' => 'career']) }}" class="hover:text-blue-600 transition-colors duration-200">
                    Career
                </a>
                <span class="text-gray-300">›</span>
                <span class="text-gray-900 font-semibold truncate max-w-[150px]">{{ Str::limit($this->getCareerTitle(), 40) }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-8 space-y-8">
                    {{-- Main Content Card --}}
                    <div class="bg-white rounded-lg shadow overflow-hidden transition-all duration-300 hover:shadow-md">
                        {{-- Header Section --}}
                        <div class="bg-gradient-to-r from-{{ $meta['color'] }}-50 via-{{ $meta['color'] }}-100 to-{{ $meta['color'] }}-200 border-b border-{{ $meta['color'] }}-200 p-6 lg:p-8">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                                {{-- Person Info & Title --}}
                                <div class="flex-1">
                                    {{-- Person Card --}}
                                    {{-- Responsive Profile Header with Top Image --}}
                                    <div class="flex flex-col items-center text-center sm:flex-row sm:items-start sm:text-left gap-4 mb-6">
                                        {{-- Profile Image - Top on Mobile, Left on Desktop --}}
                                        <a href="{{ route('people.people.show', $person->slug) }}" class="flex-shrink-0 transform hover:scale-105 transition-transform duration-200">
                                            <div class="relative">
                                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-gray-200 to-gray-300 overflow-hidden border-4 border-white shadow-lg">
                                                    @if($person->profile_image_url)
                                                    <img src="{{ $person->imageSize(100, 100) }}" alt="{{ $person->display_name }}"
                                                        class="w-full h-full object-cover" loading="lazy">
                                                    @else
                                                    <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                                                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                    @endif
                                                </div>
                                                {{-- Career Type Badge --}}
                                                <div class="absolute -bottom-1 -right-1 w-5 h-5 sm:w-6 sm:h-6 bg-{{ $meta['color'] }}-500 rounded-full border-2 border-white flex items-center justify-center shadow-md">
                                                    <span class="text-xs text-white font-bold">{{ $meta['icon'] }}</span>
                                                </div>
                                            </div>
                                        </a>

                                        {{-- Name & Professions --}}
                                        <div class="flex-1 min-w-0 sm:mt-0">
                                            <a href="{{ route('people.people.show', $person->slug) }}" class="group block">
                                                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200 leading-tight mb-2 sm:mb-3">
                                                    {{ $person->display_name }}
                                                </h2>
                                            </a>
                                            <div class="flex flex-wrap justify-center sm:justify-start gap-2">
                                                @foreach($person->professions as $profession)
                                                <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs md:font-semibold bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border border-blue-200 whitespace-nowrap">
                                                    {{ $profession }}
                                                </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Career Title --}}
                                    <h1 class="text-xl lg:text-2xl font-bold text-gray-900 mb-6 leading-tight tracking-tight flex items-center">
                                        <span class="text-2xl mr-4">{{ $meta['icon'] }}</span>
                                        {{ $this->getCareerTitle() }}
                                    </h1>

                                    {{-- Consolidated Meta Information Card --}}
                                    <div class="bg-white rounded-xl border border-gray-200 p-4 lg:p-6">
                                        <div class="flex flex-wrap items-center gap-3 lg:gap-4">
                                            {{-- Primary Field --}}
                                            @if($meta['primary_field'])
                                            <div class="flex items-center px-3 py-2 rounded-lg border border-gray-200">
                                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-700">{{ $meta['primary_field'] }}</span>
                                            </div>
                                            @endif

                                            {{-- Secondary Field --}}
                                            @if($meta['secondary_field'])
                                            <div class="flex items-center px-3 py-2 rounded-lg border border-gray-200">
                                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-700">{{ $meta['secondary_field'] }}</span>
                                            </div>
                                            @endif

                                            {{-- Duration --}}
                                            @if($dateInfo['duration'])
                                            <div class="flex items-center  px-3 py-2 rounded-lg border border-blue-200">
                                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm text-blue-700">{{ $dateInfo['duration'] }}</span>
                                            </div>
                                            @endif

                                            {{-- Status --}}
                                            @if($dateInfo['is_current'] !== null)
                                            <div class="flex items-center {{ $dateInfo['is_current'] ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }} px-3 py-2 rounded-lg border">
                                                <div class="w-2 h-2 rounded-full {{ $dateInfo['is_current'] ? 'bg-green-500 animate-pulse' : 'bg-gray-500' }} mr-2"></div>
                                                <span class="text-sm font-medium {{ $dateInfo['is_current'] ? 'text-green-700' : 'text-gray-700' }}">
                                                    {{ $dateInfo['is_current'] ? 'Current' : 'Former' }}
                                                </span>
                                            </div>
                                            @endif

                                            {{-- Date Range --}}
                                            @if($dateInfo['start'])
                                            <div class="flex items-center px-3 py-2 rounded-lg border border-gray-200">
                                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-700">
                                                    {{ $dateInfo['start']->format('Y') }}
                                                    @if($dateInfo['end'])
                                                    - {{ $dateInfo['end']->format('Y') }}
                                                    @else
                                                    - Present
                                                    @endif
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex flex-col sm:flex-row lg:flex-col gap-3">
                                    @if($careerData->source_url ?? $careerData->website_url ?? $careerData->link)
                                    <a href="{{ $careerData->source_url ?? $careerData->website_url ?? $careerData->link }}" target="_blank" class="group inline-flex items-center justify-center px-5 py-3 border border-{{ $meta['color'] }}-200 text-sm font-semibold rounded-xl text-{{ $meta['color'] }}-700 bg-white hover:bg-{{ $meta['color'] }}-50 hover:border-{{ $meta['color'] }}-300 hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        {{ $careerType === 'business' ? 'Website' : 'Source' }}
                                    </a>
                                    @endif

                                    <a href="{{ route('people.people.show', $person->slug) }}" class="group inline-flex items-center justify-center px-5 py-3 border border-gray-200 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Full Profile
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Structured Data --}}
                        <script type="application/ld+json">
                            {!! $structuredData !!}
                        </script>

                        {{-- Content Section --}}
                        <div class="p-6 lg:p-8">
                            {{-- Dynamic Content Based on Career Type --}}
                            @switch($careerType)
                                @case('politics')
                                    @include('livewire.front.person.career.partials.politics')
                                @break

                                @case('film')
                                    @include('livewire.front.person.career.partials.film')
                                @break

                                @case('sports')
                                    @include('livewire.front.person.career.partials.sports')
                                @break

                                @case('business')
                                    @include('livewire.front.person.career.partials.business')
                                @break

                                @case('literature')
                                    @include('livewire.front.person.career.partials.literature')
                                @break
                            @endswitch

                            {{-- Awards Section (Common for all types) --}}
                            @if($careerData->awards_count > 0)
                            <div class="mt-12 p-6 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-2xl border border-yellow-200 shadow-sm">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-900">Awards & Recognitions</h2>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($careerData->awards as $award)
                                    <div class="p-4 bg-white rounded-xl border border-yellow-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <h3 class="font-semibold text-yellow-900 mb-2 text-lg">{{ $award->award_name }}</h3>
                                        @if($award->organization)
                                        <p class="text-yellow-700 text-sm mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            {{ $award->organization }}
                                        </p>
                                        @endif
                                        @if($award->awarded_at)
                                        <p class="text-yellow-600 text-sm flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $award->awarded_at->format('F Y') }}
                                        </p>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Related Careers --}}
                    @if($relatedCareers->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-bold text-gray-900">More {{ $meta['type_name'] }} by {{ $person->display_name }}</h2>
                            <a href="{{ route('people.details.tab', ['slug' => $person->slug, 'tab' => 'career']) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm flex items-center group">
                                View All
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($relatedCareers as $related)
                            <a href="{{ route('people.career.show', ['personSlug' => $person->slug, 'slug' => $related->slug]) }}" class="group block bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 hover:border-{{ $meta['color'] }}-300 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                                <div class="p-5">
                                    <div class="flex items-start justify-between mb-3">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-{{ $meta['color'] }}-100 text-{{ $meta['color'] }}-700">
                                            {{ $meta['type_name'] }}
                                        </span>
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-{{ $meta['color'] }}-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 group-hover:text-{{ $meta['color'] }}-600 transition-colors duration-200 line-clamp-3 leading-relaxed mb-3">
                                        {{ $this->getRelatedCareerTitle($related) }}
                                    </h3>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $this->getRelatedCareerDate($related) }}
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-4">
                    <div class="sticky top-6 space-y-6">
                        <livewire:partials.profession-category-list />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }
        .line-clamp-3 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }
    </style>
</div>
