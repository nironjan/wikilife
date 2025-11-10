<!-- Office Type -->
@if($careerData->office_type)
    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <h3 class="font-semibold text-blue-900 mb-2 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Office Type
        </h3>
        <p class="text-blue-800 font-medium">{{ $careerData->office_type }}</p>
    </div>
@endif

<!-- Political Journey -->
@if($careerData->political_journey)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Political Journey</h2>
        <div class="prose prose-lg max-w-none text-gray-700
                  prose-headings:font-bold prose-headings:text-gray-900
                  prose-p:leading-relaxed prose-p:text-gray-700
                  prose-ul:my-6 prose-li:my-2
                  prose-strong:text-gray-900 prose-strong:font-semibold
                  prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                  prose-blockquote:border-l-blue-400 prose-blockquote:bg-blue-50 prose-blockquote:py-1
                  prose-img:rounded-lg prose-img:shadow-sm">
            {!! $careerData->political_journey !!}
        </div>
    </div>
@endif

<!-- Notable Achievements -->
@if($careerData->notable_achievements)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Notable Achievements</h2>
        <div class="prose prose-lg max-w-none text-gray-700">
            {!! $careerData->notable_achievements !!}
        </div>
    </div>
@endif

<!-- Major Initiatives -->
@if($careerData->major_initiatives)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Major Initiatives</h2>
        <div class="prose prose-lg max-w-none text-gray-700">
            {!! $careerData->major_initiatives !!}
        </div>
    </div>
@endif

<!-- Memberships -->
@if($careerData->memberships && count($careerData->memberships) > 0)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Committee Memberships</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($careerData->memberships as $membership)
                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-gray-700">{{ $membership }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Party Timeline -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Party Affiliation</h2>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Political Party</h3>
                <p class="text-lg text-blue-700 font-medium">{{ $careerData->political_party }}</p>
            </div>
            @if($careerData->joining_date)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Party Member Since</h3>
                <p class="text-gray-700">{{ $careerData->joining_date->format('F j, Y') }}</p>
            </div>
            @endif
            @if($careerData->end_date)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Party Membership Ended</h3>
                <p class="text-gray-700">{{ $careerData->end_date->format('F j, Y') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Notes -->
@if($careerData->notes)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Additional Notes</h2>
        <div class="prose prose-lg max-w-none text-gray-700">
            {!! $careerData->notes !!}
        </div>
    </div>
@endif
