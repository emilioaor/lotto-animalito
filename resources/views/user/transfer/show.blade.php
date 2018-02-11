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
                                    Referencia:
                                    <span class="text-primary bg-primary">{{ $transfer->references }}</span>
                                </p>
                            </div>

                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Estatus:

                                    @if($transfer->status === \App\Transfer::STATUS_ACCEPTED)
                                        <span class="text-success bg-success">Aceptado</span>
                                    @elseif($transfer->status === \App\Transfer::STATUS_REJECTED)
                                        <span class="text-danger bg-danger">Rechazado</span>
                                    @elseif($transfer->status === \App\Transfer::STATUS_PENDING)
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
                                        {{ $transfer->created_at->format('d-m-Y h:i a') }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Monto:
                                    <span class="bg-info text-info">
                                        {{ number_format($transfer->amount, 2, ',', '.') }}
                                        <small>({{ number_format($transfer->approved, 2, ',', '.') }} aprobados)</small>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Origen:
                                    <span class="bg-danger text-danger">
                                        {{ $transfer->from->name }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Destino:
                                    <span class="bg-success text-success">
                                        {{ $transfer->to->name }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        @if(Auth::user()->level === \App\User::LEVEL_ADMIN)


                            @if($transfer->status === \App\Transfer::STATUS_PENDING)

                                <process-transfer
                                        accept_url = "{{ route('transfer.changeStatus', ['transfer' => $transfer->id, 'status' => \App\Transfer::STATUS_ACCEPTED]) }}"
                                        rejected_url = "{{ route('transfer.changeStatus', ['transfer' => $transfer->id, 'status' => \App\Transfer::STATUS_REJECTED]) }}"
                                        max_amount = "{{ $transfer->amount }}"
                                    ></process-transfer>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection