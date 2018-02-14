@extends('partial.base')

@section('main')

    <h1>
        <i class="glyphicon glyphicon-envelope"></i> Notificaciones
    </h1>
    <p>
        Visualiza todas tus notificaciones en el sistema
    </p>

    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">

                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th width="60%">Mensaje</th>
                            <th width="30%">Fecha</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($notifications as $notification)
                            <tr>
                                <td>{{ $notification->message }}</td>
                                <td>{{ $notification->created_at->format('d-m-Y h:i a') }}</td>
                                <td>
                                    <a href="{{ $notification->url }}">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                        Detalle
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 text-center">
            {{ $notifications->render() }}
        </div>
    </div>
@endsection