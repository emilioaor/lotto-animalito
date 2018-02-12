<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<table style="width: 100%;max-width: 600px; margin: 0 auto; background-color: #f4f4f4;">
    <tbody>
    <tr>
        <td style="background-color: #dff0d8; padding: 10px; color: #3c763d">
            <h3>{{ env('APP_NAME') }}</h3>
        </td>
    </tr>
    <tr>
        <td style="padding: 10px;">
            <h3 style="color: #333">Retiro aprobado</h3>
            <p>
                Saludos {{ $withdraw->user->name }}, el retiro solicitado ha sido aprobado, recuerda que puede tardar
                hasta 24 horas habiles bancarias para ser efectivo:
            </p>

            <div style="width: 50%;float: left;">
                <h4 style="color: #333; margin-bottom: 0">Monto:</h4>
                <p style="margin-top: 5px">{{ number_format($withdraw->amount, 2, ',', '.') }}</p>
            </div>

            <div style="width: 50%;float: left;">
                <h4 style="color: #333; margin-bottom: 0">Banco:</h4>
                <p style="margin-top: 5px">{{ $withdraw->user->bank->name }}</p>
            </div>

            <div style="width: 50%;float: left;">
                <h4 style="color: #333; margin-bottom: 0">Cuenta:</h4>
                <p style="margin-top: 5px">{{ $withdraw->user->number_account }}</p>
            </div>

            <div style="width: 50%;float: left;">
                <h4 style="color: #333; margin-bottom: 0">C.I:</h4>
                <p style="margin-top: 5px">{{ $withdraw->user->identity_card }}</p>
            </div>

            <div style="width: 50%;float: left;">
                <h4 style="color: #333; margin-bottom: 0">Email:</h4>
                <p style="margin-top: 5px">{{ $withdraw->user->email }}</p>
            </div>

            @if(! empty($withdraw->capture))
                <div style="width: 100%;float: left;">
                    <h4 style="color: #333; margin-bottom: 0">Captura:</h4>
                    <img style="width: 100%;" src="{{ asset($withdraw->capture) }}">
                </div>
            @endif
        </td>
    </tr>
    <tr>
        <td style="padding: 20px 10px 30px;">
            <div style="text-align: center">
                <a href="{{ route('withdraw.show', ['withdraw' => $withdraw->id]) }}"
                   style="background-color: #3097D1; text-decoration: none;color: #FFFFFF; padding: 10px; -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;font-size: 18px;">
                    Ver detalle
                </a>
            </div>


            <h3 style="color: #333;">O siga el siguiente enlace:</h3>
            <p style="background-color: #3097D1; padding: 10px; color: #f4f4f4;">
                <a style="color: #FFFFFF; text-decoration: none" href="{{ route('withdraw.show', ['withdraw' => $withdraw->id]) }}">
                    {{ route('withdraw.show', ['withdraw' => $withdraw->id]) }}
                </a>
            </p>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>