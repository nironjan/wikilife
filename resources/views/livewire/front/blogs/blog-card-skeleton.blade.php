<div>
  <section class="py-12 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 animate-pulse">
      {{-- Section Header --}}
      <div class="flex items-center justify-between mb-8">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gray-200 rounded-lg"></div>
          <div>
            <div class="h-4 bg-gray-200 rounded w-40 mb-2"></div>
            <div class="h-3 bg-gray-200 rounded w-64"></div>
          </div>
        </div>
        <div class="hidden sm:flex h-3 bg-gray-200 rounded w-20"></div>
      </div>

      <div class="space-y-8">
        {{-- First Row --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
          {{-- Left: Featured Blog --}}
          <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
            <div class="bg-gray-200 h-64 lg:h-48 w-full"></div>
            <div class="p-4 flex flex-col flex-1">
              <div class="h-5 bg-gray-200 rounded w-3/4 mb-4"></div>
              <div class="flex items-center gap-3 mb-4">
                <div class="h-3 bg-gray-200 rounded w-20"></div>
                <div class="h-3 bg-gray-200 rounded w-16"></div>
              </div>
              <div class="space-y-2 mb-4">
                <div class="h-3 bg-gray-200 rounded w-full"></div>
                <div class="h-3 bg-gray-200 rounded w-5/6"></div>
                <div class="h-3 bg-gray-200 rounded w-4/6"></div>
              </div>
              <div class="flex justify-between items-center pt-3 border-t border-gray-100 mt-auto">
                <div class="h-3 bg-gray-200 rounded w-24"></div>
                <div class="h-3 bg-gray-200 rounded w-12"></div>
              </div>
            </div>
          </div>

          {{-- Right Column --}}
          <div class="grid grid-cols-1 gap-6">
            {{-- Top Right Single Post --}}
            <div class="bg-white rounded-lg shadow overflow-hidden flex">
              <div class="w-1/3 bg-gray-200 h-40"></div>
              <div class="w-2/3 p-4 flex flex-col justify-between">
                <div>
                  <div class="h-4 bg-gray-200 rounded w-3/4 mb-3"></div>
                  <div class="h-3 bg-gray-200 rounded w-2/3 mb-3"></div>
                  <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                </div>
                <div class="flex justify-between text-xs pt-3 border-t border-gray-100">
                  <div class="h-3 bg-gray-200 rounded w-16"></div>
                  <div class="h-3 bg-gray-200 rounded w-20"></div>
                </div>
              </div>
            </div>

            {{-- Bottom Right: Two Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              @for ($i = 0; $i < 2; $i++)
              <div class="bg-white rounded-lg shadow overflow-hidden flex flex-row md:flex-col">
                <div class="w-1/3 md:w-full bg-gray-200 h-32 md:h-48"></div>
                <div class="w-2/3 md:w-full p-4 flex flex-col flex-1">
                  <div class="h-3 bg-gray-200 rounded w-3/4 mb-2"></div>
                  <div class="h-3 bg-gray-200 rounded w-1/2 mb-2"></div>
                  <div class="h-3 bg-gray-200 rounded w-1/3 mb-4"></div>
                  <div class="flex justify-between text-xs pt-3 border-t border-gray-100 mt-auto">
                    <div class="h-3 bg-gray-200 rounded w-16"></div>
                    <div class="h-3 bg-gray-200 rounded w-12"></div>
                  </div>
                </div>
              </div>
              @endfor
            </div>
          </div>
        </div>

        {{-- Second Row: 4 Column Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
          @for ($i = 0; $i < 4; $i++)
          <div class="bg-white rounded-xl shadow overflow-hidden flex flex-col border border-gray-100">
            <div class="bg-gray-200 h-48 w-full"></div>
            <div class="p-4 flex flex-col flex-1">
              <div class="h-4 bg-gray-200 rounded w-3/4 mb-3"></div>
              <div class="h-3 bg-gray-200 rounded w-1/2 mb-4"></div>
              <div class="space-y-2 mb-4">
                <div class="h-3 bg-gray-200 rounded w-full"></div>
                <div class="h-3 bg-gray-200 rounded w-5/6"></div>
              </div>
              <div class="flex justify-between text-xs pt-3 border-t border-gray-100 mt-auto">
                <div class="h-3 bg-gray-200 rounded w-20"></div>
                <div class="h-3 bg-gray-200 rounded w-12"></div>
              </div>
            </div>
          </div>
          @endfor
        </div>
      </div>

      {{-- Mobile View All --}}
      <div class="sm:hidden text-center mt-6">
        <div class="inline-flex h-3 bg-gray-200 rounded w-28 mx-auto"></div>
      </div>
    </div>
  </section>
</div>
