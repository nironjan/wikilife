<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($popularPersons as $person)
    <article class="bg-white rounded-lg shadow overflow-hidden group">
        {{-- Simplified compact layout --}}
        <a href="{{ route('people.people.show', $person->slug) }}" class="block p-4">
            <div class="flex items-center space-x-4">
                {{-- Image --}}
                <div class="w-16 h-16 rounded-full overflow-hidden shrink-0">
                    @if($person->profile_image_url)
                    <img src="{{ $person->imageSize(64, 64, 80) }}"
                         alt="{{ $person->name }}"
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 truncate">{{ $person->name }}</h3>
                    @if($person->primary_profession)
                    <p class="text-sm text-gray-600 truncate">{{ $person->primary_profession }}</p>
                    @endif
                </div>
            </div>
        </a>
    </article>
    @endforeach
</div>
