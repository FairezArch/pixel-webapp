@extends('layouts.admin')

@section('title', 'Employee List')

@section('link')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <section class="employee">
        <div class="employee__header">
            <div class="employee__title">
                <span>List Karyawan</span>
            </div>
            <div class="employee__button">
                @can('employee_create')
                    <a class="btn-add" href="{{ route('user.create') }}">
                        Tambah Karyawan
                    </a>
                @endcan
            </div>
        </div>
        <div class="employee__body">
            <div class="employee__body-top">
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
                <div class="employee__search">
                    <div class="search__wrapper">
                        <input type="text" placeholder="Cari..." id="searchEmployee">
                        <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                    </div>
                </div>
            </div>
            <table id="employeeTable" style="width: 100%"></table>
        </div>
    </section>
@endsection

@can('employee_delete')
    @section('modal')
        <x-others.delete-confirmation-modal name="Karyawan" url="user" />
    @endsection
@endcan

@can('employee_list')
    @section('script')
        @stack('script')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(() => {
                let mainStoreSelect = $('select[name=main_store]')
                let branchStoreSelect = $('select[name=branch_store]')

                mainStoreSelect.select2();
                branchStoreSelect.select2();

                const productTable = $('#employeeTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('user.index') }}",
                    columns: [{
                            data: 'image',
                            name: 'image',
                            title: 'Foto'
                        },
                        {
                            data: 'nameTemp',
                            name: 'name',
                            title: 'Nama'
                        },
                        {
                            data: 'mobile',
                            name: 'mobile',
                            title: 'No.Telp'
                        },
                        {
                            data: 'email',
                            name: 'email',
                            title: 'Email'
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
                                    return data.store.channel.name;
                                }
                            },
                            name: 'store.channel.name',
                            title: 'Toko Utama'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            title: 'Status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'Kelola'
                        },
                    ]
                })

                mainStoreSelect.on('change', (e) => {
                    let value = e.target.value
                    productTable.column(6).search(value).draw()
                })

                branchStoreSelect.on('change', (e) => {
                    let value = e.target.value
                    productTable.column(5).search(value).draw()
                })

                $('#searchEmployee').keyup((e) => {
                    let value = e.target.value.toLowerCase()
                    productTable.search(value).draw()
                })
            })
        </script>
    @endsection
@endcan
