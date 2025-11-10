<div>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-6xl mx-auto px-4 py-6">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-4" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors duration-200">Home</a>
                    <span class="text-gray-400" aria-hidden="true">›</span>
                    <a href="{{ route('people.people.index') }}" class="hover:text-blue-600 transition-colors duration-200">People</a>
                    <span class="text-gray-400" aria-hidden="true">›</span>
                    <a href="{{ route('people.people.show', $person->slug) }}" class="hover:text-blue-600 transition-colors duration-200">{{ $person->display_name }}</a>
                    <span class="text-gray-400" aria-hidden="true">›</span>
                    <a href="{{ route('people.updates.index', $person->slug) }}" class="hover:text-blue-600 transition-colors duration-200">Updates</a>
                    <span class="text-gray-400" aria-hidden="true">›</span>
                    <span class="text-gray-900 font-medium" aria-current="page">{{ Str::limit($update->title, 50) }}</span>
                </nav>

                <!-- Article Header -->
                <article itemscope itemtype="https://schema.org/Article">
                    <meta itemprop="datePublished" content="{{ $update->created_at->toIso8601String() }}">
                    <meta itemprop="dateModified" content="{{ $update->updated_at->toIso8601String() }}">
                    <meta itemprop="author" content="{{ $person->display_name }}">

                    <div class="flex items-center gap-3 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ match(strtolower($update->update_type)) {
                                'news' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                'achievement' => 'bg-green-100 text-green-800 border border-green-200',
                                'event' => 'bg-purple-100 text-purple-800 border border-purple-200',
                                'milestone' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                'award' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                default => 'bg-gray-100 text-gray-800 border border-gray-200'
                            } }}" itemprop="articleSection">
                            {{ ucfirst($update->update_type) }}
                        </span>
                        <time class="text-sm text-gray-500" datetime="{{ $update->created_at->format('Y-m-d') }}" itemprop="datePublished">
                            {{ $update->created_at->format('F j, Y') }}
                        </time>
                        <span class="text-sm text-gray-500" aria-hidden="true">•</span>
                        <span class="text-sm text-gray-500" itemprop="timeRequired">{{ $update->read_time }}</span>
                    </div>

                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6 leading-tight" itemprop="headline">
                        {{ $update->title }}
                    </h1>

                    <!-- Description/Excerpt -->
                    @if($update->description)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg" itemprop="description">
                            <p class="text-gray-700 text-lg font-medium leading-relaxed">{{ $update->description }}</p>
                        </div>
                    @endif

                    <div class="flex items-center text-gray-600">
                        <span>Update about</span>
                        <a href="{{ route('people.people.show', $person->slug) }}"
                           class="ml-2 font-semibold text-blue-600 hover:text-blue-700 transition-colors duration-200"
                           itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <span itemprop="name">{{ $person->display_name }}</span>
                        </a>
                    </div>
                </article>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-8 shadow-sm">
                        <!-- Person Info -->
                        <div class="text-center mb-6" itemscope itemtype="https://schema.org/Person">
                            <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-white shadow-md mx-auto mb-3">
                                @if($person->profile_image_url)
                                    <img src="{{ $person->imageSize(100, 100) }}"
                                         alt="{{ $person->display_name }}"
                                         class="w-full h-full object-cover"
                                         itemprop="image">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <h3 class="font-semibold text-gray-900" itemprop="name">{{ $person->display_name }}</h3>
                            <p class="text-sm text-gray-600 mt-1" itemprop="jobTitle">{{ implode(', ', $person->professions) }}</p>
                        </div>

                        <!-- Quick Actions -->
                        <div class="space-y-3">
                            <a href="{{ route('people.people.show', $person->slug) }}"
                               class="w-full flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm font-medium shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                View Full Profile
                            </a>
                            <a href="{{ route('people.updates.index', $person->slug) }}"
                               class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0-12a2 2 0 012-2h2a2 2 0 012 2V6z"/>
                                </svg>
                                All Updates
                            </a>
                        </div>

                        <!-- Share Buttons -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Share This Update</h4>
                            <x-share-buttons
                                :url="url()->current()"
                                :title="$update->title . ' - ' . $person->display_name"
                                :description="$update->description ?: Str::limit(strip_tags($update->content), 100)"
                                :image="$update->hasImage() ? $update->image_url : ''"
                                size="sm"
                                layout="horizontal"
                                :showLabels="false"
                            />
                        </div>
                    </div>
                </div>

                <!-- Article Content -->
                <div class="lg:col-span-2">
                    <article class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm" itemscope itemtype="https://schema.org/Article">
                        <!-- Featured Image -->
                        @if($update->hasImage())
                            <div class="mb-8 rounded-xl overflow-hidden shadow-md" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                <img src="{{ $update->image_url }}"
                                     alt="{{ $update->title }}"
                                     class="w-full h-auto max-h-96 object-cover"
                                     itemprop="url"
                                     loading="lazy">
                                <meta itemprop="width" content="800">
                                <meta itemprop="height" content="600">
                            </div>
                        @endif

                        <!-- Article Body -->
                        <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900
                                   prose-p:leading-relaxed prose-p:text-gray-700 prose-strong:text-gray-900
                                   prose-ul:mt-6 prose-ul:space-y-2 prose-li:marker:text-gray-400
                                   prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                                   prose-blockquote:border-l-blue-500 prose-blockquote:bg-blue-50 prose-blockquote:py-2 prose-blockquote:px-4
                                   prose-img:rounded-lg prose-img:shadow-sm prose-img:mx-auto
                                   prose-table:border-collapse prose-table:border prose-table:border-gray-300
                                   prose-th:bg-gray-100 prose-th:p-3 prose-td:p-3 prose-td:border-t"
                             itemprop="articleBody">
                            {!! $update->safe_html_content !!}
                        </div>

                        <!-- Article Footer -->
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm text-gray-500">
                                <div class="flex items-center space-x-2">
                                    <span>Last updated:</span>
                                    <time datetime="{{ $update->updated_at->format('Y-m-d') }}" itemprop="dateModified">
                                        {{ $update->updated_at->format('M j, Y') }}
                                    </time>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Structured Data JSON-LD -->
                    <script type="application/ld+json">
                    {!! $this->getStructuredData() !!}
                    </script>
                </div>

                <!-- Related Updates -->
                <div class="lg:col-span-1">
                    @if($relatedUpdates->count() > 0)
                        <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-8 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Related Updates</h3>
                            <div class="space-y-4">
                                @foreach($relatedUpdates as $related)
                                    <article class="group p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                        <a href="{{ route('people.updates.show', ['personSlug' => $person->slug, 'slug' => $related->slug]) }}"
                                           class="block">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded font-medium">
                                                    {{ ucfirst($related->update_type) }}
                                                </span>
                                                <time class="text-xs text-gray-500">
                                                    {{ $related->created_at->format('M j') }}
                                                </time>
                                            </div>
                                            <h4 class="font-medium text-gray-900 group-hover:text-blue-600 leading-snug transition-colors duration-200 line-clamp-2">
                                                {{ $related->title }}
                                            </h4>
                                            @if($related->description)
                                                <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                                    {{ $related->description }}
                                                </p>
                                            @endif
                                        </a>
                                    </article>
                                @endforeach
                            </div>

                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <a href="{{ route('people.updates.index', $person->slug) }}"
                                   class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-sm font-medium shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0-12a2 2 0 012-2h2a2 2 0 012 2V6z"/>
                                    </svg>
                                    View All Updates
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
