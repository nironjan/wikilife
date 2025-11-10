<div class="p-2 md:p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Controversies</h2>

    @if($person->controversies->count() > 0)
    <div class="space-y-6">
        @foreach($person->controversies as $controversy)
        <div
            class="border border-red-200 rounded-lg bg-red-50 overflow-hidden transition-all duration-200 hover:shadow-md my-6">
            <!-- Header with Status -->
            <div class="p-4 border-b border-red-200 bg-white">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $controversy->title }}</h3>

                        <!-- Date and Status -->
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            @if($controversy->date)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ $controversy->date->format('M j, Y') }}
                            </span>
                            @endif

                            <!-- Resolution Status -->
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $controversy->is_resolved ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $controversy->is_resolved ? 'Resolved' : 'Unresolved' }}
                            </span>

                            <!-- Publication Status -->
                            @if(!$controversy->is_published)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                    </path>
                                </svg>
                                Unpublished
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Source Link -->
                    @if($controversy->source_url)
                    <a href="{{ $controversy->source_url }}" target="_blank"
                        class="inline-flex items-center px-3 py-1.5 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Source
                    </a>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="p-4">
                <!-- HTML Content -->
                <div
                    class="prose max-w-none text-gray-700 prose-headings:font-semibold prose-p:leading-relaxed prose-ul:my-2 prose-li:my-1 prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline prose-a:font-medium">
                    {!! $controversy->safe_html_content !!}
                </div>

                <!-- Excerpt for longer content -->
                @if(strlen(strip_tags($controversy->safe_html_content)) > 500)
                <div class="mt-4 p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-2 text-sm flex items-center">Quick Summary</h4>
                            <div
                                class="text-gray-700 text-sm leading-relaxed prose prose-sm max-w-none prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline prose-a:font-medium">
                                {!! Str::markdown($controversy->excerpt) !!}
                            </div>

                            <!-- Action Links -->
                            <div class="mt-3 flex flex-wrap gap-3">
                                @if($controversy->source_url)
                                <a href="{{ $controversy->source_url }}" target="_blank"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors duration-200">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                        </path>
                                    </svg>
                                    View Source
                                </a>
                                @endif

                                <a href="{{ route('people.controversies.show', ['personSlug' => $person->slug, 'slug' => $controversy->slug]) }}"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors duration-200">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    View Full Controversy
                                </a>

                                <button
                                    onclick="navigator.clipboard.writeText(window.location.href + '#controversy-{{ $controversy->id }}')"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-full hover:bg-green-200 transition-colors duration-200">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                                        </path>
                                    </svg>
                                    Share
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12 bg-gray-50 rounded-lg border border-gray-200">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
            </path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No Controversies</h3>
        <p class="text-gray-500 max-w-md mx-auto">
            No controversy information has been recorded for {{ $person->display_name }}.
            @if($person->is_alive)
            This could indicate a clean public record or that information hasn't been documented yet.
            @endif
        </p>
    </div>
    @endif

    <!-- Lesser Known Facts -->
    @if($person->lesserKnownFacts->count() > 0)
    <div class="mt-12 pt-8 border-t border-gray-200">
        <div class="flex items-center mb-6">
            <div class="w-1.5 h-6 bg-blue-500 rounded-full mr-3"></div>
            <h3 class="text-2xl font-bold text-gray-900">Lesser Known Facts</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($person->lesserKnownFacts as $fact)
            <div
                class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-5 transition-all duration-200 hover:shadow-md hover:border-blue-300 group">
                <div class="flex items-start space-x-3">
                    <div
                        class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors duration-200">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4
                            class="font-semibold text-gray-900 mb-2 group-hover:text-blue-700 transition-colors duration-200">
                            {{ $fact->title }}</h4>
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $fact->description }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
