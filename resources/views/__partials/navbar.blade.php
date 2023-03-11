<div class="navbar__wrapper">
    <div class="mobile__bars">
        <img src="{{ asset('assets/images/bars-icon.png') }}" alt="Bars Icon">
    </div>

     <!-- Button trigger modal -->
     <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModalCenter">
        Informasi Penting!
    </button>

    <!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Informasi singkat</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <h4>Selamat datang dalam CMS Pixel Comunity Indonesia, Ini adalah bentuk dan data sederhana dari Pixel Comunity Indonesia. Perlu diketahui untuk
                data disini hanya sebagai contoh saja, tidak selengkap dari versi asli.</h4>
            <p>Dibawah ini adalah list API, silahkan download env dan collection untuk mencoba. Untuk file ini sudah
                di convert untuk support dengan postman. Jika di postman mengalami kendala bisa di coba untuk
                install extension thunder client di vs code dan import env dan collection</p>
            <p>Perlu diketahui untuk mengirim email saat lupa password menggunkan queue laravel dengan set cron job 1 menit, Mungkin bisa agak delay</p>
            <p>Jika sekiranya ada masukkan dan bug, jangan ragu untuk email ke <span class="text-primary">fairez.work@gmail.com</span></p>
            <p>Terimakasih</p>
        </div>
        <div class="modal-footer">
            <a href="{{ route('get.env.tc') }}"
                class="btn btn-warning">Download env</a>
            <a href="{{ route('get.collect.tc') }}"
                class="btn btn-primary">Download collection</a>
        </div>
    </div>
</div>
</div>

    <div class="navbar__menu">
        {!! Form::open(['method' => 'POST', 'route' => 'auth.logout']) !!}
        <div>
            <button type="submit">Logout</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
