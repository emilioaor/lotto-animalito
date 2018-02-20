<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSpooler extends Model
{
    /** Estatus de la cola de correos */
    const STATUS_PENDING = 1;
    const STATUS_SEND = 2;

    protected $table = 'email_spooler';

    protected $fillable = [
        'class', 'params', 'recipients', 'status'
    ];
}
