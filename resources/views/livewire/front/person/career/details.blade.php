<div>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Home</a>
                <span class="text-gray-400">›</span>
                <a href="{{ route('people.people.show', $person->slug) }}" class="hover:text-blue-600 transition-colors">
                    {{ $person->display_name }}
                </a>
                <span class="text-gray-400">›</span>
                <a href="{{ route('people.people.show', [$person->slug, 'tab' => 'career']) }}" class="hover:text-blue-600 transition-colors">
                    Career
                </a>
                <span class="text-gray-400">›</span>
                <span class="text-gray-900 font-medium truncate">{{ Str::limit($this->getCareerTitle(), 50) }}</span>
            </nav>

            <!-- Main Content Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-{{ $meta['color'] }}-50 to-{{ $meta['color'] }}-100 border-b border-{{ $meta['color'] }}-200 p-6 lg:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex-1">
                            <!-- Person Info -->
                            <div class="flex items-center space-x-4 mb-4">
                                <a href="{{ route('people.people.show', $person->slug) }}" class="flex-shrink-0">
                                    <div class="w-16 h-16 rounded-full bg-gray-200 overflow-hidden border-2 border-white shadow-sm">
                                        @if($person->profile_image_url)
                                            <img src="{{ $person->imageSize(100, 100) }}"
                                                 alt="{{ $person->display_name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                                <div class="flex-1">
                                    <a href="{{ route('people.people.show', $person->slug) }}"
                                       class="text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors block">
                                        {{ $person->display_name }}
                                    </a>
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        @foreach($person->professions as $profession)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">
                                                {{ $profession }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Career Title -->
                            <h1 class="text-3xl font-bold text-gray-900 mb-4 leading-tight flex items-center">
                                <span class="text-2xl mr-3">{{ $meta['icon'] }}</span>
                                {{ $this->getCareerTitle() }}
                            </h1>

                            <!-- Meta Information -->
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                <!-- Career Type -->
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $meta['color'] }}-100 text-{{ $meta['color'] }}-800">
                                        {{ $meta['type_name'] }}
                                    </span>
                                </div>

                                <!-- Primary Field -->
                                @if($meta['primary_field'])
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $meta['primary_field'] }}
                                    </div>
                                @endif

                                <!-- Secondary Field -->
                                @if($meta['secondary_field'])
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        {{ $meta['secondary_field'] }}
                                    </div>
                                @endif

                                <!-- Duration -->
                                @if($dateInfo['duration'])
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $dateInfo['duration'] }}
                                    </div>
                                @endif

                                <!-- Status -->
                                @if($dateInfo['is_current'] !== null)
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $dateInfo['is_current'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $dateInfo['is_current'] ? 'bg-green-500' : 'bg-gray-500' }} mr-1.5"></span>
                                            {{ $dateInfo['is_current'] ? 'Current' : 'Former' }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Date Information -->
                            @if($dateInfo['start'])
                                <div class="mt-4 p-3 bg-white rounded-lg border border-{{ $meta['color'] }}-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700">Start:</span>
                                            <span class="text-gray-900 ml-2">
                                                {{ $dateInfo['start']->format('F j, Y') }}
                                            </span>
                                        </div>
                                        @if($dateInfo['end'])
                                            <div>
                                                <span class="font-medium text-gray-700">End:</span>
                                                <span class="text-gray-900 ml-2">
                                                    {{ $dateInfo['end']->format('F j, Y') }}
                                                </span>
                                            </div>
                                        @else
                                            <div>
                                                <span class="font-medium text-gray-700">Status:</span>
                                                <span class="text-green-600 ml-2 font-medium">Ongoing</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row lg:flex-col gap-2">
                            @if($careerData->source_url ?? $careerData->website_url ?? $careerData->link)
                                <a href="{{ $careerData->source_url ?? $careerData->website_url ?? $careerData->link }}" target="_blank"
                                   class="inline-flex items-center justify-center px-4 py-2 border border-{{ $meta['color'] }}-300 text-sm font-medium rounded-lg text-{{ $meta['color'] }}-700 bg-white hover:bg-{{ $meta['color'] }}-50 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    {{ $careerType === 'business' ? 'Visit Website' : 'View Source' }}
                                </a>
                            @endif

                            <a href="{{ route('people.people.show', $person->slug) }}"
                               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 lg:p-8">
                    <!-- Dynamic Content Based on Career Type -->
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

                    <!-- Awards Section (Common for all types) -->
                    @if($careerData->awards_count > 0)
                        <div class="mt-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Awards & Recognitions</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($careerData->awards as $award)
                                    <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                        <h3 class="font-semibold text-yellow-900 mb-1">{{ $award->award_name }}</h3>
                                        @if($award->organization)
                                            <p class="text-yellow-700 text-sm">{{ $award->organization }}</p>
                                        @endif
                                        @if($award->awarded_at)
                                            <p class="text-yellow-600 text-xs mt-1">
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

            <!-- Related Careers -->
            @if($relatedCareers->count() > 0)
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Other {{ $meta['type_name'] }} by {{ $person->display_name }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($relatedCareers as $related)
                            <a href="{{ route('people.career.show', ['personSlug' => $person->slug, 'slug' => $related->slug]) }}"
                               class="block bg-white rounded-lg border border-gray-200 hover:border-{{ $meta['color'] }}-300 hover:shadow-md transition-all duration-200 overflow-hidden">
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                        {{ $this->getRelatedCareerTitle($related) }}
                                    </h3>
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <div class="flex items-center justify-between">
                                            <span>{{ $this->getRelatedCareerDate($related) }}</span>
                                            <span class="px-2 py-1 rounded-full text-xs bg-{{ $meta['color'] }}-100 text-{{ $meta['color'] }}-800">
                                                {{ $meta['type_name'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
    </style>
</div>
