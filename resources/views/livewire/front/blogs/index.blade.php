<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Home
                </a>
                <span class="text-gray-300">›</span>
                <span class="text-gray-900 font-semibold">Blog Articles</span>
            </nav>

            {{-- Structured Data --}}
            <script type="application/ld+json">
                {!! json_encode($structuredData['website']) !!}
            </script>
            <script type="application/ld+json">
                {!! json_encode($structuredData['blog']) !!}
            </script>
            <script type="application/ld+json">
                {!! json_encode($structuredData['breadcrumb']) !!}
            </script>
            <script type="application/ld+json">
                {!! json_encode($structuredData['itemList']) !!}
            </script>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-8">
                    {{-- Header Section --}}
                    <div class="bg-white rounded-2xl shadow-sm p-8 mb-8 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V7a2 2 0 012-2h2l1-2h8l1 2h2a2 2 0 012 2v11a2 2 0 01-2 2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 10h10M7 14h6" />
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Blog & Articles</h1>
                                    <p class="text-gray-600 text-lg leading-relaxed max-w-2xl">
                                        Discover comprehensive articles, news, and insights about famous personalities,
                                        entertainment trends, political analysis, and lifestyle topics.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Search & Filters Section --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 mb-8 border border-gray-100">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            {{-- Search --}}
                            <div class="flex-1">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" wire:model.live="search"
                                        placeholder="Search articles, topics, or keywords..."
                                        class="block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-lg shadow-sm transition-all duration-200"
                                        aria-label="Search articles">
                                </div>
                            </div>

                            {{-- Filters --}}
                            <div class="flex flex-wrap gap-3">
                                {{-- Sort By --}}
                                <div class="relative">
                                    <select wire:model.live="sortBy"
                                        class="appearance-none px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 bg-white shadow-sm text-gray-700 font-medium cursor-pointer pr-10 transition-all duration-200 hover:border-gray-300"
                                        aria-label="Sort articles by">
                                        <option value="latest">Latest Articles</option>
                                        <option value="popular">Most Popular</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>

                                {{-- Clear Filters --}}
                                <button wire:click="clearFilters"
                                    class="px-6 py-4 text-base font-semibold text-gray-700 hover:text-gray-900 border border-gray-200 rounded-2xl hover:bg-gray-50 bg-white shadow-sm transition-all duration-200 hover:shadow-md flex items-center"
                                    aria-label="Clear all filters">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Clear
                                </button>
                            </div>
                        </div>

                        {{-- Active Filters --}}
                        @if($search || $category || $sortBy !== 'latest')
                        <div class="mt-4 flex flex-wrap gap-2">
                            @if($search)
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                Search: "{{ $search }}"
                                <button wire:click="$set('search', '')"
                                    class="ml-2 hover:text-blue-900 transition-colors"
                                    aria-label="Remove search filter">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                            @endif
                            @if($category)
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                Category: {{ $categories->firstWhere('slug', $category)->name ?? $category }}
                                <button wire:click="$set('category', '')"
                                    class="ml-2 hover:text-green-900 transition-colors"
                                    aria-label="Remove category filter">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                            @endif
                            @if($sortBy === 'popular')
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                Sort: Popular
                                <button wire:click="$set('sortBy', 'latest')"
                                    class="ml-2 hover:text-purple-900 transition-colors"
                                    aria-label="Remove sort filter">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>

                    {{-- Articles List --}}
                    @if($articles->count() > 0)
                    <div class="space-y-6" role="list" aria-label="Blog articles list">
                        @foreach($articles as $article)
                        <article
                            class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 overflow-hidden group"
                            itemscope itemtype="https://schema.org/BlogPosting">
                            <div class="flex flex-col md:flex-row">
                                {{-- Image Column --}}
                                <div class="md:w-2/5 relative">
                                    @if($article->featured_image)
                                    <img src="{{ $article->imageSize(400, 250) }}" alt="{{ $article->title }}"
                                        class="w-full h-64 md:h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy" itemprop="image">
                                    @else
                                    <div
                                        class="w-full h-64 md:h-full bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0 0h6m-6 0v-6" />
                                        </svg>
                                    </div>
                                    @endif
                                    <div class="absolute top-4 left-4">
                                        @if($article->blogCategory)
                                        <a href="{{ route('articles.category', $article->blogCategory->slug) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-white/90 backdrop-blur-sm text-blue-800 text-xs font-bold rounded-full hover:bg-white hover:text-blue-900 transition-all duration-200 shadow-sm"
                                            itemprop="articleSection">
                                            {{ $article->blogCategory->name }}
                                        </a>
                                        @endif
                                    </div>
                                </div>

                                {{-- Content Column --}}
                                <div class="md:w-3/5 p-6 lg:p-8">
                                    {{-- Meta Information --}}
                                    <div class="flex items-center justify-between mb-4">
                                        <time class="text-sm text-gray-500 font-medium flex items-center"
                                            datetime="{{ $article->published_at->toISOString() }}"
                                            itemprop="datePublished">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $article->published_at->format('F j, Y') }}
                                        </time>
                                        <div class="flex items-center space-x-1 text-sm text-gray-500"
                                            itemprop="timeRequired" content="PT{{ $article->read_time }}M">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ $article->read_time }} min read</span>
                                        </div>
                                    </div>

                                    {{-- Title --}}
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4 leading-tight group-hover:text-blue-600 transition-colors duration-200 line-clamp-2"
                                        itemprop="headline">
                                        <a href="{{ route('articles.show', $article->slug) }}" itemprop="url">
                                            {{ $article->title }}
                                        </a>
                                    </h2>

                                    {{-- Excerpt --}}
                                    <p class="text-gray-600 text-base mb-6 leading-relaxed line-clamp-3"
                                        itemprop="description">
                                        {{ $article->excerpt }}
                                    </p>

                                    {{-- Author & Read More --}}
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center space-x-3" itemprop="author" itemscope
                                            itemtype="https://schema.org/Person">
                                            <div
                                                class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                {{ substr($article->author->name ?? 'A', 0, 1) }}
                                            </div>
                                            <span class="text-sm text-gray-700 font-medium" itemprop="name">{{
                                                $article->author->name ?? 'Admin' }}</span>
                                        </div>
                                        <a href="{{ route('articles.show', $article->slug) }}"
                                            class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold text-sm group/readmore transition-colors duration-200"
                                            aria-label="Read full article: {{ $article->title }}">
                                            Read More
                                            <svg class="w-4 h-4 ml-1 group-hover/readmore:translate-x-1 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Hidden Schema Data --}}
                            <meta itemprop="dateModified" content="{{ $article->updated_at->toISOString() }}" />
                            <meta itemprop="publisher" content="{{ config('app.name', 'WikiLife') }}" />
                            <meta itemprop="mainEntityOfPage" content="{{ route('articles.show', $article->slug) }}" />
                        </article>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-12 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        {{ $articles->links('components.pagination') }}
                    </div>
                    @else
                    {{-- Empty State --}}
                    <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0 0h6m-6 0v-6" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">No articles found</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg leading-relaxed">
                            @if(!empty($search))
                            We couldn't find any articles matching "<strong>{{ $search }}</strong>". Try adjusting your
                            search terms or browse our categories.
                            @else
                            We couldn't find any articles matching your current filters. Try clearing the filters to see
                            all available articles.
                            @endif
                        </p>
                        <button wire:click="clearFilters"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Show All Articles
                        </button>
                    </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-4">
                    <div class="sticky top-6 space-y-8">
                        {{-- Blog Categories --}}
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <h3
                                class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100 flex items-center">
                                <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Article Categories
                            </h3>
                            <div class="space-y-3" role="list" aria-label="Blog categories">
                                @foreach($categories as $cat)
                                <a href="{{ route('articles.category', $cat->slug) }}"
                                    class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-blue-50 transition-all duration-200 group {{ $category === $cat->slug ? 'bg-blue-50 border border-blue-200' : 'hover:border-blue-200 border border-transparent' }}"
                                    aria-current="{{ $category === $cat->slug ? 'page' : 'false' }}">
                                    <span
                                        class="font-semibold text-gray-700 group-hover:text-blue-700 {{ $category === $cat->slug ? 'text-blue-700' : '' }}">
                                        {{ $cat->name }}
                                    </span>
                                    <span
                                        class="text-sm bg-gray-100 group-hover:bg-blue-200 px-2.5 py-1 rounded-full font-medium text-gray-600 group-hover:text-blue-800 min-w-8 text-center transition-colors duration-200">
                                        {{ $cat->blog_posts_count }}
                                    </span>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Popular Articles --}}
                        <livewire:front.blogs.popular-articles :limit="5" :show-ranking="true" title="Trending Articles"
                            :show-images="true" />
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
