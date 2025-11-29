@if($relatedPersons->count() > 0)
<div class="max-w-6xl mx-auto mt-6 border-t border-gray-200 pt-4">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-md text-gray-900">
            @if ($person->state_code)
                Related Persons from
                <span class="font-bold text-gray-700 uppercase">
                    {{ $person->state_name }}
                </span>
            @else
                Related Persons
            @endif
        </h2>
    </div>


    {{-- Grid with 5 columns on large screens --}}
    <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-7 gap-4">
        @foreach($relatedPersons as $relatedPerson)
            <a href="{{ route('people.people.show', $relatedPerson->slug) }}"
               class="group bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden hover:border-blue-300">

                {{-- Profile Image --}}
                <div class="relative h-32 bg-gray-100 overflow-hidden">
                    @if($relatedPerson->profile_image)
                        <img src="{{ $relatedPerson->imageSize(300, 300) }}"
                             alt="{{ $relatedPerson->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                </div>


            </a>
        @endforeach
    </div>

</div>
@endif
