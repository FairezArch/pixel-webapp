@extends('layouts.admin')

@section('title', 'Edit Toko Utama')

@section('content')
    <section class="channel-edit">
        <div class="channel-edit__header">
            <div class="channel-edit__title">
                <span>Edit Toko Utama</span>
            </div>
        </div>

        <div class="channel-edit__body">
            <form method="POST" action="{{ route('main-store.update',['main_store'=>request()->main_store]) }}" class="form-edit">
                @method('PUT')
                @csrf
                <div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Toko Utama" value="{{ $main_store->name }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="region">Regional</label>
                        <select name="region" id="region" required>
                            @foreach ( $regions as $region )
                                <option value="{{$region->id}}" {{($region->id == $main_store->region_id) ? 'selected' : ''}}>{{$region->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="1" {{ ($main_store->status == 1) ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ ($main_store->status == 0) ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-button">
                        <a href="{{ route('main-store.index') }}">Batal</a>
                        <button type="submit">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
