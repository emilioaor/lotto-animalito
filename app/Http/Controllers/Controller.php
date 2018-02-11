<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** Tipos de alertas */
    const ALERT_SUCCESS = 'alert-success';
    const ALERT_DANGER = 'alert-danger';

    /** Enviroment */
    const ENV_PRODUCTION = 'production';
    const ENV_DEVELOP = 'local';

    /**
     * Crea una sesion para un mensaje de alerta
     *
     * @param $message
     * @param string $alertType
     */
    protected function sessionMessage($message, $alertType = self::ALERT_SUCCESS)
    {
        Session::flash('lotto.alert.message', $message);
        Session::flash('lotto.alert.type', $alertType);
    }

}
