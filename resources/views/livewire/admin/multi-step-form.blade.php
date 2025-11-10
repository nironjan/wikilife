<div>
    <div class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="container mx-auto p-4">
            <div class="bg-white rounded-lg shadow-lg p-6 md:p-10 max-w-3xl mx-auto">
                <h1 class="text-3xl font-bold text-center mb-8">Account Setup Wizard</h1>

                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex justify-between mb-2">
                        <span
                            class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200 {{ $step >= 1 ? 'opacity-100' : 'opacity-50' }}"
                            id="step1">
                            Product Info
                        </span>
                        <span
                            class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200 opacity-50 {{ $step >= 2 ? 'opacity-100' : 'opacity-50' }}"
                            id="step2">
                            Inventory
                        </span>
                        <span
                            class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200 {{ $step >= 3 ? 'opacity-100' : 'opacity-50' }}"
                            id="step3">
                            Details
                        </span>
                    </div>
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-green-200">
                        <div id="progress-bar"
                            class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500 w-1/3 transition-all duration-500 ease-in-out {{ $step == 1 ? 'w-1/3' : ($step == 2 ? 'w-2/3' : 'w-full') }}">
                        </div>
                    </div>
                </div>



                @if ($step === 1)
                    <div class="mt-3 space-y-4">
                        <flux:input type="text" label="Product Name" wire:model='name'
                            placeholder="Enter Product name Here" />
                        <flux:input type="text" label="Product Slug" wire:model='slug'
                            placeholder="Auto generated Slug if empty" />
                        <flux:textarea label="Description" wire:model='description'
                            placeholder="No lettuce, tomato, or onion..." />
                    </div>
                    <div class="mt-3 mx-auto">
                        <flux:button variant="primary" class="cursor-pointer" x-on:click="$wire.nextStep()">Next
                        </flux:button>
                    </div>
                @endif

                @if ($step === 2)
                    <div class="mt-3 space-y-4">
                        <flux:input type="text" label="SKU" wire:model='sku'
                            placeholder="Enter Product SKU Here" />
                        <flux:input type="text" label="Price" wire:model='price'
                            placeholder="Enter Product Price Here" />
                        <flux:input type="number" label="Stock" wire:model='stock'
                            placeholder="Enter Product Stock Here" />
                        <flux:select label="Select Category" wire:model.defer="category_id" placeholder="None">
                            <flux:select.option value="">Select Category</flux:select.option>
                            @foreach ($categories as $cat)
                                <flux:select.option value="{{ $cat->id }}">{{ $cat->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    <div class="mt-3 mx-auto">
                        <flux:button variant="primary" class="cursor-pointer" x-on:click="$wire.nextStep()">Next
                        </flux:button>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
