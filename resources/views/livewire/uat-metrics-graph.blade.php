@php
    $currentDateTime = now()->format('d M Y H:i');
@endphp

<div class="p-6 bg-white rounded shadow-md overflow-x-auto">
    <h2 class="text-base font-bold mb-4">Grafik CPU Usage {{ $currentDateTime }}</h2>
    <div class="relative mb-10">
        <canvas id="cpuChart" class="w-full aspect-[2/1]"></canvas>
    </div>

    <h2 class="text-base font-bold mb-4">Grafik Memory Usage {{ $currentDateTime }}</h2>
    <div class="relative mb-10">
        <canvas id="memoryChart" class="w-full aspect-[2/1]"></canvas>
    </div>

    <div class="mt-8">
        <h4 class="text-sm font-semibold mb-3">Daftar Hostname Aktif:</h4>
        <ul class="list-disc list-inside text-base text-gray-800">
            @foreach ($availableHosts as $hostname => $ip)
                <li>{{ $hostname }} <span class="text-gray-500">({{ $ip }})</span></li>
            @endforeach
        </ul>
    </div>
</div>

<script>
    let cpuChartInstance = null;
    let memoryChartInstance = null;

    function renderChart(canvasId, chartData, yLabel) {
        const ctx = document.getElementById(canvasId).getContext('2d');

        if (canvasId === 'cpuChart' && cpuChartInstance) {
            cpuChartInstance.destroy();
        }
        if (canvasId === 'memoryChart' && memoryChartInstance) {
            memoryChartInstance.destroy();
        }

        const datasets = chartData.datasets.map(dataset => {
            const hasHighUsage = dataset.data.some(val => val !== null && val > 90);

            return {
                ...dataset,
                borderColor: hasHighUsage ? 'red' : dataset.borderColor,
                backgroundColor: hasHighUsage ? 'red' : dataset.borderColor,
                pointRadius: dataset.data.map(val => val !== null && val > 90 ? 5 : 2),
                pointBackgroundColor: dataset.data.map(val => val !== null && val > 90 ? 'red' : dataset.borderColor),
                label: dataset.label,
                fill: false,
                tension: 0.2,
                borderWidth: 1,
            };
        });

        const chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: yLabel
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Waktu'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                        position: 'nearest'
                    }
                }
            }
        });

        if (canvasId === 'cpuChart') {
            cpuChartInstance = chartInstance;
        } else {
            memoryChartInstance = chartInstance;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        renderChart('cpuChart', @json($cpuChartData), 'CPU (%)');
        renderChart('memoryChart', @json($memoryChartData), 'Memory (%)');
    });
</script>
