@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-transfer"></i> Historial de retiros
    </h1>
    <p>
        Verifica el estatus de tus retiros. Recuerda que puede tardar hasta 24 horas habiles bancarias.
    </p>

    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">

                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        @if(Auth::user()->level === \App\User::LEVEL_ADMIN)
                            <th>Usuario</th>
                        @endif
                        <th>Estatus</th>
                        <th>Monto</th>
                        @if(Auth::user()->level === \App\User::LEVEL_ADMIN)
                            <th width="5%"></th>
                        @endif
                    </thead>

                    <tbody>
                    @foreach($withdraws as $withdraw)
                        <tr>
                            <td>{{ $withdraw->created_at->format('d-m-Y') }}</td>
                            @if(Auth::user()->level === \App\User::LEVEL_ADMIN)
                                <td>{{ $withdraw->user->email }}</td>
                            @endif
                            <td>
                                @if($withdraw->status === \App\Withdraw::STATUS_PENDING)
                                    <span class="bg-warning text-warning">Pendiente</span>
                                @elseif($withdraw->status === \App\Withdraw::STATUS_COMPLETE)
                                    <span class="bg-success text-success">Completo</span>
                                @elseif($withdraw->status === \App\Withdraw::STATUS_REJECTED)
                                    <span class="bg-danger text-danger">Rechazado</span>
                                @endif
                            </td>
                            <td>{{ number_format($withdraw->amount, 2, ',', '.') }}</td>
                            @if(Auth::user()->level === \App\User::LEVEL_ADMIN)
                                <td>
                                    <a href="{{ route('withdraw.show', ['withdraw' => $withdraw->id]) }}" class="btn btn-primary">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 text-center">
            {{ $withdraws->render() }}
        </div>
    </div>
@endsection