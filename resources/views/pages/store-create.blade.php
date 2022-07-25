@extends('layouts.admin')

@section('title', 'Add Store')

@section('link')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <section class="store-create">
        <div class="store-create__header">
            <div class="store-create__title">
                <span>Tambah Store</span>
            </div>
        </div>

        <div class="store-create__body">
            <form action="{{ route('branch-store.store') }}" class="form-create" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <div class="form-group__image">
                        <input type="file" name="file" accept=".jpg,.png,.jpeg" onchange="handleImagePreview(event)"
                            >
                        <img src="{{ asset('assets/images/image-default.png') }}" alt="Image Default Icon">
                        <a href="javascript:void(0)" onclick="handleImage()">Tambah Gambar</a>
                        @error('file')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label for="channel_id">Toko Utama</label>
                        <select name="channel_id" id="channel_id" required>
                            <option value="" disabled selected>Pilih Toko Utama</option>
                            @foreach ($channels as $channel)
                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                            @endforeach
                        </select>
                        @error('channel_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Nama Toko</label>
                        <input type="text" name="name" id="name" placeholder="Nama Toko" value="{{ old('name') }}"
                            required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group d-none">
                        <label for="promoter_ids">Promotor</label>
                        <select name="promoter_ids[]" id="promoter_ids" class="promoter_ids" multiple="multiple">
                            @foreach ($promoters as $promoter)
                                <option value="{{ $promoter->id }}">{{ $promoter->name }}</option>
                            @endforeach
                        </select>
                        @error('promotor_ids')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group d-none">
                        <label for="frontliner_ids">Front Liner</label>
                        <select name="frontliner_ids[]" id="frontliner_ids" class="frontliner_ids" multiple="multiple">
                            @foreach ($frontliners as $frontliner)
                                <option value="{{ $frontliner->id }}">{{ $frontliner->name }}</option>
                            @endforeach
                        </select>
                        @error('frontliner_ids')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="location">Lokasi</label>
                        <textarea name="location" id="location" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-button">
                        <a href="{{ route('branch-store.index') }}">Batal</a>
                        <button type="submit">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const handleImage = () => {
            $('.store-create .form-group__image input').click()
        }

        const handleImagePreview = (e) => {
            let file = e?.target?.files[0]
            let imageElement = $('.store-create .form-group__image img')
            imageElement.prop('src', URL.createObjectURL(file))
        }

        $(document).ready(() => {
            $('select[name=channel_id]').select2();
            $('.promoter_ids').select2({multiple: true})
            $('.frontliner_ids').select2({multiple: true})
        });
    </script>
@endsection
