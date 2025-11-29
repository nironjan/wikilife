<div>
    <div class="min-h-screen dark:bg-gray-900 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Left Sidebar - Progress Steps -->
                    <div class="lg:w-1/4 bg-gray-50 dark:bg-gray-700 p-6 border-r border-gray-200 dark:border-gray-600">
                        <!-- Header -->
                        <div class="mb-8">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $editingId ? 'Edit Political Career' : 'Add Political Career' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                {{ $editingId ? 'Update political career information' : 'Create a new political career entry' }}
                            </p>
                        </div>

                        <!-- Progress Steps -->
                        <div class="space-y-4">
                            <!-- Step 1: Basic Info -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 1 ? 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800' : 'bg-gray-100 dark:bg-gray-600' }}">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-500 text-gray-600 dark:text-gray-300' }}">
                                    1
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium
                                    {{ $currentStep >= 1 ? 'text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300' }}">
                                        Basic Information
                                    </span>
                                </div>
                            </div>

                            <!-- Step 2: Political Details -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 2 ? 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800' : 'bg-gray-100 dark:bg-gray-600' }}">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-500 text-gray-600 dark:text-gray-300' }}">
                                    2
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium
                                    {{ $currentStep >= 2 ? 'text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300' }}">
                                        Political Details
                                    </span>
                                </div>
                            </div>

                            <!-- Step 3: Additional Info -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 3 ? 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800' : 'bg-gray-100 dark:bg-gray-600' }}">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-500 text-gray-600 dark:text-gray-300' }}">
                                    3
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium
                                    {{ $currentStep >= 3 ? 'text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300' }}">
                                        Additional Information
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-8">
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <span>Progress</span>
                                <span>{{ round(($currentStep / 3) * 100) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ ($currentStep / 3) * 100 }}%"></div>
                            </div>
                        </div>

                        <!-- Action Buttons for Mobile -->
                        <div class="lg:hidden mt-6 space-y-3">
                            @if ($currentStep > 1)
                                <x-flux::button type="button" wire:click="previousStep" class="cursor-pointer"
                                    class="w-full">
                                    ← Previous
                                </x-flux::button>
                            @endif
                            @if ($currentStep < 3)
                                <x-flux::button type="button" wire:click="nextStep" class="w-full">
                                    Next Step →
                                </x-flux::button>
                            @else
                                <x-flux::button type="button" wire:click="save" class="w-full">
                                    ✓ {{ $editingId ? 'Update Career' : 'Create Career' }}
                                </x-flux::button>
                            @endif
                        </div>
                    </div>

                    <!-- Right Content - Form Steps -->
                    <div class="lg:w-3/4 p-6 lg:p-8">
                        <form wire:submit="save">
                            <!-- Step 1: Basic Information -->
                            @if ($currentStep == 1)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Basic
                                            Information</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Enter the fundamental details about
                                            the political career.</p>
                                    </div>

                                    <!-- Person Selection -->
                                    @include('livewire.admin.politician.partials.person-select')

                                    <!-- Political Party -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Political Party *
                                        </label>
                                        <input wire:model="political_party" list="parties"
                                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Select or enter political party" required />
                                        <datalist id="parties">
                                            @foreach ($commonParties as $party)
                                                <option value="{{ $party }}">
                                            @endforeach
                                        </datalist>
                                    </div>

                                    <!-- Position -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Position *
                                        </label>
                                        <input wire:model="position" list="positions"
                                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Select or enter position" required />
                                        <datalist id="positions">
                                            @foreach ($commonPositions as $position)
                                                <option value="{{ $position }}">
                                            @endforeach
                                        </datalist>
                                    </div>

                                    <!-- Office Type -->
                                    <div>
                                        <x-flux::select label="Office Type" wire:model="office_type">
                                            <option value="">Office Type</option>
                                            <option value="Local">Local Government</option>
                                            <option value="State">State Government</option>
                                            <option value="National">Central Government</option>
                                            <option value="International">International</option>
                                        </x-flux::select>
                                    </div>

                                    <!-- Slug with Auto-generation -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Slug *
                                            </label>
                                            <div class="flex items-center space-x-2">
                                                @if (!$autoSlug)
                                                    <x-flux::button type="button" wire:click="resetSlug"
                                                        class="cursor-pointer" size="sm">↻ Reset to Auto
                                                    </x-flux::button>
                                                @endif
                                                <span
                                                    class="text-xs px-2 py-1 rounded-full {{ $autoSlug ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' }}">
                                                    {{ $autoSlug ? 'Auto' : 'Manual' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="relative">
                                            <x-flux::input wire:model="slug" placeholder="URL-friendly identifier"
                                                required class="pr-10" />

                                            @if ($autoSlug && !empty($political_party) && !empty($position) && empty($slug))
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <div
                                                        class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        @if (!empty($slug))
                                            <div class="mt-2 p-2 bg-gray-50 dark:bg-gray-700 rounded text-sm">
                                                <span class="text-gray-600 dark:text-gray-400">Preview URL:</span>
                                                <span class="font-mono text-blue-600 dark:text-blue-400 ml-2">
                                                    /politicians/{{ $slug }}
                                                </span>
                                            </div>
                                        @endif

                                        @if ($autoSlug)
                                            <p class="mt-1 text-sm text-green-600 dark:text-green-400">
                                                ✓ Slug auto-generates from party and position
                                            </p>
                                        @else
                                            <p class="mt-1 text-sm text-blue-600 dark:text-blue-400">
                                                ⚡ Manual mode - you can customize the slug
                                            </p>
                                        @endif

                                        @error('slug')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <!-- Step 2: Political Details -->
                            @if ($currentStep == 2)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Political
                                            Details</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Provide specific details about the
                                            political career.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Constituency -->
                                        <x-flux::input label="Constituency" wire:model="constituency"
                                            placeholder="e.g., Varanasi, Gandhinagar"
                                            helper="Geographical constituency represented" />

                                        <!-- Office Type -->
                                        <x-flux::input label="Office Type" wire:model="office_type" disabled />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Joining Date -->
                                        <x-flux::input label="Party Joining Date" wire:model="joining_date"
                                            type="date" helper="When they joined the political party" />

                                        <!-- End Date -->
                                        <x-flux::input label="Party End Date" wire:model="end_date" type="date"
                                            helper="When they left the party (if applicable)" />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Tenure Start -->
                                        <x-flux::input label="Tenure Start Date *" wire:model="tenure_start"
                                            type="date" required helper="When they started in this position" />

                                        <!-- Tenure End -->
                                        <x-flux::input label="Tenure End Date" wire:model="tenure_end" type="date"
                                            helper="When they ended in this position (leave empty for current)" />
                                    </div>

                                    <!-- Memberships -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Committee Memberships & Affiliations
                                        </label>
                                        <div class="space-y-2">
                                            @foreach ($memberships as $index => $membership)
                                                <div class="flex gap-2">
                                                    <x-flux::input wire:model="memberships.{{ $index }}"
                                                        placeholder="e.g., Finance Committee, Foreign Affairs Committee"
                                                        class="flex-1" />
                                                    @if (count($memberships) > 1)
                                                        <x-flux::button type="button"
                                                            wire:click="removeMembership({{ $index }})"
                                                            variant="danger" size="sm">
                                                            Remove
                                                        </x-flux::button>
                                                    @endif
                                                </div>
                                            @endforeach
                                            <x-flux::button type="button" wire:click="addMembership"
                                                class="cursor-pointer" size="sm">
                                                Add Membership
                                            </x-flux::button>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            List committee memberships and political affiliations
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Step 3: Additional Information -->
                            @if ($currentStep == 3)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Additional
                                            Information</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Set status, achievements, and
                                            additional details.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Status -->
                                        <div>
                                            <label class="flex items-center">
                                                <input type="checkbox" wire:model="is_active"
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active
                                                    Political Career</span>
                                            </label>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Uncheck if this political career has ended
                                            </p>
                                        </div>

                                        <!-- Sort Order -->
                                        <x-flux::input label="Sort Order" wire:model="sort_order" type="number"
                                            min="0" helper="Lower numbers appear first in lists" />
                                    </div>

                                    <!-- Political Journey -->
                                    <div class="space-y-2">
                                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Political Journey
                                        </label>
                                        <div>


                                            <x-quill-editor wire:model="political_journey" placeholder="Describe their political journey, career progression, and key milestones..."
                                                height="400px" toolbar="basic" />

                                            <!-- Hidden field for validation -->
                                            <input type="hidden" wire:model="political_journey" />

                                            @error('political_journey')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Use the toolbar above to format your content with rich text editing.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Notable Achievements -->
                                    <div class="space-y-2">
                                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Notable Achievements
                                        </label>
                                        <div>


                                            <x-quill-editor wire:model="notable_achievements" placeholder="List significant political achievements, legislation passed, reforms implemented..."
                                                height="400px" toolbar="basic" />

                                            <!-- Hidden field for validation -->
                                            <input type="hidden" wire:model="notable_achievements" />

                                            @error('notable_achievements')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Use the toolbar above to format your content with rich text editing.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Major Initiatives -->
                                    <div class="space-y-2">
                                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Notable Achievements
                                        </label>
                                        <div>


                                            <x-quill-editor wire:model="major_initiatives" placeholder="Describe major political initiatives, campaigns, or projects led..."
                                                height="400px" toolbar="basic" />

                                            <!-- Hidden field for validation -->
                                            <input type="hidden" wire:model="major_initiatives" />

                                            @error('major_initiatives')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Use the toolbar above to format your content with rich text editing.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Additional Notes
                                        </label>
                                        <textarea wire:model="notes" rows="2"
                                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Any additional information or context..."></textarea>
                                    </div>

                                    <!-- Source URL -->
                                    <x-flux::input label="Source URL" wire:model="source_url"
                                        placeholder="https://example.com/reference" type="url"
                                        helper="Link to official source or reference" />

                                    <!-- Award Selection -->
                                    @include('livewire.admin.politician.partials.award-select')
                                </div>
                            @endif

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between pt-8 border-t border-gray-200 dark:border-gray-600 mt-8">
                                @if ($currentStep > 1)
                                    <x-flux::button type="button" wire:click="previousStep" class="cursor-pointer">
                                        ← Previous
                                    </x-flux::button>
                                @else
                                    <div></div>
                                @endif

                                @if ($currentStep < 3)
                                    <x-flux::button type="button" wire:click="nextStep">
                                        Next Step →
                                    </x-flux::button>
                                @else
                                    <x-flux::button type="submit">
                                        ✓ {{ $editingId ? 'Update Political Career' : 'Create Political Career' }}
                                    </x-flux::button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
