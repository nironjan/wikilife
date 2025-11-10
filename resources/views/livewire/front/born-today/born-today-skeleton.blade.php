<div>
    <section class="py-12 bg-white border-t border-gray-100 animate-pulse">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gray-200 rounded-lg"></div>
                <div>
                    <div class="h-6 bg-gray-200 rounded w-32 mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded w-48"></div>
                </div>
            </div>
            <div class="hidden sm:block h-4 bg-gray-200 rounded w-16"></div>
        </div>

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            @for($i = 0; $i < 5; $i++)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                    <!-- Image Placeholder -->
                    <div class="w-full h-48 bg-gray-200 relative rounded-t-2xl">
                        <div class="absolute top-3 left-3 w-16 h-5 bg-gray-300 rounded-full"></div>
                    </div>

                    <!-- Content -->
                    <div class="p-4 space-y-3">
                        <div class="h-5 bg-gray-200 rounded w-3/4"></div>
                        <div class="flex items-center justify-between">
                            <div class="h-4 bg-gray-200 rounded w-20"></div>
                            <div class="h-4 bg-gray-200 rounded w-10"></div>
                        </div>

                        <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
                            <div class="h-3 bg-gray-200 rounded w-16"></div>
                            <div class="h-3 bg-gray-200 rounded w-8"></div>
                        </div>
                    </div>

                </div>
            @endfor
        </div>

    </div>
</section>
</div>
