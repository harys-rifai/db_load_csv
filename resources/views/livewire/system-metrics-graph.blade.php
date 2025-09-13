<h1 class="text-2xl font-bold mb-4 text-gray-800">Server Graph</h1>
<br><br><h1 class="text-2xl font-bold mb-4 text-gray-800">Server Graph</h1>

<div class="container mt-4">
       
    <!-- Chart Canvas -->
    <div>
        <canvas id="cpuChart" height="100"></canvas>
    </div>

     
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', (message, component) => {
                const chartData = @js($cpuChartData);

                const ctx = document.getElementById('cpuChart').getContext('2d');

                // Destroy previous chart if it exists
                if (window.cpuChart) {
                    window.cpuChart.destroy();
                }

                // Create new chart
                window.cpuChart = new Chart(ctx, {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'CPU Usage by Environment'
                            },
                            legend: {
                               <div class="container mt position: 'bottom'
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
                                title: {
                                    display: true,
                                    text: 'CPU Usage (%)'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
        });
    </script>
</div>
