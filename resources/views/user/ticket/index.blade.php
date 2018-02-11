@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-list"></i> Lista de tickets
    </h1>
    <p>
        Visualiza tu historial de ticket desde tu inicio en {{ env('APP_NAME') }}
    </p>


    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">

                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Creado</th>
                            @if(Auth::user()->level === \App\User::LEVEL_ADMIN)
                                <th>Usuario</th>
                            @endif
                            <th>Estatus</th>
                            <th>Monto</th>
                            <th>Ganado</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>
                                    <a href="{{ route('ticket.show', ['ticket' => $ticket->id]) }}">
                                        {{ $ticket->public_id }}
                                    </a>
                                </td>
                                <td>{{ $ticket->created_at->format('d-m-Y h:i a') }}</td>
                                @if(Auth::user()->level === \App\User::LEVEL_ADMIN)
                                    <td>{{ $ticket->user->email }}</td>
                                @endif
                                <td>
                                    @if($ticket->isGain())
                                        <span class="bg-primary text-primary">Ganador</span>
                                    @elseif($ticket->status === \App\Ticket::STATUS_ACTIVE)
                                        <span class="bg-success text-success">Activo</span>
                                    @elseif($ticket->status === \App\Ticket::STATUS_CANCEL)
                                        <span class="bg-danger text-danger">Anulado</span>
                                    @elseif($ticket->status === \App\Ticket::STATUS_PENDING)
                                        <span class="bg-warning text-warning">Pendiente de pago</span>
                                    @elseif($ticket->status === \App\Ticket::STATUS_PENDING_APPROBATION)
                                        <span class="bg-info text-info">Pendiente de aprobación</span>
                                    @endif
                                </td>
                                <td>{{ number_format($ticket->total() * count($ticket->dailySorts), 2, ',', '.') }}</td>
                                <td>{{ number_format($ticket->gainAmount(), 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 text-center">
            {{ $tickets->render() }}
        </div>
    </div>
@endsection