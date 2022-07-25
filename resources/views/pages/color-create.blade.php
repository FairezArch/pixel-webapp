@extends('layouts.admin')

@section('title', 'Tambah Warna')

@section('content')
    <section class="color-create">
        <div class="color-create__header">
            <div class="color-create__title">
                <span>Tambah Warna</span>
            </div>
        </div>

        <div class="color-create__body">
            <form method="POST" action="{{ route('color.store') }}" class="form-create">
                @csrf
                <div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Warna" value="{{ old('name') }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="code_color">Kode Warna</label>
                        <input type="color" name="code_color" id="code_color" class="code_color" placeholder="Nama Warna" value="{{ old('code_color') }}" style="">
                        @error('code_color')
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
                        <a href="{{ route('color.index') }}">Batal</a>
                        <button type="submit">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

