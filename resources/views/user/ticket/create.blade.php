@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-list"></i> Registro de ticket
    </h1>
    <p>
        Marca todos los animales que quieras, elige un monto, selecciona los sorteos y ya estarás participando.
    </p>

    @if(! isset($sorts) || ! isset($animals))

        <div class="row">
            <div class="col-sm-7">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <h5>Seleccione el sorteo</h5>
                        <div class="form-group">
                            <select
                                    name="sort"
                                    id="sort"
                                    class="form-control"
                                    onchange="selectSort()"
                                    >
                                <option value="">- Sorteos disponibles</option>
                                @foreach($sortList as $sort)
                                    <option
                                            value="{{ $sort->slug }}"
                                            >
                                        {{ $sort->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        <register-ticket
                animals = "{{ json_encode($animals) }}"
                balance = "{{ Auth::user()->balance }}"
                block_balance = "{{ Auth::user()->block_balance }}"
                sorts = "{{ json_encode($sorts) }}"
                >
        </register-ticket>
    @endif
@endsection

@section('js')
    <script>
        function selectSort()
        {
            location.href = '{{ route('ticket.create') }}?sort=' + $('#sort').val();
        }
    </script>
@endsection