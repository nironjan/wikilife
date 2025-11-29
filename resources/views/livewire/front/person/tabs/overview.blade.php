<div>
    <div class="p-3 sm:p-4 md:p-6">
        {{-- Main Heading --}}
        <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Who is {{ $person->name }}?</h2>

        {{-- About Preview --}}
        @if($person->about)
            <div class="mb-4 sm:mb-6">
                <div class="prose prose-sm sm:prose-base max-w-none text-gray-700">
                    {!! $person->about !!}
                </div>
            </div>
        @endif

        {{-- Key Information Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            {{-- Career Highlights --}}
            @if($this->getCareerItemsCount() > 0)
                <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-3">Career Highlights</h3>
                    <div class="space-y-1.5 sm:space-y-2">
                        @foreach($person->professions as $profession)
                            <div class="flex items-center text-gray-700 text-sm sm:text-base">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                                <span class="truncate">{{ $profession }}</span>
                            </div>
                        @endforeach
                    </div>
                    <button wire:click="setActiveTab('career')"
                        class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium mt-2 sm:mt-3 cursor-pointer">
                        View full career →
                    </button>
                </div>
            @endif

            {{-- Personal Life Preview --}}
            @if($this->getPersonalLifeItemsCount() > 0)
                <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-3">Personal Life</h3>
                    <div class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm text-gray-700">
                        @if($person->getSpouse())
                            <div class="flex items-start">
                                <strong class="w-12 sm:w-16 flex-shrink-0">Spouse:</strong>
                                <span class="flex-1">{{ $person->getSpouse()?->display_related_person_name ?? 'N/A' }}</span>
                            </div>
                        @endif
                        @if($person->getChildren()->count() > 0)
                            <div class="flex items-start">
                                <strong class="w-12 sm:w-16 flex-shrink-0">Children:</strong>
                                <span class="flex-1">{{ $person->getChildren()->count() }}</span>
                            </div>
                        @endif
                        @if($person->educations->count() > 0)
                            <div class="flex items-start">
                                <strong class="w-12 sm:w-16 flex-shrink-0">Education:</strong>
                                <span class="flex-1">{{ $person->educations->first()->degree ?? 'N/A' }}</span>
                            </div>
                        @endif
                    </div>
                    <button wire:click="setActiveTab('personal_life')"
                        class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium mt-2 sm:mt-3 cursor-pointer">
                        View details →
                    </button>
                </div>
            @endif
        </div>

        {{-- Early Life --}}
        @if($person->early_life)
            <div class="mt-4 sm:mt-6">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">Early Life of {{ $person->name }}</h2>
                <div class="mb-4 sm:mb-6">
                    <div class="prose prose-sm sm:prose-base max-w-none text-gray-700">
                        {!! $person->early_life !!}
                    </div>
                </div>
            </div>
        @endif

        {{-- Latest Update --}}
        @if($person->latestUpdates->count() > 0)
            <div class="mb-4 sm:mb-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Latest Updates & Announcements</h3>
                <div class="space-y-3 sm:space-y-4">
                    @foreach($person->latestUpdates->where('status', 'published')->where('is_approved', true)->take(3) as $update)
                        <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow duration-200 bg-blue-50">
                            <div class="flex justify-between items-start mb-2 gap-2">
                                <h4 class="font-semibold text-gray-900 text-sm sm:text-base leading-tight flex-1">{{ $update->title }}</h4>
                                @if($update->update_type)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium flex-shrink-0">
                                        {{ $update->update_type }}
                                    </span>
                                @endif
                            </div>

                            @if($update->html_content)
                                <div class="prose prose-xs sm:prose-sm max-w-none text-gray-600 mb-2 sm:mb-3">
                                    {!! Str::limit(strip_tags($update->html_content), 120) !!}
                                </div>
                            @endif

                            <div class="flex justify-between items-center text-xs sm:text-sm text-gray-500">
                                <span>{{ $update->created_at->format('M j, Y') }}</span>
                                <a href="{{ route('people.updates.show', ['personSlug' => $person->slug, 'slug' => $update->slug]) }}"
                                    class="text-blue-600 hover:text-blue-800 font-medium">
                                    Read more →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($person->latestUpdates->where('status', 'published')->where('is_approved', true)->count() > 3)
                    <div class="mt-3 sm:mt-4 text-center">
                        <button wire:click="setActiveTab('latest_updates')"
                            class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium cursor-pointer">
                            View all updates →
                        </button>
                    </div>
                @endif
            </div>
        @endif

        {{-- Lesser Known Facts Section --}}
        @if($person->lesserKnownFacts->count() > 0)
            <div class="my-4 sm:my-6 md:my-8" itemscope itemtype="https://schema.org/FAQPage">
                <div class="flex items-center justify-between mb-3 sm:mb-4 md:mb-6 gap-2">
                    <h3 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 leading-tight">
                        Lesser Known Facts {{ $person->name }}
                    </h3>

                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full whitespace-nowrap flex-shrink-0">
                        {{ $person->lesserKnownFacts->count() }} facts
                    </span>
                </div>

                <div class="space-y-3 sm:space-y-4">
                    @foreach($person->lesserKnownFacts->take(5) as $index => $fact)
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-white hover:shadow-md transition-all duration-300"
                            itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
                            {{-- Fact Header - Always Visible --}}
                            <button
                                class="w-full px-3 sm:px-4 md:px-6 py-3 sm:py-4 text-left hover:bg-gray-50 transition-colors duration-200 flex justify-between items-start cursor-pointer"
                                onclick="toggleFact({{ $fact->id }})" aria-expanded="false"
                                aria-controls="fact-content-{{ $fact->id }}">
                                <div class="flex items-start gap-2 sm:gap-3 md:gap-4 flex-1">
                                    {{-- Fact Icon --}}
                                    <div class="shrink-0 w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 rounded-lg flex items-center justify-center mt-0.5 sm:mt-1">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>

                                    {{-- Fact Title & Category --}}
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2 md:gap-3 mb-1 sm:mb-2 space-y-1 sm:space-y-0">
                                            <h4 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900 leading-tight" itemprop="name">
                                                {{ $fact->title }}
                                            </h4>
                                            @if($fact->category)
                                                <span class="px-1.5 py-0.5 sm:px-2 sm:py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full capitalize flex-shrink-0">
                                                    {{ $fact->category }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Preview Text (Always visible for SEO) --}}
                                        <div class="prose prose-xs sm:prose-sm text-gray-600 max-w-none">
                                            <p itemprop="text">{!! Str::limit(strip_tags($fact->fact), 70) !!}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Expand/Collapse Icon --}}
                                <div class="flex-shrink-0 ml-2 sm:ml-4">
                                    <svg id="icon-{{ $fact->id }}"
                                        class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 transition-transform duration-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>

                            {{-- Expandable Content --}}
                            <div id="fact-content-{{ $fact->id }}"
                                role="region"
                                aria-labelledby="fact-title-{{ $fact->id }}"
                                class="hidden px-3 sm:px-4 md:px-6 bg-gray-50 pb-3 sm:pb-4 border-t border-gray-100"
                                itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer">
                                <div class="pt-3 sm:pt-4 md:pl-10 lg:pl-14">
                                    <div class="prose prose-sm sm:prose-base max-w-none text-gray-700" itemprop="text">
                                        {!! $fact->fact !!}
                                    </div>

                                    {{-- Fact Metadata --}}
                                    <div class="mt-3 sm:mt-4 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0 text-xs text-gray-500">
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Added {{ $fact->created_at->format('M j, Y') }}
                                        </div>
                                        @if($fact->category)
                                            <div class="flex items-center">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                                {{ ucfirst($fact->category) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- View All Facts Button --}}
                @if($person->lesserKnownFacts->count() > 5)
                    <div class="mt-4 sm:mt-6 text-center">
                        <button wire:click="setActiveTab('lesser_known_facts')"
                            class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm sm:text-base font-medium rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            View All {{ $person->lesserKnownFacts->count() }} Facts
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <style>
        .prose {
            line-height: 1.6;
        }

        .prose p {
            margin-bottom: 1em;
        }

        .prose ul,
        .prose ol {
            margin-bottom: 1em;
            padding-left: 1.5em;
        }

        .prose li {
            margin-bottom: 0.5em;
        }

        .prose-xs {
            font-size: 0.75rem;
            line-height: 1.5;
        }

        .prose-xs p {
            margin-bottom: 0.75em;
        }
    </style>
</div>

<script>
    function toggleFact(factId) {
        const content = document.getElementById(`fact-content-${factId}`);
        const icon = document.getElementById(`icon-${factId}`);

        if (content.classList.contains('hidden')) {
            // Expand
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
            content.setAttribute('aria-expanded', 'true');
        } else {
            // Collapse
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
            content.setAttribute('aria-expanded', 'false');
        }
    }

    // Initialize first 2 facts as expanded by default for better SEO visibility
    document.addEventListener('DOMContentLoaded', function () {
        @if($person->lesserKnownFacts->count() > 0)
            // Expand first 2 facts
            const firstFacts = @json($person->lesserKnownFacts->take(2)->pluck('id'));
            firstFacts.forEach(factId => {
                const content = document.getElementById(`fact-content-${factId}`);
                const icon = document.getElementById(`icon-${factId}`);
                if (content && icon) {
                    content.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                    content.setAttribute('aria-expanded', 'true');
                }
            });
        @endif
    });
</script>
