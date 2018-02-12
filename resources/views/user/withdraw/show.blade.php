@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-transfer"></i> Detalle del retiro.
    </h1>
    <p>
        Verifica el estatus de tu retiro, recuerda que puede tardar hasta 24 horas habiles bancarias en hacer efectivo.
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

                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Usuario:
                            <span class="bg-primary text-primary">
                                {{ $withdraw->user->email }}
                            </span>
                                </p>
                            </div>
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

                        @if(! empty($withdraw->capture))
                            <div class="row">
                                <div class="col-xs-12">
                                    <img src="{{ asset($withdraw->capture) }}" class="img-responsive">
                                </div>
                            </div>
                        @endif

                        @if(Auth::user()->level === \App\User::LEVEL_ADMIN && $withdraw->status === \App\Withdraw::STATUS_PENDING)

                            <process-withdraw
                                accept_url = "{{ route('withdraw.changeStatus', ['withdraw' => $withdraw->id, 'status' => \App\Withdraw::STATUS_COMPLETE]) }}"
                            >
                            </process-withdraw>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection