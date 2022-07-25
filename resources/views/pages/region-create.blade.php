@extends('layouts.admin')

@section('title', 'Add Region')

@section('content')
    <section class="region-create">
        <div class="region-create__header">
            <div class="region-create__title">
                <span>Tambah Daerah</span>
            </div>
        </div>
        <div class="region-create__body">
            <form method="POST" action="{{ route('region.store') }}" class="form-create">
                @csrf
                <div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Daerah" value="{{ old('name') }}"
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
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-button">
                        <a href="{{ route('region.index') }}">Batal</a>
                        <button type="submit">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
