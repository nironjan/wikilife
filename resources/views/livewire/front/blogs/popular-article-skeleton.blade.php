<div class="bg-white rounded-lg shadow p-6 border border-gray-100 animate-pulse">
    {{-- Header --}}
    <div class="flex items-center mb-6">
        <div class="w-5 h-5 bg-red-200 rounded mr-3"></div>
        <div class="h-5 w-32 bg-gray-200 rounded"></div>
    </div>

    {{-- Skeleton list --}}
    <div class="space-y-4">
        @for ($i = 0; $i < 5; $i++)
            <div class="flex items-start space-x-3 p-2 rounded-lg">
                {{-- Image placeholder --}}
                <div class="w-12 h-12 rounded-lg bg-gray-200 shrink-0 relative">
                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-gray-300 rounded-full"></div>
                </div>

                {{-- Text placeholders --}}
                <div class="flex-1 space-y-2">
                    <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-gray-200 rounded-full"></div>
                        <div class="h-2 bg-gray-200 rounded w-16"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>
