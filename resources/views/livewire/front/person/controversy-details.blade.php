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
            <a href="{{ route('people.people.show', [$person->slug, 'tab' => 'controversies']) }}" class="hover:text-blue-600 transition-colors">
                Controversies
            </a>
            <span class="text-gray-400">›</span>
            <span class="text-gray-900 font-medium truncate">{{ Str::limit($controversy->title, 50) }}</span>
        </nav>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-50 to-orange-50 border-b border-red-200 p-6 lg:p-8">
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

                        <!-- Controversy Title -->
                        <h1 class="text-3xl font-bold text-gray-900 mb-4 leading-tight">
                            {{ $controversy->title }}
                        </h1>

                        <!-- Meta Information -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                            @if($controversy->date)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $controversy->date->format('F j, Y') }}
                                </div>
                            @endif

                            <!-- Status -->
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $controversy->is_resolved ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $controversy->is_resolved ? 'bg-green-500' : 'bg-orange-500' }} mr-1.5"></span>
                                    {{ $controversy->is_resolved ? 'Resolved' : 'Unresolved' }}
                                </span>
                            </div>

                            <!-- Last Updated -->
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Updated {{ $controversy->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row lg:flex-col gap-2">
                        @if($controversy->source_url)
                            <a href="{{ $controversy->source_url }}" target="_blank"
                               class="inline-flex items-center justify-center px-4 py-2 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                View Source
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
                <!-- Controversy Content -->
                <div class="prose prose-lg max-w-none text-gray-700
                          prose-headings:font-bold prose-headings:text-gray-900
                          prose-p:leading-relaxed prose-p:text-gray-700
                          prose-ul:my-6 prose-li:my-2
                          prose-strong:text-gray-900 prose-strong:font-semibold
                          prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                          prose-blockquote:border-l-blue-400 prose-blockquote:bg-blue-50 prose-blockquote:py-1
                          prose-img:rounded-lg prose-img:shadow-sm">
                    {!! $controversy->safe_html_content !!}
                </div>

                <!-- Source Section -->
                @if($controversy->source_url)
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            Reference Source
                        </h3>
                        <a href="{{ $controversy->source_url }}" target="_blank"
                           class="text-blue-600 hover:text-blue-800 text-sm break-all">
                            {{ $controversy->source_url }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Controversies -->
        @php
            $relatedControversies = \App\Models\Controversy::with(['person'])
                ->where('person_id', $person->id)
                ->where('id', '!=', $controversy->id)
                ->where('is_published', true)
                ->orderBy('date', 'desc')
                ->take(3)
                ->get();
        @endphp

        @if($relatedControversies->count() > 0)
            <div class="mt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Other Controversies by {{ $person->display_name }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedControversies as $related)
                        <a href="{{ route('people.controversies.show', ['personSlug' => $person->slug, 'slug' => $related->slug]) }}"
                           class="block bg-white rounded-lg border border-gray-200 hover:border-red-300 hover:shadow-md transition-all duration-200 overflow-hidden">
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $related->title }}</h3>
                                <div class="flex items-center justify-between text-sm text-gray-600">
                                    <span>{{ $related->date ? $related->date->format('M Y') : 'N/A' }}</span>
                                    <span class="px-2 py-1 rounded-full text-xs {{ $related->is_resolved ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                        {{ $related->is_resolved ? 'Resolved' : 'Active' }}
                                    </span>
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
