<div class="p-2 md:p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Gallery</h2>

    @if($person->photos->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($person->photos as $photo)
        <div class="group relative bg-gray-100 rounded-lg overflow-hidden cursor-pointer">
            <img src="{{ $photo->image_url }}" alt="{{ $photo->caption ?? $person->display_name }}"
                class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
            <div
                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-end">
                @if($photo->caption)
                <div
                    class="p-4 text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <p class="text-sm font-medium">{{ $photo->caption }}</p>
                    @if($photo->year)
                    <p class="text-xs opacity-90">{{ $photo->year }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
            </path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No Photos Available</h3>
        <p class="text-gray-500">Photo gallery for {{ $person->display_name }} is not available yet.</p>
    </div>
    @endif

    {{-- Profile Images Section --}}
    <div class="mt-12">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Profile Images</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @if($person->profile_image_url)
            <div class="text-center">
                <div class="bg-gray-100 rounded-lg p-4">
                    <img src="{{ $person->profile_image_url }}" alt="{{ $person->display_name }} - Profile"
                        class="w-48 h-48 object-cover rounded-lg mx-auto">
                </div>
                <p class="mt-2 text-sm text-gray-600">Current Profile Image</p>
            </div>
            @endif

            @if($person->cover_image_url)
            <div class="text-center">
                <div class="bg-gray-100 rounded-lg p-4">
                    <img src="{{ $person->cover_image_url }}" alt="{{ $person->display_name }} - Cover"
                        class="w-full h-32 object-cover rounded-lg">
                </div>
                <p class="mt-2 text-sm text-gray-600">Cover Image</p>
            </div>
            @endif
        </div>
    </div>
</div>
