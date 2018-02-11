@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-home"></i> Bienvenido!
    </h1>
    <p>
        {{ env('APP_NAME') }} te saluda, que gusto tenerte de vuelta, ingresa tu usuario y contraseña para acceder
        a tu panel de juego.
    </p>

    <login-user-form></login-user-form>
@endsection