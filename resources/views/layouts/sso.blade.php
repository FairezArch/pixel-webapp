<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title') | Pixel</title>

    <link rel="stylesheet" href="{{ mix('css/sso.css') }}">
    @yield('link')
</head>

<body>
    
    <div class="base">
        @yield('content')
    </div>

    {{-- @yield('modal') --}}

    <script src="{{ mix('js/app.js') }}"></script>
    
    @yield('script')
</body>

</html>
