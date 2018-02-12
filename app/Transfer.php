<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transfer extends Model
{
    /** Estatus */
    const STATUS_PENDING = 1;
    const STATUS_ACCEPTED = 2;
    const STATUS_REJECTED = 3;

    /** Carpeta publica donde se suben las capturas para las transferencias */
    const DIR_UPLOAD = 'uploads/captures/transfers/';
    /** Prefijo para las capturas subidas */
    const PREFIX_UPLOAD = 'Transfer-';

    protected $table = 'transfers';

    protected $fillable = [
        'user_id', 'from_id', 'to_id', 'amount', 'references',
        'status', 'approved', 'comment', 'capture',
    ];

    public function __construct(array $attributes = [])
    {
        $this->status = self::STATUS_PENDING;
        $this->approved = 0;
        parent::__construct($attributes);
    }

    /**
     * Ticket que se pago con esta transferencia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Banco desde el cual se hizo la transferencia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function from()
    {
        return $this->belongsTo('App\Bank', 'from_id');
    }

    /**
     * Banco destino de la transferencia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function to()
    {
        return $this->belongsTo('App\Bank', 'to_id');
    }
}
