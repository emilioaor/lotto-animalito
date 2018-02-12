<?php

namespace App\Mail;


use App\Withdraw;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WithdrawMail extends Mailable
{
    use Queueable, SerializesModels;

    private $withdraw;

    /**
     * Create a new message instance.
     *
     * @param Withdraw $withdraw
     */
    public function __construct(Withdraw $withdraw)
    {
        $this->withdraw = $withdraw;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to(env('APP_MY_EMAIL'))
            ->subject(env('APP_NAME') . ' - Retiro solicitado')
            ->view('mail.withdraw')
            ->with(['withdraw' => $this->withdraw])
        ;
    }
}
