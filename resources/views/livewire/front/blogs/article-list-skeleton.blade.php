<div class="min-h-screen bg-gray-50 py-8 animate-pulse">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-8 space-y-8">
                {{-- Header --}}
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-blue-200 rounded-lg"></div>
                    <div>
                        <div class="h-5 bg-gray-200 rounded w-40 mb-2"></div>
                        <div class="h-4 bg-gray-200 rounded w-64"></div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="w-full lg:w-1/2 h-10 bg-gray-200 rounded-lg"></div>
                        <div class="flex gap-3">
                            <div class="w-36 h-10 bg-gray-200 rounded-lg"></div>
                            <div class="w-32 h-10 bg-gray-200 rounded-lg"></div>
                        </div>
                    </div>
                </div>

                {{-- Article Cards --}}
                <div class="space-y-6">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="bg-white rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                            <div class="flex flex-col md:flex-row">
                                {{-- Image Placeholder --}}
                                <div class="md:w-2/5 h-56 bg-gray-200"></div>

                                {{-- Content --}}
                                <div class="md:w-3/5 p-4 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <div class="h-4 w-24 bg-gray-200 rounded"></div>
                                        <div class="h-4 w-20 bg-gray-200 rounded"></div>
                                    </div>
                                    <div class="h-5 w-3/4 bg-gray-200 rounded"></div>
                                    <div class="space-y-2">
                                        <div class="h-4 w-full bg-gray-200 rounded"></div>
                                        <div class="h-4 w-5/6 bg-gray-200 rounded"></div>
                                        <div class="h-4 w-2/3 bg-gray-200 rounded"></div>
                                    </div>
                                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                        <div class="h-4 w-24 bg-gray-200 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-4 space-y-8">
                {{-- Categories --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="h-5 w-40 bg-gray-200 rounded mb-6"></div>
                    <div class="space-y-3">
                        @for ($i = 0; $i < 6; $i++)
                            <div class="flex items-center justify-between py-2">
                                <div class="h-4 w-32 bg-gray-200 rounded"></div>
                                <div class="h-4 w-8 bg-gray-200 rounded"></div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Popular Articles --}}
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                    <div class="h-5 w-36 bg-gray-200 rounded mb-6"></div>
                    <div class="space-y-4">
                        @for ($i = 0; $i < 5; $i++)
                            <div class="flex items-start space-x-3">
                                <div class="w-12 h-12 bg-gray-200 rounded-lg"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 w-3/4 bg-gray-200 rounded"></div>
                                    <div class="h-3 w-1/2 bg-gray-200 rounded"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
