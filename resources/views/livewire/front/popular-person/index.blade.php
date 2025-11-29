<div>
    <section class="py-12 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Configurable Section Header --}}
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $title }}</h2>
                        <p class="text-gray-600 text-sm">{{ $description }}</p>
                    </div>
                </div>
            </div>

            @if($layout === 'default')
                @include('livewire.front.popular-person.layouts.default')
            @elseif($layout === 'compact')
                @include('livewire.front.popular-person.layouts.compact')
            @elseif($layout === 'minimal')
                @include('livewire.front.popular-person.layouts.minimal')
            @endif
        </div>
    </section>
</div>
