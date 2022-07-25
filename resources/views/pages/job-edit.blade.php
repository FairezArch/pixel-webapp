@extends('layouts.admin')

@section('title', 'Edit Pekerjaan')

@section('content')
    <section class="job-edit">
        <div class="job-edit__header">
            <div class="job-edit__title">
                <span>Ubah Pekerjaan</span>
            </div>
        </div>
        <div class="job-edit__body">
            <form method="POST" action="{{ route('job.update', ['job' => request()->job]) }}"
                class="form-edit">
                @csrf
                @method('put')
                <div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Perkerjaan" value="{{ $job->name }}"
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
                            <option value="1" {{ $job->status == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $job->status == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-button">
                        <a href="{{ route('job.index') }}">Batal</a>
                        <button type="submit">Ubah</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
