@props(['value' => '', 'placeholder' => 'Write content...'])

<div
    x-data="markdownEditor(@entangle($attributes->wire('model')), @js($placeholder))"
    x-init="init()"
    wire:ignore
    {{ $attributes->whereDoesntStartWith('wire:model') }}
    class="markdown-editor bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600"
>
    <!-- Simplified Toolbar -->
    <div class="flex flex-wrap items-center gap-2 p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
        <!-- Text Formatting -->
        <div class="flex items-center gap-1">
            <!-- Bold -->
            <button type="button" @click="formatText('**', '**')"
                class="py-1 px-3 text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-600 rounded border border-gray-300 dark:border-gray-500 transition-colors"
                title="Bold (Ctrl+B)">
                <span class="font-bold text-sm">B</span>
            </button>

            <!-- Italic -->
            <button type="button" @click="formatText('*', '*')"
                class="py-1 px-3 text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-600 rounded border border-gray-300 dark:border-gray-500 transition-colors"
                title="Italic (Ctrl+I)">
                <span class="italic text-sm">I</span>
            </button>
        </div>

        <!-- Lists -->
        <div class="flex items-center gap-1">
            <!-- Bullet List -->
            <button type="button" @click="insertBulletList()"
                class="p-2 text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-600 rounded border border-gray-300 dark:border-gray-500 transition-colors"
                title="Bullet List">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Numbered List -->
            <button type="button" @click="insertNumberedList()"
                class="p-2 text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-600 rounded border border-gray-300 dark:border-gray-500 transition-colors"
                title="Numbered List">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
            </button>
        </div>

        <!-- Links & Quotes -->
        <div class="flex items-center gap-1">
            <!-- Link -->
            <button type="button" @click="insertLink()"
                class="p-2 text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-600 rounded border border-gray-300 dark:border-gray-500 transition-colors"
                title="Insert Link (Ctrl+K)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </button>

            <!-- Quote -->
            <button type="button" @click="insertQuote()"
                class="p-2 text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-600 rounded border border-gray-300 dark:border-gray-500 transition-colors"
                title="Blockquote">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            </button>
        </div>

        <!-- Table -->
        <div class="flex items-center gap-1">
            <button type="button" @click="insertTable()"
                class="p-2 text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-600 rounded border border-gray-300 dark:border-gray-500 transition-colors"
                title="Insert Table">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </button>
        </div>

        <!-- Preview Toggle -->
        <div class="ml-auto flex items-center gap-2">
            <button type="button" @click="previewMode = !previewMode"
                class="px-3 py-1.5 text-sm bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors font-medium">
                <span x-text="previewMode ? '✏️ Edit' : '👁️ Preview'"></span>
            </button>
        </div>
    </div>

    <!-- Editor/Preview Area -->
    <div class="flex flex-col md:flex-row">
        <!-- Editor -->
        <div class="flex-1" :class="{ 'hidden': previewMode }">
            <textarea
                x-ref="textarea"
                x-model="localContent"
                @input="updateLivewire()"
                :placeholder="placeholder"
                class="w-full h-96 px-6 py-4 text-gray-900 dark:text-white bg-white dark:bg-gray-800 border-0 focus:ring-0 resize-none font-sans text-base leading-relaxed"
                style="min-height: 400px;"
            ></textarea>
        </div>

        <!-- Preview -->
        <div x-show="previewMode"
            class="flex-1 px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t md:border-t-0 md:border-l border-gray-200 dark:border-gray-600 overflow-auto prose prose-gray dark:prose-invert max-w-none"
            x-html="previewContent"
            style="min-height: 400px; max-height: 500px;"
        ></div>
    </div>

    <!-- Character Count & Help -->
    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 text-xs text-gray-500 dark:text-gray-400">
        <div class="flex justify-between items-center">
            <div>
                <span x-text="`${localContent.length} characters`"></span>
                <span class="mx-2">•</span>
                <button @click="showHelp = !showHelp" class="hover:text-gray-700 dark:hover:text-gray-300 underline">
                    Markdown Help
                </button>
            </div>
            <div x-show="previewMode" class="text-green-600 dark:text-green-400">
                Preview Mode
            </div>
        </div>

        <!-- Quick Help -->
        <div x-show="showHelp" class="mt-2 p-3 bg-white dark:bg-gray-600 rounded border border-gray-200 dark:border-gray-500">
            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <p><strong>**Bold**</strong> → <strong>Bold</strong></p>
                    <p><em>*Italic*</em> → <em>Italic</em></p>
                    <p><code>`Code`</code> → <code>Code</code></p>
                </div>
                <div>
                    <p><strong>Lists:</strong></p>
                    <p>- Bullet item</p>
                    <p>1. Numbered item</p>
                    <p><strong>Quote:</strong> > Text</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function markdownEditor(livewireContent, placeholderText) {
        return {
            livewireContent: livewireContent,
            localContent: '',
            previewMode: false,
            showHelp: false,
            placeholder: placeholderText,

            init() {
                console.log('🔄 Markdown Editor Initializing...');
                console.log('Initial Livewire content:', this.livewireContent ? this.livewireContent.substring(0, 50) + '...' : 'EMPTY');

                // Initialize with Livewire content
                this.localContent = this.livewireContent || '';

                // Watch for external changes from Livewire
                this.$watch('livewireContent', (value) => {
                    console.log('🔄 Livewire content changed:', value ? value.substring(0, 50) + '...' : 'EMPTY');
                    if (value !== this.localContent) {
                        this.localContent = value || '';
                    }
                });

                // Setup keyboard shortcuts
                this.$nextTick(() => {
                    this.setupKeyboardShortcuts();
                });
            },

            get previewContent() {
                return this.parseMarkdown(this.localContent);
            },

            parseMarkdown(text) {
                if (!text) return '<p class="text-gray-500 italic">Preview will appear here...</p>';

                return text
                    // Headers
                    .replace(/^#### (.*$)/gim, '<h4 class="text-base font-semibold mt-2 mb-1">$1</h4>')
                    .replace(/^### (.*$)/gim, '<h3 class="text-lg font-semibold mt-4 mb-2">$1</h3>')
                    .replace(/^## (.*$)/gim, '<h2 class="text-xl font-semibold mt-6 mb-3 border-b pb-1">$1</h2>')
                    .replace(/^# (.*$)/gim, '<h1 class="text-2xl font-bold mt-8 mb-4 border-b pb-2">$1</h1>')

                    // Bold & Italic
                    .replace(/\*\*(.*?)\*\*/gim, '<strong class="font-semibold">$1</strong>')
                    .replace(/\*(.*?)\*/gim, '<em class="italic">$1</em>')
                    .replace(/_(.*?)_/gim, '<em class="italic">$1</em>')

                    // Links
                    .replace(/\[([^\[]+)\]\(([^\)]+)\)/gim,
                        '<a href="$2" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline" target="_blank" rel="noopener">$1</a>'
                    )

                    // Lists
                    .replace(/^\s*[\-\*\+] (.*$)/gim, '<ul class="list-disc list-inside ml-4 my-2"><li>$1</li></ul>')
                    .replace(/^\s*\d+\. (.*$)/gim, '<ol class="list-decimal list-inside ml-4 my-2"><li>$1</li></ol>')

                    // Blockquotes
                    .replace(/^> (.*$)/gim,
                        '<blockquote class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 pl-4 py-2 my-3 italic text-gray-700 dark:text-gray-300">$1</blockquote>'
                    )

                    // Horizontal Rule (---, ***, ___)
                    .replace(/^\s*([-*_])\s*\1\s*\1\s*$/gim, '<hr class="my-6 border-gray-300 dark:border-gray-600">')

                    // Tables
                    .replace(/\|(.+)\|\n\|[-:\s|]+\|\n((?:\|.*\|\n?)*)/g, (match, headers, rows) => {
                        const headerCells = headers.split('|').filter(cell => cell.trim()).map(cell =>
                            `<th class="px-4 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-left font-semibold">${cell.trim()}</th>`
                        ).join('');

                        const rowLines = rows.trim().split('\n');
                        const tableRows = rowLines.map(row => {
                            const cells = row.split('|').filter(cell => cell.trim()).map(cell =>
                                `<td class="px-4 py-2 border border-gray-300 dark:border-gray-500">${cell.trim()}</td>`
                            ).join('');
                            return `<tr>${cells}</tr>`;
                        }).join('');

                        return `<div class="overflow-x-auto my-4"><table class="min-w-full border-collapse border border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-700"><thead><tr>${headerCells}</tr></thead><tbody>${tableRows}</tbody></table></div>`;
                    })

                    // Simple table rows
                    .replace(/^\|(.+)\|$/gim, (match, cells) => {
                        const cellContents = cells.split('|').filter(cell => cell.trim()).map(cell =>
                            `<td class="px-4 py-2 border border-gray-300 dark:border-gray-500">${cell.trim()}</td>`
                        ).join('');
                        return `<tr>${cellContents}</tr>`;
                    })

                    // Wrap tables
                    .replace(/(<tr>.*<\/tr>)/gim, (match) => {
                        if (!match.includes('<thead>') && !match.includes('<th>')) {
                            return `<div class="overflow-x-auto my-4"><table class="min-w-full border-collapse border border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-700"><tbody>${match}</tbody></table></div>`;
                        }
                        return match;
                    })

                    // Line breaks and paragraphs
                    .replace(/\n\n/gim, '</p><p class="my-3">')
                    .replace(/\n/gim, '<br>')
                    .replace(/^(?!<)(.*)$/gim, '<p class="my-3">$1</p>')
                    .replace(/<p class="my-3"><\/p>/gim, '')
                    .replace(/<p class="my-3"><br><\/p>/gim, '');
            },

            updateLivewire() {
                // Update Livewire with local content
                console.log('📝 Updating Livewire with:', this.localContent ? this.localContent.substring(0, 50) + '...' : 'EMPTY');
                this.livewireContent = this.localContent;
            },

            formatText(before, after) {
                const textarea = this.$refs.textarea;
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const selectedText = this.localContent.substring(start, end);

                if (selectedText) {
                    const newText = this.localContent.substring(0, start) + before + selectedText + after + this
                        .localContent.substring(end);
                    this.localContent = newText;

                    setTimeout(() => {
                        textarea.setSelectionRange(start + before.length, end + before.length);
                        textarea.focus();
                    }, 10);
                } else {
                    const newText = this.localContent.substring(0, start) + before + after + this.localContent
                        .substring(end);
                    this.localContent = newText;

                    setTimeout(() => {
                        textarea.setSelectionRange(start + before.length, start + before.length);
                        textarea.focus();
                    }, 10);
                }

                this.updateLivewire();
            },

            insertLink() {
                const url = prompt('Enter URL:');
                if (!url) return;

                const text = prompt('Enter link text (optional):') || url;
                this.formatText(`[${text}](`, `${url})`);
            },

            insertBulletList() {
                this.insertAtCursor('- ');
            },

            insertNumberedList() {
                this.insertAtCursor('1. ');
            },

            insertQuote() {
                this.insertAtCursor('> ');
            },

            insertTable() {
                const table =
                    `| Year | Event | Description |\n|------|-------|-------------|\n| 1990 | Born | Enter details |\n| 2010 | Achievement | More details |\n`;
                this.insertAtCursor('\n' + table + '\n');
            },

            insertAtCursor(text) {
                const textarea = this.$refs.textarea;
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;

                this.localContent = this.localContent.substring(0, start) + text + this.localContent.substring(end);

                setTimeout(() => {
                    textarea.setSelectionRange(start + text.length, start + text.length);
                    textarea.focus();
                }, 10);

                this.updateLivewire();
            },

            setupKeyboardShortcuts() {
                const textarea = this.$refs.textarea;

                textarea.addEventListener('keydown', (e) => {
                    if ((e.ctrlKey || e.metaKey)) {
                        switch (e.key) {
                            case 'b':
                                e.preventDefault();
                                this.formatText('**', '**');
                                break;
                            case 'i':
                                e.preventDefault();
                                this.formatText('*', '*');
                                break;
                            case 'k':
                                e.preventDefault();
                                this.insertLink();
                                break;
                        }
                    }
                });
            }
        };
    }
</script>

<style>
    .markdown-editor table {
        border-collapse: collapse;
        width: 100%;
        margin: 1rem 0;
    }

    .markdown-editor th,
    .markdown-editor td {
        border: 1px solid #e5e7eb;
        padding: 0.5rem 1rem;
        text-align: left;
    }

    .dark .markdown-editor th,
    .dark .markdown-editor td {
        border-color: #4b5563;
    }

    .markdown-editor th {
        background-color: #f9fafb;
        font-weight: 600;
    }

    .dark .markdown-editor th {
        background-color: #374151;
    }

    .markdown-editor blockquote {
        border-left: 4px solid #3b82f6;
        background-color: #eff6ff;
        padding: 1rem;
        margin: 1rem 0;
        font-style: italic;
    }

    .dark .markdown-editor blockquote {
        background-color: #1e3a8a;
        border-left-color: #60a5fa;
    }

    .markdown-editor ul,
    .markdown-editor ol {
        margin: 0.5rem 0;
        padding-left: 1.5rem;
    }

    .markdown-editor ul {
        list-style-type: disc;
    }

    .markdown-editor ol {
        list-style-type: decimal;
    }

    .markdown-editor a {
        color: #2563eb;
        text-decoration: underline;
    }

    .dark .markdown-editor a {
        color: #60a5fa;
    }

    .markdown-editor a:hover {
        color: #1d4ed8;
    }

    .dark .markdown-editor a:hover {
        color: #93c5fd;
    }
</style>
