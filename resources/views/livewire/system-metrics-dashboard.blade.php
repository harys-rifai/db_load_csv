<div class="p-6 bg-white rounded shadow mt-20">
    <canvas id="cpuChart" height="100"></canvas>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($cpuChartData);
            const currentDateTime = new Date().toLocaleString();
            const ctx = document.getElementById('cpuChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: {
                            display: true,
                            text: `CPU Usage per Hour per Environment (${currentDateTime})`
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: `Waktu (${currentDateTime})`
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'CPU Usage (%)'
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>
