<div>
    <!-- Hero Section -->
    <section class="relative py-16 bg-gradient-to-r from-primary-600 to-primary-800 dark:from-gray-900 dark:to-gray-800">
        <div class="absolute inset-0 bg-black/80 dark:bg-black/40"></div>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-6">
                    <span class="text-white text-sm font-medium">About {{ site_name() }}</span>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    About {{ site_name() }}
                </h1>
                <p class="text-xl text-white/90 max-w-3xl mx-auto leading-relaxed">
                    Your trusted source for comprehensive biographies, career insights, and professional profiles of notable personalities worldwide.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content with Sidebar -->
    <section class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-6 gap-8">
                <!-- Main Content (3 columns) -->
                <div class="lg:col-span-4">
                    <!-- About Us Content -->
                    <article class="mb-12">
                        <div class="prose prose-lg max-w-none dark:prose-invert prose-headings:font-bold prose-h2:text-2xl prose-h3:text-xl prose-a:text-primary-600 hover:prose-a:text-primary-700 prose-img:rounded-lg">
                            {!! $page->content !!}
                        </div>
                    </article>

                    <!-- What We Offer Section -->
                    <section class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">What We Offer</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Card 1 -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-300">
                                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Comprehensive Profiles</h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Detailed biographies with career timelines, achievements, and personal insights for thousands of notable personalities.
                                </p>
                            </div>

                            <!-- Card 2 -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-300">
                                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Career Insights</h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    In-depth analysis of professional journeys, major milestones, and industry contributions across various fields.
                                </p>
                            </div>

                            <!-- Card 3 -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-300">
                                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Verified Information</h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    All content is thoroughly researched and verified to ensure accuracy and reliability for our readers.
                                </p>
                            </div>

                            <!-- Card 4 -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-300">
                                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Regular Updates</h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Profiles are continuously updated with the latest information, news, and career developments.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Our Team Section -->
                    <section class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Our Team</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @php
                                // Fetch team members
                                $teamMembers = \App\Models\User::where('status', 'active')
                                    ->where(function($query) {
                                        $query->where('role', 'editor')
                                            ->orWhere('role', 'admin')
                                            ->orWhere('role', 'author')
                                            ->orWhere('is_team_member', true);
                                    })
                                    ->limit(6)
                                    ->get();
                            @endphp

                            @forelse($teamMembers as $member)
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-6 text-center">
                                    @if($member->profile_image_url)
                                        <!-- Profile Image with optimized size -->
                                        <div class="w-20 h-20 rounded-full mx-auto mb-4 overflow-hidden border-2 border-primary-200 dark:border-primary-700">
                                            <img
                                                src="{{ $member->imageSize(80, 80) ?? $member->profile_image_url }}"
                                                alt="{{ $member->name }}"
                                                class="w-full h-full object-cover"
                                                loading="lazy"
                                            >
                                        </div>
                                    @else
                                        <!-- Fallback Initials -->
                                        <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-xl font-bold">
                                            {{ substr($member->name, 0, 2) }}
                                        </div>
                                    @endif

                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $member->name }}</h3>
                                    <p class="text-primary-600 dark:text-primary-400 text-sm font-medium mb-3">
                                        {{ $member->getRoleDisplayName() }}
                                    </p>
                                    @if($member->bio)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                                            {{ Str::limit($member->bio, 100) }}
                                        </p>
                                    @else
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                                            Dedicated team member contributing to {{ site_name() }}'s mission.
                                        </p>
                                    @endif
                                </div>
                            @empty
                                <!-- Fallback team members if no users found -->
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 text-center">
                                    <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-xl font-bold">
                                        TM
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Our Team</h3>
                                    <p class="text-primary-600 dark:text-primary-400 text-sm font-medium mb-3">Expert Researchers</p>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                                        Meet our dedicated team of researchers and content creators.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>

                <!-- Sidebar (1 column) -->
                <div class="lg:col-span-2">
                    @php
                        // Fetch statistics from People model
                        $totalPeople = \App\Models\People::active()->verified()->count();
                        $verifiedPeople = \App\Models\People::verified()->count();
                        $alivePeople = \App\Models\People::alive()->count();

                        // Get unique professions count
                        $totalProfessions = \App\Models\People::active()->verified()
                            ->get()
                            ->flatMap(function($person) {
                                return $person->professions ?? [];
                            })
                            ->unique()
                            ->count();
                    @endphp

                    <!-- Biography Summary Stats -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-6 mb-6 sticky top-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 uppercase">Key Figures</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Total Profiles</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($totalPeople) }}+</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Total Professions</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($totalProfessions) }}+</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Verified Profiles</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($verifiedPeople) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Living Personalities</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($alivePeople) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact & Support Section -->
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-xl shadow border border-primary-200 dark:border-primary-700 p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Contact & Support</h3>
                        <div class="space-y-4">
                            @php
                                use App\Helpers\SiteSettingsHelper
                            @endphp
                            @if(SiteSettingsHelper::siteEmail())
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Email Support</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ SiteSettingsHelper::siteEmail() }}</p>
                                </div>
                            </div>
                            @endif

                            @if(SiteSettingsHelper::sitePhone())
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Phone Support</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ SiteSettingsHelper::sitePhone() }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-1"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Live Chat</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Available 24/7</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <a href="/p/contact-us" class="text-sm font-medium text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                                        Help Center
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">FAQ & Guides</p>
                                </div>
                            </div>
                        </div>

                        @if(SiteSettingsHelper::siteEmail())
                        <a href="mailto:{{ SiteSettingsHelper::siteEmail() }}" class="w-full mt-6 bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 block text-center">
                            Contact Us
                        </a>
                        @else
                        <button class="w-full mt-6 bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200">
                            Contact Us
                        </button>
                        @endif
                    </div>

                    <!-- AdSense Ad Unit (Example) -->
                    <div class="mt-6 p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Advertisement</p>
                        <div class="bg-gray-100 dark:bg-gray-700 h-60 flex items-center justify-center rounded">
                            <span class="text-gray-400 dark:text-gray-500">AdSense Unit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
