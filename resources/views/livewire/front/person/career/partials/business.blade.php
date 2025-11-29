{{-- Company Details --}}
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Company Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Company Name</h3>
                <p class="text-lg text-indigo-700 font-medium">{{ $careerData->company_name }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Role</h3>
                <p class="text-gray-700">{{ $careerData->role }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Industry</h3>
                <p class="text-gray-700">{{ $careerData->industry }}</p>
            </div>
        </div>

        <div class="space-y-4">
            @if($careerData->founding_date)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Founded</h3>
                <p class="text-gray-700">{{ $careerData->founding_date->format('F j, Y') }}</p>
                <p class="text-sm text-gray-500">({{ $careerData->company_age }} years ago)</p>
            </div>
            @endif
            @if($careerData->headquarters_location)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Headquarters</h3>
                <p class="text-gray-700">{{ $careerData->headquarters_location }}</p>
            </div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Founder</h3>
                <p class="text-gray-700">{{ $careerData->is_founder ? 'Yes' : 'No' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Investment Information --}}
@if($careerData->investment)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Investment & Funding</h2>
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-indigo-900 mb-1">Investment Amount</h3>
                    <p class="text-2xl font-bold text-indigo-700">
                        ${{ number_format($careerData->investment) }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Funded
                    </span>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Timeline --}}
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Career Timeline</h2>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if($careerData->joining_date)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Joining Date</h3>
                <p class="text-gray-700">{{ $careerData->joining_date->format('F j, Y') }}</p>
            </div>
            @endif
            @if($careerData->exit_date)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Exit Date</h3>
                <p class="text-gray-700">{{ $careerData->exit_date->format('F j, Y') }}</p>
            </div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Current Status</h3>
                <p class="text-gray-700">
                    <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium
                        {{ $careerData->status === 'active' ? 'bg-green-100 text-green-800' :
                           ($careerData->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($careerData->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Notable Achievements --}}
@if($careerData->notable_achievements && count($careerData->notable_achievements) > 0)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Business Achievements</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($careerData->notable_achievements as $achievement)
                <div class="flex items-start p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                    <svg class="w-5 h-5 text-indigo-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-indigo-800">{{ $achievement }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Website Link --}}
@if($careerData->website_url)
    <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
            </svg>
            Official Website
        </h3>
        <a href="{{ $careerData->website_url }}" target="_blank"
           class="text-indigo-600 hover:text-indigo-800 text-sm break-all font-medium">
            {{ $careerData->website_url }}
        </a>
    </div>
@endif
