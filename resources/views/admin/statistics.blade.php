@extends('admin.dashboard')
@section('page_title', 'Thống kê')

@section('statistics')
    <!-- Metrics Group -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <!-- Metric 1: Unique Visitors -->
                    <div class="metric-card p-6 rounded-xl shadow-md">
                        <div class="flex items-center gap-3">
                            <svg class="h-6 w-6 text-brand-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Unique Visitors</h3>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">24.7K</p>
                            <span class="inline-flex items-center gap-1 rounded-full bg-success-100 dark:bg-success-900 px-3 py-1 text-xs font-medium text-success-600 dark:text-success-400">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 00-8.66 15l1.41-1.41A8 8 0 1112 20v2a10 10 0 000-20z"/>
                                </svg>
                                +20%
                            </span>
                        </div>
                    </div>
                    <!-- Metric 2: Total Pageviews -->
                    <div class="metric-card p-6 rounded-xl shadow-md">
                        <div class="flex items-center gap-3">
                            <svg class="h-6 w-6 text-brand-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3h18v18H3V3zm16 16V5H5v14h14z"/>
                            </svg>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pageviews</h3>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">55.9K</p>
                            <span class="inline-flex items-center gap-1 rounded-full bg-success-100 dark:bg-success-900 px-3 py-1 text-xs font-medium text-success-600 dark:text-success-400">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 00-8.66 15l1.41-1.41A8 8 0 1112 20v2a10 10 0 000-20z"/>
                                </svg>
                                +4%
                            </span>
                        </div>
                    </div>
                    <!-- Metric 3: Bounce Rate -->
                    <div class="metric-card p-6 rounded-xl shadow-md">
                        <div class="flex items-center gap-3">
                            <svg class="h-6 w-6 text-brand-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                            </svg>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Bounce Rate</h3>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">54%</p>
                            <span class="inline-flex items-center gap-1 rounded-full bg-error-100 dark:bg-error-900 px-3 py-1 text-xs font-medium text-error-600 dark:text-error-400">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 22a10 10 0 008.66-15l-1.41 1.41A8 8 0 1112 4v-2a10 10 0 000 20z"/>
                                </svg>
                                -1.59%
                            </span>
                        </div>
                    </div>
                    <!-- Metric 4: Visit Duration -->
                    <div class="metric-card p-6 rounded-xl shadow-md">
                        <div class="flex items-center gap-3">
                            <svg class="h-6 w-6 text-brand-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2a10 10 0 00-8.66 15l1.41-1.41A8 8 0 1112 20v2a10 10 0 000-20z"/>
                            </svg>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Visit Duration</h3>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">2m 56s</p>
                            <span class="inline-flex items-center gap-1 rounded-full bg-success-100 dark:bg-success-900 px-3 py-1 text-xs font-medium text-success-600 dark:text-success-400">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 00-8.66 15l1.41-1.41A8 8 0 1112 20v2a10 10 0 000-20z"/>
                                </svg>
                                +7%
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Chart: Visitor Analytics -->
                <div class="chart-section bg-white dark:bg-gray-800 p-8 rounded-xl shadow-md">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Visitor Analytics</h3>
                        <div class="flex gap-2">
                            <button class="tab-button px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">12 months</button>
                            <button class="tab-button px-4 py-2 rounded-lg bg-brand-600 text-white hover:bg-brand-700 transition-colors active">30 days</button>
                            <button class="tab-button px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">7 days</button>
                            <button class="tab-button px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">24 hours</button>
                        </div>
                    </div>
                    <canvas id="visitorChart" class="w-full"></canvas>
                </div>
            </div>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeIcon = document.getElementById('darkModeIcon');
        const html = document.documentElement;

        darkModeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));
            if (html.classList.contains('dark')) {
                darkModeIcon.innerHTML = '<path d="M12 3a9 9 0 009 9c0 4.97-4.03 9-9 9S3 16.97 3 12 7.03 3 12 3zm0-2a11 11 0 00-11 11 11 11 0 0011 11 11 11 0 0011-11A11 11 0 0012 1z"/>';
            } else {
                darkModeIcon.innerHTML = '<path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
            }
        });

        // Load dark mode preference from localStorage
        if (localStorage.getItem('darkMode') === 'true') {
            html.classList.add('dark');
            darkModeIcon.innerHTML = '<path d="M12 3a9 9 0 009 9c0 4.97-4.03 9-9 9S3 16.97 3 12 7.03 3 12 3zm0-2a11 11 0 00-11 11 11 11 0 0011 11 11 11 0 0011-11A11 11 0 0012 1z"/>';
        }
    // Chart.js for Visitor Analytics (Updated)
    const ctx = document.getElementById('visitorChart').getContext('2d');
        const visitorChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27'],
                datasets: [{
                    label: 'Visitors',
                    data: [200, 300, 150, 250, 100, 200, 300, 150, 400, 100, 200, 300, 150, 250, 100, 200, 300, 150, 400, 100, 200, 300, 150, 250, 100, 200, 150],
                    backgroundColor: 'rgba(70, 95, 255, 0.8)', // Using --color-brand-500
                    borderColor: 'rgba(70, 95, 255, 1)',
                    borderWidth: 1,
                    borderRadius: 5,
                    hoverBackgroundColor: 'rgba(70, 95, 255, 1)',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 400,
                        ticks: {
                            stepSize: 100,
                            color: html.classList.contains('dark') ? '#d1d5db' : '#6b7280',
                        },
                        grid: {
                            color: html.classList.contains('dark') ? 'rgba(209, 213, 219, 0.1)' : 'rgba(107, 114, 128, 0.1)',
                        }
                    },
                    x: {
                        ticks: {
                            color: html.classList.contains('dark') ? '#d1d5db' : '#6b7280',
                        },
                        grid: {
                            display: false,
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: html.classList.contains('dark') ? '#d1d5db' : '#6b7280',
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart',
                }
            }
        });

        // Update Chart.js colors when dark mode toggles
        darkModeToggle.addEventListener('click', () => {
            visitorChart.options.scales.y.ticks.color = html.classList.contains('dark') ? '#d1d5db' : '#6b7280';
            visitorChart.options.scales.x.ticks.color = html.classList.contains('dark') ? '#d1d5db' : '#6b7280';
            visitorChart.options.scales.y.grid.color = html.classList.contains('dark') ? 'rgba(209, 213, 219, 0.1)' : 'rgba(107, 114, 128, 0.1)';
            visitorChart.options.plugins.legend.labels.color = html.classList.contains('dark') ? '#d1d5db' : '#6b7280';
            visitorChart.update();
        });
    </script>

@endsection