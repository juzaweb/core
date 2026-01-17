<x-card :title="$chart->getTitle()" :icon="$chart->getIcon()">
    <canvas id="pie-chart-canvas_{{ $chart->id }}" height="300" style="height: 300px;"></canvas>
</x-card>

<script type="text/javascript" nonce="{{ csp_script_nonce() }}">
    document.addEventListener('DOMContentLoaded', function() {
        const {{ ' ' . Str::camel($chart->id) }}Canvas = document.getElementById(
            'pie-chart-canvas_{{ $chart->id }}').getContext('2d');

        const {{ ' ' . Str::camel($chart->id) }}Chart = new Chart({{ Str::camel($chart->id) }}Canvas, {
            type: 'pie',
            data: {
                labels: [],
                datasets: [],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: '{{ $chart->getTitle() }}'
                    }
                },
                legend: {
                    position: 'right',
                    align: 'center'
                }
            }
        });

        $.ajax({
            url: '{{ route('admin.charts.data', [$chart->id]) }}',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                {{ Str::camel($chart->id) }}Chart.data.labels = res.labels;
                {{ Str::camel($chart->id) }}Chart.data.datasets = res.datasets.map(
                    dataset => ({
                        ...dataset,
                        backgroundColor: res.labels.map((_, i) =>
                            `hsl(${i * 36}, 70%, 60%)`
                        ),
                    }),
                );

                {{ Str::camel($chart->id) }}Chart.update();
            },
            error: function(xhr) {
                console.error('Failed to load chart data:', xhr.responseText);
            }
        });
    });
</script>
