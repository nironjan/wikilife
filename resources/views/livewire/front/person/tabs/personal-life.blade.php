<div class="p-2 md:p-6">
    <h2 class="text-md md:text-2xl font-bold text-gray-900 mb-6">Personal Life of {{ $person->name }}</h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Family & Relationships --}}
        <div>
            <h3 class="text-base md:text-xl font-semibold text-gray-900 mb-4">Family & Relationships</h3>

            @if($person->relations->count() > 0)
                <div class="space-y-4">
                    @foreach($person->relations->groupBy('relation_type') as $relationType => $relations)
                        <div>
                            <h4 class="md:font-medium text-sm md:text-base font-semibold text-gray-900 mb-2 capitalize">{{ str_replace('_', ' ', $relationType) }}
                            </h4>
                            <div class="space-y-2">
                                @foreach($relations as $relation)
                                    <div class="flex flex-col md:items-start p-3 bg-gray-50 rounded-lg">
                                        {{-- Left Container: Profile Picture + Basic Info --}}
                                        <div class="flex items-start space-x-3 flex-shrink-0">
                                            {{-- Profile Picture --}}
                                            <div class="flex-shrink-0">
                                                @if($relation->relatedPerson && $relation->relatedPerson->profile_image_url)
                                                    <img src="{{ $relation->relatedPerson->imageSize(60, 60) }}"
                                                        alt="{{ $relation->relatedPerson->name }}"
                                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover border-2 border-white shadow-sm">
                                                @else
                                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center border-2 border-white shadow-sm">
                                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Basic Info Container --}}
                                            <div class="flex flex-col space-y-1 min-w-0">
                                                {{-- Name and Marital Status --}}
                                                <span class="font-medium text-gray-900 text-sm sm:text-base break-words">
                                                    {{ $relation->relatedPerson->name ?? $relation->related_person_name }}
                                                </span>
                                                <div class="flex flex-row items-center gap-1 sm:gap-2">
                                                    {{-- Marital Status Badge --}}
                                                    @if($relation->marital_status && $relationType === 'spouse')
                                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full capitalize whitespace-nowrap flex-shrink-0 self-start sm:self-auto">
                                                            {{ $relation->marital_status }}
                                                        </span>
                                                    @endif
                                                    {{-- Relationship Period --}}
                                                    @if($relation->since || $relation->until)
                                                        <p class="text-xs italic sm:text-sm text-gray-600">
                                                            @if($relation->since && $relation->until)
                                                                {{ $relation->since }} - {{ $relation->until }}
                                                            @elseif($relation->since)
                                                                Since {{ $relation->since }}
                                                            @elseif($relation->until)
                                                                Until {{ $relation->until }}
                                                            @endif
                                                        </p>
                                                    @endif
                                                </div>


                                                {{-- Death Indicator --}}
                                                @if($relation->related_person_death_year)
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full whitespace-nowrap self-start sm:self-auto flex-shrink-0">
                                                        d {{ $relation->related_person_death_year }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Right Container: Additional Content and Actions --}}
                                        <div class="flex flex-col md:items-end space-y-2 ml-4 flex-1 min-w-0">
                                            {{-- Notes Content --}}
                                            @if($relation->notes)
                                                <div class="text-sm text-gray-600 break-words w-full">
                                                    {{ $relation->notes }}
                                                </div>
                                            @endif


            </div>
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

            {{-- Marital Status Summary --}}
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

        <div class="space-y-6">
            {{-- Assets & Wealth Section --}}
            @if($person->assets && $person->assets->count() > 0)
                <div>
                    <h3 class="text-base md:text-xl font-semibold text-gray-900 mb-4">Assets & Wealth</h3>
                    <div class="space-y-4">
                        {{-- Assets & Wealth Section --}}
                        @if($person->assets)
                            @php $asset = $person->assets; @endphp
                            <div>
                                <div class="bg-linear-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-5">
                                    {{-- Header with Year --}}
                                    <div class="flex flex-col gap-1 md:flex-row md:items-center justify-between mb-4">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h4 class="text-sm md:text-base font-bold text-gray-900">Financial Overview</h4>
                                        </div>
                                        @if($asset->year_estimated)
                                            <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs md:text-sm md:font-medium rounded-full">
                                                {{ $asset->estimated_year_label }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Net Worth - Main Highlight --}}
                                    @if($asset->net_worth)
                                        <div class="text-center mb-4">
                                            <div class="text-sm text-gray-600 mb-1">Estimated Net Worth</div>
                                            <div class="text-xl md:text-3xl font-bold text-gray-900">
                                                {{ $asset->formatted_net_worth }}
                                            </div>
                                            @if($asset->is_high_net_worth)
                                                <div
                                                    class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs md:text-sm md:font-medium rounded-full mt-2">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    High Net Worth Individual
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Financial Details Grid --}}
                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                        @if($asset->income)
                                            <div class="text-center p-4 bg-white rounded-lg border border-amber-100 shadow-sm">
                                                <div class="text-xs text-gray-500 mb-2 uppercase tracking-wide">Annual Income</div>
                                                <div class="font-semibold text-gray-900 text-md md:text-lg">
                                                    {{ $asset->formatted_income }}
                                                </div>
                                                @if($asset->income_source)
                                                    <div class="text-xs text-gray-600 mt-2">{{ $asset->income_source }}</div>
                                                @endif
                                            </div>
                                        @endif

                                        @if($asset->current_assets)
                                            <div class="text-center p-4 bg-white rounded-lg border border-amber-100 shadow-sm">
                                                <div class="text-xs text-gray-500 mb-2 uppercase tracking-wide">Current Assets</div>
                                                <div class="font-semibold text-gray-900 text-md md:text-lg">
                                                    {{ $asset->formatted_current_assets }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Additional Financial Metrics --}}
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-center">
                                        <div class="p-3 bg-amber-100 rounded-lg">
                                            <div class="text-xs text-amber-800 font-medium">Currency</div>
                                            <div class="text-sm font-semibold text-amber-900">{{ getCurrencySymbol($asset->currency) }}</div>
                                        </div>
                                        <div class="p-3 bg-amber-100 rounded-lg">
                                            <div class="text-xs text-amber-800 font-medium">Year</div>
                                            <div class="text-sm font-semibold text-amber-900">{{ $asset->year_estimated }}</div>
                                        </div>
                                        <div class="p-3 bg-amber-100 rounded-lg">
                                            <div class="text-xs text-amber-800 font-medium">Data Source</div>
                                            <div class="text-sm font-semibold text-amber-900">1 Record</div>
                                        </div>
                                    </div>

                                    {{-- References --}}
                                    @if($asset->references_list && count($asset->references_list) > 0)
                                        <div class="mt-6 pt-4 border-t border-amber-200">
                                            <div class="flex items-center mb-3">
                                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                                <span class="text-sm font-medium text-gray-700">Sources & References</span>
                                            </div>
                                            <div class="space-y-2">
                                                @foreach($asset->references_list as $reference)
                                                    @if(is_array($reference) && isset($reference['url']))
                                                        <a href="{{ $reference['url'] }}" target="_blank"
                                                            class="text-blue-600 hover:text-blue-800 flex items-start">
                                                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                            </svg>
                                                            <span class="break-words">{{ $reference['name'] ?? 'Source' }}</span>
                                                        </a>
                                                    @elseif(is_string($reference))
                                                        <div class="text-gray-600 flex items-start">
                                                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                            </svg>
                                                            <span class="break-words">{{ $reference }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Data Disclaimer --}}
                                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-xs md:text-sm text-blue-700">
                                            <strong>Note:</strong> Financial figures are estimates based on available public
                                            records and declarations.
                                            Actual values may vary and should be verified through official sources.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Empty State for Assets --}}
                            <div>
                                <h3 class="text-md md:text-xl font-semibold text-gray-900 mb-4">Assets & Wealth</h3>
                                <div class="text-center py-12 bg-gray-50 rounded-lg border border-gray-200">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-500 text-lg mb-2">Wealth information not available</p>
                                    <p class="text-gray-400 text-sm">Financial data for {{ $person->name }} has not been
                                        recorded yet.</p>
                                </div>
                            </div>
                        @endif
            </div>
        </div>
            @else
                {{-- Empty State for Assets --}}
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Assets & Wealth</h3>
                    <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500">Wealth and asset information not available</p>
                    </div>
                </div>
            @endif

            {{-- Education --}}
            @if($person->educations->count() > 0)
                <div>
                    <h3 class="text-base md:text-xl font-semibold text-gray-900 mb-4">Education</h3>
                    <div class="space-y-3">
                        @foreach($person->educations as $education)
                            <div class="border-l-3 border-green-500 pl-4 py-2">
                                <h4 class="font-medium text-gray-900">{{ $education->degree }}</h4>
                                <p class="text-gray-600 text-sm">{{ $education->institution }}</p>
                                <div class="flex justify-between text-xs font-semibold md:text-sm text-gray-500">
                                    <span>{{ $education->duration }}</span>
                                    @if($education->field_of_study)
                                        <span>{{ $education->field_of_study }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Physical Stats --}}
            @if($person->physical_stats && count($person->physical_stats) > 0)
                <div>
                    <h3 class="text-base md:text-xl font-semibold text-gray-900 mb-4">Physical Statistics</h3>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($person->physical_stats as $key => $value)
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <div class="text-sm text-gray-600 capitalize mb-1">
                                    {{ str_replace('_', ' ', $key) }}
                                </div>
                                <div class="text-sm md:text-base font-semibold text-gray-900">{{ $value }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Personal Details --}}
            <div>
                <h3 class="text-base md:text-xl font-semibold text-gray-900 mb-4">Personal Details</h3>
                <div class="space-y-3">
                    @if($person->religion)
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-sm md:text-base text-gray-600">Religion</span>
                            <span class="text-sm md:text-base font-medium text-gray-900">{{ $person->religion }}</span>
                        </div>
                    @endif

                    @if($person->caste)
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-sm md:text-base text-gray-600">Caste</span>
                            <span class="text-sm md:text-base font-medium text-gray-900">{{ $person->caste }}</span>
                        </div>
                    @endif

                    @if($person->ethnicity)
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-sm md:text-base text-gray-600">Ethnicity</span>
                            <span class="text-sm md:text-base font-medium text-gray-900">{{ $person->ethnicity }}</span>
                        </div>
                    @endif

                    @if($person->hometown)
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-sm md:text-base text-gray-600">Hometown</span>
                            <span class="text-sm md:text-base font-medium text-gray-900">{{ $person->hometown }}</span>
                        </div>
                    @endif

                    @if($person->nationality)
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-sm md:text-base text-gray-600">Nationality</span>
                            <span class="text-sm md:text-base font-medium text-gray-900">{{ $person->nationality }}</span>
                        </div>
                    @endif

                    {{-- Relationship Summary --}}
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-sm md:text-base text-gray-600">Relations</span>
                        <span class="text-sm md:text-base font-medium text-gray-900">
                            {{ $person->relations->count() }} recorded
                        </span>
                    </div>

                    {{-- Assets Summary --}}
                    @if($person->assets && $person->assets->count() > 0)
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-sm md:text-base text-gray-600">Wealth Records</span>
                            <span class="text-sm md:text-base font-medium text-gray-900">
                                {{ $person->assets->count() }} estimates
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
