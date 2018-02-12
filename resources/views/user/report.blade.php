@extends('partial.base')

@section('main')

    <h1>
        <i class="glyphicon glyphicon-file"></i> Reporte
    </h1>
    <p>
        Genera un reporte de venta para un rango de fechas especificado.
    </p>

    <div class="row">

        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <daily-report
                            report_url="{{ route('user.generateReport') }}"
                            csrf="{{ csrf_token() }}"
                    ></daily-report>
                </div>
            </div>
        </div>
    </div>
@endsection