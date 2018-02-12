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
            <h3 style="color: #333">Transferencia aprobada</h3>
            <p>
                Saludos {{ $transfer->user->name }}, la transferencia que registro en {{ env('APP_NAME') }} ha sido aprobada.
                Ahora dispone de VEF {{ number_format($transfer->user->realBalance(), 2, ',', '.') }} para usar en la plataforma
            </p>

            <div style="width: 50%;float: left;">
                <h4 style="color: #333; margin-bottom: 0">Monto registrado:</h4>
                <p style="margin-top: 5px">{{ number_format($transfer->amount, 2, ',', '.') }}</p>
            </div>
            <div style="width: 50%;float: left;">
                <h4 style="color: #333; margin-bottom: 0">Monto aprobado:</h4>
                <p style="margin-top: 5px">{{ number_format($transfer->amount, 2, ',', '.') }}</p>
            </div>

            <div style="width: 50%;float: left;">
                <h4 style="color: #333; margin-bottom: 0">Desde:</h4>
                <p style="margin-top: 5px">{{ $transfer->from->name }}</p>
            </div>
            <div style="width: 50%;float: left;">
                <h4 style="color: #333; margin-bottom: 0">Hasta:</h4>
                <p style="margin-top: 5px">{{ $transfer->to->name }}</p>
            </div>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>