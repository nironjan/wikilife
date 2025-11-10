<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <!-- Card Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">People Growth (Last 12 Months)</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monthly new people registration trends</p>
        </div>
        <div class="relative">
            <button type="button"
                    class="inline-flex items-center p-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    id="chart-options-menu"
                    aria-expanded="false"
                    aria-haspopup="true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none border border-gray-200 dark:border-gray-600"
                 id="chart-menu-items"
                 role="menu"
                 aria-orientation="vertical">
                <div class="px-4 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-600">
                    Chart Options
                </div>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" role="menuitem">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>View Report</span>
                    </div>
                </a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" role="menuitem">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        <span>Export Data</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="relative h-80">
        <canvas id="peopleGrowthChart" class="w-full h-full"></canvas>
    </div>

    <!-- Chart Stats -->
    <!-- Chart Stats -->
    @php
        $growthData = $stats['peopleGrowth'] ?? [];
        $counts = $growthData['counts'] ?? [];

        // Safe approach without reference issues
        $currentMonth = !empty($counts) ? $counts[count($counts) - 1] : 0;
        $previousMonth = !empty($counts) && count($counts) > 1 ? $counts[count($counts) - 2] : 0;

        $growthRate = $previousMonth > 0 ? (($currentMonth - $previousMonth) / $previousMonth) * 100 : 0;
        $totalThisYear = array_sum($counts);
    @endphp
    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
        <div class="grid grid-cols-3 gap-4">
            <div class="text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Current Month</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $currentMonth }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Growth Rate</p>
                <div class="flex items-center justify-center space-x-1">
                    @if($growthRate > 0)
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="text-lg font-bold text-green-500">{{ number_format(abs($growthRate), 1) }}%</span>
                    @elseif($growthRate < 0)
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                        <span class="text-lg font-bold text-red-500">{{ number_format(abs($growthRate), 1) }}%</span>
                    @else
                        <span class="text-lg font-bold text-gray-500">0%</span>
                    @endif
                </div>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total This Year</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalThisYear }}</p>
            </div>
        </div>
    </div>
</div>

@script
<script>
    document.addEventListener('livewire:init', function () {
        // Toggle dropdown menu
        const menuButton = document.getElementById('chart-options-menu');
        const menuItems = document.getElementById('chart-menu-items');

        if (menuButton && menuItems) {
            menuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                menuItems.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                menuItems.classList.add('hidden');
            });
        }

        // Initialize chart
        const stats = @json($stats['peopleGrowth'] ?? []);

        if (stats.months && stats.counts) {
            const ctx = document.getElementById('peopleGrowthChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: stats.months,
                    datasets: [{
                        label: 'New People',
                        data: stats.counts,
                        borderColor: '#3b82f6', // blue-500
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#3b82f6',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#3b82f6',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgb(255, 255, 255)',
                            bodyColor: '#374151',
                            titleColor: '#6b7280',
                            titleMarginBottom: 8,
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            intersect: false,
                            mode: 'index',
                            caretPadding: 8,
                            callbacks: {
                                label: function(context) {
                                    return `New People: ${context.parsed.y}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                maxRotation: 0
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgb(243, 244, 246)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                precision: 0
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    elements: {
                        line: {
                            tension: 0.3
                        }
                    }
                }
            });
        }
    });

    // Update chart when Livewire updates
    document.addEventListener('livewire:update', function () {
        const stats = @json($stats['peopleGrowth'] ?? []);
        // You might want to add chart update logic here if needed
    });
</script>
@endscript
