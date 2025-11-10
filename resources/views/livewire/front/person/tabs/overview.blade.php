<div>
    <div class="p-2 md:p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Who is {{ $person->name }} ?</h2>

        <!-- About Preview -->
        @if($person->about)
        <div class="mb-6">
            <div class="prose max-w-none text-gray-700">
                {!! $person->about !!}
            </div>
        </div>
        @endif

        <!-- Latest Update -->
        @if($person->latestUpdates->count() > 0)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Latest Updates</h3>
                <div class="space-y-4">
                    @foreach($person->latestUpdates->where('status', 'published')->where('is_approved', true)->take(3) as $update)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200 bg-blue-50">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-gray-900 text-base">{{ $update->title }}</h4>
                                @if($update->update_type)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">
                                        {{ $update->update_type }}
                                    </span>
                                @endif
                            </div>

                            @if($update->html_content)
                                <div class="prose prose-sm max-w-none text-gray-600 mb-3">
                                    {!! Str::limit(strip_tags($update->html_content), 150) !!}
                                </div>
                            @endif

                            <div class="flex justify-between items-center text-sm text-gray-500">
                                <span>{{ $update->created_at->format('M j, Y') }}</span>
                                <!-- Fixed route call -->
                                <a href="{{ route('people.updates.show', ['personSlug' => $person->slug, 'slug' => $update->slug]) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Read more →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($person->latestUpdates->where('status', 'published')->where('is_approved', true)->count() > 3)
                    <div class="mt-4 text-center">
                        <button wire:click="setActiveTab('latest_updates')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View all updates →
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <!-- Key Information Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Career Highlights -->
            @if($this->getCareerItemsCount() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Career Highlights</h3>
                <div class="space-y-2">
                    @foreach($person->professions as $profession)
                    <div class="flex items-center text-gray-700">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        {{ $profession }}
                    </div>
                    @endforeach
                </div>
                <button wire:click="setActiveTab('career')"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2">
                    View full career →
                </button>
            </div>
            @endif

            <!-- Personal Life Preview -->
            @if($this->getPersonalLifeItemsCount() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Personal Life</h3>
                <div class="space-y-2 text-sm text-gray-700">
                    @if($person->getSpouse())
                    <div><strong>Spouse:</strong> {{ $person->getSpouse()->relatedPerson->name ?? 'N/A' }}</div>
                    @endif
                    @if($person->getChildren()->count() > 0)
                    <div><strong>Children:</strong> {{ $person->getChildren()->count() }}</div>
                    @endif
                    @if($person->educations->count() > 0)
                    <div><strong>Education:</strong> {{ $person->educations->first()->degree ?? 'N/A' }}</div>
                    @endif
                </div>
                <button wire:click="setActiveTab('personal_life')"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2">
                    View details →
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
