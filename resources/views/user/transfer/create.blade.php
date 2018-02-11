@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-transfer"></i> Registrar recarga
    </h1>
    <p>
        Luego de hacer el deposito o transferencia debe registrarla en este formulario y su saldo se hara efectivo en un maximo
        de 24 horas habiles bancarias.
    </p>

    <section class="transfer-create">
        <div class="row">

            <div class="col-xs-12">

                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="alert alert-info">
                            <p>
                                <strong>ATENCIÓN:</strong>
                                Utilice los siguientes datos para hacer su deposito o transferencia. Despues de hacer el pago registre en el siguiente formulario
                                los datos del movimiento y en un periodo no mayor a una hora tendra su saldo disponible.
                            </p>
                            <p>
                                <strong>Nota:</strong> En caso de efectuar el pago desde una cuenta de banco diferente a la de destino, tomará un periodo maximo
                                de 24 horas habiles bancarias para recibir su saldo.
                            </p>
                        </div>

                        <register-transfer
                                banks = "{{ json_encode($banks) }}"
                                >

                        </register-transfer>

                        <h3>Cuentas bancarias</h3>

                        <div class="row">

                            <div class="col-sm-4">
                                <div class="transfer-create__bank-account">
                                    <p>
                                        <strong>Nombre:</strong> Emilio Ochoa
                                    </p>
                                    <p>
                                        <strong>CI:</strong> 21029522
                                    </p>
                                    <p>
                                        <strong>Banco:</strong> Banesco
                                    </p>
                                    <p>
                                        <strong>Cuenta:</strong> 4664653456454454544
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="transfer-create__bank-account">
                                    <p>
                                        <strong>Nombre:</strong> Emilio Ochoa
                                    </p>
                                    <p>
                                        <strong>CI:</strong> 21029522
                                    </p>
                                    <p>
                                        <strong>Banco:</strong> Banesco
                                    </p>
                                    <p>
                                        <strong>Cuenta:</strong> 4664653456454454544
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="transfer-create__bank-account">
                                    <p>
                                        <strong>Nombre:</strong> Emilio Ochoa
                                    </p>
                                    <p>
                                        <strong>CI:</strong> 21029522
                                    </p>
                                    <p>
                                        <strong>Banco:</strong> Banesco
                                    </p>
                                    <p>
                                        <strong>Cuenta:</strong> 4664653456454454544
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection