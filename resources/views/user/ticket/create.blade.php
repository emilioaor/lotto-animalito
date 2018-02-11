@extends('partial.base')

@section('main')
    <h1>
        <i class="glyphicon glyphicon-list"></i> Registro de ticket
    </h1>
    <p>
        Marca todos los animales que quieras, elige un monto, selecciona los sorteos y ya estarás participando.
    </p>

    <register-ticket
        animals = "{{ json_encode($animals) }}"
        balance = "{{ Auth::user()->balance }}"
        block_balance = "{{ Auth::user()->block_balance }}"
        sorts = "{{ json_encode($sorts) }}"
    >
    </register-ticket>
@endsection