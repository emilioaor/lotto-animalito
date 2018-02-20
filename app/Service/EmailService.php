<?php

namespace App\Service;
use App\EmailSpooler;
use App\Transfer;

/**
 * Servicio para el envio de correos en el sistema
 *
 * @author Emilio Ochoa <emilioaor@gmail.com>
 */
class EmailService
{

    /**
     * Agrega un correo a la cola
     *
     * @param $class
     * @param array $recipients
     * @param array $params
     */
    public static function addEmail($class, array $recipients, array $params = [])
    {
        if (env('APP_ENV') !== 'production') {
            $recipients = [env('APP_MY_EMAIL')];
        }

        $email = new EmailSpooler();
        $email->class = $class;
        $email->recipients = json_encode($recipients);
        $email->params = json_encode($params);
        $email->status = EmailSpooler::STATUS_PENDING;
        $email->save();
    }

    /**
     * Envia un correo para indicar que una transferencia
     * fue registrada
     *
     * @param Transfer $transfer
     */
    public static function sendTransferRegistered(Transfer $transfer)
    {

    }
}