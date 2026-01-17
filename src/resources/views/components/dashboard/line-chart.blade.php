<x-card :title="$chart->getTitle()" :icon="$chart->getIcon()">
    <canvas id="line-chart-canvas_{{ $chart->id }}" height="300" style="height: 300px;"></canvas>
</x-card>

<script type="text/javascript" nonce="{{ csp_script_nonce() }}">
    document.addEventListener('DOMContentLoaded', function() {
        const chartCanvas = document.getElementById('line-chart-canvas_{{ $chart->id }}').getContext('2d');

        const salesChart = new Chart(chartCanvas, {
            type: 'line',
            data: {
                labels: [],
                datasets: [],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });

        $.ajax({
            url: '{{ route('admin.charts.data', [$chart->id]) }}',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                salesChart.data.labels = res.labels;
                salesChart.data.datasets = res.datasets;
                salesChart.update();
            },
            error: function(xhr) {
                console.error('Failed to load chart data:', xhr.responseText);
            }
        });
    });
</script>
