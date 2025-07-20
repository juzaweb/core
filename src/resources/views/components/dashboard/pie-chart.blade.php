<div class="col-md-{{ $chart->getColumnSize() }}">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-pie mr-1"></i>
                {{ $chart->getTitle() }}
            </h3>
        </div><!-- /.card-header -->
        <div class="card-body">
            <div class="tab-content p-0">
                <canvas id="sales-chart-canvas_{{ Str::slug($chart->getTitle()) }}" height="300" style="height: 300px;"></canvas>
            </div>
        </div><!-- /.card-body -->
    </div>
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        // Donut Chart
        const pieChartCanvas = $('#sales-chart-canvas_{{ Str::slug($chart->getTitle()) }}').get(0).getContext('2d')
        const pieData = {
            labels: [
                'Instore Sales',
                'Download Sales',
                'Mail-Order Sales'
            ],
            datasets: [
                {
                    data: [30, 12, 20],
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12']
                }
            ]
        }
        const pieOptions = {
            legend: {
                display: false
            },
            maintainAspectRatio: false,
            responsive: true
        }
        // Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        // eslint-disable-next-line no-unused-vars
        const pieChart = new Chart(pieChartCanvas, { // lgtm[js/unused-local-variable]
            type: 'doughnut',
            data: pieData,
            options: pieOptions
        });
    });
</script>