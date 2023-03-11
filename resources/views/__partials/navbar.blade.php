<div class="navbar__wrapper">
    <div class="mobile__bars">
        <img src="{{ asset('assets/images/bars-icon.png') }}" alt="Bars Icon">
    </div>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModalCenter">
        Informasi Penting!
    </button>

    <div class="navbar__menu">
        {!! Form::open(['method' => 'POST', 'route' => 'auth.logout']) !!}
        <div>
            <button type="submit">Logout</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
