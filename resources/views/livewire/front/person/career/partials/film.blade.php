<!-- Movie Details -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Movie Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Role and Profession -->
        <div class="space-y-4">
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Role</h3>
                <p class="text-gray-700">{{ $careerData->role }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Profession Type</h3>
                <p class="text-gray-700">{{ $careerData->profession_type }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Industry</h3>
                <p class="text-gray-700">{{ $careerData->industry }}</p>
            </div>
        </div>

        <!-- Release and Production -->
        <div class="space-y-4">
            @if($careerData->release_date)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Release Date</h3>
                <p class="text-gray-700">{{ $careerData->release_date->format('F j, Y') }}</p>
            </div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Director</h3>
                <p class="text-gray-700">{{ $careerData->director_name }}</p>
            </div>
            @if($careerData->production_company)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Production Company</h3>
                <p class="text-gray-700">{{ $careerData->production_company }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Genres -->
@if($careerData->genres && count($careerData->genres) > 0)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Genres</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($careerData->genres as $genre)
                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                    {{ $genre }}
                </span>
            @endforeach
        </div>
    </div>
@endif

<!-- Box Office Collection -->
@if($careerData->box_office_collection)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Box Office Performance</h2>
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-purple-900 mb-1">Collection</h3>
                    <p class="text-2xl font-bold text-purple-700">
                        {{ number_format($careerData->box_office_collection) }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Commercial Success
                    </span>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Description -->
@if($careerData->description)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">About the Movie</h2>
        <div class="prose prose-lg max-w-none text-gray-700
                  prose-headings:font-bold prose-headings:text-gray-900
                  prose-p:leading-relaxed prose-p:text-gray-700
                  prose-ul:my-6 prose-li:my-2
                  prose-strong:text-gray-900 prose-strong:font-semibold
                  prose-a:text-purple-600 prose-a:no-underline hover:prose-a:underline
                  prose-blockquote:border-l-purple-400 prose-blockquote:bg-purple-50 prose-blockquote:py-1
                  prose-img:rounded-lg prose-img:shadow-sm">
            {!! $careerData->description !!}
        </div>
    </div>
@endif

<!-- Verification Status -->
<div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-3 {{ $careerData->is_verified ? 'text-green-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            @if($careerData->is_verified)
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            @else
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            @endif
        </svg>
        <div>
            <h3 class="font-semibold text-gray-900">
                {{ $careerData->is_verified ? 'Verified Information' : 'Unverified Information' }}
            </h3>
            <p class="text-sm text-gray-600">
                {{ $careerData->is_verified ? 'This information has been verified and is accurate.' : 'This information requires verification.' }}
            </p>
        </div>
    </div>
</div>
