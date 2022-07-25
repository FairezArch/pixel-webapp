@extends('layouts.admin')

@section('title', 'Target Report')

@section('link')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <section class="target-report">
        <div class="target-report__header">
            <div class="target-report__title">
                <span>Laporan Target</span>
            </div>
        </div>
        <div class="target-report__body">
            <div class="target-report__body-top">
                <div>
                    <select name="employee">
                        <option value="">Pilih Sales</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->name }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="date" id="dateFilter" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                </div>
                <div class="button-export__wrapper">
                    <button id="excelButton">
                        <img src="{{ asset('assets/images/excel-icon.png') }}" alt="Excel Icon">
                        <span>
                            EXCEL
                        </span>
                    </button>
                </div>
            </div>

            <table id="targetReportTable"></table>
        </div>
    </section>
@endsection

@can('target_report_list')
    @section('script')
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(() => {
                let employeeSelect = $('select[name=employee]')
                let dateFilter = $('#dateFilter')
                let excelButton = $('button#excelButton')

                employeeSelect.select2();

                const targetReportTable = $('#targetReportTable').DataTable({
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
                        url: "{{ route('report.target') }}",
                        data: (d) => {
                            d.date = dateFilter.val();
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            title: '#',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'user.name',
                            name: 'user.name',
                            title: 'Nama Sales'
                        },
                        {
                            data: 'percentage',
                            name: 'percentage',
                            title: 'Persentase'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            title: 'Capaian'
                        },
                    ]
                })

                excelButton.on('click', () => {
                    excelButton.prop('disabled', true)
                    $.when(targetReportTable.page.len(-1).draw())
                        .done(() => {
                            setTimeout(() => {
                                $('.dt-button.buttons-excel').click()
                                targetReportTable.page.len(10).draw()
                                excelButton.prop('disabled', false)
                            }, 3000);
                        })
                })

                employeeSelect.on('change', (e) => {
                    let value = e.target.value
                    targetReportTable.column(1).search(value).draw()
                })

                $(dateFilter).on('change', (e) => {
                    let value = e.target.value
                    targetReportTable.draw()
                })
            })
        </script>
    @endsection
@endcan
