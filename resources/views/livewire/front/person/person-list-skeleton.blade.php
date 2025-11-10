<div class="bg-white">
    <!-- SEO-Friendly Header Section -->
    <div class="bg-gray-50 border-b">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Famous Personalities</h1>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Discover comprehensive profiles of notable figures from various fields including entertainment, sports, politics, business, and more.
                </p>
            </div>

            <!-- Search and Filter Section -->
            <div class="mt-8 max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Search Skeleton -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Personalities</label>
                        <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-200 animate-pulse h-10"></div>
                    </div>

                    <!-- Category Filter Skeleton -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Category</label>
                        <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-200 animate-pulse h-10"></div>
                    </div>

                    <!-- Profession Filter Skeleton -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Profession</label>
                        <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-200 animate-pulse h-10"></div>
                    </div>
                </div>

                <!-- Category Quick Links Skeleton -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-4">Browse by Category:</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3">
                        @for($i = 0; $i < 7; $i++)
                            <div class="flex flex-col items-center p-3 rounded-lg border-2 border-gray-200 bg-gray-50 animate-pulse">
                                <div class="w-5 h-5 bg-gray-300 rounded mb-2"></div>
                                <div class="h-3 bg-gray-300 rounded w-16"></div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb Skeleton -->
    <div class="max-w-6xl mx-auto px-4 py-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <div class="h-4 bg-gray-200 rounded w-12 animate-pulse"></div>
                </li>
                <li>
                    <div class="h-4 w-4 bg-gray-200 rounded"></div>
                </li>
                <li>
                    <div class="h-4 bg-gray-200 rounded w-24 animate-pulse"></div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- People Grid Skeleton -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Results Count Skeleton -->
        <div class="mb-6">
            <div class="h-5 bg-gray-200 rounded w-64 animate-pulse"></div>
        </div>

        <!-- People Grid Skeleton -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @for($i = 0; $i < 10; $i++)
                <div class="bg-white rounded-lg shadow-md overflow-hidden animate-pulse">
                    <!-- Profile Image Skeleton -->
                    <div class="w-full h-48 bg-gray-300"></div>

                    <!-- Person Info Skeleton -->
                    <div class="p-4">
                        <!-- Name Skeleton -->
                        <div class="h-5 bg-gray-300 rounded mb-2"></div>

                        <!-- Profession Skeleton -->
                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-3"></div>

                        <!-- Age and Status Skeleton -->
                        <div class="flex items-center justify-between mb-2">
                            <div class="h-3 bg-gray-200 rounded w-12"></div>
                            <div class="h-3 bg-gray-200 rounded w-10"></div>
                        </div>

                        <!-- Quick Stats Skeleton -->
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                            <div class="h-3 bg-gray-200 rounded w-8"></div>
                            <div class="h-3 bg-gray-200 rounded w-10"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Pagination Skeleton -->
        <div class="mt-12">
            <div class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                <!-- Previous Button Skeleton -->
                <div class="-mt-px flex w-0 flex-1">
                    <div class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-300">
                        <div class="h-5 w-5 bg-gray-200 rounded mr-3"></div>
                        <div class="h-4 bg-gray-200 rounded w-16"></div>
                    </div>
                </div>

                <!-- Page Numbers Skeleton -->
                <div class="hidden md:-mt-px md:flex">
                    @for($i = 0; $i < 5; $i++)
                        <div class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium">
                            <div class="h-6 w-6 bg-gray-200 rounded"></div>
                        </div>
                    @endfor
                </div>

                <!-- Next Button Skeleton -->
                <div class="-mt-px flex w-0 flex-1 justify-end">
                    <div class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-300">
                        <div class="h-4 bg-gray-200 rounded w-12 mr-3"></div>
                        <div class="h-5 w-5 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
