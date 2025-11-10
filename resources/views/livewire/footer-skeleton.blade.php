<div>
  <footer class="bg-white border-t border-gray-200 animate-pulse">
    <div class="max-w-6xl mx-auto px-4 py-12">
      <!-- Footer Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

        <!-- 1️⃣ About & Social -->
        <div class="space-y-6">
          <!-- Logo + Info -->
          <div class="flex items-start space-x-4">
            <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
            <div class="space-y-2">
              <div class="h-4 bg-gray-200 rounded w-32"></div>
              <div class="h-3 bg-gray-200 rounded w-48"></div>
            </div>
          </div>
          <div class="space-y-2">
            <div class="h-3 bg-gray-200 rounded w-full"></div>
            <div class="h-3 bg-gray-200 rounded w-5/6"></div>
            <div class="h-3 bg-gray-200 rounded w-3/4"></div>
          </div>

          <!-- Social Links -->
          <div class="space-y-3">
            <div class="h-3 bg-gray-200 rounded w-24"></div>
            <div class="flex space-x-3">
              @for ($i = 0; $i < 4; $i++)
                <div class="w-10 h-10 bg-gray-200 rounded-lg"></div>
              @endfor
            </div>
          </div>
        </div>

        <!-- 2️⃣ Quick Links -->
        <div class="space-y-6">
          <div class="h-4 bg-gray-200 rounded w-32 mb-4"></div>
          <ul class="space-y-3">
            @for ($i = 0; $i < 6; $i++)
            <li class="flex items-center space-x-3">
              <div class="w-4 h-4 bg-gray-200 rounded-full shrink-0"></div>
              <div class="h-3 bg-gray-200 rounded w-32"></div>
            </li>
            @endfor
          </ul>
        </div>

        <!-- 3️⃣ Professions -->
        <div class="space-y-6">
          <div class="h-4 bg-gray-200 rounded w-40 mb-4"></div>
          <ul class="space-y-3">
            @for ($i = 0; $i < 6; $i++)
            <li class="flex items-center space-x-3">
              <div class="w-2 h-2 bg-gray-200 rounded-full"></div>
              <div class="h-3 bg-gray-200 rounded w-32"></div>
            </li>
            @endfor
          </ul>
        </div>

        <!-- 4️⃣ Contact & Newsletter -->
        <div class="space-y-6">
          <div class="h-4 bg-gray-200 rounded w-36 mb-4"></div>

          <!-- Contact -->
          <div class="space-y-4">
            @for ($i = 0; $i < 2; $i++)
            <div class="flex items-center space-x-3">
              <div class="w-5 h-5 bg-gray-200 rounded-full shrink-0"></div>
              <div class="h-3 bg-gray-200 rounded w-40"></div>
            </div>
            @endfor
          </div>

          <!-- Newsletter -->
          <div class="pt-4 space-y-3">
            <div class="h-3 bg-gray-200 rounded w-28 mb-2"></div>
            <div class="h-9 bg-gray-200 rounded-lg w-full"></div>
            <div class="h-9 bg-gray-300 rounded-lg w-full"></div>
          </div>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="border-t border-gray-200 mt-12 pt-8">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
          <div class="h-3 bg-gray-200 rounded w-64 mx-auto md:mx-0"></div>
        </div>
      </div>
    </div>
  </footer>
</div>
