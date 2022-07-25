@extends('layouts.admin')

@section('title', 'List Toko Utama')

@section('content')
    <section class="channel">
        <div class="channel__header">
            <div class="channel__title">
                <span>List Toko Utama</span>
            </div>
            <div class="channel__button">
                @can('main_shop_create')
                <a class="btn-add" href="{{ route('main-store.create') }}">Tambah Toko Utama</a>
                @endcan
            </div>
            <div class="channel__search">
                <div class="search__wrapper">
                    <input type="text" placeholder="Cari..." id="searchchannel">
                    <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                </div>
            </div>
        </div>
        <div class="channel__body">
            <div class="channel__wrapper">
                @forelse ($channels as $channel)
                    <div class="channel__list">
                        <div class="channel__list-name">
                            <span>{{ $channel->name }}</span>
                        </div>
                        <div class="channel__list-button">
                            @can('main_shop_update')
                            <a href="{{ route('main-store.edit', ['main_store' => $channel->id]) }}">
                                <img src="{{ asset('assets/images/edit-icon.png') }}" alt="Edit Icon">
                            </a>
                            @endcan
                            @can('main_shop_delete')
                            <a href="javascript:void(0)" data-id="{{ $channel->id }}" data-toggle="modal"
                                data-target="#deleteConfirmationModal" id="btnDelete">
                                <img src="{{ asset('assets/images/delete-icon.png') }}" alt="Delete Icon">
                            </a>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="empty-list">
                        <span>Channel belum ditambahkan.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@can('main_shop_delete')
@section('modal')
<x-others.delete-confirmation-modal name="Channel" url="main-store" />
@endsection
@endcan

@section('script')
    @stack('script')
    <script>
        $('#searchchannel').keyup(function() {
            let search = $(this).val().toLowerCase()
            let categories = $('.channel__list')

            Object.keys(categories).forEach(key => {
                let channel = $(categories[key])
                let value = channel.find('.channel__list-name span').text().toLowerCase()
                if (!value.includes(search)) {
                    channel.css('display', 'none')
                } else {
                    channel.css('display', 'flex')
                }
            })
        })
    </script>
@endsection
