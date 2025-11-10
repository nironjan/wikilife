<div>
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Left Sidebar - Progress Steps -->
                    <div class="lg:w-1/4 bg-gray-50 dark:bg-gray-700 p-6 border-r border-gray-200 dark:border-gray-600">
                        <!-- Header -->
                        <div class="mb-8">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $editingId ? 'Edit Person' : 'Create New Person' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                Complete all steps to {{ $editingId ? 'update' : 'create' }} the biography
                            </p>
                        </div>

                        <!-- Progress Steps -->
                        <div class="space-y-4">
                            <!-- Step 1: Basic Info -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 1 ? 'bg-blue-50 dark:bg-blue-900/20 border border-dashed border-blue-200 dark:border-blue-800' : 'bg-gray-100 dark:bg-gray-600' }}">
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

                            <!-- Step 2: Biography -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 2 ? 'bg-blue-50 dark:bg-blue-900/20 border border-dashed border-blue-200 dark:border-blue-800' : 'bg-gray-100 dark:bg-gray-600' }}">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-500 text-gray-600 dark:text-gray-300' }}">
                                    2
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium
                                    {{ $currentStep >= 2 ? 'text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300' }}">
                                        Biography Details
                                    </span>
                                </div>
                            </div>

                            <!-- Step 3: Background -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 3 ? 'bg-blue-50 dark:bg-blue-900/20 border border-dashed border-blue-200 dark:border-blue-800' : 'bg-gray-100 dark:bg-gray-600' }}">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-500 text-gray-600 dark:text-gray-300' }}">
                                    3
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium
                                    {{ $currentStep >= 3 ? 'text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300' }}">
                                        Background & Stats
                                    </span>
                                </div>
                            </div>

                            <!-- Step 4: Media & Status -->
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg
                            {{ $currentStep >= 4 ? 'bg-blue-50 dark:bg-blue-900/20 border border-dashed border-blue-200 dark:border-blue-800' : 'bg-gray-100 dark:bg-gray-600' }}">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $currentStep >= 4 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-500 text-gray-600 dark:text-gray-300' }}">
                                    4
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium
                                    {{ $currentStep >= 4 ? 'text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300' }}">
                                        Media & Status
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-8">
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <span>Progress</span>
                                <span>{{ round(($currentStep / 4) * 100) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ ($currentStep / 4) * 100 }}%"></div>
                            </div>
                        </div>

                        <!-- Action Buttons for Mobile -->
                        <div class="lg:hidden mt-6 space-y-3">
                            @if ($currentStep > 1)
                                <x-flux::button type="button" wire:click="previousStep" class="w-full">
                                    ← Previous
                                </x-flux::button>
                            @endif
                            @if ($currentStep < 4)
                                <x-flux::button type="button" wire:click="nextStep" class="w-full">
                                    Next Step →
                                </x-flux::button>
                            @else
                                <x-flux::button type="button" wire:click="save" class="w-full">
                                    ✓ {{ $editingId ? 'Update Person' : 'Create Person' }}
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
                                            the person.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Name with live updates -->
                                        <x-flux::input label="Display Name *" wire:model.live="name"
                                            placeholder="Enter display name" required />

                                        <!-- Full Name -->
                                        <x-flux::input label="Full Name" wire:model="full_name"
                                            placeholder="Enter full legal name" />
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
                                                        class="cursor-pointer" size="sm">

                                                        ↻ Reset to Auto
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

                                            <!-- Loading indicator -->
                                            @if ($autoSlug && !empty($name) && empty($slug))
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <div
                                                        class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Real-time preview -->
                                        @if (!empty($slug))
                                            <div class="mt-2 p-2 bg-gray-50 dark:bg-gray-700 rounded text-sm">
                                                <span class="text-gray-600 dark:text-gray-400">Preview URL:</span>
                                                <span class="font-mono text-blue-600 dark:text-blue-400 ml-2">
                                                    /people/{{ $slug }}
                                                </span>
                                            </div>
                                        @endif

                                        @if ($autoSlug)
                                            <p class="mt-1 text-sm text-green-600 dark:text-green-400">
                                                ✓ Slug auto-generates as you type
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

                                    <!-- Nicknames -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Nicknames
                                        </label>
                                        <div class="space-y-2">
                                            @foreach ($nicknames as $index => $nickname)
                                                <div class="flex gap-2">
                                                    <x-flux::input wire:model="nicknames.{{ $index }}"
                                                        placeholder="Enter nickname" class="flex-1" />
                                                    @if (count($nicknames) > 1)
                                                        <x-flux::button type="button"
                                                            wire:click="removeNickname({{ $index }})"
                                                            variant="danger" size="sm">
                                                            Remove
                                                        </x-flux::button>
                                                    @endif
                                                </div>
                                            @endforeach
                                            <x-flux::button type="button" wire:click="addNickname"
                                                class="cursor-pointer" size="sm">
                                                Add Nickname
                                            </x-flux::button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Step 2: Biography Details -->
                            @if ($currentStep == 2)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Biography
                                            Details</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Provide personal and life event
                                            information.</p>
                                    </div>

                                    <!-- About -->
                                    <x-flux::textarea label="About / Biography" wire:model="about"
                                        placeholder="Write a detailed biography..." rows="6" />

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Gender -->
                                        <x-flux::select label="Gender" wire:model="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </x-flux::select>

                                        <!-- Birth Date -->
                                        <x-flux::input label="Birth Date" wire:model="birth_date" type="date" />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Death Date -->
                                        <x-flux::input label="Death Date" wire:model="death_date" type="date" />

                                        <!-- Death Cause -->
                                        <x-flux::input label="Cause of Death" wire:model="death_cause"
                                            placeholder="If applicable" />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Place of Birth -->
                                        <x-flux::input label="Place of Birth" wire:model="place_of_birth"
                                            placeholder="City, Country" />

                                        <!-- Place of Death -->
                                        <x-flux::input label="Place of Death" wire:model="place_of_death"
                                            placeholder="City, Country" />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Hometown -->
                                        <x-flux::input label="Hometown" wire:model="hometown"
                                            placeholder="Hometown or ancestral place" />

                                        <!-- Address -->
                                        <x-flux::input label="Current Address" wire:model="address"
                                            placeholder="Current residence" />
                                    </div>
                                </div>
                            @endif

                            <!-- Step 3: Background & Stats -->
                            @if ($currentStep == 3)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Background &
                                            Statistics</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Cultural, physical, and
                                            professional information.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Nationality -->
                                        <x-flux::input label="Nationality" wire:model="nationality"
                                            placeholder="e.g., American, Indian" />

                                        <!-- Religion -->
                                        <x-flux::input label="Religion" wire:model="religion"
                                            placeholder="Religious affiliation" />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Caste -->
                                        <x-flux::input label="Caste" wire:model="caste"
                                            placeholder="If applicable" />

                                        <!-- Ethnicity -->
                                        <x-flux::input label="Ethnicity" wire:model="ethnicity"
                                            placeholder="Ethnic background" />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Zodiac Sign -->
                                        <x-flux::input label="Zodiac Sign" wire:model="zodiac_sign"
                                            placeholder="e.g., Leo, Scorpio" />

                                        <!-- Blood Group -->
                                        <x-flux::input label="Blood Group" wire:model="blood_group"
                                            placeholder="e.g., O+, AB-" />
                                    </div>

                                    <!-- Professions -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Professions
                                        </label>
                                        <div class="space-y-2">
                                            @foreach ($professions as $index => $profession)
                                                <div class="flex gap-2">
                                                    <x-flux::input wire:model="professions.{{ $index }}"
                                                        placeholder="Enter profession" class="flex-1" />
                                                    @if (count($professions) > 1)
                                                        <x-flux::button type="button"
                                                            wire:click="removeProfession({{ $index }})"
                                                            variant="danger" size="sm">
                                                            Remove
                                                        </x-flux::button>
                                                    @endif
                                                </div>
                                            @endforeach
                                            <x-flux::button type="button" wire:click="addProfession"
                                                class="cursor-pointer" size="sm" class="cursor-pointer">
                                                Add Profession
                                            </x-flux::button>
                                        </div>
                                    </div>

                                    <!-- Physical Stats -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Physical Statistics
                                        </label>
                                        <div class="space-y-3">
                                            @foreach ($physical_stats as $index => $stat)
                                                <div class="flex gap-2 items-start">
                                                    <x-flux::input wire:model="physical_stats.{{ $index }}.key"
                                                        placeholder="Attribute (e.g., Height, Weight)"
                                                        class="flex-1" />
                                                    <x-flux::input
                                                        wire:model="physical_stats.{{ $index }}.value"
                                                        placeholder="Value (e.g., 175 cm)" class="flex-1" />
                                                    @if (count($physical_stats) > 1)
                                                        <x-flux::button type="button"
                                                            wire:click="removePhysicalStat({{ $index }})"
                                                            variant="danger" size="sm" class="mt-1">
                                                            Remove
                                                        </x-flux::button>
                                                    @endif
                                                </div>
                                            @endforeach
                                            <x-flux::button type="button" wire:click="addPhysicalStat"
                                                class="cursor-pointer" size="sm" class="cursor-pointer">
                                                Add Physical Stat
                                            </x-flux::button>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Add physical attributes like height, weight, eye color, etc.
                                        </p>
                                    </div>

                                    <!-- Favorite Things -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Favorite Things
                                        </label>
                                        <div class="space-y-3">
                                            @foreach ($favourite_things as $index => $thing)
                                                <div class="flex gap-2 items-start">
                                                    <x-flux::input
                                                        wire:model="favourite_things.{{ $index }}.key"
                                                        placeholder="Category (e.g., Color, Food, Hobby)"
                                                        class="flex-1" />
                                                    <x-flux::input
                                                        wire:model="favourite_things.{{ $index }}.value"
                                                        placeholder="Favorite (e.g., Blue, Pizza, Reading)"
                                                        class="flex-1" />
                                                    @if (count($favourite_things) > 1)
                                                        <x-flux::button type="button"
                                                            wire:click="removeFavoriteThing({{ $index }})"
                                                            variant="danger" size="sm" class="mt-1">
                                                            Remove
                                                        </x-flux::button>
                                                    @endif
                                                </div>
                                            @endforeach
                                            <x-flux::button type="button" wire:click="addFavoriteThing"
                                                class="cursor-pointer" size="sm" class="cursor-pointer">
                                                Add Favorite Thing
                                            </x-flux::button>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Add personal preferences and favorites.
                                        </p>
                                    </div>

                                    <!-- References -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            References
                                        </label>
                                        <x-flux::textarea wire:model="references"
                                            placeholder='e.g., ["https://example.com/bio", "Book: The Biography"]'
                                            rows="3"
                                            helper="Enter as JSON array format for multiple references" />
                                    </div>
                                </div>
                            @endif

                            <!-- Step 4: Media & Status -->
                            @if ($currentStep == 4)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Media & Status
                                        </h2>
                                        <p class="text-gray-600 dark:text-gray-400">Upload images and set publication
                                            status.</p>
                                    </div>

                                    <!-- Profile Image -->
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Profile Image
                                            </label>
                                            @if ($existing_profile_image)
                                                <div class="mb-4">
                                                    <img src="{{ $existing_profile_image }}"
                                                        alt="Current profile image"
                                                        class="w-32 h-32 rounded-full object-cover border-2 border-gray-300">
                                                </div>
                                                <x-flux::button type="button" wire:click="removeProfileImage"
                                                    variant="danger" size="sm" class="mb-2">
                                                    Remove Current Image
                                                </x-flux::button>
                                            @endif
                                            <x-flux::input type="file" wire:model="profile_image"
                                                accept="image/*" />
                                            @error('profile_image')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <!-- Cover Image -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Cover Image
                                            </label>
                                            @if ($existing_cover_image)
                                                <div class="mb-4">
                                                    <img src="{{ $existing_cover_image }}" alt="Current cover image"
                                                        class="w-full h-32 object-cover rounded-lg border-2 border-gray-300">
                                                </div>
                                                <x-flux::button type="button" wire:click="removeCoverImage"
                                                    variant="danger" size="sm" class="mb-2">
                                                    Remove Current Image
                                                </x-flux::button>
                                            @endif
                                            <x-flux::input type="file" wire:model="cover_image"
                                                accept="image/*" />
                                            @error('cover_image')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status & Verification -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Status -->
                                        <x-flux::select label="Status *" wire:model="status" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="deceased">Deceased</option>
                                        </x-flux::select>

                                        <!-- Verified -->
                                        <div class="flex items-center space-x-3 pt-6">
                                            <x-flux::checkbox wire:model="verified" label="Verified Profile"
                                                helper="Mark this profile as verified" />
                                        </div>
                                    </div>

                                    <!-- Image Optimization Settings -->
                                    <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Image
                                            Optimization</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <x-flux::input label="Width (px)" wire:model="imageWidth" type="number"
                                                min="100" max="4000" />
                                            <x-flux::input label="Height (px)" wire:model="imageHeight"
                                                type="number" min="100" max="4000" />
                                            <x-flux::input label="Quality (%)" wire:model="imageQuality"
                                                type="number" min="10" max="100" />
                                        </div>
                                    </div>
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

                                @if ($currentStep < 4)
                                    <x-flux::button type="button" wire:click="nextStep" class="cursor-pointer">
                                        Next Step →
                                    </x-flux::button>
                                @else
                                    <x-flux::button type="submit" class="cursor-pointer">
                                        ✓ {{ $editingId ? 'Update Person' : 'Create Person' }}
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
