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
                    <p style="text-align: justify; color: #303030;">
                        Saludos {{ $name }}, ha indicado que olvido su contraseña y hemos generado una contraseña temporal
                        desde la cual podrá acceder a su panel de usuario. Recuerde cambiar su contraseña y
                        apuntarla en un lugar seguro.
                    </p>

                    <p style="text-align: justify; color: #303030;">
                        Si no ha sido usted quien solicito la contraseña provisional haga caso omiso de este
                        comunicado.
                    </p>

                    <h3 style="color: #333;">Contraseña temporal:</h3>
                    <p style="background-color: #3097D1; padding: 10px; color: #f4f4f4;">
                        {{ $token }}
                    </p>
                    <h3 style="color: #333;">Iniciar sesión</h3>
                    <div>
                        <a href="{{ route('index.index') }}"
                           style="background-color: #3097D1; text-decoration: none;color: #FFFFFF; padding: 10px; -webkit-border-radius: 5px;-moz-border-radius: ;border-radius: ;">
                            Iniciar sesión
                        </a>
                    </div>
                    <h3 style="color: #333;">O visita la siguiente url:</h3>
                    <p style="background-color: #3097D1; padding: 10px; color: #f4f4f4;">
                        {{ route('index.index') }}
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>