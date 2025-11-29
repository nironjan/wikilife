{{-- Work Details --}}
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Work Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Work Type</h3>
                <p class="text-lg text-yellow-700 font-medium">{{ $careerData->work_type }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Role</h3>
                <p class="text-gray-700">{{ $careerData->role }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Media Type</h3>
                <p class="text-gray-700">{{ $careerData->media_type }}</p>
            </div>
        </div>

        <div class="space-y-4">
            @if($careerData->publishing_year)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Publishing Year</h3>
                <p class="text-gray-700">{{ $careerData->publishing_year }}</p>
            </div>
            @endif
            @if($careerData->language)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Language</h3>
                <p class="text-gray-700">{{ $careerData->language }}</p>
            </div>
            @endif
            @if($careerData->genre)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Genre</h3>
                <p class="text-gray-700">{{ $careerData->genre }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Cover Image --}}
@if($careerData->cover_image_url)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Cover Image</h2>
        <div class="flex justify-center">
            <img src="{{ $careerData->cover_image_url }}"
                 alt="{{ $careerData->display_title }}"
                 class="max-w-sm rounded-lg shadow-lg border border-gray-200">
        </div>
    </div>
@endif

{{-- ISBN --}}
@if($careerData->isbn)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Publication Information</h2>
        <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-yellow-900">ISBN</h3>
                    <p class="text-yellow-800 font-mono">{{ $careerData->isbn }}</p>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Description --}}
@if($careerData->description)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">About the Work</h2>
        <div class="prose prose-lg max-w-none text-gray-700
                  prose-headings:font-bold prose-headings:text-gray-900
                  prose-p:leading-relaxed prose-p:text-gray-700
                  prose-ul:my-6 prose-li:my-2
                  prose-strong:text-gray-900 prose-strong:font-semibold
                  prose-a:text-yellow-600 prose-a:no-underline hover:prose-a:underline
                  prose-blockquote:border-l-yellow-400 prose-blockquote:bg-yellow-50 prose-blockquote:py-1
                  prose-img:rounded-lg prose-img:shadow-sm">
            {!! $careerData->description !!}
        </div>
    </div>
@endif

{{-- Career Timeline --}}
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Career Timeline</h2>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if($careerData->start_date)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Start Date</h3>
                <p class="text-gray-700">{{ $careerData->start_date->format('F j, Y') }}</p>
            </div>
            @endif
            @if($careerData->end_date)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">End Date</h3>
                <p class="text-gray-700">{{ $careerData->end_date->format('F j, Y') }}</p>
            </div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Career Status</h3>
                <p class="text-gray-700">{{ $careerData->career_status }}</p>
            </div>
        </div>
    </div>
</div>

{{-- External Link --}}
@if($careerData->link)
    <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
            External Link
        </h3>
        <a href="{{ $careerData->link }}" target="_blank"
           class="text-yellow-600 hover:text-yellow-800 text-sm break-all font-medium">
            {{ $careerData->link }}
        </a>
    </div>
@endif

{{-- Verification Status --}}
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
                {{ $careerData->is_verified ? 'This literary work information has been verified.' : 'This information requires verification.' }}
            </p>
        </div>
    </div>
</div>
