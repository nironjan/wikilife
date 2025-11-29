<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Structured Data -->
    <script type="application/ld+json">
        {!! $breadcrumbStructuredData !!}
    </script>
    <script type="application/ld+json">
        {!! $websiteStructuredData !!}
    </script>
    <script type="application/ld+json">
        {!! $collectionStructuredData !!}
    </script>

    <!-- Page Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Indian States & Union Territories
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Browse famous people, celebrities, and notable personalities from all Indian states and union territories.
            Discover actors, politicians, athletes, artists and more categorized by their home states.
        </p>
    </div>


    <!-- States Section -->
    <section aria-labelledby="states-heading" class="mb-12">
        <h2 id="states-heading" class="text-2xl font-bold text-gray-900 dark:text-white mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
            States of India ({{ $statesData['states']->count() }})
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($statesData['states'] as $state)
                <a
                    href="{{ $state['url'] }}"
                    class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-400 group"
                    aria-label="Explore famous people from {{ $state['name'] }}"
                >
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto mb-3 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors">
                            <span class="text-green-600 dark:text-green-300 font-semibold text-sm">{{ $state['code'] }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors mb-2">
                            {{ $state['name'] }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $state['count'] }} {{ Str::plural('person', $state['count']) }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Union Territories Section -->
    <section aria-labelledby="uts-heading">
        <h2 id="uts-heading" class="text-2xl font-bold text-gray-900 dark:text-white mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
            Union Territories of India ({{ $statesData['unionTerritories']->count() }})
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($statesData['unionTerritories'] as $ut)
                <a
                    href="{{ $ut['url'] }}"
                    class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-400 group"
                    aria-label="Explore famous people from {{ $ut['name'] }}"
                >
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto mb-3 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center group-hover:bg-purple-200 dark:group-hover:bg-purple-800 transition-colors">
                            <span class="text-purple-600 dark:text-purple-300 font-semibold text-sm">{{ $ut['code'] }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors mb-2">
                            {{ $ut['name'] }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $ut['count'] }} {{ Str::plural('person', $ut['count']) }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- SEO Content Section -->
    <section class="mt-16 bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
        <div class="prose dark:prose-invert max-w-none">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Explore Famous People by Indian States</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                Discover notable personalities, celebrities, and famous people from all across India categorized by their respective states and union territories.
                Our comprehensive directory helps you find influential figures from specific regions of India.
            </p>

            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">About Indian States & Union Territories</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                India is divided into 28 states and 8 union territories, each with its unique cultural heritage and notable personalities.
                From Bollywood celebrities in Maharashtra to tech entrepreneurs in Karnataka, political leaders in Uttar Pradesh to sports stars in Punjab -
                every region contributes remarkable individuals to India's diverse tapestry.
            </p>

            <div class="grid md:grid-cols-2 gap-6 mt-6">
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Popular States for Famous Personalities:</h4>
                    <ul class="text-gray-600 dark:text-gray-300 list-disc list-inside space-y-1">
                        <li><strong>Maharashtra</strong> - Bollywood, Business, Politics</li>
                        <li><strong>Delhi</strong> - Politics, Literature, Arts</li>
                        <li><strong>Tamil Nadu</strong> - Cinema, Sports, Business</li>
                        <li><strong>Karnataka</strong> - Technology, Literature, Cinema</li>
                        <li><strong>West Bengal</strong> - Literature, Arts, Cinema</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Categories Available:</h4>
                    <ul class="text-gray-600 dark:text-gray-300 list-disc list-inside space-y-1">
                        <li>Actors & Film Personalities</li>
                        <li>Politicians & Leaders</li>
                        <li>Sports Persons & Athletes</li>
                        <li>Business Leaders & Entrepreneurs</li>
                        <li>Artists & Cultural Icons</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>
