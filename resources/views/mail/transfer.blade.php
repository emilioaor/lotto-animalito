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
        <td style="background-color: #3097D1; padding: 10px; color: #ffffff">
            <h3>{{ env('APP_NAME') }}</h3>
        </td>
    </tr>
    <tr>
        <td style="padding: 10px;">
            <h3 style="color: #333">Transferencia registrada</h3>
            <p>
                El usuario {{ $transfer->user->name }}, indico que realizó una transferencia con los siguientes datos:
            </p>

            <div style="width: 50%;float: left;">
                <h4 style="color: #333; margin-bottom: 0">Referencia:</h4>
                <p style="margin-top: 5px">{{ $transfer->references }}</p>
            </div>
            <div style="width: 50%;float: left;">
                <h4 style="color: #333; margin-bottom: 0">Monto:</h4>
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

            @if(! empty($transfer->capture))
                <div style="width: 100%;float: left;">
                    <h4 style="color: #333; margin-bottom: 0">Captura:</h4>
                    <img style="width: 100%;" src="{{ asset($transfer->capture) }}">
                </div>
            @endif
        </td>
    </tr>
    <tr>
        <td style="padding: 20px 10px 30px;">
            <div style="text-align: center">
                <a href="{{ route('transfer.show', ['transfer' => $transfer->id]) }}"
                   style="background-color: #3097D1; text-decoration: none;color: #FFFFFF; padding: 10px; -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;font-size: 18px;">
                    Ver detalle
                </a>
            </div>


            <h3 style="color: #333;">O siga el siguiente enlace:</h3>
            <p style="background-color: #3097D1; padding: 10px; color: #f4f4f4;">
                <a style="color: #FFFFFF; text-decoration: none" href="{{ route('transfer.show', ['transfer' => $transfer->id]) }}">
                    {{ route('transfer.show', ['transfer' => $transfer->id]) }}
                </a>
            </p>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>