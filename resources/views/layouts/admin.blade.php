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

</html>
