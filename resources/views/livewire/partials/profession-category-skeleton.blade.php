<div>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
  {{-- Header --}}
  <div class="bg-linear-to-r from-blue-600 to-purple-700 px-6 py-4">
    <div class="animate-pulse" role="status" aria-hidden="true">
      <div class="h-5 w-56 bg-white/30 rounded mb-2"></div>
      <div class="h-3 w-72 bg-white/20 rounded"></div>
    </div>
  </div>

  {{-- Categories List (skeleton rows) --}}
  <div class="divide-y divide-gray-100">
    @for ($i = 0; $i < 6; $i++)
    <div class="group hover:bg-blue-50 transition-colors duration-200">
      <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center space-x-3">
          {{-- Icon placeholder --}}
          <div class="w-10 h-10 rounded-lg flex items-center justify-center transform transition-transform duration-200">
            <div class="h-10 w-10 rounded-lg bg-gray-200 dark:bg-gray-700 animate-pulse" aria-hidden="true"></div>
          </div>

          {{-- Text placeholders --}}
          <div class="min-w-0">
            <div class="h-4 w-36 bg-gray-200 dark:bg-gray-700 rounded mb-2 animate-pulse" aria-hidden="true"></div>
            <div class="h-3 w-20 bg-gray-100 dark:bg-gray-700 rounded animate-pulse" aria-hidden="true"></div>
          </div>
        </div>

        {{-- arrow / count placeholder --}}
        <div class="flex items-center space-x-3">
          <div class="h-4 w-12 bg-gray-100 dark:bg-gray-700 rounded-full animate-pulse" aria-hidden="true"></div>
          <div class="h-4 w-4 bg-gray-100 dark:bg-gray-700 rounded animate-pulse" aria-hidden="true"></div>
        </div>
      </div>
    </div>
    @endfor
  </div>

  {{-- Footer --}}
  <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
    <div class="flex items-center justify-center">
      <div class="h-4 w-40 bg-gray-200 dark:bg-gray-700 rounded animate-pulse" aria-hidden="true"></div>
    </div>
  </div>
</div>

</div>
