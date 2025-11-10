<div>
    <!-- Simple Header -->
    <section class="py-12 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">{{ $page->title }}</h1>
            @if($page->description)
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">{{ $page->description }}</p>
            @endif
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none dark:prose-invert">
                {!! $page->content !!}
            </div>
        </div>
    </section>
</div>
