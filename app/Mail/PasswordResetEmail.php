<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $params;

    /**
     * Email de recuperación de contraseña
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to($this->params['email'])
            ->subject(env('APP_NAME') . ' - Recuperación de contraseña')
            ->view('mail.passwordReset')
            ->with($this->params)
        ;
    }
}
