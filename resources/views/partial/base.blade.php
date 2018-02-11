<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
    <title>@yield('title', 'Lotto Animalito')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                @if(Auth::check())
                    <a class="navbar-brand" href="{{ route('user.index') }}">{{ env('APP_NAME') }}</a>
                @else
                    <a class="navbar-brand" href="{{ route('index.index') }}">{{ env('APP_NAME') }}</a>
                @endif
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    @if(Auth::check())
                        <li><a href="{{ route('user.results') }}">Resultados </a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tickets <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('ticket.create') }}">Registrar ticket</a></li>
                                <li><a href="{{ route('ticket.index') }}">Lista de tickets</a></li>
                            </ul>
                        </li>
                    @else
                        <li><a href="{{ route('index.register') }}">Registro</a></li>
                    @endif
                </ul>

                <ul class="nav navbar-nav navbar-right">


                    @if(Auth::check())
                        <!-- Usuario autenticado -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Bsf. {{ number_format(Auth::user()->realBalance(), 2, ',', '.') }}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('transfer.create') }}">Depositar dinero</a></li>
                                <li><a href="{{ route('withdraw.create') }}">Retirar dinero</a></li>
                                <li><a href="{{ route('withdraw.index') }}">Historial de retiros</a></li>
                                <li><a href="{{ route('transfer.index') }}">Historial de recargas</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->email }} <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('user.config') }}">Configuración</a></li>
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a href="{{ route('index.logout') }}">
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Fin usuario autenticado -->
                    @endif
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <main id="app">
        <div class="container">

            <!-- Mensaje de errores -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Alertas en la sesion -->
            @if(Session::has('lotto.alert.message') && Session::has('lotto.alert.type'))
                <div class="alert {{ Session::get('lotto.alert.type') }} alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>{{ trans('message.precaution') }}</strong>
                    {{ trans(Session::get('lotto.alert.message')) }}
                </div>
            @endif

            @yield('main')
        </div>
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('js')
</body>
</html>