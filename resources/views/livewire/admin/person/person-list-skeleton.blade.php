<div>
<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
  <!-- Header / Title -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div class="space-y-2 w-full sm:w-auto">
      <div class="h-6 w-48 bg-gray-200 dark:bg-gray-700 rounded animate-pulse" role="status" aria-hidden="true"></div>
      <div class="h-4 w-72 bg-gray-100 dark:bg-gray-700 rounded animate-pulse"></div>
    </div>

    <div class="w-full sm:w-auto flex justify-start sm:justify-end">
      <div class="h-10 w-36 rounded-md bg-gray-200 dark:bg-gray-700 animate-pulse"></div>
    </div>
  </div>

  <!-- Filters / Search -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="h-10 bg-gray-100 dark:bg-gray-700 rounded animate-pulse"></div>
    <div class="h-10 bg-gray-100 dark:bg-gray-700 rounded animate-pulse"></div>
    <div class="h-10 bg-gray-100 dark:bg-gray-700 rounded animate-pulse"></div>
  </div>

  <!-- Table skeleton -->
  <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
    <table class="min-w-full">
      <thead class="sr-only">
        <tr>
          <th>Person</th>
          <th>Profession</th>
          <th>Status</th>
          <th>Views</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        @for ($i = 0; $i < 6; $i++)
          <tr class="animate-pulse">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center space-x-4">
                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                <div class="space-y-2">
                  <div class="h-4 w-40 bg-gray-200 dark:bg-gray-700 rounded"></div>
                  <div class="h-3 w-28 bg-gray-100 dark:bg-gray-700 rounded"></div>
                </div>
              </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="h-4 w-36 bg-gray-100 dark:bg-gray-700 rounded"></div>
              <div class="h-3 w-20 bg-gray-100 dark:bg-gray-700 rounded mt-2"></div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="inline-block h-6 w-20 rounded-full bg-gray-200 dark:bg-gray-700"></div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="h-4 w-20 bg-gray-100 dark:bg-gray-700 rounded"></div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="h-4 w-24 bg-gray-100 dark:bg-gray-700 rounded"></div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-right">
              <div class="flex justify-end space-x-2">
                <div class="h-8 w-16 rounded bg-gray-200 dark:bg-gray-700"></div>
                <div class="h-8 w-16 rounded bg-gray-200 dark:bg-gray-700"></div>
              </div>
            </td>
          </tr>
        @endfor
      </tbody>
    </table>
  </div>

  <!-- Pagination / Footer skeleton -->
  <div class="mt-6 flex justify-between items-center">
    <div class="h-6 w-48 bg-gray-100 dark:bg-gray-700 rounded animate-pulse"></div>
    <div class="space-x-2 flex">
      <div class="h-8 w-12 bg-gray-100 dark:bg-gray-700 rounded animate-pulse"></div>
      <div class="h-8 w-12 bg-gray-100 dark:bg-gray-700 rounded animate-pulse"></div>
      <div class="h-8 w-12 bg-gray-100 dark:bg-gray-700 rounded animate-pulse"></div>
    </div>
  </div>
</div>

{{-- Optional: compact card layout for very small screens (keeps accessibility) --}}
<div class="mt-4 space-y-4 sm:hidden">
  @for ($i = 0; $i < 4; $i++)
    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow animate-pulse">
      <div class="flex items-center space-x-4">
        <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-700"></div>
        <div class="flex-1">
          <div class="h-4 w-3/5 bg-gray-200 dark:bg-gray-700 rounded mb-2"></div>
          <div class="h-3 w-1/4 bg-gray-100 dark:bg-gray-700 rounded"></div>
        </div>
        <div class="h-8 w-20 bg-gray-100 dark:bg-gray-700 rounded"></div>
      </div>
    </div>
  @endfor
</div>

</div>
