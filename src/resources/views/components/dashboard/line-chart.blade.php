<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-pie mr-1"></i>
            Sales
        </h3>
    </div>
    <div class="card-body">
        <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
    </div>
</div>


<script type="text/javascript">
    $(function () {
        const salesChartCanvas = document.getElementById('revenue-chart-canvas').getContext('2d');

        const salesChartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [
                {
                    label: 'Digital Goods',
                    data: [28, 48, 40, 19, 86, 27, 90]
                },
                {
                    label: 'Electronics',
                    data: [65, 59, 80, 81, 56, 55, 40]
                }
            ]
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
