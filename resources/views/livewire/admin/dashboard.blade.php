<div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Overview</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Welcome to your biography website admin
                    dashboard</p>
            </div>
            <div class="flex items-center space-x-3">
                <select wire:model.live="timeRange"
                    class="block w-full sm:w-auto rounded-lg border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-600 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                    <option value="today">Today</option>
                    <option value="week" selected>This Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                </select>
                <button wire:click="refreshData"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Statistics Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total People Card -->
            @include('livewire.admin.partials.dashboard-cards.people-stats', ['stats' => $stats])

            <!-- Career Statistics Card -->
            @include('livewire.admin.partials.dashboard-cards.career-stats', ['stats' => $stats])

            <!-- Awards & Recognition Card -->
            @include('livewire.admin.partials.dashboard-cards.awards-stats', ['stats' => $stats])

            <!-- System Overview Card -->
            @include('livewire.admin.partials.dashboard-cards.system-stats', ['stats' => $stats])
        </div>

        <!-- Detailed Stats Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- People Statistics Chart -->
            <div class="lg:col-span-2">
                @include('livewire.admin.partials.dashboard-widgets.recent-activity', ['stats' => $stats])
            </div>

            <!-- Recent Activity -->
            <div class="lg:col-span-1">
                @include('livewire.admin.partials.dashboard-widgets.top-viewed', ['stats' => $stats])
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            @include('livewire.admin.partials.dashboard-widgets.quick-actions')
        </div>
    </div>


</div>
@script
<script>
    // Listen for notify events
    Livewire.on('notify', (event) => {
        // Simple notification - you can replace this with your preferred notification system
        console.log('Notification:', event.message);
        alert(event.message); // Simple alert for demo
    });
</script>
@endscript
