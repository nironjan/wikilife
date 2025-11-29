<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" wire:poll.5s="refreshCounts">
    {{-- Header --}}
    <div class="bg-linear-to-r from-blue-600 to-purple-700 px-6 py-4">
        <h3 class="text-lg font-bold text-white flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            Browse By Profession
        </h3>
        <p class="text-blue-100 text-sm mt-1">Discover personalities by their field of work</p>
    </div>

    {{-- Categories List --}}
    <div class="divide-y divide-gray-100">
        @foreach($categories as $categoryKey => $category)
            @if($peopleCounts[$categoryKey] > 0)
            <div class="group hover:bg-blue-50 transition-colors duration-200">
                <a
                    href="{{ route('people.profession.details', $categoryKey) }}"
                    class="flex items-center justify-between px-6 py-4 text-gray-700 hover:text-blue-700 transition-colors duration-200"
                >
                    <div class="flex items-center space-x-3">
                        {{-- Category Icon --}}
                        <div class="w-10 h-10 bg-linear-to-br from-blue-100 to-purple-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                            {!! $category['icon'] !!}
                        </div>

                        {{-- Category Info --}}
                        <div>
                            <h4 class="font-semibold text-sm group-hover:text-blue-800">{{ $category['name'] }}</h4>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $peopleCounts[$categoryKey] }}
                                {{ $peopleCounts[$categoryKey] === 1 ? 'person' : 'people' }}
                            </p>
                        </div>
                    </div>

                    {{-- Arrow --}}
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            @endif
        @endforeach
    </div>

    {{-- Footer --}}
    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
        <a href="{{ route('people.people.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center group">
            View All Personalities
            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</div>

