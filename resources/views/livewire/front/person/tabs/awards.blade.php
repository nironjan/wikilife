<div class="p-2 md:p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Awards & Achievements</h2>

    @if($person->awards->count() > 0)
    <div class="space-y-6">
        @foreach($person->awards->groupBy('category') as $category => $awards)
        <div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b pb-2">{{ $category ?: 'Awards' }}</h3>
            <div class="space-y-4">
                @foreach($awards as $award)
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">{{ $award->title }}</h4>
                        <p class="text-gray-600">{{ $award->organization }}</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($award->year)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                {{ $award->year }}
                            </span>
                            @endif
                            @if($award->category)
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                {{ $award->category }}
                            </span>
                            @endif
                        </div>
                        @if($award->description)
                        <p class="text-gray-700 mt-2 text-sm">{{ $award->description }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No Awards Recorded</h3>
        <p class="text-gray-500">Award information for {{ $person->display_name }} is not available yet.</p>
    </div>
    @endif
</div>
