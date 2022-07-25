@extends('layouts.admin')

@section('title', 'Add Region')

@section('content')
    <section class="region-edit">
        <div class="region-edit__header">
            <div class="region-edit__title">
                <span>Ubah Daerah</span>
            </div>
        </div>
        <div class="region-edit__body">
            <form method="POST" action="{{ route('region.update', ['region' => request()->region]) }}"
                class="form-edit">
                @csrf
                @method('put')
                <div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Daerah" value="{{ $region->name }}"
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
                            <option value="1" {{ $region->status == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $region->status == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-button">
                        <a href="{{ route('region.index') }}">Batal</a>
                        <button type="submit">Ubah</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
