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
                                <input
                                    type="date"
                                    class="form-control"
                                    max="{{ date('Y-m-d') }}"
                                    value="{{ $date }}"
                                    onchange="location.href = '{{ route('user.results') }}?date=' + this.value"
                                >
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

                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Sorteo</th>
                                <th>Hora</th>
                                <th>Ganador</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sorts as $dailySort)
                                <tr>
                                    <td>{{ $dailySort->sort->name }}</td>
                                    <td>{{ $dailySort->time }}</td>
                                    <td>
                                        @foreach($results as $result)
                                            @if($result->daily_sort_id == $dailySort->id)
                                                {{ $result->animal->name }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection