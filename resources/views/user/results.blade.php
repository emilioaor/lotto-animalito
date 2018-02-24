@extends('partial.base')

@section('main')

    <h1>
        <i class="glyphicon glyphicon-calendar"></i> Resultados
    </h1>
    <p>
        Todos los resultados diarios. Desde aqui puedes visualizar el animal ganador de cada sorteo.
    </p>

    <div class="row">

        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <selector-date
                                        value="{{ $date }}"
                                        url="{{ route('user.results') }}"
                                ></selector-date>
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="form-group">
                                @if(Auth::user()->level === \App\User::LEVEL_ADMIN)
                                    <animal-gain
                                            animals = "{{ json_encode($animals) }}"
                                            sorts = "{{ json_encode($sorts) }}"
                                            date = "{{ $date }}"
                                            >
                                    </animal-gain>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @foreach($sorts as $dailySort)
                            <div class="col-sm-3">
                                <div class="alert alert-info">
                                    <h4>
                                        {{ $dailySort->sort->name }}
                                    </h4>
                                    <p>
                                        <strong>Hora:</strong>
                                        {{ $dailySort->timeFormat() }}
                                    </p>
                                    <p>
                                        <strong>Estatus:</strong>
                                        @if($dailySort->isOpen)
                                            <span class="text-success bg-success">
                                            Abierto
                                        </span>
                                        @else
                                            <span class="text-danger bg-danger">
                                            Cerrado
                                        </span>
                                        @endif
                                    </p>
                                    <p>
                                        <strong>Ganador:</strong>
                                        @foreach($results as $result)
                                            @if($result->daily_sort_id == $dailySort->id)
                                                <img    width="20px"
                                                        src="{{ asset('img/animals/' . $result->animal->cleanName() . '.jpg') }}">
                                                {{ $result->animal->name }}
                                            @endif
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection