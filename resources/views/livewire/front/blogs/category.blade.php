<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <!-- Header -->
                <div>
                    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                        <a href="{{ route('articles.index') }}" class="hover:text-blue-600">Blog</a>
                        <span>/</span>
                        <span class="text-gray-900">{{ $category->name }}</span>
                    </nav>
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                                @if($category->description)
                                    <p class="text-gray-600 text-sm">{{ $category->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <!-- Search -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    wire:model.live="search"
                                    placeholder="Search in {{ $category->name }}..."
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                                >
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="flex flex-wrap gap-3">
                            <!-- Sort By -->
                            <select wire:model.live="sortBy" class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 bg-white shadow-sm">
                                <option value="latest">Latest Articles</option>
                                <option value="popular">Most Popular</option>
                            </select>

                            <!-- Clear Filters -->
                            <button
                                wire:click="clearFilters"
                                class="px-6 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 border border-gray-300 rounded-xl hover:bg-gray-50 bg-white shadow-sm transition-colors duration-200"
                            >
                                Clear Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Articles List -->
                @if($articles->count() > 0)
                    <div class="space-y-4">
                        @foreach($articles as $article)
                            <article class="bg-white rounded-lg overflow-hidden border border-gray-100">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Image Column -->
                                    <div class="md:w-2/5">
                                        @if($article->featured_image)
                                            <img
                                                src="{{ $article->imageSize(400, 250) }}"
                                                alt="{{ $article->title }}"
                                                class="w-full h-64 object-cover"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="w-full h-64 md:h-full bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0 0h6m-6 0v-6"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content Column -->
                                    <div class="md:w-3/5 p-4">
                                        <!-- Category & Date -->
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="inline-flex items-center px-4 py-2 text-blue-800 text-sm font-semibold">
                                                {{ $category->name }}
                                            </span>
                                            <span class="text-sm text-gray-500 font-medium">
                                                {{ $article->published_at->format('M j, Y') }}
                                            </span>
                                        </div>

                                        <!-- Title -->
                                        <h2 class="text-xl font-bold text-gray-900 mb-4 leading-tight hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                                            <a href="{{ route('articles.show', $article->slug) }}">
                                                {{ $article->title }}
                                            </a>
                                        </h2>

                                        <!-- Excerpt -->
                                        <p class="text-gray-600 text-sm mb-1 leading-relaxed line-clamp-3">
                                            {{ $article->excerpt }}
                                        </p>

                                        <!-- Meta Information -->
                                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                <div class="flex items-center space-x-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>{{ $article->read_time }} min read</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $articles->links('components.pagination') }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <svg class="w-20 h-20 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0 0h6m-6 0v-6"/>
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">No articles found</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">
                            @if(!empty($search))
                                No results found for "{{ $search }}" in {{ $category->name }}. Try adjusting your search terms.
                            @else
                                No articles found in {{ $category->name }} category yet.
                            @endif
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button
                                wire:click="clearFilters"
                                class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Clear Filters
                            </button>
                            <a
                                href="{{ route('articles.index') }}"
                                class="inline-flex items-center px-8 py-4 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Browse All Categories
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Blog Categories -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Blog Categories
                    </h3>
                    <div class="space-y-3">
                        @foreach($categories as $cat)
                            <a
                                href="{{ route('articles.category', $cat->slug) }}"
                                class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-blue-50 transition-all duration-200 group {{ $category->slug === $cat->slug ? 'bg-blue-50 border border-blue-200' : 'hover:border-blue-200 border border-transparent' }}"
                            >
                                <span class="font-semibold text-gray-700 group-hover:text-blue-700 {{ $category->slug === $cat->slug ? 'text-blue-700' : '' }}">
                                    {{ $cat->name }}
                                </span>
                                <span class="text-sm bg-gray-100 group-hover:bg-blue-200 px-2.5 py-1 rounded-full font-medium text-gray-600 group-hover:text-blue-800 min-w-8 text-center">
                                    {{ $cat->blog_posts_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Popular Articles -->
                <livewire:front.blogs.popular-articles
                    :limit="5"
                    :show-ranking="true"
                    title="Popular Articles"
                    :show-images="true"
                />
            </div>
        </div>
    </div>
</div>
