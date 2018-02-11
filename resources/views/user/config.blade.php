@extends('partial.base')

@section('main')

    <h1>
        <i class="glyphicon glyphicon-cog"></i> Configuración!
    </h1>
    <p>
        Puedes cambiar tu configuración siempre que necesites. Es recomendable aplicar cambios de contraseña
        periodicamente.
    </p>

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Configuración de usuario</h3>
                </div>
                <div class="panel-body">

                    <bank-data-update
                        banks = "{{ json_encode($banks) }}"
                        bank_id = "{{ Auth::user()->bank_id }}"
                        number_account = "{{ Auth::user()->number_account }}"
                        name = "{{ Auth::user()->name }}"
                        identity_card = "{{ Auth::user()->identity_card }}"
                    >

                    </bank-data-update>
                </div>

                <div class="panel-heading">
                    <h3 class="panel-title">Cambio de contraseña</h3>
                </div>

                <div class="panel-body">
                    <change-password></change-password>
                </div>
            </div>
        </div>
    </div>

@endsection