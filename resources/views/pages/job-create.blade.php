@extends('layouts.admin')

@section('title', 'Tambah Pekerjaan')

@section('content')
    <section class="job-create">
        <div class="job-create__header">
            <div class="job-create__title">
                <span>Tambah Pekerjaan</span>
            </div>
        </div>

        <div class="job-create__body">
            <form method="POST" action="{{ route('job.store') }}" class="form-create">
                @csrf
                <div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Pekerjaan" value="{{ old('name') }}" required>
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
                        <a href="{{ route('job.index') }}">Batal</a>
                        <button type="submit">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
