@extends('layouts.admin')

@section('title', 'Sales Report')

@section('link')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <section class="sale-report">
        <div class="sale-report__header">
            <div class="sale-report__title">
                <span>Laporan Penjualan</span>
            </div>
        </div>
        <div class="sale-report__body">
            <div class="sale-report__body-top">
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
                    <select name="employee" style="width: 100%">
                        <option value="">Pilih Sales</option>
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
                <div class="report-total">
                    <span>Total Penjualan</span>
                    <span>0</span>
                </div>
            </div>
            <div class="sale-report__table-header">
                <div class="button-export__wrapper">
                    <button id="excelButton">
                        <img src="{{ asset('assets/images/excel-icon.png') }}" alt="Excel Icon">
                        <span>
                            EXCEL
                        </span>
                    </button>
                </div>
                <div class="sale-report__search">
                    <div class="search__wrapper">
                        <input type="text" placeholder="Cari..." id="searchSaleReport">
                        <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                    </div>
                </div>
            </div>
            <table id="saleReportTable" style="width: 100%"></table>
        </div>
    </section>
@endsection

@can('sales_report_list')
    @section('script')
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(() => {
                let minDateFilter = $('#minDateFilter')
                let maxDateFilter = $('#maxDateFilter')
                let mainStoreSelect = $('select[name=main_store]')
                let branchStoreSelect = $('select[name=branch_store]')
                let employeeSelect = $('select[name=employee]')
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

                employeeSelect.select2({
                    allowClear: true,
                    placeholder: 'Pilih Sales'
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
                    $(employeeSelect).empty().trigger('change')

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
                    $(employeeSelect).empty().trigger('change')
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
                    $(employeeSelect).empty().trigger('change')

                    $(branchStoreSelect).select2({
                        placeholder: 'Pilih Toko Cabang',
                        data: stores,
                        allowClear: true
                    })

                    $(branchStoreSelect).val('').trigger('change')
                })

                $(mainStoreSelect).on('select2:clear', () => {
                    $(branchStoreSelect).empty().trigger('change')
                    $(employeeSelect).empty().trigger('change')
                })

                $(branchStoreSelect).on('select2:select', async (e) => {
                    let storeID = e.target.value
                    const res = await axios.get(`/user/store/${storeID}`)
                    const {
                        data
                    } = await res
                    const employees = data.data

                    $(employeeSelect).empty().trigger('change')

                    $(employeeSelect).select2({
                        placeholder: 'Pilih Sales',
                        data: employees,
                        allowClear: true
                    })

                    $(employeeSelect).val('').trigger('change')
                })

                $(branchStoreSelect).on('select2:select', () => {
                    $(employeeSelect).empty().trigger('change')
                })

                let total = 0;

                const saleReportTable = $('#saleReportTable').DataTable({
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
                        url: "{{ route('report.sale') }}",
                        data: (d) => {
                            d.minDate = minDateFilter.val();
                            d.maxDate = maxDateFilter.val();
                            d.mainStore = mainStoreSelect.val();
                            d.branchStore = branchStoreSelect.val();
                            d.employee = employeeSelect.val();
                            d.region = regionSelect.val();
                            d.product = productSelect.val();
                        },
                    },
                    drawCallback: function() {
                        let api = this.api()
                        let data = api.column(9).data()

                        if (data.length > 0) {
                            total = data[0]
                        } else {
                            total = 'Rp0,00'
                        }

                        $('.report-total span:last-child').html(total)
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
                            data: 'user.name',
                            name: 'user.name',
                            title: 'Nama Sales'
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
                            data: 'imei_filename',
                            name: 'imei_filename',
                            title: 'IMEI'
                        },
                        {
                            data: 'nominal',
                            name: 'nominal',
                            title: 'Nominal'
                        },
                        {
                            data: 'total',
                            name: 'total',
                            visible: false
                        },
                    ]
                })

                excelButton.on('click', () => {
                    excelButton.prop('disabled', true)
                    $.when(saleReportTable.page.len(-1).draw())
                        .done(() => {
                            setTimeout(() => {
                                $('.dt-button.buttons-excel').click()
                                saleReportTable.page.len(10).draw()
                                excelButton.prop('disabled', false)
                            }, 3000);
                        })
                })

                mainStoreSelect.on('change', (e) => {
                    let value = e.target.value
                    saleReportTable.draw()
                })

                branchStoreSelect.on('change', (e) => {
                    let value = e.target.value
                    saleReportTable.draw()
                })

                regionSelect.on('change', (e) => {
                    let value = e.target.value
                    saleReportTable.draw()
                })

                employeeSelect.on('change', (e) => {
                    let value = e.target.value
                    saleReportTable.draw()
                })

                productSelect.on('change', (e) => {
                    let value = e.target.value
                    saleReportTable.draw()
                })

                $(minDateFilter).on('change', (e) => {
                    let value = e.target.value
                    saleReportTable.draw()
                })

                $(maxDateFilter).on('change', (e) => {
                    let value = e.target.value
                    saleReportTable.draw()
                })

                $('#searchSaleReport').keyup((e) => {
                    let value = e.target.value.toLowerCase()
                    saleReportTable.search(value).draw()
                })
            })
        </script>
    @endsection
@endcan
