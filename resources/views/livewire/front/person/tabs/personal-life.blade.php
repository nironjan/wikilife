<div class="p-2 md:p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Personal Life</h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Family & Relationships -->
        <div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Family & Relationships</h3>

            @if($person->relations->count() > 0)
            <div class="space-y-4">
                @foreach($person->relations->groupBy('relation_type') as $relationType => $relations)
                <div>
                    <h4 class="font-medium text-gray-900 mb-2 capitalize">{{ str_replace('_', ' ', $relationType) }}
                    </h4>
                    <div class="space-y-2">
                        @foreach($relations as $relation)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-900">
                                        {{ $relation->relatedPerson->name ?? $relation->related_person_name }}
                                    </span>

                                    <!-- Marital Status Badge -->
                                    @if($relation->marital_status && $relationType === 'spouse')
                                    <span
                                        class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full capitalize">
                                        {{ $relation->marital_status }}
                                    </span>
                                    @endif

                                    <!-- Death Indicator -->
                                    @if($relation->related_person_death_year)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                        d {{ $relation->related_person_death_year }}
                                    </span>
                                    @endif
                                </div>

                                <!-- Relationship Period -->
                                @if($relation->since || $relation->until)
                                <p class="text-sm text-gray-600 mt-1">
                                    @if($relation->since && $relation->until)
                                    ({{ $relation->since }} - {{ $relation->until }})
                                    @elseif($relation->since)
                                    (Since {{ $relation->since }})
                                    @elseif($relation->until)
                                    (Until {{ $relation->until }})
                                    @endif
                                </p>
                                @endif

                                <!-- Notes -->
                                @if($relation->notes)
                                <p class="text-sm text-gray-600 mt-1">{{ $relation->notes }}</p>
                                @endif
                            </div>

                            <!-- View Profile Link (only if related person exists) -->
                            @if($relation->relatedPerson)
                            <a href="{{ route('people.people.show', $relation->relatedPerson->slug) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium whitespace-nowrap ml-4">
                                View Profile
                            </a>
                            @else
                            <!-- Show placeholder if no profile exists -->
                            <span class="text-gray-400 text-sm whitespace-nowrap ml-4">
                                No Profile
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <p class="text-gray-500">Relationship information not available</p>
            </div>
            @endif

            <!-- Marital Status Summary -->
            @php
            $spouseRelations = $person->relations->where('relation_type', 'spouse');
            $currentSpouse = $spouseRelations->where('marital_status', 'married')->first();
            @endphp

            @if($currentSpouse)
            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                    <div>
                        <p class="text-green-800 font-medium">Currently Married</p>
                        <p class="text-green-700 text-sm">
                            to {{ $currentSpouse->relatedPerson->name ?? $currentSpouse->related_person_name }}
                            @if($currentSpouse->since)
                            since {{ $currentSpouse->since }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Education & Background -->
        <div class="space-y-6">
            <!-- Education -->
            @if($person->educations->count() > 0)
            <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Education</h3>
                <div class="space-y-3">
                    @foreach($person->educations as $education)
                    <div class="border-l-4 border-green-500 pl-4 py-2">
                        <h4 class="font-medium text-gray-900">{{ $education->degree }}</h4>
                        <p class="text-gray-600">{{ $education->institution }}</p>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>{{ $education->year }}</span>
                            @if($education->field_of_study)
                            <span>{{ $education->field_of_study }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Physical Stats -->
            @if($person->physical_stats && count($person->physical_stats) > 0)
            <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Physical Statistics</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($person->physical_stats as $key => $value)
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-sm text-gray-600 capitalize mb-1">
                            {{ str_replace('_', ' ', $key) }}
                        </div>
                        <div class="font-semibold text-gray-900">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Personal Details -->
            <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Personal Details</h3>
                <div class="space-y-3">
                    @if($person->religion)
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Religion</span>
                        <span class="font-medium text-gray-900">{{ $person->religion }}</span>
                    </div>
                    @endif

                    @if($person->caste)
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Caste</span>
                        <span class="font-medium text-gray-900">{{ $person->caste }}</span>
                    </div>
                    @endif

                    @if($person->ethnicity)
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Ethnicity</span>
                        <span class="font-medium text-gray-900">{{ $person->ethnicity }}</span>
                    </div>
                    @endif

                    @if($person->hometown)
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Hometown</span>
                        <span class="font-medium text-gray-900">{{ $person->hometown }}</span>
                    </div>
                    @endif

                    @if($person->nationality)
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Nationality</span>
                        <span class="font-medium text-gray-900">{{ $person->nationality }}</span>
                    </div>
                    @endif

                    <!-- Relationship Summary -->
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Relations</span>
                        <span class="font-medium text-gray-900">
                            {{ $person->relations->count() }} recorded
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
