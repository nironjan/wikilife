<div class="min-h-screen bg-white">
    {{-- Breadcrumb Skeleton --}}
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center space-x-2">
                <div class="h-4 w-16 bg-gray-200 rounded animate-pulse"></div>
                <div class="h-4 w-4 bg-gray-200 rounded animate-pulse"></div>
                <div class="h-4 w-24 bg-gray-200 rounded animate-pulse"></div>
                <div class="h-4 w-4 bg-gray-200 rounded animate-pulse"></div>
                <div class="h-4 w-20 bg-gray-300 rounded animate-pulse"></div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header Skeleton --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gray-200 rounded-lg animate-pulse"></div>
                <div>
                    <div class="h-7 w-48 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-4 w-64 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </div>
            <div class="hidden sm:block">
                <div class="h-8 w-32 bg-gray-200 rounded animate-pulse"></div>
            </div>
        </div>


        {{-- People Grid Skeleton --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @for($i = 0; $i < 4; $i++)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Image Skeleton --}}
                <div class="w-full h-64 bg-gray-200 animate-pulse relative">
                    <div class="absolute top-3 right-3">
                        <div class="h-6 w-12 bg-gray-300 rounded-full animate-pulse"></div>
                    </div>
                    <div class="absolute top-3 left-3">
                        <div class="h-6 w-16 bg-gray-300 rounded-full animate-pulse"></div>
                    </div>
                </div>

                {{-- Content Skeleton --}}
                <div class="p-4">
                    <div class="h-5 w-32 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-4 w-24 bg-gray-200 rounded animate-pulse mb-3"></div>
                    <div class="h-3 w-28 bg-gray-200 rounded animate-pulse mb-3"></div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div class="h-3 w-16 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-4 w-12 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>

        {{-- Pagination Skeleton --}}
        <div class="flex justify-center mb-12">
            <div class="flex space-x-2">
                <div class="h-10 w-10 bg-gray-200 rounded animate-pulse"></div>
                <div class="h-10 w-10 bg-gray-200 rounded animate-pulse"></div>
                <div class="h-10 w-10 bg-gray-300 rounded animate-pulse"></div>
                <div class="h-10 w-10 bg-gray-200 rounded animate-pulse"></div>
                <div class="h-10 w-10 bg-gray-200 rounded animate-pulse"></div>
            </div>
        </div>

        {{-- Browse All Section Skeleton --}}
        <div class="bg-gray-100 rounded-2xl p-8 border border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-300 rounded-lg animate-pulse"></div>
                    <div>
                        <div class="h-6 w-48 bg-gray-300 rounded animate-pulse mb-2"></div>
                        <div class="h-4 w-64 bg-gray-300 rounded animate-pulse"></div>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <div class="h-12 w-32 bg-gray-300 rounded-lg animate-pulse"></div>
                </div>
            </div>
            <div class="sm:hidden mt-4">
                <div class="h-12 w-full bg-gray-300 rounded-lg animate-pulse"></div>
            </div>
        </div>
    </div>
</div>
