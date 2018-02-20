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
    private $recipients;

    /**
     * Create a new message instance.
     *
     * @param int $id
     * @param array $recipients
     */
    public function __construct($id, array $recipients)
    {
        $this->withdraw = Withdraw::find($id);
        $this->recipients = $recipients;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to($this->recipients)
            ->subject(env('APP_NAME') . ' - Retiro solicitado')
            ->view('mail.withdraw')
            ->with(['withdraw' => $this->withdraw])
        ;
    }
}
