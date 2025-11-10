<!-- Sport Details -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Sports Details</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="space-y-4">
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Sport</h3>
                <p class="text-lg text-green-700 font-medium">{{ $careerData->sport }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Team</h3>
                <p class="text-gray-700">{{ $careerData->team }}</p>
            </div>
            @if($careerData->jersey_number)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Jersey Number</h3>
                <p class="text-2xl font-bold text-gray-900">#{{ $careerData->jersey_number }}</p>
            </div>
            @endif
        </div>

        <div class="space-y-4">
            @if($careerData->debut_date)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Debut Date</h3>
                <p class="text-gray-700">{{ $careerData->debut_date->format('F j, Y') }}</p>
            </div>
            @endif
            @if($careerData->retirement_date)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Retirement Date</h3>
                <p class="text-gray-700">{{ $careerData->retirement_date->format('F j, Y') }}</p>
            </div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Career Status</h3>
                <p class="text-gray-700">{{ $careerData->career_status }}</p>
            </div>
        </div>

        <div class="space-y-4">
            @if($careerData->coach_name)
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Coach</h3>
                <p class="text-gray-700">{{ $careerData->coach_name }}</p>
            </div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Player Type</h3>
                <p class="text-gray-700">
                    {{ $careerData->international_player ? 'International Player' : 'Domestic Player' }}
                </p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Career Duration</h3>
                <p class="text-gray-700">{{ $careerData->career_duration }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics -->
@if($careerData->stats && count($careerData->stats) > 0)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Career Statistics</h2>
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6">
                @foreach($careerData->stats as $statName => $statValue)
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 mb-1">{{ $statValue }}</div>
                        <div class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $statName) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Achievements -->
@if($careerData->achievements && count($careerData->achievements) > 0)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Career Achievements</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($careerData->achievements as $achievement)
                <div class="flex items-start p-4 bg-green-50 rounded-lg border border-green-200">
                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-green-800">{{ $achievement }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Notable Events -->
@if($careerData->notable_events && count($careerData->notable_events) > 0)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Notable Events</h2>
        <div class="space-y-3">
            @foreach($careerData->notable_events as $event)
                <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <svg class="w-4 h-4 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span class="text-blue-800">{{ $event }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Leagues Participated -->
@if($careerData->leagues_participated && count($careerData->leagues_participated) > 0)
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Leagues & Tournaments</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($careerData->leagues_participated as $league)
                <span class="px-3 py-2 bg-gray-100 text-gray-800 rounded-lg text-sm font-medium border border-gray-200">
                    {{ $league }}
                </span>
            @endforeach
        </div>
    </div>
@endif
