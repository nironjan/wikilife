@if ($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $onFirstPage = $paginator->onFirstPage();
        $hasMorePages = $paginator->hasMorePages();

        // Calculate window for pagination links
        $window = 2; // Number of pages to show on each side of current page
        $start = max(1, $currentPage - $window);
        $end = min($lastPage, $currentPage + $window);
    @endphp

    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
        {{-- Previous Page Link --}}
        <div class="-mt-px flex w-0 flex-1">
            @if (!$onFirstPage)
                <button
                    wire:click="previousPage"
                    wire:loading.attr="disabled"
                    rel="prev"
                    aria-label="Previous Page"
                    class="inline-flex items-center cursor-pointer border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg class="mr-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1l-2.1 1.95h12.59A.75.75 0 0118 10z" clip-rule="evenodd" />
                    </svg>
                    Previous
                </button>
            @else
                <span class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-300 cursor-not-allowed">
                    <svg class="mr-3 h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1l-2.1 1.95h12.59A.75.75 0 0118 10z" clip-rule="evenodd" />
                    </svg>
                    Previous
                </span>
            @endif
        </div>

        {{-- Pagination Links --}}
        <div class="hidden md:-mt-px md:flex">
            {{-- First Page Link --}}
            @if ($start > 1)
                <button
                    wire:click="gotoPage(1)"
                    wire:loading.attr="disabled"
                    aria-label="Go to page 1"
                    class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    1
                </button>
                @if ($start > 2)
                    <span class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500">
                        ...
                    </span>
                @endif
            @endif

            {{-- Page Number Links --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $currentPage)
                    <span
                        aria-current="page"
                        class="inline-flex items-center border-t-2 border-blue-500 px-4 pt-4 text-sm font-medium text-blue-600"
                    >
                        {{ $page }}
                    </span>
                @else
                    <button
                        wire:click="gotoPage({{ $page }})"
                        wire:loading.attr="disabled"
                        aria-label="Go to page {{ $page }}"
                        class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ $page }}
                    </button>
                @endif
            @endfor

            {{-- Last Page Link --}}
            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <span class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500">
                        ...
                    </span>
                @endif
                <button
                    wire:click="gotoPage({{ $lastPage }})"
                    wire:loading.attr="disabled"
                    aria-label="Go to page {{ $lastPage }}"
                    class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ $lastPage }}
                </button>
            @endif
        </div>

        {{-- Mobile Page Info --}}
        <div class="flex md:hidden items-center space-x-2">
            <span class="text-sm text-gray-700">
                Page <span class="font-medium">{{ $currentPage }}</span> of <span class="font-medium">{{ $lastPage }}</span>
            </span>
        </div>

        {{-- Next Page Link --}}
        <div class="-mt-px flex w-0 flex-1 justify-end">
            @if ($hasMorePages)
                <button
                    wire:click="nextPage"
                    wire:loading.attr="disabled"
                    rel="next"
                    aria-label="Next Page"
                    class="inline-flex items-center cursor-pointer border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Next
                    <svg class="ml-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd" />
                    </svg>
                </button>
            @else
                <span class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-300 cursor-not-allowed">
                    Next
                    <svg class="ml-3 h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>

    {{-- Mobile Simple Pagination --}}
    <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6 md:hidden">
        <div class="flex flex-1 justify-between sm:hidden">
            @if (!$onFirstPage)
                <button
                    wire:click="previousPage"
                    wire:loading.attr="disabled"
                    rel="prev"
                    class="relative inline-flex items-center cursor-pointer rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Previous
                </button>
            @else
                <span class="relative inline-flex items-center  rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-300 cursor-not-allowed">
                    Previous
                </span>
            @endif

            @if ($hasMorePages)
                <button
                    wire:click="nextPage"
                    wire:loading.attr="disabled"
                    rel="next"
                    class="relative ml-3 inline-flex items-center cursor-pointer rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Next
                </button>
            @else
                <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-300 cursor-not-allowed">
                    Next
                </span>
            @endif
        </div>
    </div>
@else
    {{-- No pagination needed for single page --}}
    <div class="border-t border-gray-200 px-4 py-4 sm:px-6">
        <p class="text-sm text-gray-500 text-center">
            Showing all {{ $paginator->count() }} results
        </p>
    </div>
@endif
