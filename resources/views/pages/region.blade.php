@extends('layouts.admin')

@section('title', 'Region List')

@section('content')
    <section class="region">
        <div class="region__header">
            <div class="region__title">
                <span>List Daerah</span>
            </div>
            <div class="region__button">
                @can('region_create')
                <a class="btn-add" href="{{ route('region.create') }}">Tambah Daerah</a>
                @endcan
            </div>
            <div class="region__search">
                <div class="search__wrapper">
                    <input type="text" placeholder="Cari..." id="searchRegion">
                    <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                </div>
            </div>
        </div>
        <div class="region__body">
            <div class="region__wrapper">
                @forelse ($regions as $region)
                    <div class="region__list">
                        <div class="region__list-name">
                            <span>{{ $region->name }}</span>
                        </div>
                        <div class="region__list-button">
                            @can('region_edit')
                            <a href="{{ route('region.edit', ['region' => $region->id]) }}">
                                <img src="{{ asset('assets/images/edit-icon.png') }}" alt="Edit Icon">
                            </a>
                            @endcan
                            @can('region_delete')                            
                            <a href="javascript:void(0)" data-id="{{ $region->id }}" data-toggle="modal"
                                data-target="#deleteConfirmationModal" id="btnDelete">
                                <img src="{{ asset('assets/images/delete-icon.png') }}" alt="Delete Icon">
                            </a>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="empty-list">
                        <span>Daerah belum ditambahkan.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@can('region_delete')
@section('modal')
<x-others.delete-confirmation-modal name="Daerah" url="region" />
@endsection
@endcan

@section('script')
    @stack('script')
    <script>
        $('#searchRegion').keyup(function() {
            let search = $(this).val().toLowerCase()
            let regions = $('.region__list')

            Object.keys(regions).forEach(key => {
                let region = $(regions[key])
                let value = region.find('.region__list-name span').text().toLowerCase()
                if (!value.includes(search)) {
                    region.css('display', 'none')
                } else {
                    region.css('display', 'flex')
                }
            })
        })
    </script>
@endsection
