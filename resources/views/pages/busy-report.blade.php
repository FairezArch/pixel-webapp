@extends('layouts.admin')

@section('title', 'Busy Time Report')

@section('link')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <section class="busy-report">
        <div class="busy-report__header">
            <div class="busy-report__title">
                <span>Laporan Keramaian</span>
            </div>
        </div>
        <div class="busy-report__body">
            <div class="busy-report__body-top">
                <div>
                    <select name="region">
                        <option value="" selected disabled>Pilih Daerah</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="main_store" style="width: 100%">
                        <option value="" selected disabled>Pilih Toko Utama</option>
                    </select>
                </div>
                <div>
                    <select name="branch_store" style="width: 100%">
                        <option value="" selected disabled>Pilih Toko Cabang</option>
                    </select>
                </div>
                <div>
                    <select name="product">
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $product)
                            <option
                                value="{{ $product['product_id'] }}{{ $product['color_id'] ? '-' . $product['color_id'] : '' }}">
                                {{ $product['name'] }}
                                {{ $product['color_id'] ? ' (' . $product['color'] . ') ' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="date" id="minDateFilter"
                        value="{{ \Carbon\Carbon::today()->startOfMonth()->format('Y-m-d') }}">
                    <span>to</span>
                    <input type="date" id="maxDateFilter"
                        value="{{ \Carbon\Carbon::today()->startOfMonth()->copy()->endOfMonth()->format('Y-m-d') }}">
                </div>
            </div>
            <div class="busy-report__chart">
                <canvas id="busyChart"></canvas>
            </div>
            <div class="busy-report__table-header">
                <div class="button-export__wrapper">
                    <button id="excelButton">
                        <img src="{{ asset('assets/images/excel-icon.png') }}" alt="Excel Icon">
                        <span>
                            EXCEL
                        </span>
                    </button>
                </div>
                <div class="busy-report__search">
                    <div class="search__wrapper">
                        <input type="text" placeholder="Cari..." id="searchBusyReport">
                        <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                    </div>
                </div>
            </div>
            <table id="busyReportTable"></table>
        </div>
    </section>
@endsection

@can('buyer_report_list')
    @section('script')
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js">
        </script>
        <script>
            $(document).ready(() => {
                let minDateFilter = $('#minDateFilter')
                let maxDateFilter = $('#maxDateFilter')
                let mainStoreSelect = $('select[name=main_store]')
                let branchStoreSelect = $('select[name=branch_store]')
                let regionSelect = $('select[name=region]')
                let productSelect = $('select[name=product]')
                let excelButton = $('button#excelButton')


                mainStoreSelect.select2({
                    allowClear: true,
                    placeholder: 'Pilih Toko Utama'
                });

                branchStoreSelect.select2({
                    allowClear: true,
                    placeholder: 'Pilih Toko Cabang'
                });

                regionSelect.select2({
                    allowClear: true,
                    placeholder: 'Pilih Daerah'
                });

                productSelect.select2({
                    allowClear: true,
                    placeholder: 'Pilih Produk'
                });

                $(regionSelect).on('select2:select', async (e) => {
                    let regionID = e.target.value

                    const res = await axios.get(`main-store/${regionID}`)
                    const {
                        data
                    } = await res
                    const channels = data.data

                    $(mainStoreSelect).empty().trigger('change')
                    $(branchStoreSelect).empty().trigger('change')

                    $(mainStoreSelect).select2({
                        placeholder: 'Pilih Toko Utama',
                        data: channels,
                        allowClear: true
                    })

                    $(mainStoreSelect).val('').trigger('change')
                })

                $(regionSelect).on('select2:clear', () => {
                    $(mainStoreSelect).empty().trigger('change')
                    $(branchStoreSelect).empty().trigger('change')
                })

                $(mainStoreSelect).on('select2:select', async (e) => {
                    let channelID = e.target.value
                    const res = await axios.get(`/branch-store/${channelID}`)
                    const {
                        data
                    } = await res
                    const stores = data.data
                    console.log(stores);

                    $(branchStoreSelect).empty().trigger('change')

                    $(branchStoreSelect).select2({
                        placeholder: 'Pilih Toko Cabang',
                        data: stores,
                        allowClear: true
                    })

                    $(branchStoreSelect).val('').trigger('change')
                })

                $(mainStoreSelect).on('select2:clear', () => {
                    $(branchStoreSelect).empty().trigger('change')
                })

                let data = []
                let value = []
                let labels = []

                const getChartData = async () => {
                    let params = {
                        minDate: minDateFilter.val(),
                        maxDate: maxDateFilter.val(),
                        mainStore: mainStoreSelect.val(),
                        branchStore: branchStoreSelect.val(),
                        region: regionSelect.val(),
                        product: productSelect.val(),
                    }

                    await axios.get('/busy-chart', {
                        params
                    }).then((res) => {
                        data = res.data
                    })
                }

                const busyReportTable = $('#busyReportTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }],
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('report.busy') }}",
                        data: (d) => {
                            d.minDate = minDateFilter.val();
                            d.maxDate = maxDateFilter.val();
                            d.mainStore = mainStoreSelect.val();
                            d.branchStore = branchStoreSelect.val();
                            d.region = regionSelect.val();
                            d.product = productSelect.val();
                        },
                    },
                    drawCallback: async function() {
                        await getChartData()
                        let hash = Object.create(null)
                        let grouped = []

                        const groupBy = (items, key) => items.reduce(
                            (result, item) => {
                                let newKey = item.created_at.slice(0, 13)

                                if (!hash[newKey]) {
                                    hash[newKey] = {
                                        time: newKey + ':00',
                                        nominal: 0
                                    }
                                    grouped.push(hash[newKey])
                                }

                                hash[newKey].nominal += item.nominal
                                return grouped
                            }, {},
                        )

                        let groupData = groupBy(data, 'created_at')
                        if (!Array.isArray(groupData)) {
                            groupData = []
                        }

                        labels = groupData.map(list => new Date(list['time']))
                        value = groupData.map(list => list['nominal'])

                        busyChart.data.datasets[0].data = value
                        busyChart.data.labels = labels
                        busyChart.update()
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            title: '#',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            title: 'Waktu'
                        },
                        {
                            data: null,
                            render: (data) => {
                                if(Object.keys(data.store).length === 0 && data.store.length === 0) {
                                    return '';
                                }else{
                                    return data.store.name;
                                }
                            },
                            name: 'store.name',
                            title: 'Toko Cabang'
                        },
                        {
                            data: null,
                            render: (data) => {
                                if((Object.keys(data.store).length === 0 && data.store.length === 0) || (Object.keys(data.store.channel).length === 0 && data.store.length.channel === 0)) {
                                    return '';
                                }else{
                                    return data.store.channel.region.name;
                                }
                            },
                            name: 'store.channel.region.name',
                            title: 'Daerah'
                        },
                        {
                            data: null,
                            render: (data) => {
                                if((Object.keys(data.store).length === 0 && data.store.length === 0) || (Object.keys(data.store.channel).length === 0 && data.store.length.channel === 0)) {
                                    return '';
                                }else{
                                    return data.store.channel.name;
                                }
                            },
                            name: 'store.channel.name',
                            title: 'Toko Utama'
                        },
                        {
                            data: 'product',
                            name: 'product',
                            title: 'Produk'
                        },
                        {
                            data: 'nominal',
                            name: 'nominal',
                            title: 'Nominal'
                        },
                    ]
                })

                const busyChart = new Chart(
                    $('#busyChart'), {
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
                                    type: 'time',
                                    time: {
                                        unit: 'minute',
                                        displayFormats: {
                                            'minute': 'hh a',
                                        }
                                    },
                                    grid: {
                                        display: false
                                    }
                                },
                            }
                        }
                    }
                )

                excelButton.on('click', () => {
                    excelButton.prop('disabled', true)
                    $.when(busyReportTable.page.len(-1).draw())
                        .done(() => {
                            setTimeout(() => {
                                $('.dt-button.buttons-excel').click()
                                busyReportTable.page.len(10).draw()
                                excelButton.prop('disabled', false)
                            }, 3000);
                        })
                })

                mainStoreSelect.on('change', (e) => {
                    let value = e.target.value
                    busyReportTable.draw()
                })

                branchStoreSelect.on('change', (e) => {
                    let value = e.target.value
                    busyReportTable.draw()
                })

                regionSelect.on('change', (e) => {
                    let value = e.target.value
                    busyReportTable.draw()
                })

                productSelect.on('change', (e) => {
                    let value = e.target.value
                    busyReportTable.draw()
                })

                $(minDateFilter).on('change', (e) => {
                    let value = e.target.value
                    busyReportTable.draw()
                })

                $(maxDateFilter).on('change', (e) => {
                    let value = e.target.value
                    busyReportTable.draw()
                })

                $('#searchBusyReport').keyup((e) => {
                    let value = e.target.value.toLowerCase()
                    busyReportTable.search(value).draw()
                })
            })
        </script>
    @endsection
@endcan
