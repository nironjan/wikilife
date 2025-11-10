<div>
  <section class="py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header Skeleton -->
      <div class="flex items-center space-x-3 mb-8 animate-pulse">
        <div class="w-10 h-10 bg-gray-200 rounded-lg"></div>
        <div>
          <div class="h-4 bg-gray-200 rounded w-40 mb-2"></div>
          <div class="h-3 bg-gray-200 rounded w-64"></div>
        </div>
      </div>

      <div class="space-y-8 animate-pulse">
        <!-- First Row -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
          <!-- Featured Person Card -->
          <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="flex flex-col lg:flex-row h-full">
              <!-- Image Section -->
              <div class="lg:w-2/5 h-64 lg:h-auto bg-gray-200"></div>
              <!-- Content Section -->
              <div class="lg:w-3/5 p-6 flex flex-col justify-between">
                <div>
                  <div class="h-5 bg-gray-200 rounded w-48 mb-3"></div>
                  <div class="h-3 bg-gray-200 rounded w-32 mb-3"></div>
                  <div class="space-y-2 mb-4">
                    <div class="h-3 bg-gray-200 rounded w-full"></div>
                    <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-3 bg-gray-200 rounded w-5/6"></div>
                  </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-4">
                  <div class="h-3 bg-gray-200 rounded w-24"></div>
                  <div class="h-3 bg-gray-200 rounded w-16"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="grid grid-cols-1 gap-6">
            <!-- Top Right Card -->
            <div class="bg-white rounded-lg shadow overflow-hidden flex h-40">
              <div class="w-2/5 bg-gray-200"></div>
              <div class="w-3/5 p-4 flex flex-col justify-between">
                <div>
                  <div class="h-4 bg-gray-200 rounded w-32 mb-2"></div>
                  <div class="h-3 bg-gray-200 rounded w-24 mb-2"></div>
                  <div class="h-3 bg-gray-200 rounded w-40"></div>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100 mt-3">
                  <div class="h-3 bg-gray-200 rounded w-16"></div>
                  <div class="h-3 bg-gray-200 rounded w-20"></div>
                </div>
              </div>
            </div>

            <!-- Bottom Right 2 Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              @for ($i = 0; $i < 2; $i++)
              <div class="bg-white rounded-lg shadow overflow-hidden flex">
                <div class="w-1/3 bg-gray-200 h-24"></div>
                <div class="w-2/3 p-4">
                  <div class="h-3 bg-gray-200 rounded w-24 mb-2"></div>
                  <div class="h-3 bg-gray-200 rounded w-16 mb-1"></div>
                  <div class="h-3 bg-gray-200 rounded w-12"></div>
                </div>
              </div>
              @endfor
            </div>
          </div>
        </div>

        <!-- Second Row (4 cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
          @for ($i = 0; $i < 4; $i++)
          <div class="bg-white rounded-lg shadow overflow-hidden flex">
            <div class="w-1/3 bg-gray-200 h-24"></div>
            <div class="w-2/3 p-4">
              <div class="h-3 bg-gray-200 rounded w-28 mb-2"></div>
              <div class="h-3 bg-gray-200 rounded w-20 mb-2"></div>
              <div class="h-3 bg-gray-200 rounded w-16"></div>
            </div>
          </div>
          @endfor
        </div>
      </div>
    </div>
  </section>
</div>
