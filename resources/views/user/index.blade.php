@extends('partial.base')

@section('main')

    <h1>
        <i class="glyphicon glyphicon-user"></i> Bienvenido!
    </h1>
    <p>
        Bienvenido a {{ env('APP_NAME') }}, tu agencia abierta las 24 horas del día. Esta pendiente de los proximos
        sorteos y no pierdas la oportunidad de ganar desde la comodidad de tu hogar.
    </p>

    <div class="row">

        <div class="col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Menu de usuario</h3>
                </div>
                <div class="panel-body">
                    <h4>
                        Saldo:
                        <strong>
                            <span class="text-success">{{ number_format(Auth::user()->realBalance(), 2, ',', '.') }}</span>
                        </strong>
                    </h4>
                    <h5>
                        Bloqueado:
                        <strong>
                            <span class="text-danger">{{ number_format(Auth::user()->block_balance, 2, ',', '.') }}</span>
                        </strong>
                    </h5>

                    <a href="{{ route('ticket.create') }}" class="btn btn-primary bt-lg">
                        <i class="glyphicon glyphicon-list"></i>
                        Registrar un ticket
                    </a>

                    <a href="{{ route('user.results') }}" class="btn btn-info bt-lg">
                        <i class="glyphicon glyphicon-eye-open"></i>
                        Ver resultados
                    </a>

                    <a href="{{ route('user.config') }}" class="btn btn-warning bt-lg">
                        <i class="glyphicon glyphicon-cog"></i>
                        Datos bancarios
                    </a>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Sorteos del día</h3>
                </div>
                <div class="panel-body">

                    @foreach($sorts as $sort)

                        <div class="row">

                            <div class="col-xs-6">
                                <p>{{ $sort->sort->name }}</p>
                                <p>
                                    <strong>{{ $sort->time }}</strong>
                                </p>
                            </div>

                            <div class="col-xs-6 text-center">
                                @if($results = $sort->getResultsByDate(date('Y-m-d')))
                                    @foreach($results as $result)
                                        @if($result->daily_sort_id == $sort->id)
                                            <img src="{{ asset('img/animals/' . $result->animal->cleanName() . '.jpg') }}" alt="{{ $result->animal->name }}" class="img-responsive">
                                        @endif
                                    @endforeach
                                @else
                                    -
                                @endif
                            </div>

                            <div class="col-xs-12">
                                <hr>
                            </div>

                        </div>

                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection