<div class="p-6">
    <flux:heading size="xl" level="1">{{ __('Products') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Manage your products') }}</flux:subheading>
    <flux:separator variant="subtle" />

    <div class="flex flex-col md:flex-row justify-between items-center my-4 space-y-2 md:space-y-0 md:space-x-2">
        <div class="w-64">
            <flux:input placeholder="Search Product..." wire:model.live.debounce.300ms='search' class="w-full" />
        </div>

        <div class="relative">
            <flux:button variant="primary" type="submit"
                class="cursor-pointer relative flex items-center justify-center" wire:click="create"
                wire:loading.attr="disabled">
                New Product
            </flux:button>
        </div>
    </div>

    {{-- Table List --}}
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full text-sm text-left text-gray-700 border border-gray-200">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Image</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Price</th>
                    <th class="px-6 py-3">Stock</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td class="px-6 py-3">
                            @php
                                $firstImage = $product->images->first();
                                $imageUrl = $firstImage?->image_path ?? null;
                            @endphp

                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                    class="w-16 h-12 object-cover rounded border border-gray-200 dark:border-gray-700" />
                            @else
                                <div
                                    class="w-16 h-12 bg-gray-100 dark:bg-zinc-700 text-gray-500 text-xs flex items-center justify-center rounded">
                                    No Image
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-3 font-medium text-gray-800 dark:text-gray-200">
                            {{ $product->name }}
                        </td>

                        <td class="px-6 py-3 text-gray-600 dark:text-gray-400">
                            {{ $product->category?->name ?? '—' }}
                        </td>

                        <td class="px-6 py-3 text-gray-700 dark:text-gray-300">
                            ₹{{ number_format($product->price, 2) }}
                        </td>

                        <td class="px-6 py-3 text-gray-700 dark:text-gray-300">
                            {{ $product->stock }}
                        </td>

                        <td class="px-6 py-3 space-x-2">
                            <button wire:click="edit({{ $product->id }})"
                                class="text-blue-500 hover:text-blue-700 font-semibold cursor-pointer">
                                <flux:icon.pencil-square />
                            </button>
                            <button wire:click="confirmDelete({{ $product->id }})"
                                class="text-red-500 hover:text-red-700 font-semibold cursor-pointer">
                                <flux:icon.trash />
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    <div class="mt-4">
        {{ $products->links() }}
    </div>

    {{-- Form Modal --}}
    <flux:modal wire:model.self="showFormModal" name="product-form" class="w-full max-w-md md:max-w-lg lg:max-w-xl">

        <form wire:submit.prevent="save" class="space-y-6">
            <flux:heading>
                <h3 class="text-lg font-medium">
                    {{ $editingId ? 'Edit Product' : 'Create New Product' }}
                </h3>
            </flux:heading>

            {{-- Image Optimization Settings --}}
            <div class="bg-gray-50 p-4 rounded-lg border">
                <div class="flex items-center justify-between mb-3">
                    <flux:heading size="sm">Image Optimization Settings</flux:heading>
                    <button type="button" wire:click="toggleImageSettings" class="text-blue-600 text-sm">
                        {{ $showImageSettings ? 'Hide' : 'Show' }} Settings
                    </button>
                </div>

                @if ($showImageSettings)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                        <flux:input type="number" label="Width (px)" wire:model="imageWidth" placeholder="1200"
                            help="Max width in pixels" />
                        <flux:input type="number" label="Height (px)" wire:model="imageHeight" placeholder="1200"
                            help="Max height in pixels" />
                        <flux:input type="number" label="Quality (%)" wire:model="imageQuality" placeholder="85"
                            help="Image quality (10-100)" min="10" max="100" />
                    </div>
                    <div class="flex justify-between items-center text-sm text-gray-600">
                        <span>Images will be resized and optimized automatically</span>
                        <button type="button" wire:click="applyDefaultSettings"
                            class="text-blue-600 hover:text-blue-800">
                            Reset to Defaults
                        </button>
                    </div>
                @else
                    <div class="text-sm text-gray-600">
                        <button type="button" wire:click="toggleImageSettings"
                            class="text-blue-600 hover:text-blue-800">
                            Configure image optimization settings
                        </button>
                    </div>
                @endif
            </div>

            {{-- Two-column responsive grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input label="Name" wire:model="name" />
                <flux:input label="Slug" wire:model="slug" :value="$slug"
                    placeholder="Auto generated if empty" />

                <flux:input type="Number" label="Price" wire:model.defer='price' />
                <flux:input label="Stocks" wire:model.defer='stock' />

                <flux:input label="SKU NO" wire:model.defer='sku' />
                <flux:select label="Select Category" wire:model.defer="category_id" placeholder="None">
                    <flux:select.option value="">Select Category</flux:select.option>
                    @foreach ($categories as $cat)
                        <flux:select.option value="{{ $cat->id }}">{{ $cat->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            {{-- Full-width description --}}
            <flux:textarea label="Description" placeholder="write something..." wire:model='description' />

            {{-- File upload --}}
            <div class="mt-3">
                <flux:input type="file" wire:model="images" label="Upload Images" multiple />

                {{-- Upload info --}}
                @if ($imageWidth || $imageHeight)
                    <div class="text-xs text-gray-500 mt-1">
                        Images will be optimized to
                        @if ($imageWidth && $imageHeight)
                            {{ $imageWidth }}x{{ $imageHeight }}px
                        @elseif($imageWidth)
                            max width {{ $imageWidth }}px
                        @elseif($imageHeight)
                            max height {{ $imageHeight }}px
                        @endif
                        with {{ $imageQuality }}% quality
                    </div>
                @endif

                {{-- Uploading Spinner --}}
                <div wire:loading wire:target="images" class="text-sm text-blue-500 mt-2 flex items-center gap-1">
                    <flux:icon.loading class="w-4 h-4 text-blue-500 animate-spin" />
                    Uploading images...
                </div>

                {{-- Preview new images --}}
                @if (!empty($images))
                    <div class="mt-4">
                        <div class="text-sm font-medium mb-2">New Images to Upload</div>
                        <div class="flex gap-2 flex-wrap">
                            @foreach ($images as $index => $image)
                                <div class="relative w-24 h-24 rounded overflow-hidden group border">
                                    <img src="{{ $image->temporaryUrl() }}" alt="preview"
                                        class="object-cover w-full h-full" />
                                    <div
                                        class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1">
                                        {{ round($image->getSize() / 1024, 1) }} KB
                                    </div>
                                    <button type="button"
                                        class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 py-0.5 rounded opacity-0 group-hover:opacity-100 transition"
                                        wire:click="removeTempImage({{ $index }})">
                                        ✕
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Existing Images (From ImageKit) --}}
                @if (!empty($existingImages))
                    <div class="mt-4">
                        <div class="text-sm font-medium mb-2">Existing Images</div>
                        <div class="flex gap-2 flex-wrap">
                            @foreach ($existingImages as $img)
                                <div class="relative w-24 h-24 rounded overflow-hidden group">
                                    <img src="{{ $img['path'] }}" alt="existing image"
                                        class="object-cover w-full h-full" />
                                    @if ($img['width'] && $img['height'])
                                        <div
                                            class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 text-center">
                                            {{ $img['width'] }}x{{ $img['height'] }}
                                        </div>
                                    @endif
                                    <button type="button"
                                        class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 py-0.5 rounded opacity-0 group-hover:opacity-100 transition"
                                        wire:click="removeExistingImage({{ $img['id'] }})">
                                        ✕
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 justify-end">
                <flux:button type="button" variant="ghost" x-on:click="$wire.showFormModal = false">Cancel
                </flux:button>
                <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                    <span wire:loading wire:target="save">Uploading...</span>
                    <span wire:loading.remove>{{ $editingId ? 'Update' : 'Create' }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Delete Modal --}}
    <flux:modal wire:model.self="showDeleteModal" name="confirm-delete" class="md:w-96">
        <form class="space-y-6">
            <flux:heading>
                <h3 class="text-lg font-medium">
                    Confirm Delete
                </h3>
            </flux:heading>

            <flux:text>
                Are You Sure you Want to delete this Product? This action cannot be undone.
            </flux:text>

            <div class="flex">
                <flux:spacer />
                <flux:button type="button" variant="ghost" x-on:click="$wire.showDeleteModal = false"
                    class="cursor-pointer">Cancel</flux:button>
                <flux:button type="button" variant="danger" wire:click="delete" class="cursor-pointer"
                    wire:loading.attr="disabled">
                    <span wire:loading wire:target="delete">Deleting...</span>
                    <span wire:loading.remove>Delete</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
