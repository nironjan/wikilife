<div class="space-y-6">
    <!-- Section Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Speeches & Interviews</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Public appearances and media interactions by {{ $person->display_name }}
            </p>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
            {{ $person->speechesInterviews->count() }} {{ Str::plural('item', $person->speechesInterviews->count()) }}
        </span>
    </div>

    <!-- Speeches & Interviews Grid -->
    <div class="space-y-4">
        @forelse($person->speechesInterviews->sortByDesc('date')->take(3) as $speech)
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow duration-200 bg-white dark:bg-gray-800">
                <!-- Header with Type Badge -->
                <div class="flex justify-between items-start mb-3">
                    <h4 class="font-semibold text-gray-900 dark:text-white text-base leading-tight pr-2">
                        {{ $speech->title }}
                    </h4>
                    <span class="px-2 py-1 text-xs rounded-full font-medium flex-shrink-0
                        {{ $speech->type === 'speech'
                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                            : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                        {{ ucfirst($speech->type) }}
                    </span>
                </div>

                <!-- Description -->
                @if($speech->description)
                    <div class="prose prose-sm max-w-none text-gray-600 dark:text-gray-300 mb-3">
                        {!! Str::limit(strip_tags($speech->description), 150) !!}
                    </div>
                @endif

                <!-- Meta Information -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-3">
                    @if($speech->date)
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $speech->date->format('M j, Y') }}
                        </span>
                    @endif

                    @if($speech->location)
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ Str::limit($speech->location, 30) }}
                        </span>
                    @endif
                </div>

                <!-- Thumbnail (if available) -->
                @if($speech->thumbnail_url)
                    <div class="mb-3 rounded-lg overflow-hidden">
                        <img src="{{ $speech->imageSize(400, 200) ?? $speech->thumbnail_url }}"
                             alt="{{ $speech->title }}"
                             class="w-full h-32 object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                        @if($speech->url)
                            <a href="{{ $speech->url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Source
                            </a>
                        @endif
                    </div>

                    <!-- Read More Link -->
                    <a href="{{ route('people.speeches.show', ['personSlug' => $person->slug, 'slug' => $speech->slug]) }}"
                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium flex items-center">
                        Read more →
                    </a>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0-12a2 2 0 012-2h2a2 2 0 012 2V6z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Speeches or Interviews</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                    {{ $person->display_name }} doesn't have any public speeches or interviews listed yet.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Load More Section -->
    @if($person->speechesInterviews->count() > 3)
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <div class="text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Showing 3 of {{ $person->speechesInterviews->count() }} items
                </p>

                <!-- View All Button -->
                <a href="{{ route('people.speeches.index', ['personSlug' => $person->slug]) }}"
                   class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0-12a2 2 0 012-2h2a2 2 0 012 2V6z"/>
                    </svg>
                    View All Speeches & Interviews
                </a>
            </div>
        </div>
    @endif
</div>
