@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-file"></i> Reporte diario
    </h1>
    <p>
        Reporte del dia. En esta consulta encuentra detalles de las ventas para el rango de fecha especificado,
        el monto en premios y balance general
    </p>

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Reporte</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <h5>
                                Fecha inicio:
                                <strong>{{ $start->format('d-m-Y') }}</strong>
                            </h5>
                        </div>

                        <div class="col-sm-4">
                            <h5>
                                Fecha fin:
                                <strong>{{ $end->format('d-m-Y') }}</strong>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h5>
                                Total de ventas:
                                <strong>{{ number_format($total, 2, ',', '.') }}</strong>
                            </h5>
                        </div>

                        <div class="col-sm-4">
                            <h5>
                                Total en premios:
                                <strong>{{ number_format($gain, 2, ',', '.') }}</strong>
                            </h5>
                        </div>

                        <div class="col-sm-4">
                            <h5>
                                Balance:
                                <strong>{{ number_format($total - $gain, 2, ',', '.') }}</strong>
                            </h5>
                        </div>
                    </div>

                    <table class="table table-responsive table-striped">
                        <thead>
                            <tr>
                                <th>Public ID</th>
                                <th>Creado</th>
                                <th>Usuario</th>
                                <th>Monto</th>
                                <th>Premio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->public_id }}</td>
                                    <td>{{ $ticket->created_at->format('d-m-Y h:i a') }}</td>
                                    <td>{{ $ticket->user->email }}</td>
                                    <td>{{ number_format($ticket->total(), 2, ',', '.') }}</td>
                                    <td>{{ number_format($ticket->gainAmount(), 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection