<div>
    <div class="min-h-screen bg-linear-to-br from-gray-50 to-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                <a href="{{ route('people.details.tab', ['slug' => $person->slug, 'tab' => 'controversies']) }}" class="hover:text-blue-600 transition-colors duration-200">
                    Controversies
                </a>
                <span class="text-gray-300">›</span>
                <span class="text-gray-900 font-semibold truncate max-w-[150px]">{{ Str::limit($controversy->title, 40) }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-8 space-y-8">
                    {{-- Main Content Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                        {{-- Header Section --}}
                        <div class="bg-linear-to-r from-red-50 via-orange-50 to-amber-50 border-b border-red-100 p-6 lg:p-8">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                                {{-- Person Info & Title --}}
                                <div class="flex-1">
                                    {{-- Person Card --}}
                                    <div class="flex items-start space-x-4 mb-6">
                                        <a href="{{ route('people.people.show', $person->slug)}}" class="shrink-0 transform hover:scale-105 transition-transform duration-200">
                                            <div class="relative">
                                                <div class="w-20 h-20 rounded-2xl bg-linear-to-br from-gray-200 to-gray-300 overflow-hidden border-4 border-white shadow-lg">
                                                    @if($person->profile_image_url)
                                                    <img src="{{ $person->imageSize(100, 100) }}" alt="{{ $person->display_name }}" class="w-full h-full object-cover">
                                                    @else
                                                    <div class="w-full h-full bg-linear-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-blue-500 rounded-full border-2 border-white flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('people.people.show', $person->slug)}}" class="group block">
                                                <h2 class="text-2xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200 leading-tight mb-2">
                                                    {{ $person->display_name }}
                                                </h2>
                                            </a>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($person->professions as $profession)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border border-blue-200">
                                                    {{ $profession }}
                                                </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Controversy Title --}}
                                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6 leading-tight tracking-tight">
                                        {{ $controversy->title }}
                                    </h1>

                                    {{-- Meta Information --}}
                                    <div class="flex flex-wrap items-center gap-4 text-sm">
                                        @if($controversy->date)
                                        <div class="flex items-center text-gray-600 bg-white px-3 py-2 rounded-lg border border-gray-200 shadow-sm">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $controversy->date->format('F j, Y') }}
                                        </div>
                                        @endif

                                        {{-- Status Badge --}}
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-sm {{ $controversy->is_resolved ? 'bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200' : 'bg-gradient-to-r from-orange-50 to-red-50 text-orange-700 border border-orange-200' }}">
                                                <span class="w-2 h-2 rounded-full {{ $controversy->is_resolved ? 'bg-green-500' : 'bg-orange-500' }} mr-2 animate-pulse"></span>
                                                {{ $controversy->is_resolved ? 'Resolved' : 'Ongoing' }}
                                            </span>
                                        </div>

                                        {{-- Last Updated --}}
                                        <div class="flex items-center text-gray-600 bg-white px-3 py-2 rounded-lg border border-gray-200 shadow-sm">
                                            <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Updated {{ $controversy->updated_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex flex-col sm:flex-row lg:flex-col gap-3">
                                    @if($controversy->source_url)
                                    <a href="{{ $controversy->source_url }}" target="_blank" class="group inline-flex items-center justify-center px-5 py-3 border border-red-200 text-sm font-semibold rounded-xl text-red-700 bg-white hover:bg-red-50 hover:border-red-300 hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Source
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
                            {!! $this->getStructuredData() !!}
                        </script>

                        {{-- Content Section --}}
                        <div class="p-6 lg:p-8">
                            {{-- Controversy Content --}}
                            <div class="prose prose-lg max-w-none
                                prose-headings:font-bold prose-headings:text-gray-900 prose-headings:leading-tight
                                prose-p:leading-relaxed prose-p:text-gray-700 prose-p:text-lg
                                prose-ul:my-8 prose-li:my-3 prose-li:leading-relaxed
                                prose-strong:text-gray-900 prose-strong:font-semibold
                                prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline prose-a:font-medium
                                prose-blockquote:border-l-4 prose-blockquote:border-blue-400 prose-blockquote:bg-blue-50 prose-blockquote:py-4 prose-blockquote:px-6 prose-blockquote:rounded-r-xl
                                prose-img:rounded-xl prose-img:shadow-md prose-img:mx-auto
                                prose-pre:bg-gray-900 prose-pre:text-gray-100
                                prose-hr:border-gray-200
                                prose-table:shadow-sm prose-table:rounded-lg
                                prose-lead:text-gray-600">
                                {!! $controversy->safe_html_content !!}
                            </div>

                            {{-- Source Section --}}
                            @if($controversy->source_url)
                            <div class="mt-12 p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl border border-gray-200 shadow-sm">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Reference Source</h3>
                                        <a href="{{ $controversy->source_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-base font-medium break-all hover:underline transition-colors duration-200">
                                            {{ $controversy->source_url }}
                                        </a>
                                        <p class="text-sm text-gray-600 mt-2">External link for verification and additional context</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Related Controversies --}}
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
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-bold text-gray-900">More from {{ $person->display_name }}</h2>
                            <a href="{{ route('people.people.show', [$person->slug, 'tab' => 'controversies']) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm flex items-center group">
                                View All
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($relatedControversies as $related)
                            <a href="{{ route('people.controversies.show', ['personSlug' => $person->slug, 'slug' => $related->slug]) }}" class="group block bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 hover:border-red-300 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                                <div class="p-5">
                                    <div class="flex items-start justify-between mb-3">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $related->is_resolved ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                            {{ $related->is_resolved ? 'Resolved' : 'Active' }}
                                        </span>
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 group-hover:text-red-600 transition-colors duration-200 line-clamp-3 leading-relaxed mb-3">
                                        {{ $related->title }}
                                    </h3>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $related->date ? $related->date->format('M Y') : 'N/A' }}
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

        /* Smooth scrolling for the entire page */
    </style>
</div>
