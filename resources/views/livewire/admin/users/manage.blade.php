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
                                {{ $editingId ? 'Edit User' : 'Create New User' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                Complete all steps to {{ $editingId ? 'update' : 'create' }} the user
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

                            <!-- Step 2: Profile -->
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
                                        Profile & Bio
                                    </span>
                                </div>
                            </div>

                            <!-- Step 3: Security -->
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
                                        Security
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
                                <x-flux::button type="button" wire:click="previousStep" class="w-full">
                                    ← Previous
                                </x-flux::button>
                            @endif
                            @if ($currentStep < 3)
                                <x-flux::button type="button" wire:click="nextStep" class="w-full">
                                    Next Step →
                                </x-flux::button>
                            @else
                                <x-flux::button type="button" wire:click="save" class="w-full">
                                    ✓ {{ $editingId ? 'Update User' : 'Create User' }}
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
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Basic Information</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Enter the user's basic details and role information.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Name -->
                                        <x-flux::input label="Full Name *" wire:model="name"
                                            placeholder="Enter full name" required />

                                        <!-- Email -->
                                        <x-flux::input label="Email Address *" wire:model="email"
                                            placeholder="Enter email address" type="email" required />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Role -->
                                        <x-flux::select label="Role *" wire:model="role" required>
                                            <option value="user">User</option>
                                            <option value="author">Author</option>
                                            <option value="editor">Editor</option>
                                            <option value="admin">Administrator</option>
                                        </x-flux::select>

                                        <!-- Status -->
                                        <x-flux::select label="Status *" wire:model="status" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </x-flux::select>
                                    </div>

                                    <!-- Team Member -->
                                    <div class="flex items-center space-x-3">
                                        <x-flux::checkbox wire:model="is_team_member" label="Team Member"
                                            helper="This user will appear on the About page as a team member" />
                                    </div>
                                </div>
                            @endif

                            <!-- Step 2: Profile & Bio -->
                            @if ($currentStep == 2)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Profile & Bio</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Set up the user's profile information and biography.</p>
                                    </div>

                                    <!-- Profile Image -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Profile Image
                                        </label>
                                        <div class="flex items-center space-x-6">
                                            @if ($existing_profile_image)
                                                <div class="flex-shrink-0">
                                                    <div class="relative">
                                                        <img src="{{ $existing_profile_image }}"
                                                             alt="Current profile image"
                                                             class="w-24 h-24 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                                                        <button type="button"
                                                                wire:click="removeProfileImage"
                                                                class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex-shrink-0">
                                                    <div class="w-24 h-24 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                                        {{ substr($name, 0, 2) ?? 'US' }}
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="flex-1">
                                                <x-flux::input type="file" wire:model="profile_image" accept="image/*" />
                                                @error('profile_image')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                                @enderror
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    PNG, JPG, GIF up to 2MB. Recommended: 400x400px
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bio -->
                                    <x-flux::textarea label="Bio" wire:model="bio"
                                        placeholder="Write a short biography about this user..."
                                        rows="4"
                                        helper="This will be displayed on the team page if the user is a team member" />
                                </div>
                            @endif

                            <!-- Step 3: Security -->
                            @if ($currentStep == 3)
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Security Settings</h2>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $editingId ? 'Update the password if needed.' : 'Set up the user\'s password.' }}
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Password -->
                                        <x-flux::input label="{{ $editingId ? 'New Password' : 'Password' }}"
                                            wire:model="password"
                                            type="password"
                                            placeholder="{{ $editingId ? 'Leave blank to keep current' : 'Enter password' }}" />

                                        <!-- Confirm Password -->
                                        <x-flux::input label="Confirm Password"
                                            wire:model="password_confirmation"
                                            type="password"
                                            placeholder="Confirm password" />
                                    </div>

                                    @if ($editingId && empty($password))
                                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                                        Password Notice
                                                    </h3>
                                                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                                        <p>Leave password fields blank to keep the current password.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Image Optimization Settings -->
                                    <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Profile Image Optimization</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <x-flux::input label="Width (px)" wire:model="imageWidth" type="number"
                                                min="100" max="2000" />
                                            <x-flux::input label="Height (px)" wire:model="imageHeight" type="number"
                                                min="100" max="2000" />
                                            <x-flux::input label="Quality (%)" wire:model="imageQuality" type="number"
                                                min="10" max="100" />
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            These settings apply to profile image uploads. Recommended: 400x400px at 85% quality.
                                        </p>
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

                                @if ($currentStep < 3)
                                    <x-flux::button type="button" wire:click="nextStep" class="cursor-pointer">
                                        Next Step →
                                    </x-flux::button>
                                @else
                                    <x-flux::button type="submit" class="cursor-pointer">
                                        ✓ {{ $editingId ? 'Update User' : 'Create User' }}
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
