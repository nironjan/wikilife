<div>
    <footer class="bg-white border-t border-gray-200 mt-10" aria-labelledby="footer-heading">
        <h2 id="footer-heading" class="sr-only">Footer</h2>

        <div class="max-w-6xl mx-auto px-4 py-12">
            <!-- Main Footer Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

                <!-- Grid 1: About & Social -->
                <div class="space-y-6">
                    <!-- Logo & Site Info -->
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center shrink-0">
                            <span class="text-white font-bold text-lg">W</span>
                        </div>
                        <div class="">
                            @if($siteSettings && $siteSettings->tagline)
                            <h3 class="text-xl font-bold text-gray-900">
                                {{ $siteSettings->site_name }}
                            </h3>

                        @endif
                            @if($siteSettings && $siteSettings->tagline)
                                <p class="text-gray-600 text-xs leading-relaxed">
                                    {{ $siteSettings->tagline }}
                                </p>
                            @endif

                        </div>
                    </div>
                    @if($siteSettings && $siteSettings->meta_description)
                        <p class="text-gray-600 text-sm leading-relaxed">
                            {{ $siteSettings->meta_description }}
                        </p>
                    @endif



                    <!-- Social Links -->
                    @if(!empty($socialLinks))
                        <div class="space-y-3">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Follow Us</h4>
                            <div class="flex space-x-3">
                                @foreach($socialLinks as $platform => $url)
                                    @if($url)
                                        @php
                                            $socialIcon = $this->getSocialIcon($platform);
                                        @endphp
                                        <a
                                            href="{{ $url }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="bg-gray-100 hover:bg-red-600 p-3 rounded-lg transition-all duration-200 transform hover:scale-105 group"
                                            aria-label="Follow us on {{ $socialIcon['name'] }}"
                                        >
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-white transition-colors duration-200" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="{{ $socialIcon['svg'] }}"/>
                                            </svg>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Grid 2: Important Links -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
                    <ul class="space-y-3">
                        @forelse($footerMenus as $menu)
                            <li>
                                <a
                                    href="{{ $menu['url'] }}"
                                    target="{{ $menu['target'] }}"
                                    rel="{{ $menu['rel'] }}"
                                    class="flex items-center space-x-3 text-gray-600 hover:text-red-600 transition-all duration-200 group"
                                >
                                    @if($menu['svg_path'])
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-red-600 transition-colors duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['svg_path'] }}"/>
                                        </svg>
                                    @endif
                                    <span class="group-hover:translate-x-1 transition-transform duration-200">{{ $menu['name'] }}</span>
                                </a>
                            </li>
                        @empty
                            <li class="text-gray-400 text-sm">No links available</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Grid 3: Professions -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Browse Professions</h3>
                    <ul class="space-y-3">
                        @foreach($professions as $key => $profession)
                            <li>
                                <a
                                    href="{{ $this->getProfessionUrl($key) }}"
                                    class="flex items-center space-x-3 text-gray-600 hover:text-red-600 transition-all duration-200 group"
                                >
                                    <div class="w-1.5 h-1.5 bg-red-500 rounded-full group-hover:bg-red-600 transition-colors duration-200 shrink-0"></div>
                                    <span class="group-hover:translate-x-1 transition-transform duration-200">{{ $profession }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Grid 4: Contact & Newsletter -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Get In Touch</h3>
                    <div class="space-y-4">
                        @if($siteSettings)
                            @if($siteSettings->site_email)
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <a href="mailto:{{ $siteSettings->site_email }}" class="text-gray-600 hover:text-red-600 text-sm transition-colors duration-200">
                                        {{ $siteSettings->site_email }}
                                    </a>
                                </div>
                            @endif

                            @if($siteSettings->site_phone)
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <a href="tel:{{ $siteSettings->site_phone }}" class="text-gray-600 hover:text-red-600 text-sm transition-colors duration-200">
                                        {{ $siteSettings->site_phone }}
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Newsletter Signup -->
                    <div class="pt-4">
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">Stay Updated</h4>
                        <form class="space-y-2">
                            <input
                                type="email"
                                placeholder="Enter your email"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 text-sm placeholder-gray-500 focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-colors duration-200"
                                aria-label="Email for newsletter"
                            >
                            <button
                                type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-white text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-white"
                            >
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-200 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <!-- Copyright Text -->
                    <div class="text-gray-500 text-sm text-center md:text-left">
                        &copy; {{ date('Y') }} {{ $siteSettings->site_name ?? 'WikiLife' }}. All rights reserved.
                    </div>

                    <!-- Footer Bar Menu -->
                    @if(!empty($footerBarMenus))
                        <div class="flex flex-wrap justify-center md:justify-end items-center gap-4 md:gap-6">
                            @foreach($footerBarMenus as $menu)
                                <a
                                    href="{{ $menu['url'] }}"
                                    target="{{ $menu['target'] }}"
                                    rel="{{ $menu['rel'] }}"
                                    class="text-gray-500 hover:text-red-600 text-sm transition-colors duration-200 flex items-center space-x-1 group"
                                >
                                    @if($menu['svg_path'])
                                        <svg class="w-3 h-3 text-gray-400 group-hover:text-red-600 transition-colors duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['svg_path'] }}"/>
                                        </svg>
                                    @endif
                                    <span>{{ $menu['name'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </footer>
</div>
