@extends('layouts.admin')

@section('title', 'Tambah Toko Utama')

@section('content')
    <section class="channel-create">
        <div class="channel-create__header">
            <div class="channel-create__title">
                <span>Tambah Toko Utama</span>
            </div>
        </div>

        <div class="channel-create__body">
            <form method="POST" action="{{ route('main-store.store') }}" class="form-create">
                @csrf
                <div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Toko Utama" value="{{ old('name') }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="region">Regional</label>
                        <select name="region" id="region" required>
                            @foreach ( $regions as $region )
                                <option value="{{$region->id}}">{{$region->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
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
