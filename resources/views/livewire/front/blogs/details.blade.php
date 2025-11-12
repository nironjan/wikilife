<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Structured Data -->
        <script type="application/ld+json">
            {!! json_encode($structuredData['article']) !!}
        </script>
        <script type="application/ld+json">
            {!! json_encode($structuredData['breadcrumb']) !!}
        </script>
        <script type="application/ld+json">
            {!! json_encode($structuredData['website']) !!}
        </script>
        <script type="application/ld+json">
            {!! json_encode($structuredData['organization']) !!}
        </script>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8" aria-label="Breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors duration-200 flex items-center space-x-1" itemprop="item">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    <span itemprop="name">Home</span>
                </a>
                <meta itemprop="position" content="1" />
            </div>

            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>

            <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ route('articles.index') }}" class="hover:text-blue-600 transition-colors duration-200" itemprop="item">
                    <span itemprop="name">Blog Articles</span>
                </a>
                <meta itemprop="position" content="2" />
            </div>

            @if($article->blogCategory)
                <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>

                <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="{{ route('articles.category', $article->blogCategory->slug) }}" class="hover:text-blue-600 transition-colors duration-200" itemprop="item">
                        <span itemprop="name">{{ $article->blogCategory->name }}</span>
                    </a>
                    <meta itemprop="position" content="3" />
                </div>
            @endif

            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>

            <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="text-blue-600 font-medium truncate max-w-xs" itemprop="name">{{ $article->title }}</span>
                <meta itemprop="position" content="{{ $article->blogCategory ? 4 : 3 }}" />
            </div>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <!-- Article Header -->
                <article class="bg-white rounded-lg shadow overflow-hidden border border-gray-100/50" itemscope itemtype="https://schema.org/BlogPosting">
                    @if($article->featured_image_url)
                        <div class="relative" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                            <img
                                src="{{ $article->imageSize(1200, 630, 80) }}"
                                alt="{{ $article->title }}"
                                class="w-full h-64 lg:h-96 object-cover"
                                loading="lazy"
                                itemprop="url"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                            <meta itemprop="width" content="1200" />
                            <meta itemprop="height" content="630" />
                        </div>
                    @endif

                    <div class="p-6 lg:p-8">
                        <!-- Category & Date -->
                        <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
                            <div class="flex items-center space-x-3">
                                @if($article->blogCategory)
                                    <a
                                        href="{{ route('articles.category', $article->blogCategory->slug) }}"
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-100 to-blue-50 text-blue-800 text-sm font-semibold rounded-full hover:from-blue-200 hover:to-blue-100 transition-all duration-200 border border-blue-200 shadow-sm"
                                        itemprop="articleSection"
                                    >
                                        <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A1 1 0 012 10V3a1 1 0 011-1h7a1 1 0 01.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $article->blogCategory->name }}
                                    </a>
                                @endif
                                <div class="flex items-center text-sm text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full border border-gray-200" itemprop="timeRequired" content="PT{{ $article->read_time }}M">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $article->read_time }} min read</span>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full border border-gray-200">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <time datetime="{{ $article->published_at->toISOString() }}" itemprop="datePublished">
                                    {{ $article->published_at->format('F j, Y') }}
                                </time>
                            </div>
                        </div>

                        <!-- Title -->
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6 leading-tight tracking-tight" itemprop="headline">
                            {{ $article->title }}
                        </h1>

                        <!-- Excerpt -->
                        @if($article->excerpt)
                            <p class="text-xl text-gray-600 mb-8 leading-relaxed font-medium" itemprop="description">
                                {{ $article->excerpt }}
                            </p>
                        @endif

                        <!-- Author & Stats -->
                        <div class="flex flex-wrap items-center justify-between gap-6 mb-8 pb-8 border-b border-gray-200">
                            <div class="flex items-center space-x-4" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                @if($article->author)
                                    <div class="flex items-center space-x-4">
                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                            <span class="text-lg font-bold text-white">
                                                {{ substr($article->author->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-lg font-semibold text-gray-900" itemprop="name">{{ $article->author->name }}</p>
                                            <p class="text-sm text-gray-500 mt-1">Published Author</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-4">
                                        <div class="w-14 h-14 bg-gradient-to-br from-gray-500 to-gray-600 rounded-2xl flex items-center justify-center shadow-lg">
                                            <span class="text-lg font-bold text-white">A</span>
                                        </div>
                                        <div>
                                            <p class="text-lg font-semibold text-gray-900" itemprop="name">Admin</p>
                                            <p class="text-sm text-gray-500 mt-1">Published Author</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center space-x-6 text-sm text-gray-600">
                                <div class="flex items-center space-x-2 bg-gray-100 px-3 py-2 rounded-lg border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span>{{ number_format($article->views) }} views</span>
                                </div>
                                <div class="flex items-center space-x-2 bg-gray-100 px-3 py-2 rounded-lg border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <span>{{ $article->comments_count ?? 0 }} comments</span>
                                </div>
                            </div>
                        </div>

                        <!-- Article Content -->
                        <div class="prose prose-lg max-w-none
                                  prose-headings:font-bold prose-headings:text-gray-900 prose-headings:leading-tight
                                  prose-p:text-gray-700 prose-p:leading-relaxed prose-p:text-lg
                                  prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline prose-a:font-medium
                                  prose-strong:text-gray-900 prose-strong:font-semibold
                                  prose-blockquote:border-l-4 prose-blockquote:border-blue-500 prose-blockquote:bg-blue-50
                                  prose-blockquote:px-6 prose-blockquote:py-4 prose-blockquote:rounded-r-xl
                                  prose-img:rounded-2xl prose-img:shadow-lg prose-img:mx-auto
                                  prose-ul:my-6 prose-li:my-2 prose-li:leading-relaxed
                                  prose-ol:my-6 prose-li:my-2 prose-li:leading-relaxed
                                  prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-pre:rounded-xl
                                  prose-code:bg-gray-100 prose-code:px-2 prose-code:py-1 prose-code:rounded prose-code:text-sm
                                  prose-hr:border-gray-200 prose-hr:my-8
                                  prose-table:shadow-sm prose-table:rounded-lg prose-table:overflow-hidden
                                  prose-lead:text-gray-600 prose-lead:text-xl prose-lead:font-medium"
                             itemprop="articleBody">
                            {!! $article->content !!}
                        </div>

                        <!-- Tags -->
                        @if($article->tags && count($article->tags) > 0)
                            <div class="mt-12 pt-8 border-t border-gray-200">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="text-base font-semibold text-gray-700 mr-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A1 1 0 012 10V3a1 1 0 011-1h7a1 1 0 01.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                        </svg>
                                        Article Tags:
                                    </span>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($article->tags as $tag)
                                            <span class="inline-block bg-gradient-to-r from-gray-100 to-gray-50 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200" itemprop="keywords">
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Share Buttons -->
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <span class="text-lg font-semibold text-gray-900">Share this article:</span>
                                <div class="flex items-center space-x-3">
                                     @include('components.share-buttons')
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Related Articles -->
                @if($relatedArticles->count() > 0)
                    <section class="mt-12" aria-labelledby="related-articles-heading">
                        <h2 id="related-articles-heading" class="text-2xl lg:text-3xl font-bold text-gray-900 mb-8 flex items-center">
                            <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            Related Articles
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($relatedArticles as $related)
                                <article class="bg-white rounded-lg shadow overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100/50 group" itemscope itemtype="https://schema.org/BlogPosting">
                                    @if($related->featured_image_url)
                                        <div class="relative overflow-hidden">
                                            <img
                                                src="{{ $related->imageSize(400, 250, 75) }}"
                                                alt="{{ $related->title }}"
                                                class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500"
                                                loading="lazy"
                                                itemprop="image"
                                            >
                                            <div class="absolute top-3 left-3">
                                                @if($related->blogCategory)
                                                    <span class="inline-block px-3 py-1.5 bg-white/90 backdrop-blur-sm text-blue-800 text-xs font-bold rounded-lg shadow-sm">
                                                        {{ $related->blogCategory->name }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                                        </div>
                                    @endif
                                    <div class="p-5">
                                        <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200" itemprop="headline">
                                            <a href="{{ route('articles.show', $related->slug) }}" itemprop="url">
                                                {{ $related->title }}
                                            </a>
                                        </h3>
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <time datetime="{{ $related->published_at->toISOString() }}" itemprop="datePublished">
                                                {{ $related->published_at->format('M j, Y') }}
                                            </time>
                                            <div class="flex items-center space-x-3">
                                                <span class="flex items-center bg-gray-100 px-2 py-1 rounded-lg">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="lg:col-span-4">
                <div class="sticky top-6 space-y-8">
                    <!-- Blog Categories -->
                    <div class="bg-white rounded-lg shadow p-6 border border-gray-100/50">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                            Article Categories
                        </h3>
                        <div class="space-y-3">
                            @foreach($categories as $category)
                                <a
                                    href="{{ route('articles.category', $category->slug) }}"
                                    class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-blue-50 transition-all duration-200 group {{ $article->blogCategory && $article->blogCategory->slug === $category->slug ? 'bg-blue-50 border border-blue-200' : 'hover:border-blue-200 border border-transparent' }}"
                                    aria-current="{{ $article->blogCategory && $article->blogCategory->slug === $category->slug ? 'page' : 'false' }}"
                                >
                                    <span class="font-semibold text-gray-700 group-hover:text-blue-700 transition-colors duration-200 {{ $article->blogCategory && $article->blogCategory->slug === $category->slug ? 'text-blue-700' : '' }}">
                                        {{ $category->name }}
                                    </span>
                                    <span class="text-sm bg-blue-100 text-blue-800 px-2.5 py-1 rounded-full font-bold min-w-8 text-center transition-colors duration-200 group-hover:bg-blue-200">
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
                        title="Trending Articles"
                        :show-images="true"
                    />
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
    </style>
</div>

