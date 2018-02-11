@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-transfer"></i> Historial de recargas
    </h1>
    <p>
        Verifica el estatus de tus recargas, recuerda que puede tardar hasta 24 horas habiles bancarias en hacer efectivo
        tu saldo.
    </p>

    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">

                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Fecha</th>
                        @if(Auth::user()->level === \App\User::LEVEL_ADMIN)
                            <th>Usuario</th>
                        @endif
                        <th>Estatus</th>
                        <th>Monto</th>
                        <th>Aprobado</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach($transfers as $transfer)
                            <tr>
                                <td>
                                    <a href="{{ route('transfer.show', ['transfer' => $transfer->id]) }}">
                                        {{ $transfer->references }}
                                    </a>
                                </td>
                                <td>{{ $transfer->created_at->format('d-m-Y h:i a') }}</td>
                                @if(Auth::user()->level === \App\User::LEVEL_ADMIN)
                                    <td>{{ $transfer->user->email }}</td>
                                @endif
                                <td>
                                    @if($transfer->status === \App\Transfer::STATUS_PENDING)
                                        <span class="text-warning bg-warning">Pendiente</span>
                                    @elseif($transfer->status === \App\Transfer::STATUS_ACCEPTED)
                                        <span class="text-success bg-success">Aceptada</span>
                                    @elseif($transfer->status === \App\Transfer::STATUS_REJECTED)
                                        <span class="text-danger bg-danger">Rechazada</span>
                                    @endif
                                </td>
                                <td>{{ number_format($transfer->amount, 2, ',', '.') }}</td>
                                <td>{{ number_format($transfer->approved, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 text-center">
            {{ $transfers->render() }}
        </div>
    </div>
@endsection