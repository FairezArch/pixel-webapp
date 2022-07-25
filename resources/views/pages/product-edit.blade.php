@extends('layouts.admin')

@section('title', 'Edit Product')

@section('link')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <section class="product-edit">
        <div class="product-edit__header">
            <div class="product-edit__title">
                <span>Ubah Produk</span>
            </div>
        </div>
        <div class="product-edit__body">
            <form action="{{ route('product.update', ['product' => request()->product]) }}" class="form-edit"
                method="POST" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div>
                    <div class="form-group__image">
                        <input type="file" name="file" accept=".jpg,.png,.jpeg" onchange="handleImagePreview(event)">
                        <img src="{{ asset('storage/product/' . $product->filename) }}" alt="Image Default Icon">
                        <a href="javascript:void(0)" onclick="handleImage()">Ubah Gambar</a>
                        @error('file')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label for="category_id">Kategori</label>
                        <select name="category_id" id="category_id" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="code">Type</label>
                        <input type="text" name="code" id="code" placeholder="Tipe Produk" value="{{ $product->code }}"
                            required>
                        @error('code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Produk" value="{{ $product->name }}"
                            required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="color">Warna</label>
                        <select name="color[]" id="color" class="color" required multiple="multiple">
                            @foreach ($colors as $color)
                                <option value="{{ $color->id }}"
                                    {{ (in_array($color->id, $product->color_ids)) ? 'selected' : '' }}>
                                    {{ $color->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="text" name="price" id="price" placeholder="Harga Produk"
                            value="{{ 'Rp. '.number_format($product->price,0,'.','.') }}" required>
                        @error('price')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="need_image">Butuh Scan IMEI</label>
                        <select name="need_image" id="need_image">
                            <option value="0" {{($product->takeImei == 0) ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{($product->takeImei == 1) ? 'selected' : '' }}>Ya</option>
                        </select>
                        <small>Digunakan saat transaksi. Untuk upload file imei</small>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="1" {{($product->status == 1) ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{($product->status == 0) ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-button">
                        <a href="{{ route('product.index') }}">Batal</a>
                        <button type="submit">Ubah</button>
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
            $('.product-edit .form-group__image input').click()
        }

        const handleImagePreview = (e) => {
            let file = e?.target?.files[0]
            let imageElement = $('.product-edit .form-group__image img')
            imageElement.prop('src', URL.createObjectURL(file))
        }

        $(document).ready(() => {
            $('select[name=category_id]').select2();
            $('.color').select2({
                multiple: true
            })

            let rupiah1 = document.getElementById("price");
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
            });
    </script>
     <script>
       
    </script>
@endsection
