@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-th-list"></i> Registro!
    </h1>
    <p>
        El siguiente es un formulario de registro para acceder a la plataforma de juego.
    </p>

    <register-user-form
        banks="{{ json_encode($banks) }}"
    >

    </register-user-form>

@endsection