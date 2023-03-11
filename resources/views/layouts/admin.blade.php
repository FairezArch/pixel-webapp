<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title') | Pixel</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('link')
</head>

<body>
    @include('__partials.sidebar')

    <main>
        @include('__partials.navbar')
        @yield('content')
    </main>

    @yield('modal')

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        $('.sidebar__menu-list.dropdown__toggle').on('click', function() {
            let dropdown = $(this).find('.sidebar__dropdown-menu-wrapper')
            let siblings = $(this).siblings()
            siblings.find('.sidebar__dropdown-menu-wrapper').removeClass('show')
            $('.sidebar__dropdown-menu-wrapper').siblings().removeClass('show')
            dropdown.toggleClass('show')
        })

        $('.mobile__bars').on('click', () => {
            $('.sidebar__wrapper').toggleClass('show')
            $('main').toggleClass('show')

            if ($('.sidebar__wrapper').hasClass('show')) {
                $('html').css('overflow-y', 'hidden')
            } else {
                $('html').css('overflow-y', 'unset')
            }
        })

        $('.sidebar__dropdown-menu-wrapper a').on('click', (e) => {
            e.stopPropagation();
        })
    </script>
    @yield('script')
</body>

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
                <h4>Selamat datang dalam CMS Pixel Communication Indonesia, Ini adalah bentuk dan data sederhana dari
                    Pixel Communication Indonesia. Perlu diketahui untuk
                    data disini hanya sebagai contoh saja, tidak selengkap dari versi asli.</h4>
                <p>Dibawah ini adalah list API, silahkan download env dan collection untuk mencoba. Untuk file ini
                    sudah
                    di convert untuk support dengan postman. Jika di postman mengalami kendala bisa di coba untuk
                    install extension thunder client di vs code dan import env dan collection</p>
                <p>Perlu diketahui untuk mengirim email saat lupa password menggunkan queue laravel dengan set cron
                    job 1 menit, terkadang butuh waktu untuk sampai ke email tujuan.</p>
                <p>Jika sekiranya ada masukkan dan bug, jangan ragu untuk email ke <span
                        class="text-primary">fairez.work@gmail.com</span></p>
                <p>Terimakasih</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('get.env.tc') }}" class="btn btn-warning">Download env</a>
                <a href="{{ route('get.collect.tc') }}" class="btn btn-primary">Download collection</a>
            </div>
        </div>
    </div>
</div>

</html>
