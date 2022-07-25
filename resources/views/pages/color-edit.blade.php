@extends('layouts.admin')

@section('title', 'Edit Warna')

@section('content')
    <section class="color-edit">
        <div class="color-edit__header">
            <div class="color-edit__title">
                <span>Ubah Warna</span>
            </div>
        </div>
        <div class="color-edit__body">
            <form method="POST" action="{{ route('color.update', ['color' => request()->color]) }}"
                class="form-edit">
                @csrf
                @method('put')
                <div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Warna" value="{{ $color->name }}"
                            required>
                        @error('name')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="code_color">Kode Warna</label>
                        <input type="color" name="code_color" id="code_color" class="code_color" value="{{ $color->code_color }}">
                        @error('code_color')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="1" {{ $color->status == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $color->status == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-button">
                        <a href="{{ route('color.index') }}">Batal</a>
                        <button type="submit">Ubah</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
