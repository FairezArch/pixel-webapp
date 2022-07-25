@extends('layouts.admin')

@section('title', 'List Warna')

@section('content')
    <section class="color">
        <div class="color__header">
            <div class="color__title">
                <span>List Warna</span>
            </div>
            <div class="color__button">
                @can('product_create')
                <a class="btn-add" href="{{ route('color.create') }}">Tambah Warna</a>
                @endcan
            </div>
            <div class="color__search">
                <div class="search__wrapper">
                    <input type="text" placeholder="Cari..." id="searchColor">
                    <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                </div>
            </div>
        </div>
        <div class="color__body">
            <div class="color__wrapper">
                @forelse ($colors as $color)
                    <div class="color__list">
                        <div class="color__list-name">
                            <span>{{ $color->name }}</span>
                        </div>
                        <div class="color__list-button">
                            @can('product_update')
                            <a href="{{ route('color.edit', ['color' => $color->id]) }}">
                                <img src="{{ asset('assets/images/edit-icon.png') }}" alt="Edit Icon">
                            </a>
                            @endcan
                            @can('product_delete')
                            <a href="javascript:void(0)" data-id="{{ $color->id }}" data-toggle="modal"
                                data-target="#deleteConfirmationModal" id="btnDelete">
                                <img src="{{ asset('assets/images/delete-icon.png') }}" alt="Delete Icon">
                            </a>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="empty-list">
                        <span>Warna belum ditambahkan.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@can('product_delete')
@section('modal')
<x-others.delete-confirmation-modal name="Warna" url="color" />
@endsection
@endcan

@section('script')
    @stack('script')
    <script>
        $('#searchColor').keyup(function() {
            let search = $(this).val().toLowerCase()
            let categories = $('.color__list')

            Object.keys(categories).forEach(key => {
                let color = $(categories[key])
                let value = color.find('.color__list-name span').text().toLowerCase()
                if (!value.includes(search)) {
                    color.css('display', 'none')
                } else {
                    color.css('display', 'flex')
                }
            })
        })
    </script>
@endsection
