<div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-24">
    <!-- Quick Facts -->
    <div class="p-6 border-b border-gray-200">
        <h3 class="font-bold text-gray-900 mb-4">Quick Facts</h3>
        <div class="space-y-3 text-sm">
            @if($person->zodiac_sign)
                <div class="flex justify-between">
                    <span class="text-gray-600">Zodiac Sign</span>
                    <span class="font-medium text-gray-900">{{ $person->zodiac_sign }}</span>
                </div>
            @endif

            @if($person->blood_group)
                <div class="flex justify-between">
                    <span class="text-gray-600">Blood Group</span>
                    <span class="font-medium text-gray-900">{{ $person->blood_group }}</span>
                </div>
            @endif

            @if($person->religion)
                <div class="flex justify-between">
                    <span class="text-gray-600">Religion</span>
                    <span class="font-medium text-gray-900">{{ $person->religion }}</span>
                </div>
            @endif

            @if($person->caste)
                <div class="flex justify-between">
                    <span class="text-gray-600">Caste</span>
                    <span class="font-medium text-gray-900">{{ $person->caste }}</span>
                </div>
            @endif

            @if($person->ethnicity)
                <div class="flex justify-between">
                    <span class="text-gray-600">Ethnicity</span>
                    <span class="font-medium text-gray-900">{{ $person->ethnicity }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Favourite Things -->
    @if($person->favourite_things && count($person->favourite_things) > 0)
        <div class="p-6 border-b border-gray-200">
            <h3 class="font-bold text-gray-900 mb-4">Favourites</h3>
            <div class="space-y-2 text-sm">
                @foreach($person->favourite_things as $key => $value)
                    <div>
                        <div class="text-gray-600 capitalize">{{ str_replace('_', ' ', $key) }}</div>
                        <div class="font-medium text-gray-900">{{ $value }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Social Links -->
    @if($person->socialLinks && $person->socialLinks->count() > 0)
        <div class="p-6">
            <h3 class="font-bold text-gray-900 mb-4">Social Media</h3>
            <div class="">
                @foreach($person->socialLinks as $link)
                    @php
                        // Get the platform key (convert to lowercase for matching)
                        $platformKey = strtolower($link->platform);
                        $iconConfig = $socialIcons[$platformKey] ?? null;

                        // If platform not found directly, try to match by icon field
                        if (!$iconConfig && $link->icon) {
                            $iconConfig = $socialIcons[strtolower($link->icon)] ?? null;
                        }
                    @endphp

                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                        class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 group">
                        @if($iconConfig)
                            <!-- Display SVG icon with brand color -->
                            <div class="w-8 h-8 flex items-center justify-center rounded-full transition-transform duration-200 group-hover:scale-110"
                                style="background-color: {{ $iconConfig['color'] }}20;">
                                <svg class="w-4 h-4 transition-colors duration-200" viewBox="0 0 24 24"
                                    style="fill: {{ $iconConfig['color'] }};">
                                    <path d="{{ $iconConfig['svg'] }}" />
                                </svg>
                            </div>
                        @else
                            <!-- Fallback icon -->
                            <div
                                class="w-8 h-8 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center transition-transform duration-200 group-hover:scale-110">
                                <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                    {{ strtoupper(substr($link->platform, 0, 1)) }}
                                </span>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div
                                class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                {{ $link->platform }}
                            </div>
                            @if($link->username)
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    @{{ $link->username }}
                                </div>
                            @endif
                        </div>

                        <!-- External link indicator -->
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                            </path>
                        </svg>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
