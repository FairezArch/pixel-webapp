@extends('layouts.admin')

@section('title', 'Add Target')

@section('link')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <section class="target-create">
        <div class="target-create__header">
            <div class="target-create__title">
                <span>Tambah Target</span>
            </div>
        </div>
        <div class="target-create__body">
            <form action="{{ route('target.store') }}" class="form-create" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <div class="form-group">
                        <label for="user_id">Nama Karyawan</label>
                        <select name="user_id" id="user_id" required>
                            <option value="" disabled selected>Pilih Karyawan</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nominal">Nominal</label>
                        <input type="text" name="nominal" id="nominal" placeholder="Nominal Target"
                            value="{{ old('nominal') }}" required>
                        @error('nominal')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-button">
                        <a href="{{ route('target.index') }}">Batal</a>
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
        $('select[name=user_id]').select2();
        $(document).ready( () => {
            let rupiah1 = document.getElementById("nominal");
            rupiah1.addEventListener("keyup", function(e) {
                rupiah1.value = `Rp. ${convertRupiah(this.value)}`;
            });
            rupiah1.addEventListener('keydown', function(event) {
                return isNumberKey(event);
            });

            function convertRupiah(angka, prefix) {
                let number_string = angka.replace(/[^,\d]/g, "").toString(),
                    split = number_string.split(","),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? "." : "";
                    rupiah += separator + ribuan.join(".");
                }

                rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
                return prefix == undefined ? rupiah : rupiah ? prefix + rupiah : "";
            }

            function isNumberKey(evt) {
                key = evt.which || evt.keyCode;
                if (key != 188 // Comma
                    &&
                    key != 8 // Backspace
                    &&
                    key != 17 && key != 86 & key != 67 // Ctrl c, ctrl v
                    &&
                    (key < 48 || key > 57) // Non digit
                ) {
                    evt.preventDefault();
                    return;
                }
            }
        })
    </script>
@endsection
