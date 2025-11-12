<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
                <a href="{{ url('/') }}" class="hover:text-blue-600">Home</a>
                <span class="text-gray-400">›</span>
                <a href="{{ route('people.people.index') }}" class="hover:text-blue-600">People</a>
                <span class="text-gray-400">›</span>
                <a href="{{ route('people.people.show', $person->slug) }}" class="hover:text-blue-600">{{ $person->display_name }}</a>
                <span class="text-gray-400">›</span>
                <span class="text-gray-900 font-medium">Latest Updates</span>
            </nav>

            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Latest Updates</h1>
                    <p class="text-gray-600 mt-2">Stay updated with the latest news and developments about {{ $person->display_name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">{{ $updates->total() }} updates</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-8">
                    <!-- Filter by Type -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter by Type</h3>
                        <div class="flex flex-col space-y-2">
                            @foreach($updateTypes as $type => $label)
                                <button wire:click="$set('updateType', '{{ $type }}')"
                                        wire:key="filter-{{ $type }}"
                                        class="w-full text-left px-3 py-2 rounded-lg transition-colors duration-200
                                               {{ $updateType === $type
                                                   ? 'bg-blue-50 text-blue-700 border border-blue-200 font-semibold'
                                                   : 'text-gray-700 hover:bg-gray-50 border border-transparent' }}">
                                    <div class="flex justify-between items-center">
                                        <span>{{ $label }}</span>
                                        @if($type !== 'all')
                                            @php
                                                $dbType = match($type) {
                                                    'news' => 'News',
                                                    'achievement' => 'Achievement',
                                                    'event' => 'Event',
                                                    'milestone' => 'Milestone',
                                                    'award' => 'Award',
                                                    default => ucfirst($type)
                                                };
                                                $count = $person->latestUpdates()
                                                    ->published()
                                                    ->approved()
                                                    ->where('update_type', $dbType)
                                                    ->count();
                                            @endphp
                                            <span class="inline-flex items-center justify-center min-w-6 h-6 bg-gray-100 text-gray-600 text-xs rounded-full">
                                                {{ $count }}
                                            </span>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($updates->hasPages())
                        <div class="mb-12">
                            {{ $updates->links('components.pagination') }}
                        </div>
                        @endif
                    </div>

                    <!-- Quick Stats -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Stats</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Updates</span>
                                <span class="font-semibold text-gray-900">{{ $person->latestUpdates()->published()->approved()->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Showing</span>
                                <span class="font-semibold text-gray-900">{{ $updates->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Updates List -->
            <div class="lg:col-span-3">
                @if($updates->count() > 0)
                    <div class="space-y-6">
                        @foreach($updates as $update)
                            <article class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                                <div class="p-6">
                                    <!-- Update Header -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                    {{ match(strtolower($update->update_type)) {
                                                        'news' => 'bg-blue-100 text-blue-800',
                                                        'achievement' => 'bg-green-100 text-green-800',
                                                        'event' => 'bg-purple-100 text-purple-800',
                                                        'milestone' => 'bg-orange-100 text-orange-800',
                                                        'award' => 'bg-yellow-100 text-yellow-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    } }}">
                                                    {{ $update->update_type }}
                                                </span>
                                                <span class="text-sm text-gray-500">{{ $update->created_at->format('M j, Y') }}</span>
                                            </div>
                                            <h2 class="text-xl font-bold text-gray-900 hover:text-blue-600">
                                                <a href="{{ route('people.updates.show', ['personSlug' => $person->slug, 'slug' => $update->slug]) }}">
                                                    {{ $update->title }}
                                                </a>
                                            </h2>
                                        </div>
                                    </div>

                                    <!-- Update Content -->
                                    <div class="prose prose-gray max-w-none text-gray-700 mb-4">
                                        {!! Str::limit(strip_tags($update->safe_html_content), 200) !!}
                                    </div>

                                    <!-- Update Footer -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $update->read_time }}
                                            </span>
                                        </div>
                                        <a href="{{ route('people.updates.show', ['personSlug' => $person->slug, 'slug' => $update->slug]) }}"
                                           class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium text-sm">
                                            Read More
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $updates->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            @if($updateType !== 'all')
                                No {{ $updateTypes[$updateType] ?? $updateType }} Updates
                            @else
                                No Updates Available
                            @endif
                        </h3>
                        <p class="text-gray-500 mb-6">
                            @if($updateType !== 'all')
                                There are no {{ strtolower($updateTypes[$updateType] ?? $updateType) }} updates for {{ $person->display_name }}.
                            @else
                                There are no updates available for {{ $person->display_name }} at the moment.
                            @endif
                        </p>
                        @if($updateType !== 'all')
                            <button wire:click="updateType = 'all'"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                View All Updates
                            </button>
                        @endif
                        <a href="{{ route('people.people.show', $person->slug) }}" class="inline-flex items-center px-4 py-2 ml-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                            Back to Profile
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- JSON-LD Structured Data -->
    @isset($structuredData)
        @foreach($structuredData as $data)
            <script type="application/ld+json">
                {!! json_encode($data) !!}
            </script>
        @endforeach
    @endisset
</div>
