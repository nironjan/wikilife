<div class="bg-white rounded-lg shadow p-6 border border-gray-100">
    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
        {{-- Trending Icon --}}
        <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
        </svg>
        {{ $title }}
    </h3>

    <div class="space-y-4">
        @foreach($popularArticles as $index => $popular)
            <a
                href="{{ route('articles.show', $popular->slug) }}"
                class="flex items-start space-x-3 group p-2 rounded-lg hover:bg-gray-50 transition-all duration-200"
            >
                @if($showImages)
                    <div class="shrink-0 relative">
                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-200 flex items-center justify-center">
                            @if($popular->featured_image_url)
                                <img
                                    src="{{ $popular->imageSize(100, 100, 70) }}"
                                    alt="{{ $popular->title }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                >
                            @else
                                {{-- Fallback icon --}}
                                <div class="w-full h-full bg-linear-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        @if($showRanking && $index < 3)
                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center shadow-sm">
                                <span class="text-xs text-white font-bold">{{ $index + 1 }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 line-clamp-2 mb-1 transition-colors duration-200">
                        {{ $popular->title }}
                    </h4>
                    <div class="flex items-center text-xs text-gray-500 space-x-2">
                        <span class="flex items-center space-x-1">
                            {{-- Calendar Icon --}}
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $popular->published_at->format('M j') }}</span>
                        </span>
                    </div>
                </div>
            </a>
        @endforeach

        @if($popularArticles->isEmpty())
            <div class="text-center py-4 text-gray-500">
                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm">No popular articles found</p>
            </div>
        @endif
    </div>
</div>
