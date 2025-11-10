<div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
  <!-- Header -->
  <div class="px-6 py-4 border-b border-gray-200">
    <div class="animate-pulse" role="status" aria-hidden="true">
      <div class="h-5 w-48 bg-gray-200 dark:bg-gray-700 rounded mb-2"></div>
      <div class="h-3 w-64 bg-gray-100 dark:bg-gray-700 rounded"></div>
    </div>
  </div>

  <!-- Persons Grid -->
  <div class="p-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @for ($i = 0; $i < 6; $i++)
      <div class="group block bg-white rounded-lg border border-gray-100 hover:border-blue-300 hover:shadow-md transition-all duration-200 overflow-hidden animate-pulse" aria-hidden="true">
        <!-- Image Container -->
        <div class="relative overflow-hidden bg-gray-100">
          <div class="w-full h-48 bg-gray-200 dark:bg-gray-700"></div>

          <!-- Status Badge placeholder -->
          <div class="absolute top-2 right-2">
            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
              <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
              <span class="h-3 w-12 bg-gray-200 rounded"></span>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="p-4">
          <!-- Title -->
          <div class="h-4 w-3/4 bg-gray-200 dark:bg-gray-700 rounded mb-3"></div>

          <!-- Profession & Age -->
          <div class="flex items-center justify-between text-xs">
            <div class="h-3 w-28 bg-gray-100 dark:bg-gray-700 rounded"></div>
            <div class="h-3 w-12 bg-gray-100 dark:bg-gray-700 rounded"></div>
          </div>
        </div>
      </div>
      @endfor
    </div>

    <!-- View All Button placeholder -->
    <div class="mt-6 text-center">
      <div class="inline-block h-9 w-44 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
    </div>
  </div>
</div>

</div>
