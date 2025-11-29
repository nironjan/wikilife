<div>
    <section class="py-12 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0 0h6m-6 0v-6" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Recent Articles</h2>
                        <p class="text-gray-600 text-sm">Discover the latest insights, stories, and updates from our
                            blog</p>
                    </div>
                </div>

                {{-- View All Link --}}
                <a href="{{ route('articles.index') }}"
                    class="hidden sm:flex items-center text-red-600 hover:text-red-700 font-medium text-sm">
                    View All
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            {{-- Hierarchical Grid Layout --}}
            <div class="space-y-8">
                {{-- First Row: 2-column layout --}}
                @if($recentPosts->count() >= 3)
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    {{-- Left: Featured Blog Post --}}
                    <article class="bg-linear-to-br from-gray-50 to-white rounded-lg shadow overflow-hidden group">
                        @php $featuredPost = $recentPosts[0]; @endphp
                        <a href="{{ route('articles.show', $featuredPost->slug) }}" class="block h-full"
                            aria-label="Read blog post: {{ $featuredPost->title }}">
                            <div class="flex flex-col">
                                {{-- Image Section --}}
                                <div class="relative overflow-hidden">
                                    @if($featuredPost->featured_image_url)
                                    <img src="{{ $featuredPost->imageSize(400, 500, 85) }}"
                                        alt="{{ $featuredPost->title }}"
                                        class="w-full h-64 lg:h-48 object-cover group-hover:scale-110 transition-transform duration-700"
                                        loading="eager" width="400" height="500">
                                    @else
                                    <div class="w-full h-64 lg:h-80 bg-linear-to-br from-emerald-50 to-teal-100 flex items-center justify-center"
                                        role="img" aria-label="Blog post image placeholder">
                                        <svg class="w-20 h-20 text-emerald-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0 0h6m-6 0v-6" />
                                        </svg>
                                    </div>
                                    @endif
                                </div>

                                {{-- Content Section --}}
                                <div class="p-4  flex flex-col justify-center flex-1">
                                    <header class="mb-2">
                                        <h2
                                            class="text-xl font-bold text-gray-900 mb-4 group-hover:text-emerald-600 transition-colors duration-300 leading-tight line-clamp-3">
                                            {{ $featuredPost->title }}
                                        </h2>

                                        {{-- Meta Information --}}
                                        <div class="flex items-center text-gray-600 text-xs mb-2 space-x-4">
                                            @if($featuredPost->published_at)
                                            <div class="flex items-center bg-gray-100 px-3 py-1.5 rounded-full">
                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <time datetime="{{ $featuredPost->published_at->format('Y-m-d') }}"
                                                    class="font-medium">
                                                    {{ $featuredPost->published_at->format('M j, Y') }}
                                                </time>
                                            </div>
                                            @endif

                                            {{-- Category Badge --}}
                                            @if($featuredPost->blogCategory)
                                            <span
                                                class=" text-gray-800 text-xs bg-gray-100 px-3 py-1.5 rounded-full font-medium">
                                                {{ $featuredPost->blogCategory->name }}
                                            </span>
                                            @endif

                                        </div>
                                    </header>

                                    {{-- Excerpt --}}
                                    @if($featuredPost->excerpt)
                                    <p class="text-gray-700 text-sm leading-relaxed mb-2 line-clamp-3 font-light">
                                        {{ $featuredPost->excerpt }}
                                    </p>
                                    @elseif($featuredPost->content)
                                    <p class="text-gray-700 text-lg leading-relaxed mb-6 line-clamp-3 font-light">
                                        {{ Str::limit(strip_tags($featuredPost->content), 150) }}
                                    </p>
                                    @endif

                                    {{-- Read More --}}
                                    <footer class="flex items-center justify-between pt-2 border-t border-gray-200/60">
                                        <span class="text-emerald-600 font-semibold text-sm">
                                            Read Full Article →
                                        </span>
                                        <div class="text-gray-700 text-xs font-medium">
                                            {{ $featuredPost->read_time }} min read
                                        </div>
                                    </footer>
                                </div>
                            </div>
                        </a>
                    </article>

                    {{-- Right Column: Secondary Posts --}}
                    <div class="grid grid-cols-1 gap-6">
                        {{-- Top Right: Single Post --}}
                        @if(isset($recentPosts[1]))
                        @php $topRightPost = $recentPosts[1]; @endphp
                        <article class="bg-white rounded-lg shadow-md overflow-hidden group h-full border border-gray-100">
                            <a href="{{ route('articles.show', $topRightPost->slug) }}" class="block"
                                aria-label="Read blog post: {{ $topRightPost->title }}">
                                <div class="flex">
                                    {{-- Image Section --}}
                                    <div class="w-1/3 shrink-0 relative">
                                        @if($topRightPost->featured_image_url)
                                        <img src="{{ $topRightPost->imageSize(280, 200, 85) }}"
                                            alt="{{ $topRightPost->title }}"
                                            class="w-full h-full md:h-48 object-cover group-hover:scale-110 transition-transform duration-500"
                                            loading="lazy" width="280" height="200">
                                        @else
                                        <div class="w-full h-full md:h-32 bg-linear-to-br from-blue-50 to-indigo-100 flex items-center justify-center"
                                            role="img" aria-label="Blog post image placeholder">
                                            <svg class="w-14 h-14 text-blue-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0 0h6m-6 0v-6" />
                                            </svg>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Content Section --}}
                                    <div class="w-2/3 p-4 flex flex-col justify-between">
                                        <header class="mb-2">
                                            <h2
                                                class="text-base font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors duration-300 line-clamp-2 leading-tight">
                                                {{ $topRightPost->title }}
                                            </h2>

                                            {{-- Meta Information --}}
                                            <div class="flex items-center text-gray-600 text-xs mb-1 space-x-3">
                                                @if($topRightPost->published_at)
                                                <div class="flex items-center">
                                                    <time datetime="{{ $topRightPost->published_at->format('Y-m-d') }}"
                                                        class="text-xs">
                                                        {{ $topRightPost->published_at->format('M j, Y') }}
                                                    </time>
                                                </div>
                                                @endif
                                            </div>
                                            {{-- Excerpt --}}
                                            @if($topRightPost->excerpt)
                                            <p
                                                class="text-gray-700 text-sm leading-relaxed mb-2 line-clamp-2 font-light hidden sm:block">
                                                {{ Str::limit(strip_tags($topRightPost->excerpt), 100) }}
                                            </p>
                                            @elseif($topRightPost->content)
                                            <p
                                                class="text-gray-700 text-sm leading-relaxed mb-2 line-clamp-2 font-light hidden sm:block">
                                                {{ Str::limit(strip_tags($topRightPost->content), 150) }}
                                            </p>
                                            @endif
                                        </header>

                                        {{-- Category & Stats --}}
                                        <div class="flex items-center justify-between mt-auto">
                                            @if($topRightPost->blogCategory)
                                            <span class="text-gary-600 text-xs font-semibold">
                                                {{ $topRightPost->blogCategory->name }}
                                            </span>
                                            @endif

                                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                <span class="text-gray-700 font-medium">
                                                    {{ $topRightPost->read_time }} min Read
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </article>
                        @endif

                        {{-- Bottom Right: Two Posts Side by Side --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($recentPosts->slice(2, 2) as $index => $post)
                            <article class="bg-white rounded-lg shadow-md overflow-hidden group h-full border border-gray-100">
                                <a href="{{ route('articles.show', $post->slug) }}" class="block h-full"
                                    aria-label="Read blog post: {{ $post->title }}">
                                    <div class="flex flex-row md:flex-col h-full">
                                        {{-- Image - Left on mobile, full width on desktop --}}
                                        <div class="w-1/3 md:w-full relative overflow-hidden shrink-0">
                                            @if($post->featured_image_url)
                                            <img src="{{ $post->imageSize(120, 120, 85) }}" alt="{{ $post->title }}"
                                                class="w-full h-full max-h-32 md:h-48 object-cover group-hover:scale-110 transition-transform duration-500"
                                                loading="lazy" width="120" height="120">
                                            @else
                                            <div class="w-full h-full max-h-32 md:h-48 bg-linear-to-br from-purple-50 to-violet-100 flex items-center justify-center"
                                                role="img" aria-label="Blog post image placeholder">
                                                <svg class="w-10 h-10 text-purple-300" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0 0h6m-6 0v-6" />
                                                </svg>
                                            </div>
                                            @endif
                                        </div>

                                        {{-- Content - Right on mobile, below image on desktop --}}
                                        <div class="w-2/3 md:w-full p-4 flex flex-col flex-1">
                                            <header class="mb-2">
                                                <h3
                                                    class="font-medium text-gray-900 text-sm mb-1 line-clamp-2 group-hover:text-purple-600 transition-colors duration-300 leading-tight">
                                                    {{ $post->title }}
                                                </h3>
                                            </header>

                                            {{-- Meta Information --}}
                                            <div class="space-y-2 mb-1 flex-1">
                                                @if($post->published_at)
                                                <div class="flex items-center text-gray-500 text-xs">
                                                    {{ $post->published_at->format('M j Y') }}
                                                </div>
                                                @endif
                                            </div>

                                            {{-- Footer --}}
                                            <footer
                                                class="flex items-center justify-between text-xs text-gray-500 pt-1 border-t border-gray-100">
                                                @if($post->blogCategory)
                                                <span class="text-purple-700 text-xs font-medium">
                                                    {{ $post->blogCategory->name }}
                                                </span>
                                                @endif
                                                <span class="text-gray-700 font-medium">
                                                    {{ $post->read_time }} min
                                                </span>
                                            </footer>
                                        </div>
                                    </div>
                                </a>
                            </article>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Second Row: 4-column grid --}}
                @if($recentPosts->count() > 4)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                    @foreach($recentPosts->slice(4, 4) as $post)
                    <article
                        class="bg-white rounded-lg shadow-md overflow-hidden group h-full border border-gray-100">
                        <a href="{{ route('articles.show', $post->slug) }}" class="block h-full"
                            aria-label="Read blog post: {{ $post->title }}">
                            {{-- Image Section --}}
                            <div class="relative overflow-hidden">
                                @if($post->featured_image_url)
                                <img src="{{ $post->imageSize(300, 200, 85) }}" alt="{{ $post->title }}"
                                    class="w-full h-full md:h-32 object-cover group-hover:scale-110 transition-transform duration-500"
                                    loading="lazy" width="300" height="200">
                                @else
                                <div class="w-full h-full md:h-48 bg-linear-to-br from-orange-50 to-amber-100 flex items-center justify-center"
                                    role="img" aria-label="Blog post image placeholder">
                                    <svg class="w-12 h-12 text-orange-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0 0h6m-6 0v-6" />
                                    </svg>
                                </div>
                                @endif

                                {{-- Gradient Overlay --}}
                                <div
                                    class="absolute inset-0 bg-linear-to-t from-black/20 via-transparent to-transparent">
                                </div>
                            </div>

                            {{-- Content Section --}}
                            <div class="p-4">
                                <header class="mb-1">
                                    <h3
                                        class="font-medium text-gray-900 text-base mb-2 group-hover:text-orange-600 transition-colors duration-300 line-clamp-2 leading-tight">
                                        {{ $post->title }}
                                    </h3>
                                </header>

                                {{-- Meta Information --}}
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-gray-600 text-xs italic justify-between">
                                        @if($post->published_at)
                                        <time datetime="{{ $post->published_at->format('Y-m-d') }}" class="font-medium">
                                            {{ $post->published_at->format('M j, Y') }}
                                        </time>
                                        @endif

                                         {{-- Category Badge --}}
                                         @if($post->blogCategory)
                                         <span class="font-medium">
                                             {{ $post->blogCategory->name }}
                                         </span>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                    @endforeach
                </div>
                @endif
            </div>
            {{-- Mobile View All Link --}}
            <div class="sm:hidden text-center mt-6">
                <a href="{{ route('articles.index') }}"
                    class="inline-flex items-center text-red-600 hover:text-red-700 font-medium text-sm">
                    View All Born Today
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

             {{-- Empty State --}}
            @if($recentPosts->count() === 0)
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0 0h6m-6 0v-6" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Blog Posts Yet</h3>
                <p class="text-gray-600">Check back later for new articles and updates.</p>
            </div>
            @endif
        </div>
    </section>
</div>
