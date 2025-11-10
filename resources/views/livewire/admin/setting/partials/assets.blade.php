<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Assets & Wealth Information</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Manage financial information and asset details.</p>
    </div>

    <form wire:submit="saveAssets">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Currency -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Currency *
                    </label>
                    <select wire:model="assets_currency"
                        class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @foreach ($currencies as $code => $name)
                            <option value="{{ $code }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Year Estimated -->
                <x-flux::input label="Year Estimated" wire:model="assets_year_estimated" type="number" min="1900"
                    max="{{ date('Y') + 1 }}" placeholder="e.g., {{ date('Y') }}"
                    helper="Year this financial information is relevant for" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Annual Income -->
                <x-flux::input label="Annual Income" wire:model="assets_income" type="number" min="0"
                    step="0.01" placeholder="0.00" helper="Annual income in selected currency" />

                <!-- Income Source -->
                <x-flux::input label="Income Source" wire:model="assets_income_source"
                    placeholder="e.g., Acting, Business, Investments" helper="Primary source of income" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Current Assets -->
                <x-flux::input label="Current Assets" wire:model="assets_current_assets" type="number" min="0"
                    step="0.01" placeholder="0.00" helper="Value of current assets in selected currency" />

                <!-- Net Worth -->
                <x-flux::input label="Net Worth" wire:model="assets_net_worth" type="number" min="0"
                    step="0.01" placeholder="0.00" helper="Total net worth in selected currency" />
            </div>

            <!-- References Section -->
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">External References</label>

    @foreach($assets_references as $index => $reference)
        <div class="flex gap-4 mb-3 items-start reference-row">
            {{-- Reference Name --}}
            <div class="flex-1">
                <label class="block text-xs text-gray-500 mb-1">Reference Name</label>
                <input
                    type="text"
                    wire:model="assets_references.{{ $index }}.name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g., Forbes, Bloomberg, etc."
                >
                @error("assets_references.{$index}.name")
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            {{-- Reference URL --}}
            <div class="flex-1">
                <label class="block text-xs text-gray-500 mb-1">Reference URL</label>
                <input
                    type="url"
                    wire:model="assets_references.{{ $index }}.url"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="https://example.com/reference"
                >
                @error("assets_references.{$index}.url")
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            {{-- Remove button --}}
            <div class="pt-6">
                <button
                    type="button"
                    wire:click="removeReference({{ $index }})"
                    class="flex items-center justify-center w-9 h-9 rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-800 transition-colors cursor-pointer"
                    title="Remove reference"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endforeach

    {{-- Add new reference --}}
    <button
        type="button"
        wire:click="addReference"
        class="mt-2 flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors"
    >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Add Reference
    </button>
</div>


            <!-- Preview -->
            @if ($assets_net_worth)
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <div class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Net Worth Preview:
                    </div>
                    <div class="text-lg font-bold text-blue-900 dark:text-blue-100 mt-1">
                        {{ $assets_currency }} {{ number_format($assets_net_worth, 2) }}
                    </div>
                    <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                        @if ($assets_year_estimated)
                            Estimated for {{ $assets_year_estimated }}
                        @endif
                    </div>
                </div>
            @endif

            <!-- Submit Button -->
            <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-600">
                <x-flux::button type="submit" class="cursor-pointer">
                    ✓ Save Assets Information
                </x-flux::button>
            </div>
        </div>
    </form>
</div>
