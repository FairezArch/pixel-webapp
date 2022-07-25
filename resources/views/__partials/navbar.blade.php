<div class="navbar__wrapper">
    <div class="mobile__bars">
        <img src="{{ asset('assets/images/bars-icon.png') }}" alt="Bars Icon">
    </div>
    <div class="navbar__menu">
        {!! Form::open(['method' => 'POST', 'route' => 'auth.logout']) !!}
        <div>
            <button type="submit">Logout</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
