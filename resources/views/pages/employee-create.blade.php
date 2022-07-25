@extends('layouts.admin')

@section('title', 'Add Employee')

@section('link')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <section class="employee-create">
        <div class="employee-create__header">
            <div class="employee-create__title">
                <span>Tambah Karyawan</span>
            </div>
        </div>

        <div class="employee-create__body">
            <form action="{{ route('user.store') }}" class="form-create" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <div class="form-group__image">
                        <input type="file" name="file" accept=".jpg,.png,.jpeg" onchange="handleImagePreview(event)"
                            required>
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
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Karyawan" value="{{ old('name') }}"
                            required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Telepon</label>
                        <input type="text" name="phone_number" id="phone_number" placeholder="No Telepon"
                            value="{{ old('phone_number') }}" required>
                        @error('phone_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}"
                            required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="region_id">Daerah</label>
                        <select name="region_id" id="region_id" required>
                            <option value="" selected disabled>Pilih Daerah</option>
                            @foreach ($regionals as $regional)
                                <option value="{{ $regional->id }}">{{ $regional->name }}</option>
                            @endforeach
                        </select>
                        @error('region_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="channel_id">Toko Utama</label>
                        <select name="channel_id" id="channel_id" required>
                            <option value="" selected disabled>Pilih Toko Utama</option>
                        </select>
                        @error('channel_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="store_id">Toko Cabang</label>
                        <select name="store_id" id="store_id" required>
                            <option value="" selected disabled>Pilih Toko Cabang</option>
                        </select>
                        @error('store_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="role">Divisi</label>
                        <select name="role" id="role" required>
                            <option value="" disabled selected>Pilih Divisi</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
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
                        <a href="{{ route('user.index') }}">Batal</a>
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
        let mainStores = []
        let branchStores = []

        const handleImage = () => {
            $('.employee-create .form-group__image input').click()
        }

        const handleImagePreview = (e) => {
            let file = e?.target?.files[0]
            let imageElement = $('.employee-create .form-group__image img')
            imageElement.prop('src', URL.createObjectURL(file))
        }

        $(document).ready(() => {
            let regionSelect = $('select[name=region_id]')
            let channelSelect = $('select[name=channel_id]')
            let storeSelect = $('select[name=store_id]')
            regionSelect.select2({
                allowClear: true,
                placeholder: 'Pilih Daerah'
            });

            channelSelect.select2({
                allowClear: true,
                placeholder: 'Pilih Toko Utama'
            });

            storeSelect.select2({
                allowClear: true,
                placeholder: 'Pilih Toko Cabang'
            });

            axios.get("{{ route('user.create') }}")
                .then((res) => {
                    let data = res.data
                    mainStores = data.main_stores
                    branchStores = data.branch_stores
                })

            $(regionSelect).on('select2:select', (e) => {
                let regionID = e.target.value

                let filtered = Object.keys(mainStores)
                    .filter(key => key == regionID)
                    .reduce((obj, key) => {
                        return mainStores[key]
                    }, {})

                $(channelSelect).empty().trigger('change')
                $(storeSelect).empty().trigger('change')

                $(channelSelect).select2({
                    placeholder: 'Pilih Toko Utama',
                    data: filtered,
                    allowClear: true,
                })

                $(channelSelect).val('').trigger('change')
            })

            $(channelSelect).on('change', (e) => {
                let channelID = e.target.value
                let filtered = Object.keys(branchStores)
                    .filter(key => key == channelID)
                    .reduce((obj, key) => {
                        return branchStores[key]
                    }, {})

                $(storeSelect).empty().trigger('change')

                $(storeSelect).select2({
                    placeholder: 'Pilih Toko Cabang',
                    data: filtered,
                    allowClear: true,
                })

                $(storeSelect).val('').trigger('change')
            })

        });
    </script>
@endsection
