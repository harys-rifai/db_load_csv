<div class="p-6 bg-white rounded shadow">
    <canvas id="memoryChart" height="100"></canvas>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($memoryChartData);
            const currentDateTime = new Date().toLocaleString(); // Ambil tanggal dan waktu lokal

            const ctx = document.getElementById('memoryChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: `Memory Usage per Hour per Environment (${currentDateTime})`
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
                                text: 'Memory Usage (GB)'
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>
