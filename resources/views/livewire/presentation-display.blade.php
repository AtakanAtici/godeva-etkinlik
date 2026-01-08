<div class="min-h-screen bg-white p-8 flex flex-col">
    <div class="flex-1 flex flex-col">
        @if (!$currentQuestion)
            <!-- Waiting State - Full Screen -->
            <div class="flex-1 flex flex-col">
                <!-- Header -->
                <div class="flex justify-between items-center mb-12">
                    <img src="https://godeva.com.tr/assets/img/logo_home6.svg" alt="Godeva Logo" class="h-12 w-auto">
                    <div class="flex items-center space-x-2 text-gray-500">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-lg font-medium">Live</span>
                    </div>
                </div>

                <!-- Main Content - Full Height -->
                <div class="flex-1 grid grid-cols-3 gap-16 items-center">
                    <!-- Left: Large Logo with Animation -->
                    <div class="flex justify-center">
                        <div class="relative">
                            <div class="w-64 h-64 relative">
                                <img src="https://godeva.com.tr/assets/img/logo_home6.svg" alt="Godeva Logo"
                                    class="w-full h-full opacity-30 filter drop-shadow-lg">
                                <!-- Animated rings around logo -->
                                <div class="absolute inset-0 border-4 border-blue-100 rounded-full animate-ping opacity-20">
                                </div>
                                <div class="absolute inset-8 border-2 border-blue-200 rounded-full animate-ping opacity-30"
                                    style="animation-delay: 1s"></div>
                                <div class="absolute inset-16 border border-blue-300 rounded-full animate-ping opacity-40"
                                    style="animation-delay: 2s"></div>
                                <!-- Central glow effect -->
                                <div class="absolute inset-20 bg-blue-50 rounded-full opacity-20 animate-pulse"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Center: Title and Status -->
                    <div class="text-center">
                        <div class="space-y-6">
                            <h1 class="text-7xl font-bold text-gray-800 tracking-tight mb-4">Sunuma Hazır</h1>
                            <p class="text-gray-600 text-2xl font-light leading-relaxed max-w-lg mx-auto">Host bir soru
                                yayınladığında burada görünecek</p>
                            <div class="flex items-center justify-center space-x-3 text-gray-400 text-lg mt-8">
                                <svg class="w-6 h-6 animate-spin text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                <span class="font-medium">Canlı bağlantı bekleniyor...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Participation Info -->
                    <div class="flex justify-center">
                        <div class="bg-gray-50 rounded-2xl border border-gray-200 p-8 shadow-lg max-w-sm w-full">
                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-semibold text-gray-800 mb-2">Katılım</h3>
                                <p class="text-gray-600">Cep telefonunuzdan katılın</p>
                            </div>

                            <div class="space-y-6">
                                <!-- Join Code -->
                                <div class="text-center">
                                    <p class="text-gray-600 text-sm mb-3">Katılım Kodu</p>
                                    <div class="bg-white rounded-xl py-4 px-6 border-2 border-blue-100 shadow-sm">
                                        <span
                                            class="text-4xl font-bold text-blue-600 tracking-wider">{{ $room->code }}</span>
                                    </div>
                                </div>

                                <!-- QR Code -->
                                <div class="text-center">
                                    <p class="text-gray-600 text-sm mb-3">QR Kod</p>
                                    <div class="bg-white p-6 rounded-xl border-2 border-gray-200 inline-block shadow-sm">
                                        <div class="w-32 h-32 flex items-center justify-center">
                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate(url("/join/{$room->code}")) !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- URL -->
                                <div class="text-center">
                                    <p class="text-gray-600 text-sm mb-2">Web Adresi</p>
                                    <div
                                        class="bg-white rounded-lg py-3 px-4 border text-sm text-gray-700 font-mono break-all">
                                        {{ url("/join/{$room->code}") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Modern Header with Separated Title -->
            <div class="mb-12">
                <!-- Top Bar: Logo & Stats -->
                <div
                    class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-4 transition-all duration-300 hover:shadow-xl">
                    <div class="flex justify-between items-center">
                        <!-- Left: Logo -->
                        <div class="relative group">
                            <div
                                class="absolute -inset-2 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg blur opacity-0 group-hover:opacity-20 transition duration-500">
                            </div>
                            <img src="https://godeva.com.tr/assets/img/logo_home6.svg" alt="Godeva Logo"
                                class="h-10 w-auto relative transform transition hover:scale-105 duration-300">
                        </div>

                        <!-- Right: Stats -->
                        <div class="flex items-center gap-6">
                            <!-- Participants -->
                            <div class="flex items-center gap-3 px-4 py-2 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Katılımcı</span>
                                    <span
                                        class="text-xl font-black text-gray-800 leading-none">{{ $participantCount }}</span>
                                </div>
                            </div>

                            <!-- Answers -->
                            <div class="flex items-center gap-3 px-4 py-2 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Cevap</span>
                                    <span
                                        class="text-xl font-black text-gray-800 leading-none">{{ $currentQuestion->answers()->count() }}</span>
                                </div>
                            </div>

                            <!-- Response Rate -->
                            @if($participantCount > 0)
                                <div class="flex items-center gap-3 px-4 py-2 rounded-xl bg-gray-50 border border-gray-100">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Oran</span>
                                        <span
                                            class="text-xl font-black text-gray-800 leading-none">{{ round(($currentQuestion->answers()->count() / $participantCount) * 100) }}%</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Question Title Section (Outside Card) -->
                <div class="mt-24 text-center relative z-10">
                    <div class="inline-block relative group">
                        <!-- Decorative elements -->
                        <div
                            class="absolute -inset-4 bg-gradient-to-r from-blue-100 via-purple-50 to-pink-100 rounded-3xl blur-xl opacity-50 group-hover:opacity-100 transition duration-700">
                        </div>

                        <div class="relative">
                            <h2
                                class="text-5xl md:text-7xl font-serif font-medium text-gray-900 mb-8 pt-12 leading-snug tracking-normal drop-shadow-sm break-all">
                                {{ $currentQuestion->title }}
                            </h2>


                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Display -->
            <div class="flex-1">
                @if($currentQuestion->type === 'multiple_choice')
                    <!-- Multiple Choice Results Chart -->
                    <div class="h-full flex flex-col justify-center px-8">
                        <div class="bg-white rounded-2xl p-8 shadow-lg max-w-5xl mx-auto w-full">
                            @if(count($multipleChoiceResults) > 0 && array_sum(array_column($multipleChoiceResults, 'count')) > 0)
                                <!-- Chart Container -->
                                <div class="relative" style="height: 400px;" wire:ignore>
                                    <canvas id="multipleChoiceChart"></canvas>
                                </div>

                                <!-- Hidden data container for JavaScript -->
                                <script id="chart-data" type="application/json">
                                                                                                                                                            {!! json_encode($multipleChoiceResults) !!}
                                                                                                                                                        </script>

                                <!-- Statistics Summary -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="flex flex-wrap justify-center gap-6">
                                        @foreach($multipleChoiceResults as $result)
                                            <div class="flex items-center gap-3 bg-gray-50 px-4 py-2 rounded-lg">
                                                <!-- Option Circle -->
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold"
                                                    style="background-color: {{ ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4'][$loop->index] ?? '#6B7280' }}">
                                                    {{ $result['letter'] }}
                                                </div>
                                                <!-- Stats -->
                                                <div class="flex items-center gap-2">
                                                    <span class="text-2xl font-bold text-gray-900">{{ $result['count'] }}</span>
                                                    <span class="text-gray-500">oy</span>
                                                    <span class="text-sm text-gray-400">•</span>
                                                    <span class="text-lg font-medium text-gray-600">{{ $result['percentage'] }}%</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-20">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Henüz oy kullanılmadı</h3>
                                    <p class="text-gray-500">İlk oyları bekliyoruz...</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Open Text Answers Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-5 gap-6" id="answers-feed">
                        @forelse($recentAnswers as $answer)
                            <div
                                class="bg-white backdrop-blur rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 flex flex-col justify-center group relative overflow-hidden h-full">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-purple-50/50 opacity-0 group-hover:opacity-100 transition duration-500">
                                </div>
                                <div class="relative z-10">
                                    <p class="text-gray-800 text-xl font-medium text-center leading-relaxed break-all">
                                        {{ $answer->content }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-20">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="text-2xl font-bold text-gray-800 mb-2">Henüz cevap gelmedi</h3>
                                <p class="text-gray-500">İlk cevapları bekliyoruz...</p>
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let multipleChoiceChart = null;
        let lastChartData = null;

        function getChartDataFromDOM() {
            const dataElement = document.getElementById('chart-data');
            if (dataElement) {
                try {
                    return JSON.parse(dataElement.textContent);
                } catch (e) {
                    console.error('Error parsing chart data:', e);
                    return null;
                }
            }
            return null;
        }

        function initializeMultipleChoiceChart() {
            const ctx = document.getElementById('multipleChoiceChart');
            if (!ctx) {
                console.log('Chart canvas not found');
                return;
            }

            const results = getChartDataFromDOM();
            if (!results || results.length === 0) {
                console.log('No chart data available');
                return;
            }

            console.log('Initializing chart with data:', results);

            const colors = [
                '#3B82F6', // Blue
                '#10B981', // Green
                '#F59E0B', // Yellow
                '#EF4444', // Red
                '#8B5CF6', // Purple
                '#06B6D4', // Cyan
                '#EC4899', // Pink
                '#6366F1', // Indigo
                '#14B8A6', // Teal
                '#84CC16', // Lime
                '#F97316', // Orange
                '#64748B'  // Slate/Gray
            ];

            // Destroy existing chart
            if (multipleChoiceChart) {
                multipleChoiceChart.destroy();
                multipleChoiceChart = null;
            }

            try {
                multipleChoiceChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: results.map(result => `${result.letter}. ${result.option.length > 40 ? result.option.substring(0, 40) + '...' : result.option}`),
                        datasets: [{
                            label: 'Oylar',
                            data: results.map(result => result.count),
                            backgroundColor: results.map((result, index) => colors[index] || '#6B7280'),
                            borderColor: results.map((result, index) => colors[index] || '#6B7280'),
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1000,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: 'white',
                                bodyColor: 'white',
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    title: function (context) {
                                        return results[context[0].dataIndex].option;
                                    },
                                    label: function (context) {
                                        const result = results[context.dataIndex];
                                        return `${result.count} oy (${result.percentage}%)`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    color: '#6B7280',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                },
                                grid: {
                                    color: '#E5E7EB'
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#374151',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    maxRotation: 0
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });

                lastChartData = JSON.stringify(results.map(r => r.count));
                console.log('Chart created successfully');
            } catch (error) {
                console.error('Error creating chart:', error);
                multipleChoiceChart = null;
            }
        }

        function updateMultipleChoiceChart() {
            const results = getChartDataFromDOM();
            if (!results || results.length === 0) {
                return;
            }

            const newChartData = JSON.stringify(results.map(r => r.count));

            // Check if chart exists and is healthy
            if (!multipleChoiceChart) {
                console.log('Chart missing during update, reinitializing...');
                initializeMultipleChoiceChart();
                return;
            }

            // Only update if data has changed
            if (lastChartData !== newChartData) {
                try {
                    console.log('Updating chart data from', lastChartData, 'to', newChartData);

                    // Update chart data
                    multipleChoiceChart.data.datasets[0].data = results.map(result => result.count);

                    // Update tooltips
                    multipleChoiceChart.options.plugins.tooltip.callbacks.label = function (context) {
                        const result = results[context.dataIndex];
                        return `${result.count} oy (${result.percentage}%)`;
                    };

                    // Update chart
                    multipleChoiceChart.update('default');

                    lastChartData = newChartData;
                    console.log('Chart updated successfully');
                } catch (error) {
                    console.error('Error updating chart:', error);
                    multipleChoiceChart = null;
                    initializeMultipleChoiceChart();
                }
            }
        }

        function destroyChart() {
            if (multipleChoiceChart) {
                multipleChoiceChart.destroy();
                multipleChoiceChart = null;
                lastChartData = null;
            }
        }

        // Initialize chart on load
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(initializeMultipleChoiceChart, 100); // Small delay to ensure DOM is ready
        });

        // Update chart on Livewire updates (only if data changed)
        document.addEventListener('livewire:update', function () {
            @if($currentQuestion && $currentQuestion->type === 'multiple_choice')
                // Ensure chart exists before updating
                setTimeout(function () {
                    const ctx = document.getElementById('multipleChoiceChart');
                    if (ctx && !multipleChoiceChart) {
                        initializeMultipleChoiceChart();
                    } else {
                        updateMultipleChoiceChart();
                    }
                }, 50);
            @else
                destroyChart();
            @endif
        });

        // Reinitialize chart if it gets lost due to DOM changes
        document.addEventListener('livewire:navigated', function () {
            @if($currentQuestion && $currentQuestion->type === 'multiple_choice')
                setTimeout(function () {
                    const ctx = document.getElementById('multipleChoiceChart');
                    if (ctx && !multipleChoiceChart) {
                        initializeMultipleChoiceChart();
                    }
                }, 200);
            @endif
        });

        // Chart health check - ensure chart stays alive
        function ensureChartHealth() {
            @if($currentQuestion && $currentQuestion->type === 'multiple_choice')
                const ctx = document.getElementById('multipleChoiceChart');
                // console.log('Health check:', { hasCanvas: !!ctx, hasChart: !!multipleChoiceChart });

                if (ctx && !multipleChoiceChart) {
                    console.log('Chart lost, reinitializing...');
                    initializeMultipleChoiceChart();
                } else if (multipleChoiceChart) {
                    // Deep health check
                    try {
                        const canvas = multipleChoiceChart.canvas;
                        if (!canvas || !canvas.parentNode || !document.contains(canvas)) {
                            console.log('Chart canvas is orphaned, reinitializing...');
                            multipleChoiceChart = null;
                            initializeMultipleChoiceChart();
                        }
                    } catch (e) {
                        console.log('Chart corruption detected:', e);
                        multipleChoiceChart = null;
                        initializeMultipleChoiceChart();
                    }
                } else {
                    console.log('No canvas found for chart');
                }
            @else
                console.log('No multiple choice question active');
            @endif
        }

        // Keep Livewire polling for data updates
        document.addEventListener('livewire:navigated', () => {
            setInterval(() => {
                if (typeof Livewire !== 'undefined' && Livewire.find('{{ $this->getId() }}')) {
                    Livewire.find('{{ $this->getId() }}').call('checkForUpdates');
                }
            }, 2000);
        });

        // Simple polling system
        function startChartPolling() {
            console.log('Starting chart polling system');

            // Initialize chart
            setTimeout(initializeMultipleChoiceChart, 500);

            // Poll for updates every 3 seconds
            setInterval(() => {
                updateMultipleChoiceChart();
            }, 3000);
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', startChartPolling);

        // Error handling for any JavaScript errors that might affect chart
        window.addEventListener('error', function (e) {
            console.log('JavaScript error detected, checking chart health...', e);
            setTimeout(ensureChartHealth, 1000);
        });
    </script>
</div>