<?php

namespace App\Mail;

use App\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransferSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    private $transfer;
    private $recipients;

    /**
     * Create a new message instance.
     *
     * @param int $id
     * @param array $recipients
     */
    public function __construct($id, array $recipients)
    {
        $this->transfer = Transfer::find($id);
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
            ->subject(env('APP_NAME') . ' - Transferencia Aprobada')
            ->view('mail.transferSuccess')
            ->with(['transfer' => $this->transfer])
        ;
    }
}
