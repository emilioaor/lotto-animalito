<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    protected $table = 'ticket_detail';

    protected $fillable = [
        'ticket_id', 'animal_id', 'amount',
    ];

    /**
     * Ticket al que le hace detalle este registro
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo('App\Ticket', 'ticket_id');
    }

    /**
     * Animal jugado en este detalle
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function animal()
    {
        return $this->belongsTo('App\Animal', 'animal_id');
    }
}
