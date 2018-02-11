@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-transfer"></i> Solicitar retiro
    </h1>
    <p>
        Aqui podrá solicitar el pago de su saldo. Su saldo sera bloqueado por la cantidad que indique en el retiro y se
        hará efectivo en la cuenta bancaria que tenga configurada en no mas de 24 horas habiles bancarias.
    </p>

    <section class="transfer-create">
        <div class="row">

            <div class="col-xs-12">

                <div class="panel panel-default">

                    <div class="panel-body">

                        <div class="alert alert-danger">

                            <h3 class="text-danger">Importante:</h3>
                            <p>
                                {{ env('APP_NAME') }} realiza el pago a la cuenta que tenga configurada y no se hace responsable
                                por datos errados en la misma. Por favor valide que estos datos sean correctos antes de solicitar
                                el pago y recuerda que este se hace efectivo entre 1 y 24 horas habiles bancarias.
                            </p>
                        </div>

                        <h4>
                            <strong class="text-success">Saldo disponible: </strong>
                            Bsf. {{ number_format(Auth::user()->balance - Auth::user()->block_balance, 2, ',', '.') }}
                        </h4>
                        <h4>
                            <strong class="text-danger">Saldo bloqueado: </strong>
                            Bsf. {{ number_format(Auth::user()->block_balance, 2, ',', '.') }}
                        </h4>

                        <register-withdraw
                            balance = "{{ Auth::user()->balance }}"
                            block_balance = "{{ Auth::user()->block_balance }}"
                        ></register-withdraw>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection