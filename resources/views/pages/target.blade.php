@extends('layouts.admin')

@section('title', 'Target List')

@section('link')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <section class="target">
        <div class="target__header">
            <div class="target__title">
                <span>List Target</span>
            </div>
            <div class="target__button">
                @can('target_create')
                    <a class="btn-add" href="{{ route('target.create') }}">
                        Tambah Target
                    </a>
                @endcan
            </div>
        </div>
        <div class="target__body">
            <div class="target__body-top">
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
                <div class="target__search">
                    <div class="search__wrapper">
                        <input type="text" placeholder="Cari..." id="searchTarget">
                        <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                    </div>
                </div>
            </div>
            <table id="targetTable"></table>
        </div>
    </section>
@endsection

@can('target_delete')
    @section('modal')
        <x-others.delete-confirmation-modal name="Target" url="target" />
    @endsection
@endcan

@can('target_list')
    @section('script')
        @stack('script')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(() => {
                let mainStoreSelect = $('select[name=main_store]')
                let branchStoreSelect = $('select[name=branch_store]')
                let minDateFilter = $('#minDateFilter')
                let maxDateFilter = $('#maxDateFilter')

                mainStoreSelect.select2();
                branchStoreSelect.select2();

                const targetTable = $('#targetTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('target.index') }}",
                        data: function(d) {
                            d.mainStore = mainStoreSelect.val();
                            d.branchStore = branchStoreSelect.val();
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
                            data: 'nominal',
                            name: 'nominal',
                            title: 'Nominal'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'Kelola'
                        }
                    ]
                })

                mainStoreSelect.on('change', (e) => {
                    targetTable.draw()
                })

                branchStoreSelect.on('change', (e) => {
                    targetTable.draw()
                })

                $('#searchTarget').keyup((e) => {
                    let value = e.target.value.toLowerCase()
                    targetTable.search(value).draw()
                })
            })
        </script>
    @endsection
@endcan
