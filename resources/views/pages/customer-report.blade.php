@extends('layouts.admin')

@section('title', 'Customers Report')

@section('link')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection

@section('content')
    <section class="customer-report">
        <div class="customer-report__header">
            <div class="customer-report__title">
                <span>Laporan Pembeli</span>
            </div>
        </div>
        <div class="customer-report__body">
            <div class="customer-report__body-top">
                <div class="report-total">
                    <span>Total Pembeli</span>
                    <span>0</span>
                </div>
            </div>
            <div class="customer-report__table-header">
                <div class="button-export__wrapper">
                    <button id="excelButton">
                        <img src="{{ asset('assets/images/excel-icon.png') }}" alt="Excel Icon">
                        <span>
                            EXCEL
                        </span>
                    </button>
                </div>
                <div class="customer-report__search">
                    <div class="search__wrapper">
                        <input type="text" placeholder="Cari..." id="searchCustomerReport">
                        <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                    </div>
                </div>
            </div>
            <table id="customerReportTable" style="width: 100%"></table>
        </div>
    </section>
@endsection

@can('buyer_report_list')
    @section('script')
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script>
            $(document).ready(() => {
                let excelButton = $('button#excelButton')

                let total = 0;

                const customerReportTable = $('#customerReportTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible',
                        }
                    }],
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('report.customer') }}",
                    drawCallback: function() {
                        let api = this.api()
                        let data = api.column(7).data()
                        if (data.length > 0) {
                            total = data[0]
                        } else {
                            total = '0'
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
                            data: 'name',
                            name: 'name',
                            title: 'Nama Customers'
                        },
                        {
                            data: 'mobile',
                            name: 'mobile',
                            title: 'No. Telp'
                        },
                        {
                            data: 'email',
                            name: 'email',
                            title: 'Email'
                        },
                        {
                            data: 'gender',
                            name: 'gender',
                            title: 'Jenis Kelamin'
                        },
                        {
                            data: 'age.age',
                            name: 'age.age',
                            title: 'Umur'
                        },
                        {
                            data: 'job.name',
                            name: 'job.name',
                            title: 'Pekerjaan'
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
                    $.when(customerReportTable.page.len(-1).draw())
                        .done(() => {
                            setTimeout(() => {
                                $('.dt-button.buttons-excel').click()
                                customerReportTable.page.len(10).draw()
                                excelButton.prop('disabled', false)
                            }, 3000);
                        })
                })

                $('#searchCustomerReport').keyup((e) => {
                    let value = e.target.value.toLowerCase()
                    customerReportTable.search(value).draw()
                })
            })
        </script>
    @endsection
@endcan
