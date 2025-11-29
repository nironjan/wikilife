<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Media Profile</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Manage official media presence and branding.</p>
    </div>

    <form wire:submit="saveMediaProfile">
        <div class="space-y-6">
            <!-- Official Website -->
            <x-flux::input label="Official Website" wire:model="media_official_website" placeholder="https://example.com"
                type="url" helper="Official website or portfolio URL" />

            <!-- Official Email -->
            <x-flux::input label="Official Email" wire:model="media_official_email" placeholder="contact@example.com"
                type="email" helper="Official contact email for media inquiries" />

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Media Description
                </label>
                <textarea wire:model="media_description" rows="4"
                    class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    placeholder="Brief description for media kits and official profiles..."></textarea>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Professional description used in media kits and official profiles.
                </p>
            </div>

            <!-- Banner Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Banner Image
                </label>

                @if ($media_existing_banner_image)
                    <div class="mb-4">
                        <img src="{{ $media_existing_banner_image }}" alt="Current banner image"
                            class="w-full h-32 object-cover rounded-lg border-2 border-gray-300">
                    </div>
                    <x-flux::button type="button" wire:click="removeBannerImage" variant="danger" size="sm"
                        class="mb-2">
                        Remove Current Banner
                    </x-flux::button>
                @endif

                <x-flux::input type="file" wire:model="media_banner_image" accept="image/*" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Recommended size: 1200x400px. Max 5MB.
                </p>
                @error('media_banner_image')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <x-flux::input label="Image Caption" wire:model="media_banner_img_caption" placeholder="image source..."
                type="text" />
            </div>

            <!-- Signature -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Digital Signature
                </label>

                @if ($media_existing_signature)
                    <div class="mb-4">
                        <img src="{{ $media_existing_signature }}" alt="Current signature"
                            class="h-20 object-contain rounded-lg border-2 border-gray-300">
                    </div>
                    <x-flux::button type="button" wire:click="removeSignature" variant="danger" size="sm"
                        class="mb-2">
                        Remove Current Signature
                    </x-flux::button>
                @endif

                <x-flux::input type="file" wire:model="media_signature" accept="image/*" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Digital signature for official documents. Recommended size: 400x200px. Max 2MB.
                </p>
                @error('media_signature')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-600">
                <x-flux::button type="submit" class="cursor-pointer">
                    ✓ Save Media Profile
                </x-flux::button>
            </div>
        </div>
    </form>
</div>
