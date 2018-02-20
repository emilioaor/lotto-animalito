<?php

namespace App\Console\Commands;

use App\EmailSpooler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia todos los correos que estan en cola';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Envia todos los correos que estan en cola
     *
     * @return mixed
     */
    public function handle()
    {
        $emails = EmailSpooler::where('status', EmailSpooler::STATUS_PENDING)->get();

        foreach ($emails as $email) {
            $class = $email->class;
            $params = json_decode($email->params, true);
            $recipients = json_decode($email->recipients, true);

            $emailInstance = new $class(
                $params['id'],
                $recipients
            );

            Mail::send($emailInstance);

            $email->status = EmailSpooler::STATUS_SEND;
            $email->save();
        }
    }
}
