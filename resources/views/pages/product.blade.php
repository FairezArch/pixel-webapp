@extends('layouts.admin')

@section('title', 'Product List')

@section('content')
    <section class="product">
        <div class="product__header">
            <div class="product__title">
                <span>
                    List Produk
                </span>
            </div>
            <div class="product__button">
                @can('product_create')
                    <a class="btn-add" href="{{ route('product.create') }}">
                        Tambah Produk
                    </a>
                @endcan
            </div>
            <div class="product__search">
                <div class="search__wrapper">
                    <input type="text" placeholder="Cari..." id="searchProduct">
                    <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                </div>
            </div>
        </div>
        <div class="product__body">
            <table id="productTable"></table>
        </div>
    </section>
@endsection

@can('product_delete')
    @section('modal')
        <x-others.delete-confirmation-modal name="Produk" url="product" />
    @endsection
@endcan

@can('product_list')
    @section('script')
        @stack('script')
        <script>
            $(document).ready(() => {
                const productTable = $('#productTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('product.index') }}",
                    columns: [{
                            data: 'image',
                            name: 'image',
                            title: 'Gambar'
                        },
                        {
                            data: 'category_product.name',
                            name: 'category_product.name',
                            title: 'Kategori'
                        },
                        {
                            data: 'name',
                            name: 'name',
                            title: 'Nama Produk'
                        },
                        {
                            data: 'price',
                            name: 'price',
                            title: 'Harga'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'Kelola'
                        }
                    ]
                })

                $('#searchProduct').keyup(function() {
                    productTable.search($(this).val()).draw();
                })
            })
        </script>
    @endsection
@endcan
