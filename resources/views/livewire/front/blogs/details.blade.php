<div class="min-h-screen bg-linear-to-br from-gray-50 to-blue-50/30 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors duration-200 flex items-center space-x-1">
                <!-- Home SVG Icon -->
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span>Home</span>
            </a>

            <!-- Chevron Separator -->
            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>

            <a href="{{ route('articles.index') }}" class="hover:text-blue-600 transition-colors duration-200">Blog</a>

            @if($article->blogCategory)
                <!-- Chevron Separator -->
                <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>

                <a href="{{ route('articles.category', $article->blogCategory->slug) }}"
                class="hover:text-blue-600 transition-colors duration-200">
                    {{ $article->blogCategory->name }}
                </a>
            @endif

            <!-- Chevron Separator -->
            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>

            <span class="text-blue-600 font-medium truncate max-w-xs">{{ $article->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <!-- Article Header -->
                <article class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
                    @if($article->featured_image_url)
                        <div class="relative">
                            <img
                                src="{{ $article->imageSize(1200, 630, 80) }}"
                                alt="{{ $article->title }}"
                                class="w-full h-64 lg:h-96 object-cover"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-linear-to-t from-black/20 to-transparent"></div>
                        </div>
                    @endif

                    <div class="p-6 lg:p-8">
                        <!-- Category & Date -->
                        <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
                            <div class="flex items-center space-x-3">
                                @if($article->blogCategory)
                                    <a
                                        href="{{ route('articles.category', $article->blogCategory->slug) }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full hover:bg-blue-200 transition-colors duration-200"
                                    >
                                        <!-- Tag SVG Icon -->
                                        <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A1 1 0 012 10V3a1 1 0 011-1h7a1 1 0 01.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $article->blogCategory->name }}
                                    </a>
                                @endif
                                <div class="flex items-center text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                    <!-- Clock SVG Icon -->
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $article->read_time }} min read</span>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <!-- Calendar SVG Icon -->
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $article->published_at->format('F j, Y') }}</span>
                            </div>
                        </div>

                        <!-- Title -->
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-6 leading-tight">
                            {{ $article->title }}
                        </h1>

                        <!-- Excerpt -->
                        @if($article->excerpt)
                            <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                                {{ $article->excerpt }}
                            </p>
                        @endif

                        <!-- Author & Stats -->
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-200">
                            <div class="flex items-center space-x-4">
                                @if($article->author)
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-md">
                                            <span class="text-sm font-semibold text-white">
                                                {{ substr($article->author->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-base font-semibold text-gray-900">{{ $article->author->name }}</p>
                                            <p class="text-sm text-gray-500">Published Author</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center space-x-6 text-sm text-gray-500">
                                <div class="flex items-center space-x-1">
                                    <!-- Eye SVG Icon -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span>{{ number_format($article->views) }} views</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <!-- Comment SVG Icon -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <span>{{ $article->comments_count ?? 0 }} comments</span>
                                </div>
                            </div>
                        </div>

                        <!-- Article Content -->
                        <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900
                                  prose-p:text-gray-700 prose-p:leading-relaxed prose-a:text-blue-600
                                  prose-a:no-underline hover:prose-a:underline prose-strong:text-gray-900
                                  prose-blockquote:border-l-blue-500 prose-blockquote:bg-blue-50
                                  prose-blockquote:px-6 prose-blockquote:py-4 prose-blockquote:rounded-r-lg
                                  prose-img:rounded-xl prose-img:shadow-md prose-ul:list-disc prose-ol:list-decimal
                                  prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-code:bg-gray-100
                                  prose-code:px-2 prose-code:py-1 prose-code:rounded prose-code:text-sm">
                            {!! $article->content !!}
                        </div>

                        <!-- Tags -->
                        @if($article->tags && count($article->tags) > 0)
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-700 mr-2 flex items-center">
                                        <!-- Tags SVG Icon -->
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A1 1 0 012 10V3a1 1 0 011-1h7a1 1 0 01.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                        </svg>
                                        Tags:
                                    </span>
                                    @foreach($article->tags as $tag)
                                        <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </article>

                <!-- Related Articles -->
                @if($relatedArticles->count() > 0)
                    <section class="mt-12">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <!-- Link SVG Icon -->
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            Related Articles
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($relatedArticles as $related)
                                <article class="bg-white rounded-lg shadow overflow-hidden hover:shadow transition-all duration-300 border border-gray-100 group">
                                    @if($related->featured_image_url)
                                        <div class="relative overflow-hidden">
                                            <img
                                                src="{{ $related->imageSize(400, 250, 75) }}"
                                                alt="{{ $related->title }}"
                                                class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300"
                                                loading="lazy"
                                            >
                                            <div class="absolute top-3 left-3">
                                                @if($related->blogCategory)
                                                    <span class="inline-block px-2 py-1 bg-white/90 text-blue-800 text-xs font-semibold rounded">
                                                        {{ $related->blogCategory->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
                                            <a href="{{ route('articles.show', $related->slug) }}">
                                                {{ $related->title }}
                                            </a>
                                        </h3>
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>{{ $related->published_at->format('M j, Y') }}</span>
                                            <div class="flex items-center space-x-2">
                                                <span class="flex items-center">
                                                    <!-- Clock SVG Icon -->
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $related->read_time }} min
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Blog Categories -->
                <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <!-- Folder SVG Icon -->
                        <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                        Blog Categories
                    </h3>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                            <a
                                href="{{ route('articles.category', $category->slug) }}"
                                class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-blue-50 transition-all duration-200 group {{ $article->blogCategory && $article->blogCategory->slug === $category->slug ? 'bg-blue-50 border border-blue-200' : 'hover:border-blue-200' }}"
                            >
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-700 group-hover:text-blue-700 transition-colors duration-200">
                                        {{ $category->name }}
                                    </span>
                                </div>
                                <span class="text-sm bg-blue-100 text-blue-800 px-2.5 py-1 rounded-full font-semibold min-w-8 text-center">
                                    {{ $category->blog_posts_count }}
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

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
        {!! json_encode($structuredData['article']) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($structuredData['breadcrumb']) !!}
    </script>
</div>
