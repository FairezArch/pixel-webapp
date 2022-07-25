<div class="dashboard__{{ $name }}-wrapper">
    <div class="dashboard__{{ $name }}-title">
        <span>
            {{ $chartTitle }}
        </span>
    </div>
    <div class="dashboard__{{ $name }}-chart">
        <div class="dashboard__{{ $name }}-chart-header">
            <div class="dashboard__{{ $name }}-chart-filter">
                <input type="month" id="{{ $name }}-date-filter"
                    value="{{ \Carbon\Carbon::now()->format('Y-m') }}">
            </div>
        </div>
        <div class="dashboard__{{ $name }}-chart-body">
            <canvas id="{{ $name }}Chart"></canvas>
        </div>
    </div>
</div>

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js">
    </script>
    <script>
        $(document).ready(() => {
            let date = new Date()
            let year = date.getFullYear()
            let month = date.getMonth() + 1
            $('.dashboard__{{ $name }}-chart-filter input[type=month]').prop('max',
                `${year}-${month < 10 ? `0${month}` : month}`)

            const get{{ $name }}ChartData = (filter) => {
                axios.post("{{ route('dashboard.' . $name . '-chart') }}", {
                        monthFilter: filter
                    })
                    .then((res) => {
                        let data = res.data.data

                        if ('{{ $name }}' == 'busy') {
                            const labels = data.map(list => new Date(list['x']))
                            const value = data.map(list => list['y'])
                            {{ $name }}Chart.data.datasets[0].data = value
                            {{ $name }}Chart.data.labels = labels
                        } else {
                            {{ $name }}Chart.data.datasets[0].data = data
                        }

                        {{ $name }}Chart.update()
                    })
            }

            get{{ $name }}ChartData()

            const {{ $name }}Chart = new Chart(
                $('#{{ $name }}Chart'), {
                    type: 'line',
                    data: {
                        datasets: [{
                            borderColor: '#5A4A99',
                            borderWidth: 5,
                            tension: .5,
                            fill: true,
                            backgroundColor: '#9283CD1A'
                        }],
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    footer: function(item, index) {
                                        let time = item[0].label
                                        return new Date(time.substr(0, time.length - 5)).toLocaleString(
                                            'default', {
                                                month: 'long'
                                            })
                                    }
                                },
                                backgroundColors: 'white',
                                bodyFont: {
                                    size: 22,
                                },
                                footerFont: {
                                    size: 15
                                },
                                displayColors: false,
                            }
                        },
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        scales: {
                            x: {
                                @if ($name == 'busy')
                                    type: 'time',
                                    time: {
                                        unit: 'minute',
                                        displayFormats: {
                                        'minute': 'hh a',
                                        }
                                    },
                                @endif
                                grid: {
                                    display: false
                                }
                            },
                        }
                    }
                }
            )

            if ('{{ $name }}' == 'busy') {
                {{ $name }}Chart.options.scales.y.ticks.stepSize = 1
            }

            $('#{{ $name }}-date-filter').on('change', (e) => {
                let value = e.target.value
                get{{ $name }}ChartData(value)
            })
        })
    </script>
@endpush
