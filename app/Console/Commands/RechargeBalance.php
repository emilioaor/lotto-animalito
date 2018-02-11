<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\RuntimeException;

class RechargeBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balance:recharge {email} {balance}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recarga saldo a un usuario';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::where('email', $this->input->getArgument('email'))->first();
        if (! $user) {
            throw new \Exception('Email not found');
        }

        $balance = (float) $this->input->getArgument('balance');
        if (! is_float($balance)) {
            throw new \Exception('Balance format invalid');
        }

        $this->output->writeln('Saldo antes de la recarga:');
        $this->output->writeln('Usuario: ' . $user->name);
        $this->output->writeln('Saldo disponible: ' . $user->realBalance());
        $this->output->writeln('Saldo bloqueado: ' . $user->block_balance);

        $user->balance += $balance;
        $user->save();

        $this->output->writeln('-----------------------------------------');
        $this->output->writeln('Saldo despues de la recarga:');
        $this->output->writeln('Usuario: ' . $user->name);
        $this->output->writeln('Saldo disponible: ' . $user->realBalance());
        $this->output->writeln('Saldo bloqueado: ' . $user->block_balance);

    }
}
