<div>
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-blue-600 to-purple-700 dark:from-blue-900 dark:to-purple-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">{{ $page->title }}</h1>
            @if($page->description)
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">{{ $page->description }}</p>
            @endif
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none dark:prose-invert">
                {!! $page->content !!}
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Our Team</h2>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Meet the amazing people behind our success</p>
            </div>
            <!-- Team members grid would go here -->
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">100+</div>
                    <div class="text-gray-600 dark:text-gray-400">Projects Completed</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">50+</div>
                    <div class="text-gray-600 dark:text-gray-400">Happy Clients</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">5+</div>
                    <div class="text-gray-600 dark:text-gray-400">Years Experience</div>
                </div>
            </div>
        </div>
    </section>
</div>
