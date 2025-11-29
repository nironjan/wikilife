<div class="p-3 md:p-6">
    <h2 class="text-md md:text-2xl font-bold text-gray-900 mb-6">Awards & Achievements of {{ $person->name }}</h2>

   @if($person->awards->count() > 0)
    <div class="space-y-6">
        @foreach($person->awards->groupBy('category') as $category => $awards)
        <div>
            <h3 class="md:font-medium text-sm md:text-base font-semibold text-gray-900 mb-4 border-b pb-2">{{ $category ?: 'Awards' }}</h3>
            <div class="space-y-4">
                @foreach($awards as $award)
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    {{-- Award Icon and Name Container --}}
                    <div class="flex items-start space-x-4 p-4 bg-gradient-to-r from-yellow-50 to-amber-50 border-b border-gray-200">
                        <div class="flex-shrink-0 w-6 h-6 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 text-md md:text-lg">{{ $award->award_name }}</h4>
                            <p class="text-gray-600 text-sm">{{ $award->organization }}</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @if($award->year)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    {{ $award->year }}
                                </span>
                                @endif
                                @if($award->category && !$category)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                    {{ $award->category }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Description Container --}}
                    @if($award->description)
                    <div class="p-4 bg-gray-50">
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $award->description }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="text-center py-8 text-gray-500">
        <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="mt-2">No awards information available</p>
    </div>
@endif
</div>
