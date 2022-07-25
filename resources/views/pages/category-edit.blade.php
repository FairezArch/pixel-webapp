@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
    <section class="category-edit">
        <div class="category-edit__header">
            <div class="category-edit__title">
                <span>Ubah Kategori</span>
            </div>
        </div>
        <div class="category-edit__body">
            <form method="POST" action="{{ route('category.update', ['category' => request()->category]) }}"
                class="form-edit">
                @csrf
                @method('put')
                <div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Kategori" value="{{ $category->name }}"
                            required>
                        @error('name')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="1" {{ $category->status == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $category->status == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-button">
                        <a href="{{ route('category.index') }}">Batal</a>
                        <button type="submit">Ubah</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
