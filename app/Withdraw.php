<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Withdraw extends Model
{
    /** Estatus */
    const STATUS_PENDING = 1;
    const STATUS_COMPLETE = 2;
    const STATUS_REJECTED = 3;

    /** Carpeta publica donde se suben las capturas para los cobros */
    const DIR_UPLOAD = 'uploads/captures/withdraws/';
    /** Prefijo para las capturas subidas */
    const PREFIX_UPLOAD = 'Withdraw-';

    protected $table = 'withdraws';

    protected $fillable = [
        'user_id', 'amount', 'status', 'capture'
    ];

    public function __construct(array $attributes = [])
    {
        $this->user_id = Auth::user()->id;
        $this->status = self::STATUS_PENDING;
        parent::__construct($attributes);
    }

    /**
     * Usuario solicitante del retiro
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
