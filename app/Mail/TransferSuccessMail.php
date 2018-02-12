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

    /**
     * Create a new message instance.
     *
     * @param Transfer $transfer
     */
    public function __construct(Transfer $transfer)
    {
        $this->transfer = $transfer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to($this->transfer->user->email)
            ->subject(env('APP_NAME') . ' - Transferencia Aprobada')
            ->view('mail.transferSuccess')
            ->with(['transfer' => $this->transfer])
        ;
    }
}
