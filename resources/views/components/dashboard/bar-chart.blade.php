<div class="dashboard__{{ $name }}-wrapper">
    <div class="dashboard__{{ $name }}-title">
        <span>
            {{ $chartTitle }}
        </span>
    </div>
    <div class="dashboard__{{ $name }}-chart">
        <div class="dashboard__{{ $name }}-chart-body">
            <canvas id="{{ $name }}Chart"></canvas>
        </div>
    </div>
</div>

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0">
    </script>
    <script>
        $(document).ready(() => {
            const get{{ $name }}ChartData = () => {
                axios.post("{{ route('dashboard.product-chart') }}")
                    .then((res) => {
                        let data = res.data
                        {{ $name }}Chart.data.labels = data.data.labels[0]
                        {{ $name }}Chart.data.datasets[0].data = data.data.values[0]
                        {{ $name }}Chart.options.scales.y.ticks.stepSize = Math.max(...data.data
                            .values[0]) + 1
                        {{ $name }}Chart.update()

                    })
            }

            get{{ $name }}ChartData()

            const {{ $name }}Chart = new Chart(
                $('#{{ $name }}Chart'), {
                    type: 'bar',
                    plugins: [ChartDataLabels],
                    data: {
                        datasets: [{
                            backgroundColor: [
                                'rgba(255, 99, 132)',
                                'rgba(255, 159, 64)',
                                'rgba(255, 205, 86)',
                                'rgba(75, 192, 192)',
                                'rgba(54, 162, 235)',
                            ],
                            borderColor: [
                                'rgb(255, 99, 132)',
                                'rgb(255, 159, 64)',
                                'rgb(255, 205, 86)',
                                'rgb(75, 192, 192)',
                                'rgb(54, 162, 235)',
                            ],
                            borderWidth: .5
                        }]
                    },
                    options: {
                        plugins: {
                            datalabels: {
                                align: 'end',
                                anchor: 'end',
                            },
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: (item) => {
                                        return item.raw
                                    }
                                },
                                backgroundColors: 'white',
                                bodyFont: {
                                    size: 22,
                                },
                                displayColors: false,
                            }
                        },
                        barThickness: 20,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                }
            )
        })
    </script>
@endpush
