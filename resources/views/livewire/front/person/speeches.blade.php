<div>
    <div class="min-h-screen bg-gray-50">
        {{-- Header --}}
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-6xl mx-auto px-3 sm:px-4 lg:px-6 py-3 sm:py-4 lg:py-6">
                {{-- Breadcrumb --}}
                <nav class="flex flex-wrap items-center gap-1 sm:gap-2 text-xs text-gray-600 mb-2 sm:mb-3 lg:mb-4" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors duration-200">Home</a>
                    <span class="text-gray-400" aria-hidden="true">›</span>
                    <a href="{{ route('people.people.index') }}" class="hover:text-blue-600 transition-colors duration-200">People</a>
                    <span class="text-gray-400" aria-hidden="true">›</span>
                    <a href="{{ route('people.people.show', $person->slug) }}" class="hover:text-blue-600 transition-colors duration-200 truncate max-w-[60px] sm:max-w-[80px] lg:max-w-none">{{ $person->display_name }}</a>
                    <span class="text-gray-400" aria-hidden="true">›</span>
                    <a href="{{ route('people.details.tab', ['slug' => $person->slug, 'tab' => 'speeches-interviews']) }}" class="hover:text-blue-600 transition-colors duration-200">Speeches & Interviews</a>
                    <span class="text-gray-400" aria-hidden="true">›</span>
                    <span class="text-gray-900 font-medium truncate max-w-[100px] sm:max-w-[120px] lg:max-w-none" aria-current="page">{{ Str::limit($speech->title, 40) }}</span>
                </nav>

                {{-- Speech Header --}}
                <article itemscope itemtype="https://schema.org/{{ $speech->type === 'speech' ? 'PublicSpeakingEvent' : 'Conversation' }}">
                    <meta itemprop="datePublished" content="{{ $speech->created_at->toIso8601String() }}">
                    <meta itemprop="dateModified" content="{{ $speech->updated_at->toIso8601String() }}">
                    <meta itemprop="performer" content="{{ $person->display_name }}">

                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 lg:gap-3 mb-2 sm:mb-3 lg:mb-4">
                        <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full text-xs font-medium
                            {{ match(strtolower($speech->type)) {
                                'speech' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                'interview' => 'bg-green-100 text-green-800 border border-green-200',
                                default => 'bg-gray-100 text-gray-800 border border-gray-200'
                            } }}" itemprop="genre">
                            {{ ucfirst($speech->type) }}
                        </span>

                        @if($speech->date)
                        <time class="text-xs text-gray-500" datetime="{{ $speech->date->format('Y-m-d') }}" itemprop="startDate">
                            {{ $speech->date->format('M j, Y') }}
                        </time>
                        <span class="text-xs text-gray-500 hidden sm:inline" aria-hidden="true">•</span>
                        @endif

                        @if($speech->location)
                        <span class="text-xs text-gray-500 hidden sm:inline" itemprop="location">
                            {{ Str::limit($speech->location, 25) }}
                        </span>
                        @endif
                    </div>

                    <h1 class="text-lg sm:text-xl lg:text-2xl xl:text-3xl font-bold text-gray-900 mb-3 sm:mb-4 lg:mb-6 leading-tight" itemprop="name">
                        {{ $speech->title }}
                    </h1>

                    {{-- Description --}}
                    @if($speech->description)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-2.5 sm:p-3 lg:p-4 mb-3 sm:mb-4 lg:mb-6 rounded-r-lg" itemprop="description">
                            <p class="text-gray-700 text-sm sm:text-base lg:text-lg font-medium leading-relaxed">
                                {!! Str::limit($speech->description, 160) !!}
                            </p>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center text-gray-600 text-xs sm:text-sm lg:text-base">
                        <span class="mb-1 sm:mb-0">{{ $speech->type === 'speech' ? 'Speech by' : 'Interview with' }}</span>
                        <a href="{{ route('people.people.show', $person->slug) }}"
                           class="sm:ml-1 lg:ml-2 font-semibold text-blue-600 hover:text-blue-700 transition-colors duration-200"
                           itemprop="performer" itemscope itemtype="https://schema.org/Person">
                            <span itemprop="name">{{ $person->display_name }}</span>
                        </a>
                    </div>
                </article>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="max-w-6xl mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                {{-- Sidebar --}}
                <div class="lg:col-span-1 order-2 lg:order-1">
                    <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 lg:p-6 lg:sticky lg:top-6 shadow-sm">
                        {{-- Person Info --}}
                        <div class="text-center mb-3 sm:mb-4 lg:mb-6" itemscope itemtype="https://schema.org/Person">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-full overflow-hidden border-2 border-white shadow-md mx-auto mb-2 sm:mb-3">
                                @if($person->profile_image_url)
                                    <img src="{{ $person->imageSize(100, 100) }}"
                                         alt="{{ $person->display_name }}"
                                         class="w-full h-full object-cover"
                                         itemprop="image">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-8 lg:h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <h3 class="font-semibold text-gray-900 text-sm sm:text-base" itemprop="name">{{ $person->display_name }}</h3>
                            <p class="text-xs text-gray-600 mt-1" itemprop="jobTitle">{{ implode(', ', $person->professions) }}</p>
                        </div>

                        {{-- Quick Actions --}}
                        <div class="space-y-2">
                            <a href="{{ route('people.people.show', $person->slug) }}"
                               class="w-full flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-xs sm:text-sm font-medium shadow-sm">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="truncate">View Full Profile</span>
                            </a>
                            <a href="{{ route('people.details.tab', ['slug' => $person->slug, 'tab' => 'speeches-interviews']) }}"
                               class="w-full flex items-center justify-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-xs sm:text-sm font-medium shadow-sm">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0-12a2 2 0 012-2h2a2 2 0 012 2V6z"/>
                                </svg>
                                <span class="truncate">All Speeches & Interviews</span>
                            </a>
                        </div>

                        {{-- Share Buttons --}}
                        <div class="mt-3 sm:mt-4 lg:mt-6 pt-3 sm:pt-4 lg:pt-6 border-t border-gray-200">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-900 mb-2">Share This {{ ucfirst($speech->type) }}</h4>
                            <x-share-buttons
                                :url="url()->current()"
                                :title="$speech->title . ' - ' . $person->display_name"
                                :description="$speech->description ?: ($speech->type . ' by ' . $person->display_name)"
                                :image="$speech->thumbnail_url ?: $person->profile_image_url"
                                size="sm"
                                layout="horizontal"
                                :showLabels="false"
                            />
                        </div>

                        {{-- Source URL --}}
                        @if($speech->url)
                        <div class="mt-3 sm:mt-4 lg:mt-6 pt-3 sm:pt-4 lg:pt-6 border-t border-gray-200">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-900 mb-2">Source</h4>
                            <a href="{{ $speech->url }}" target="_blank" rel="noopener noreferrer"
                               class="w-full flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-xs sm:text-sm font-medium shadow-sm">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                <span class="truncate">View Original Source</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Speech Content --}}
                <div class="lg:col-span-2 order-1 lg:order-2">
                    <article class="bg-white rounded-xl border border-gray-200 p-3 sm:p-4 lg:p-6 xl:p-8 shadow-sm" itemscope itemtype="https://schema.org/{{ $speech->type === 'speech' ? 'PublicSpeakingEvent' : 'Conversation' }}">
                        {{-- Featured Image --}}
                        @if($speech->thumbnail_url)
                            <div class="mb-4 sm:mb-6 lg:mb-8 rounded-xl overflow-hidden shadow-md" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                <img src="{{ $speech->imageSize(800, 600) ?? $speech->thumbnail_url }}"
                                     alt="{{ $speech->title }}"
                                     class="w-full h-auto max-h-48 sm:max-h-64 lg:max-h-80 xl:max-h-96 object-cover"
                                     itemprop="url"
                                     loading="lazy">
                                <meta itemprop="width" content="800">
                                <meta itemprop="height" content="600">
                            </div>
                        @endif

                        {{-- Speech Details --}}
                        <div class="prose prose-xs sm:prose-sm lg:prose-base xl:prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900
                                   prose-p:leading-relaxed prose-p:text-gray-700 prose-strong:text-gray-900
                                   prose-ul:mt-3 sm:prose-ul:mt-4 lg:prose-ul:mt-6 prose-ul:space-y-1 sm:prose-ul:space-y-2 prose-li:marker:text-gray-400
                                   prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                                   prose-blockquote:border-l-blue-500 prose-blockquote:bg-blue-50 prose-blockquote:py-1 sm:prose-blockquote:py-2 prose-blockquote:px-3 sm:prose-blockquote:px-4
                                   prose-img:rounded-lg prose-img:shadow-sm prose-img:mx-auto
                                   prose-table:border-collapse prose-table:border prose-table:border-gray-300
                                   prose-th:bg-gray-100 prose-th:p-2 sm:prose-th:p-3 prose-td:p-2 sm:prose-td:p-3 prose-td:border-t"
                             itemprop="description">
                            @if($speech->content)
                                {!! $speech->content !!}
                            @else
                                <p class="text-gray-600 italic text-sm sm:text-base">No detailed content available for this {{ $speech->type }}.</p>
                            @endif
                        </div>

                        {{-- Speech Footer --}}
                        <div class="mt-6 sm:mt-8 lg:mt-12 pt-4 sm:pt-6 lg:pt-8 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 lg:gap-4 text-xs text-gray-500">
                                <div class="flex items-center space-x-1 sm:space-x-2">
                                    <span>Last updated:</span>
                                    <time datetime="{{ $speech->updated_at->format('c') }}" itemprop="dateModified">
                                        {{ $speech->updated_at->format('F j, Y g:i a T') }}
                                    </time>
                                </div>
                            </div>
                        </div>
                    </article>

                    {{-- Structured Data JSON-LD --}}
                    <script type="application/ld+json">
                    {!! $structuredData !!}
                    </script>
                </div>

                {{-- Related Speeches --}}
                <div class="lg:col-span-1 order-3">
                    @if($relatedSpeeches->count() > 0)
                        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 lg:p-6 lg:sticky lg:top-6 shadow-sm">
                            <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-900 mb-2 sm:mb-3 lg:mb-4">Related {{ ucfirst($speech->type) }}s</h3>
                            <div class="space-y-2 sm:space-y-3 lg:space-y-4">
                                @foreach($relatedSpeeches as $related)
                                    <article class="group p-2 sm:p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                        <a href="{{ route('people.speeches.show', ['personSlug' => $person->slug, 'slug' => $related->slug]) }}"
                                           class="block">
                                            <div class="flex items-center gap-1 sm:gap-2 mb-1">
                                                <span class="inline-block px-1.5 py-0.5 sm:px-2 sm:py-1 bg-gray-100 text-gray-700 text-xs rounded font-medium">
                                                    {{ ucfirst($related->type) }}
                                                </span>
                                                @if($related->date)
                                                <time class="text-xs text-gray-500">
                                                    {{ $related->date->format('M j') }}
                                                </time>
                                                @endif
                                            </div>
                                            <h4 class="font-medium text-gray-900 text-xs sm:text-sm lg:text-base group-hover:text-blue-600 leading-snug transition-colors duration-200 line-clamp-2">
                                                {{ $related->title }}
                                            </h4>
                                            @if($related->description)
                                                <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                                    {{ Str::limit($related->description, 70) }}
                                                </p>
                                            @endif
                                        </a>
                                    </article>
                                @endforeach
                            </div>

                            <div class="mt-3 sm:mt-4 lg:mt-6 pt-3 sm:pt-4 lg:pt-6 border-t border-gray-200">
                                <a href="{{ route('people.details.tab', ['slug' => $person->slug, 'tab' => 'speeches-interviews']) }}"
                                   class="w-full flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-xs sm:text-sm font-medium shadow-sm">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0-12a2 2 0 012-2h2a2 2 0 012 2V6z"/>
                                    </svg>
                                    <span class="truncate">View All Speeches & Interviews</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
