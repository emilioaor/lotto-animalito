@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-home"></i> Bienvenido!
    </h1>
    <p>
        {{ env('APP_NAME') }} te saluda, que gusto tenerte de vuelta, ingresa tu usuario y contraseña para acceder
        a tu panel de juego.
    </p>

    <div class="row">
        <div class="col-sm-8">
            <login-user-form
                    register_url = "{{ route('index.register') }}"
                    password_url = "{{ route('index.passwordReset') }}"
                    ></login-user-form>
        </div>

        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Sorteos del día</h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        @foreach($sorts as $sort)
                            <div class="col-xs-6 text-center">
                                <div class="alert alert-info">
                                    <p>{{ $sort->sort->name }}</p>
                                    <p>
                                        <strong>{{ $sort->timeFormat() }}</strong>
                                    </p>
                                    <div class="text-center">
                                        @if($results = $sort->getResultsByDate(date('Y-m-d')))
                                            @foreach($results as $result)
                                                @if($result->daily_sort_id == $sort->id)
                                                    <img width="50%" src="{{ asset('img/animals/' . $result->animal->cleanName() . '.jpg') }}" alt="{{ $result->animal->name }}">
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection