@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-eye-open"></i> Detalle del ticket
    </h1>
    <p>
        Detalle del ticket. Tratamos la información de sus jugadas con total transparencia, puede visualizar en
        todo momento sus tickets anteriores y verificar su estatus.
    </p>

    <section class="show-ticket">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Código:
                                    <span class="text-primary bg-primary">{{ $ticket->public_id }}</span>
                                </p>
                            </div>

                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Estatus:

                                    @if($ticket->status === \App\Ticket::STATUS_ACTIVE)
                                        <span class="text-success bg-success">Activo</span>
                                    @elseif($ticket->status === \App\Ticket::STATUS_CANCEL)
                                        <span class="text-danger bg-danger">Anulado</span>
                                    @elseif($ticket->status === \App\Ticket::STATUS_PENDING)
                                        <span class="text-warning bg-warning">Pendiente de pago</span>
                                    @elseif($ticket->status === \App\Ticket::STATUS_PENDING_APPROBATION)
                                        <span class="text-info bg-info">Pendiente de aprobación</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Fecha:
                                    <span class="bg-warning text-warning">
                                        {{ $ticket->created_at->format('d-m-Y h:i a') }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Jugado:
                                    <span class="bg-info text-info">
                                        {{ number_format($ticket->total(), 2, ',', '.') }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Sorteo:

                                    <span class="bg-warning text-warning">
                                        {{ $ticket->dailySorts[0]->sort->name }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-sm-6">
                                <p class="show-ticket__data">
                                    Horarios:

                                    @foreach($ticket->dailySorts as $sort)
                                        <span class="bg-warning text-warning">
                                            {{ $sort->timeFormat() }}
                                        </span>
                                    @endforeach
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <p class="show-ticket__data">
                                    Premio:

                                    <span class="bg-success text-success">
                                        Bsf. {{ number_format($ticket->gainAmount(), 2, ',', '.') }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        @if($ticket->status === \App\Ticket::STATUS_PENDING)
                            <div class="row">

                                <div class="col-xs-12">
                                    <h4>
                                        <span class="text text-danger">Atención:</span>
                                        Recuerde que su ticket no participa en los sorteos hasta que se valide el pago.
                                    </h4>
                                </div>

                                <div class="col-xs-12">
                                    <register-transfer
                                            banks = "{{ json_encode($banks) }}"
                                            ticket_id = "{{ $ticket->id }}"
                                            >

                                    </register-transfer>
                                </div>
                            </div>
                        @elseif(count($ticket->transfers))
                            <hr>
                            @foreach($ticket->transfers as $transfer)
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

                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="show-ticket__data">
                                            Referencia:
                                            <span class="bg-info text-info">
                                                {{ $transfer->references }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="col-sm-6">
                                        <p class="show-ticket__data">
                                            Aprobado:
                                            <span class="bg-info text-info">
                                                {{ number_format($transfer->approved, 2, ',', '.') }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <div class="row">
                            @foreach($ticket->ticketDetails as $detail)
                                <div class="col-sm-3 text-center">
                                    <div class="show-ticket__animals">
                                        <div>
                                            <img src="{{ asset('img/animals/' . $detail->animal->cleanName() . '.jpg') }}" alt="{{ $detail->animal->name }}">
                                        </div>

                                        <h3>{{ $detail->animal->name }}</h3>
                                        <h4>Bsf. {{ number_format($detail->amount, 2, ',', '.') }}</h4>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection