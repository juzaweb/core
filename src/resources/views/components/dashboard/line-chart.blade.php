<div class="col-md-{{ $chart->getColumnSize() }}">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-{{ $chart->getIcon() }} mr-1"></i>
                {{ $chart->getTitle() }}
            </h3>
        </div>
        <div class="card-body">
            <canvas id="revenue-chart-canvas_{{ Str::slug($chart->getTitle()) }}" height="300" style="height: 300px;"></canvas>
        </div>
    </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const salesChartCanvas = document.getElementById(
                'revenue-chart-canvas_{{ Str::slug($chart->getTitle()) }}'
            ).getContext('2d');

            const salesChartData = {
                labels: @json($chart->getLabels(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT),
                datasets: @json($chart->getData(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT),
            }

            const salesChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
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

            // This will get the first returned node in the jQuery collection.
            const salesChart = new Chart(salesChartCanvas, {
                type: 'line',
                data: salesChartData,
                options: salesChartOptions
            })
        });
    </script>
</div>