@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-envelope"></i> Recuperar contraseña
    </h1>
    <p>
        ¿Tienes problemas para recordar tu contraseña?. Te podemos ayudar enviando un correo con las instrucciones de recuperación
    </p>

    <div class="row">
        <div class="col-sm-8">
            <password-reset></password-reset>
        </div>
    </div>
@endsection