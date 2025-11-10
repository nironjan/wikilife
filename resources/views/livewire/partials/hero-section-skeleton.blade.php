<div class="animate-pulse bg-gradient-to-br from-white via-red-50/30 to-white py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            <!-- Left Column -->
            <div class="space-y-8">
                <!-- Badge -->
                <div class="h-6 w-40 bg-gray-200 rounded-full"></div>

                <!-- Heading -->
                <div class="space-y-3">
                    <div class="h-10 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-8 bg-gray-200 rounded w-1/2"></div>
                </div>

                <!-- Subheading -->
                <div class="space-y-2">
                    <div class="h-4 bg-gray-200 rounded w-full"></div>
                    <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                    <div class="h-4 bg-gray-200 rounded w-4/6"></div>
                </div>

                <!-- Search Box -->
                <div class="max-w-md">
                    <div class="h-14 bg-gray-200 rounded-xl shadow-sm"></div>
                </div>

                <!-- Quick Stats -->
                <div class="flex gap-6 pt-4">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="text-center space-y-2">
                            <div class="h-6 w-16 bg-gray-200 rounded mx-auto"></div>
                            <div class="h-3 w-20 bg-gray-200 rounded mx-auto"></div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Right Column -->
            <div class="relative">
                <div class="relative bg-gray-100 rounded-3xl p-8 lg:p-12 overflow-hidden">
                    <!-- Background -->
                    <div class="absolute inset-0 bg-gray-200 opacity-30"></div>

                    <!-- Illustration Placeholder -->
                    <div class="relative z-10 flex flex-col items-center space-y-8">
                        <div class="flex justify-center space-x-6">
                            <div class="w-16 h-20 bg-gray-200 rounded-lg"></div>
                            <div class="w-20 h-20 bg-gray-200 rounded-full"></div>
                        </div>

                        <div class="flex justify-center space-x-4">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="w-12 h-16 bg-gray-200 rounded-t-full"></div>
                            @endfor
                        </div>

                        <div class="flex justify-center space-x-6">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                            @endfor
                        </div>
                    </div>

                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 w-8 h-8 bg-gray-200 rounded-full opacity-40"></div>
                    <div class="absolute -bottom-4 -left-4 w-6 h-6 bg-gray-200 rounded-full opacity-40"></div>
                    <div class="absolute top-1/2 -right-6 w-4 h-4 bg-gray-200 rounded-full opacity-40"></div>
                </div>
            </div>

        </div>
    </div>
</div>
