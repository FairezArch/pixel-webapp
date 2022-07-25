 @extends('layouts.admin')

@section('title', 'Category List')

@section('content')
    <section class="category">
        <div class="category__header">
            <div class="category__title">
                <span>List Kategori</span>
            </div>
            <div class="category__button">
                @can('category_product_create')
                <a class="btn-add" href="{{ route('category.create') }}">Tambah Kategori</a>
                @endcan
            </div>
            <div class="category__search">
                <div class="search__wrapper">
                    <input type="text" placeholder="Cari..." id="searchCategory">
                    <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                </div>
            </div>
        </div>
        <div class="category__body">
            <div class="category__wrapper">
                @forelse ($categories as $category)
                    <div class="category__list">
                        <div class="category__list-name">
                            <span>{{ $category->name }}</span>
                        </div>
                        <div class="category__list-button">
                            @can('category_product_update')
                            <a href="{{ route('category.edit', ['category' => $category->id]) }}">
                                <img src="{{ asset('assets/images/edit-icon.png') }}" alt="Edit Icon">
                            </a>
                            @endcan
                            @can('category_product_delete')
                            <a href="javascript:void(0)" data-id="{{ $category->id }}" data-toggle="modal"
                                data-target="#deleteConfirmationModal" id="btnDelete">
                                <img src="{{ asset('assets/images/delete-icon.png') }}" alt="Delete Icon">
                            </a>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="empty-list">
                        <span>Kategori belum ditambahkan.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@can('category_product_delete')
@section('modal')
<x-others.delete-confirmation-modal name="Kategori" url="category" />
@endsection
@endcan

@section('script')
    @stack('script')
    <script>
        $('#searchCategory').keyup(function() {
            let search = $(this).val().toLowerCase()
            let categories = $('.category__list')

            Object.keys(categories).forEach(key => {
                let category = $(categories[key])
                let value = category.find('.category__list-name span').text().toLowerCase()
                if (!value.includes(search)) {
                    category.css('display', 'none')
                } else {
                    category.css('display', 'flex')
                }
            })
        })
    </script>
@endsection
