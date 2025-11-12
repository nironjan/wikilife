<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')

</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('webmaster.dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse"
            wire:navigate>
            <x-app-logo />
        </a>

        @if (auth()->user()->isAdmin())
        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')" class="grid mb-2">
                <flux:navlist.item icon="home" :href="route('webmaster.dashboard')"
                    :current="request()->routeIs('webmaster.dashboard')" wire:navigate>{{ __('Dashboard') }}
                </flux:navlist.item>
            </flux:navlist.group>

            {{-- Persons --}}
            <div x-data="{ open: false }" class="mb-2">
                <button @click="open = !open"
                    class="flex items-center cursor-pointer justify-between w-full text-left font-semibold text-gray-700 hover:text-primary-600">
                    <span>{{ __('Persons') }}</span>
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9l7 7 7-7" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 15l-7-7-7 7" />
                    </svg>
                </button>

                <div x-show="open" x-collapse>
                    <flux:navlist.group class="grid mt-2">
                        <flux:navlist.item icon="users" :href="route('webmaster.persons.index')"
                            :current="request()->routeIs('webmaster.persons.index')" wire:navigate>
                            {{ __('Persons') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="cog-6-tooth" :href="route('webmaster.persons.settings.index')"
                            :current="request()->routeIs('webmaster.persons.settings.index')" wire:navigate>
                            {{ __('Settings') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                </div>
            </div>

            {{-- Person Management --}}

            <div x-data="{ open: false }" class="mb-2">
                <button @click="open = !open"
                    class="flex items-center cursor-pointer justify-between w-full text-left font-semibold text-gray-700 hover:text-primary-600">
                    <span>{{ __('Person Management') }}</span>
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9l7 7 7-7" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 15l-7-7-7 7" />
                    </svg>
                </button>

                <div x-show="open" x-collapse>
                    <flux:navlist.group class="grid mt-2">
                        <flux:navlist.item icon="pencil-square" :href="route('webmaster.persons.latest-update.index')"
                            :current="request()->routeIs('webmaster.persons.latest-update.*')" wire:navigate>
                            {{ __('Latest Update') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="book-open" :href="route('webmaster.persons.education.index')"
                            :current="request()->routeIs('webmaster.persons.education.*')" wire:navigate>
                            {{ __('Educations') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="link" :href="route('webmaster.persons.relation.index')"
                            :current="request()->routeIs('webmaster.persons.relation.*')" wire:navigate>
                            {{ __('Relations') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="trophy" :href="route('webmaster.persons.award.index')"
                            :current="request()->routeIs('webmaster.persons.award.*')" wire:navigate>
                            {{ __('Awards') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="speaker-wave" :href="route('webmaster.persons.interviews.index')"
                            :current="request()->routeIs('webmaster.persons.interviews.*')" wire:navigate>
                            {{ __('Interviews') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="bell" :href="route('webmaster.persons.facts.index')"
                            :current="request()->routeIs('webmaster.persons.facts.*')" wire:navigate>
                            {{ __('Facts') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="bell" :href="route('webmaster.persons.controversies.index')"
                            :current="request()->routeIs('webmaster.persons.controversies.*')" wire:navigate>
                            {{ __('Controversies') }}
                        </flux:navlist.item>

                    </flux:navlist.group>
                </div>
            </div>

            {{-- Career Management --}}

            <div x-data="{ open: false }" class="mb-2">
                <button @click="open = !open"
                    class="flex items-center cursor-pointer justify-between w-full text-left font-semibold text-gray-700 hover:text-primary-600">
                    <span>{{ __('Career Management') }}</span>
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9l7 7 7-7" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 15l-7-7-7 7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse>
                    <flux:navlist.group class="grid mb-2">
                        <flux:navlist.item icon="building-office-2" :href="route('webmaster.persons.politician.index')"
                            :current="request()->routeIs('webmaster.persons.politician.*')" wire:navigate>
                            {{ __('Politician') }}</flux:navlist.item>

                        <flux:navlist.item icon="star" :href="route('webmaster.persons.sports.index')"
                            :current="request()->routeIs('webmaster.persons.sports.*')" wire:navigate>
                            {{ __('Sports Men') }}</flux:navlist.item>

                        <flux:navlist.item icon="film" :href="route('webmaster.persons.filmography.index')"
                            :current="request()->routeIs('webmaster.persons.filmography.*')" wire:navigate>
                            {{ __('Filmography') }}</flux:navlist.item>



                        <flux:navlist.item icon="book-open" :href="route('webmaster.persons.literature.index')"
                            :current="request()->routeIs('webmaster.persons.literature.*')" wire:navigate>
                            {{ __('Liturature') }}</flux:navlist.item>

                        <flux:navlist.item icon="briefcase" :href="route('webmaster.persons.entrepreneur.index')"
                            :current="request()->routeIs('webmaster.persons.entrepreneur.*')" wire:navigate>
                            {{ __('Entrepreneur') }}</flux:navlist.item>
                    </flux:navlist.group>
                </div>
            </div>

            {{-- Site Management --}}
            <div x-data="{ open: false }" class="mb-2">
                <button @click="open = !open"
                    class="flex items-center cursor-pointer justify-between w-full text-left font-semibold text-gray-700 hover:text-primary-600">
                    <span>{{ __('Site Management') }}</span>
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9l7 7 7-7" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 15l-7-7-7 7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse>
                    <flux:navlist.group class="grid mb-2">
                        <flux:navlist.item icon="cog" :href="route('webmaster.site-setting')"
                            :current="request()->routeIs('webmaster.site-setting')" wire:navigate>
                            {{ __('Site Setting') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="queue-list" :href="route('webmaster.menus')"
                            :current="request()->routeIs('webmaster.menus.*')" wire:navigate>
                            {{ __('Menus') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                </div>
            </div>

            {{-- CMS Management --}}
            <div x-data="{ open: false }" class="mb-2">
                <button @click="open = !open"
                    class="flex items-center cursor-pointer justify-between w-full text-left font-semibold text-gray-700 hover:text-primary-600">
                    <span>{{ __('CMS Management') }}</span>
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9l7 7 7-7" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 15l-7-7-7 7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse>
                    <flux:navlist.group class="grid mb-2">
                        <flux:navlist.item icon="tag" :href="route('webmaster.blog.categories.index')"
                            :current="request()->routeIs('webmaster.blog.categories.*')" wire:navigate>
                            {{ __('Categories') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="queue-list" :href="route('webmaster.blog.posts.index')"
                            :current="request()->routeIs('webmaster.blog.posts.*')" wire:navigate>
                            {{ __('Posts') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="clipboard-document-list" :href="route('webmaster.pages.index')"
                            :current="request()->routeIs('webmaster.pages.*')" wire:navigate>
                            {{ __('Pages') }}
                        </flux:navlist.item>



                    </flux:navlist.group>
                </div>
            </div>

            {{-- User Management --}}
            <div x-data="{ open: false }" class="mb-2">
                <button @click="open = !open"
                    class="flex items-center cursor-pointer justify-between w-full text-left font-semibold text-gray-700 hover:text-primary-600">
                    <span>{{ __('User Management') }}</span>
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9l7 7 7-7" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 15l-7-7-7 7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse>
                    <flux:navlist.group class="grid mb-2">
                        <flux:navlist.item icon="user" :href="route('webmaster.users.index')"
                            :current="request()->routeIs('webmaster.users.*')" wire:navigate>
                            {{ __('Users') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                </div>
            </div>

            {{-- Feedback Management --}}
            <div x-data="{ open: false }" class="mb-2">
                <button @click="open = !open"
                    class="flex items-center cursor-pointer justify-between w-full text-left font-semibold text-gray-700 hover:text-primary-600">
                    <span>{{ __('Feedback Management') }}</span>
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9l7 7 7-7" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 15l-7-7-7 7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse>
                    <flux:navlist.group class="grid mb-2">
                        <flux:navlist.item icon="envelope" :href="route('webmaster.feedback.index')"
                            :current="request()->routeIs('webmaster.feedback.*')" wire:navigate>
                            {{ __('Feedback') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                </div>
            </div>

        </flux:navlist>
        @endif

        <flux:spacer />


        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon:trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
    <x-toaster-hub />

     {{-- ✅ Quill Editor Global --}}
    @assets
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    @endassets

    <script>
        document.addEventListener('alpine:init', () => {
    if (!Alpine.data('quillEditor')) {
        Alpine.data('quillEditor', (livewireContent, placeholderText, editorHeight, toolbarType) => ({
            quill: null,
            initialized: false,
            placeholder: placeholderText,
            height: editorHeight,
            toolbar: toolbarType,
            livewireContent,

            init() {
                // 🧠 Prevent double initialization
                if (this.initialized) return;
                this.initialized = true;
                this.$nextTick(() => this.initializeQuill());
            },

            initializeQuill() {
                // 🧱 Create editor DOM node
                const editorElement = document.createElement('div');
                editorElement.style.height = this.height;
                this.$refs.editorContainer.appendChild(editorElement);

                // 🧰 Toolbar config
                const toolbars = {
                    full: [
                        [{ 'font': [] }, { 'size': [] }],
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        [{ 'direction': 'rtl' }],
                        [{ 'align': [] }],
                        ['blockquote'],
                        ['link', 'image'],
                        ['clean']
                    ],
                    basic: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        ['link', 'image'],
                        ['clean']
                    ],
                    minimal: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link'],
                        ['clean']
                    ]
                };
                const toolbarConfig = toolbars[this.toolbar] || toolbars.full;

                // 🪶 Initialize Quill
                this.quill = new Quill(editorElement, {
                    theme: 'snow',
                    modules: {
                        toolbar: toolbarConfig,
                        clipboard: { matchVisual: false },
                    },
                    placeholder: this.placeholder,
                });

                // 🔄 Set initial content
                if (this.livewireContent) {
                    this.quill.root.innerHTML = this.livewireContent;
                }

                // 🧩 Livewire sync
                this.quill.on('text-change', () => {
                    const html = this.quill.root.innerHTML;
                    this.livewireContent = html;
                    this.$refs.hiddenTextarea.value = html;
                    this.$refs.hiddenTextarea.dispatchEvent(new Event('input'));
                });

                // 🖼️ Image handler
                this.quill.getModule('toolbar').addHandler('image', () => this.selectLocalImage());

                // 🪄 React to external Livewire changes
                this.$watch('livewireContent', value => {
                    if (value && this.quill && value !== this.quill.root.innerHTML) {
                        this.quill.root.innerHTML = value;
                    }
                });
            },

            // 🧱 Helpers
            selectLocalImage() {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.click();

                input.onchange = () => {
                    const file = input.files[0];
                    if (file) this.insertBase64Image(file);
                };
            },

            insertBase64Image(file) {
                const reader = new FileReader();
                reader.onload = () => {
                    const range = this.quill.getSelection(true);
                    this.quill.insertEmbed(range.index, 'image', reader.result);
                    this.quill.setSelection(range.index + 1);
                };
                reader.readAsDataURL(file);
            },
        }));
    }
});
    </script>
</body>

</html>
