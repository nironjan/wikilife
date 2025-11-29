<div class="min-h-screen bg-white dark:bg-gray-900 transition-colors duration-300">
    {{-- Simple Header --}}
    <header class="py-8 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Structured Data --}}
            <script type="application/ld+json">
                {!! json_encode($structuredData) !!}
            </script>

            {{-- Enhanced Breadcrumb --}}
            <nav class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-6" aria-label="Breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="{{ url('/') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex items-center space-x-1 group" itemprop="item">
                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        <span itemprop="name" class="font-medium">Home</span>
                    </a>
                    <meta itemprop="position" content="1" />
                </div>

                <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>

                <div class="flex items-center space-x-2" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span class="text-blue-600 dark:text-blue-400 font-semibold truncate max-w-[200px] sm:max-w-none" itemprop="name">{{ $page->title }}</span>
                    <meta itemprop="position" content="2" />
                </div>
            </nav>

            {{-- Article Header --}}
            <article>
                <header class="space-y-4">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white leading-tight tracking-tight">
                        {{ $page->title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <time datetime="{{ $page->updated_at->toISOString() }}" itemprop="dateModified" class="flex items-center space-x-1 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-2xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Last Updated: {{ $page->updated_at->format('F j, Y') }}</span>
                        </time>
                    </div>

                    @if($page->description)
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-300 leading-relaxed max-w-3xl">
                            {{ $page->description }}
                        </p>
                    @endif
                </header>
            </article>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="py-2">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="ql-editor prose prose-gray max-w-none
                prose-headings:text-gray-900 prose-headings:mb-4
                prose-p:text-gray-600 prose-p:leading-7
                prose-strong:text-gray-900
                prose-blockquote:border-l-4 prose-blockquote:border-gray-300 prose-blockquote:pl-4
                prose-ul:text-gray-600 prose-ol:text-gray-600
                prose-img:rounded-lg
                prose-table:border prose-table:border-gray-200
                prose-th:bg-gray-50
                prose-pre:bg-gray-900 prose-pre:text-white
                prose-code:bg-gray-100 prose-code:text-gray-800
                [&_a]:text-rose-600 [&_a]:underline [&_a]:decoration-rose-400/70
                [&_a]:decoration-[0.5px] [&_a]:underline-offset-[3px]
                [&_a]:transition-colors [&_a]:duration-200
                [&_a:hover]:text-rose-700 [&_a:hover]:decoration-rose-500/90
                dark:[&_a]:text-rose-400
                dark:[&_a:hover]:text-rose-300
                dark:prose-invert dark:prose-headings:text-white dark:prose-p:text-gray-300
                dark:prose-blockquote:border-gray-600
                dark:prose-code:bg-gray-800 dark:prose-code:text-gray-200">
    {!! $page->content !!}
</article>





            {{-- Reading Progress (Optional) --}}
            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                <div class="mx-auto text-sm text-gray-600 dark:text-gray-400 flex justify-end">
                    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                            class="flex items-center space-x-1 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        <span>Back to top</span>
                    </button>
                </div>
            </div>

        </div>
    </main>

</div>

