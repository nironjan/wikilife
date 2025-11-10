<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <!-- Card Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Career Distribution</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Breakdown of people by profession categories</p>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="relative h-80">
        <canvas id="careerDistributionChart" class="w-full h-full"></canvas>

        <!-- Fallback message -->
        <div id="careerChartFallback" class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-gray-900 rounded-lg hidden">
            <div class="text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">No career distribution data available</p>
            </div>
        </div>
    </div>

    <!-- Legend -->
    @php
        $distribution = $stats['careerDistribution'] ?? [];
        $total = array_sum($distribution);
    @endphp

    @if($total > 0)
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($distribution as $career => $count)
                    @if($count > 0)
                        @php
                            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;

                            // Define colors for each career type
                            $colorConfig = [
                                'bgColor' => match($career) {
                                    'Actor' => 'bg-blue-500',
                                    'Politician' => 'bg-green-500',
                                    'Sports' => 'bg-yellow-500',
                                    'Writer' => 'bg-purple-500',
                                    'Entrepreneur' => 'bg-red-500',
                                    default => 'bg-gray-500'
                                },
                                'textColor' => match($career) {
                                    'Actor' => 'text-blue-600 dark:text-blue-400',
                                    'Politician' => 'text-green-600 dark:text-green-400',
                                    'Sports' => 'text-yellow-600 dark:text-yellow-400',
                                    'Writer' => 'text-purple-600 dark:text-purple-400',
                                    'Entrepreneur' => 'text-red-600 dark:text-red-400',
                                    default => 'text-gray-600 dark:text-gray-400'
                                },
                                'icon' => match($career) {
                                    'Actor' =>
                                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                        </svg>',
                                    'Politician' =>
                                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>',
                                    'Sports' =>
                                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>',
                                    'Writer' =>
                                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>',
                                    'Entrepreneur' =>
                                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                        </svg>',
                                    default =>
                                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>'
                                }
                            ];
                        @endphp
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $colorConfig['bgColor'] }} bg-opacity-10">
                                    {!! $colorConfig['icon'] !!}
                                </div>
                                <div>
                                    <span class="text-sm font-medium {{ $colorConfig['textColor'] }}">{{ $career }}</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $count }} people</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $percentage }}%</span>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Total Summary -->
            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Total People Analyzed</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $total }}</span>
                </div>
            </div>
        </div>
    @else
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600 text-center">
            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <p class="text-gray-500 dark:text-gray-400">No career distribution data available</p>
        </div>
    @endif
</div>

@script
<script>
    document.addEventListener('livewire:init', function () {
        // Check if Chart.js is available
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded. Please include Chart.js in your project.');
            const fallback = document.getElementById('careerChartFallback');
            if (fallback) {
                fallback.classList.remove('hidden');
                fallback.innerHTML = `
                    <div class="text-center">
                        <svg class="w-12 h-12 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="text-red-500 font-medium">Chart.js not loaded</p>
                        <p class="text-sm text-gray-500 mt-1">Please install Chart.js to view charts</p>
                    </div>
                `;
            }
            return;
        }

        const distribution = @json($stats['careerDistribution']);
        const filteredKeys = Object.keys(distribution).filter(key => distribution[key] > 0);
        const filteredValues = Object.values(distribution).filter(value => value > 0);

        if (filteredKeys.length > 0) {
            const ctx = document.getElementById('careerDistributionChart').getContext('2d');

            try {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: filteredKeys,
                        datasets: [{
                            data: filteredValues,
                            backgroundColor: [
                                '#3b82f6', // blue-500
                                '#10b981', // green-500
                                '#f59e0b', // yellow-500
                                '#8b5cf6', // purple-500
                                '#ef4444', // red-500
                                '#6b7280', // gray-500
                            ],
                            hoverBackgroundColor: [
                                '#2563eb', // blue-600
                                '#059669', // green-600
                                '#d97706', // yellow-600
                                '#7c3aed', // purple-600
                                '#dc2626', // red-600
                                '#4b5563', // gray-600
                            ],
                            hoverBorderColor: 'rgba(255, 255, 255, 0.8)',
                            borderWidth: 2,
                            hoverBorderWidth: 3,
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgb(255, 255, 255)',
                                bodyColor: '#374151',
                                titleColor: '#6b7280',
                                borderColor: '#e5e7eb',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: true,
                                caretPadding: 8,
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            },
                        },
                        elements: {
                            arc: {
                                borderWidth: 0,
                            }
                        },
                    },
                });

                // Hide fallback if chart is created successfully
                const fallback = document.getElementById('careerChartFallback');
                if (fallback) {
                    fallback.classList.add('hidden');
                }

            } catch (error) {
                console.error('Error creating career distribution chart:', error);
                const fallback = document.getElementById('careerChartFallback');
                if (fallback) {
                    fallback.classList.remove('hidden');
                    fallback.innerHTML = `
                        <div class="text-center">
                            <svg class="w-12 h-12 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p class="text-red-500 font-medium">Chart Error</p>
                            <p class="text-sm text-gray-500 mt-1">Failed to create chart</p>
                        </div>
                    `;
                }
            }
        } else {
            // Show no data message
            const fallback = document.getElementById('careerChartFallback');
            if (fallback) {
                fallback.classList.remove('hidden');
            }
        }
    });
</script>
@endscript
