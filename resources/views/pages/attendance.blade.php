@extends('layouts.admin')

@section('title', 'Attendance list')

@section('link')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <section class="attendance">
        <div class="attendance__header">
            <div class="attendance__title">
                <span>List Jam Hadir</span>
            </div>
        </div>
        <div class="attendance__body">
            <div class="attendance__body-top">
                <div>
                    <select name="main_store">
                        <option value="">Pilih Toko Utama</option>
                        @foreach ($main_stores as $store)
                            <option value="{{ $store->name }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="branch_store">
                        <option value="">Pilih Toko Cabang</option>
                        @foreach ($branch_stores as $store)
                            <option value="{{ $store->name }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="date" id="minDateFilter" value="{{\Carbon\Carbon::today()->startOfMonth()->format('Y-m-d')}}">
                    <span>to</span>
                    <input type="date" id="maxDateFilter" value="{{\Carbon\Carbon::today()->startOfMonth()->copy()->endOfMonth()->format('Y-m-d')}}">
                </div>
                <div class="attendance__search">
                    <div class="search__wrapper">
                        <input type="text" placeholder="Cari..." id="searchAttendance">
                        <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                    </div>
                </div>
            </div>
            <table id="attendanceTable"></table>
        </div>
    </section>
@endsection

@can('attendance_list')
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(() => {
            let mainStoreSelect = $('select[name=main_store]')
            let branchStoreSelect = $('select[name=branch_store]')
            let minDateFilter = $('#minDateFilter')
            let maxDateFilter = $('#maxDateFilter')

            mainStoreSelect.select2();
            branchStoreSelect.select2();

            const attendanceTable = $('#attendanceTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('attendance.index') }}",
                    data: function(d) {
                        d.mainStore = mainStoreSelect.val()
                        d.branchStore = branchStoreSelect.val()
                        d.minDate = minDateFilter.val()
                        d.maxDate = maxDateFilter.val()
                    },
                },
                columns: [{
                        data: 'image',
                        name: 'image',
                        title: 'Foto'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name',
                        title: 'Nama Sales'
                    },
                    {
                        data: 'clock_in',
                        name: 'clock_in',
                        title: 'Clock In'
                    },
                    {
                        data: 'clockInStatus',
                        name: 'clockInStatus',
                        title: 'Status'
                    },
                    {
                        data: 'clock_out',
                        name: 'clock_out',
                        title: 'Clock Out'
                    },
                    {
                        data: 'clockOutStatus',
                        name: 'clockOutStatus',
                        title: 'Status'
                    }
                ]
            })

            $('#searchAttendance').keyup((e) => {
                let value = e.target.value.toLowerCase()
                attendanceTable.search(value).draw()
            })

            $(mainStoreSelect).on('change', (e) => {
                attendanceTable.draw()
            })

            $(branchStoreSelect).on('change', (e) => {
                attendanceTable.draw()
            })

            $(minDateFilter).on('change', (e) => {
                attendanceTable.draw()
            })

            $(maxDateFilter).on('change', (e) => {
                attendanceTable.draw()
            })
        })
    </script>
@endsection
@endcan