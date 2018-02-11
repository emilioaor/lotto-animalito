@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-transfer"></i> Detalle de la recarga.
    </h1>
    <p>
        Verifica el estatus de tu recarga, recuerda que puede tardar hasta 24 horas habiles bancarias en ser aprobada.
    </p>

    <section class="show-ticket">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Monto:
                                    <span class="bg-info text-info">
                                        {{ number_format($withdraw->amount, 2, ',', '.') }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Estatus:

                                    @if($withdraw->status === \App\Withdraw::STATUS_COMPLETE)
                                        <span class="text-success bg-success">Completado</span>
                                    @elseif($withdraw->status === \App\Transfer::STATUS_REJECTED)
                                        <span class="text-danger bg-danger">Rechazado</span>
                                    @elseif($withdraw->status === \App\Transfer::STATUS_PENDING)
                                        <span class="text-warning bg-warning">Pendiente</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Fecha:
                                    <span class="bg-warning text-warning">
                                        {{ $withdraw->created_at->format('d-m-Y h:i a') }}
                                    </span>
                                </p>
                            </div>

                            @if(Auth::user()->level === \App\User::LEVEL_ADMIN)

                                <div class="col-sm-6">
                                    <p class="show-ticket__data">
                                        Usuario:
                                <span class="bg-primary text-primary">
                                    {{ $withdraw->user->email }}
                                </span>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Banco:
                                    <span class="bg-info text-info">
                                        {{ $withdraw->user->bank->name }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Cuenta:
                                    <span class="bg-info text-info">
                                        {{ $withdraw->user->number_account }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    C.I:
                                    <span class="bg-info text-info">
                                        {{ $withdraw->user->identity_card }}
                                    </span>
                                </p>
                            </div>

                        </div>

                        @if(Auth::user()->level === \App\User::LEVEL_ADMIN && $withdraw->status === \App\Withdraw::STATUS_PENDING)

                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Ingrese contraseña para aprobar este retiro</h4>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <form
                                                action="{{ route('withdraw.changeStatus', ['withdraw' => $withdraw->id, 'status' => \App\Withdraw::STATUS_COMPLETE]) }}"
                                                method="post"
                                                >
                                            {{ csrf_field() }}
                                            <input type="password" name="password" class="form-control" placeholder="Contraseña">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection