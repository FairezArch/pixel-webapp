@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')
    <section class="category-create">
        <div class="category-create__header">
            <div class="category-create__title">
                <span>Tambah Kategori</span>
            </div>
        </div>

        <div class="category-create__body">
            <form method="POST" action="{{ route('category.store') }}" class="form-create">
                @csrf
                <div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Kategori" value="{{ old('name') }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-button">
                        <a href="{{ route('category.index') }}">Batal</a>
                        <button type="submit">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
