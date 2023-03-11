@extends('layouts.sso')

@section('title', 'Login')

@section('content')
    <div class="background">

    </div>
    <main>
        <section class="login">
            <h3>W e l c o m e !</h3>
            <form action="{{ route('auth.login.submit') }}" method="POST">
                @csrf
                <div class="form-group email-group">
                    <input type="text" name="email" placeholder="email" id="email" value="testing@example.net">
                    @error('email')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                    {{-- {!! Form::label('email', '') !!} --}}
                </div>
                <div class="form-group password-group">
                    <input type="password" name="password" placeholder="password" id="password"
                        value="ByuRtuqVe">
                    @error('password')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                    {{-- {!! Form::label('password', '') !!} --}}
                </div>
                <input type="submit" value="Login">
                <a href="{{ route('auth.forgot') }}">{{-- lupa --}}</a>
            </form>
        </section>
    </main>
@endsection
