<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header Skeleton -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-gray-300 rounded-full animate-pulse mr-3"></div>
                <div class="h-8 bg-gray-300 rounded w-64 animate-pulse"></div>
            </div>
            <div class="h-4 bg-gray-300 rounded w-96 animate-pulse"></div>
        </div>

        <!-- Filters Skeleton -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Search Skeleton -->
                <div class="flex-1">
                    <div class="h-10 bg-gray-200 rounded-lg animate-pulse"></div>
                </div>

                <!-- Filter Controls Skeleton -->
                <div class="flex flex-wrap gap-4">
                    <div class="h-10 bg-gray-200 rounded-lg w-32 animate-pulse"></div>
                    <div class="h-10 bg-gray-200 rounded-lg w-32 animate-pulse"></div>
                    <div class="h-10 bg-gray-200 rounded-lg w-32 animate-pulse"></div>
                </div>
            </div>
        </div>

        <!-- Profession Tags Skeleton -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div class="space-y-2">
                    <div class="h-6 bg-gray-300 rounded w-48 animate-pulse"></div>
                    <div class="h-4 bg-gray-200 rounded w-64 animate-pulse"></div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-4 bg-gray-200 rounded w-16 animate-pulse"></div>
                    <div class="h-8 bg-gray-200 rounded w-32 animate-pulse"></div>
                </div>
            </div>

            <!-- Profession Tags Grid Skeleton -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                @for($i = 0; $i < 10; $i++)
                    <div class="p-3 border border-gray-200 rounded-lg animate-pulse">
                        <div class="flex items-center justify-between mb-2">
                            <div class="h-4 bg-gray-300 rounded w-20 animate-pulse"></div>
                            <div class="w-4 h-4 bg-gray-200 rounded animate-pulse"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="h-3 bg-gray-200 rounded w-12 animate-pulse"></div>
                            <div class="w-3 h-3 bg-gray-200 rounded animate-pulse"></div>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Show More Toggle Skeleton -->
            <div class="mt-4 text-center">
                <div class="h-10 bg-gray-200 rounded-md w-48 mx-auto animate-pulse"></div>
            </div>
        </div>

        <!-- People Grid Skeleton -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-6 mb-8">
            @for($i = 0; $i < 6; $i++)
                <div class="bg-white rounded-lg shadow overflow-hidden animate-pulse">
                    <!-- Profile Image Skeleton -->
                    <div class="w-full h-48 bg-gray-300"></div>

                    <!-- Content Skeleton -->
                    <div class="p-4">
                        <div class="h-5 bg-gray-300 rounded mb-2 animate-pulse"></div>
                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-3 animate-pulse"></div>
                        <div class="flex items-center space-x-2">
                            <div class="h-3 bg-gray-200 rounded w-16 animate-pulse"></div>
                            <div class="h-3 bg-gray-200 rounded w-12 animate-pulse"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Pagination Skeleton -->
        <div class="mt-10">
            <div class="flex justify-center space-x-2">
                @for($i = 0; $i < 5; $i++)
                    <div class="h-10 w-10 bg-gray-200 rounded animate-pulse"></div>
                @endfor
            </div>
        </div>
    </div>
</div>
