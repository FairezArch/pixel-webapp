@extends('layouts.admin')

@section('title', 'Store List')

@section('content')
    <section class="store">
        <div class="store__header">
            <div class="store__title">
                <span>
                    List Toko
                </span>
            </div>
            <div class="store__button">
                @can('branch_shop_create')
                <a class="btn-add" href="{{ route('branch-store.create') }}">
                    Tambah Toko
                </a>
                @endcan
            </div>
            <div class="store__search">
                <div class="search__wrapper">
                    <input type="text" placeholder="Cari..." id="searchstore">
                    <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                </div>
            </div>
        </div>
        <div class="store__body">
            <table id="storeTable"></table>
        </div>
    </section>
@endsection

@can('branch_shop_delete')
@section('modal')
<x-others.delete-confirmation-modal name="Toko" url="branch-store" />
@endsection
@endcan

@can('branch_shop_list')
@section('script')
    @stack('script')
    <script>
        $(document).ready(() => {
            const storeTable = $('#storeTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('branch-store.index') }}",
                columns: [{
                        data: 'name',
                        name: 'name',
                        title: 'Toko'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        title: 'Status'
                    },
                    @can(['branch_shop_update','branch_shop_delete'])
                    {
                        data: 'action',
                        name: 'action',
                        title: 'Kelola'
                    }
                    @endcan
                ]
            })

            $('#searchstore').keyup(function() {
                storeTable.search($(this).val()).draw();
            })
        })
    </script>
@endsection
@endcan
