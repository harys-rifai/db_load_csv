import Chart from 'chart.js/auto';
import annotationPlugin from 'chartjs-plugin-annotation';

Chart.register(annotationPlugin);

document.addEventListener('DOMContentLoaded', function () {
    const chartData = window.cpuChartData || {};
    const currentDateTime = new Date().toLocaleString();

    const label75 = chartData.labels[Math.floor(chartData.labels.length * 0.75)];

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
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: `CPU Usage per Hour per Environment (${currentDateTime})`
                },
                annotation: {
                    annotations: {
                        line75: {
                            type: 'line',
                            mode: 'vertical',
                            scaleID: 'x',
                            value: label75,
                            borderColor: 'red',
                            borderWidth: 2,
                            label: {
                                content: '75%',
                                enabled: true,
                                position: 'top'
                            }
                        }
                    }
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
