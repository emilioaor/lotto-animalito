<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    const PUBLIC_ID_PREFIX = 'LOTTO';

    /** Estatus de los tickets */
    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CANCEL = 2;
    const STATUS_PENDING_APPROBATION = 3;

    protected $table = 'tickets';

    protected $fillable = [
        'public_id', 'status', 'user_id'
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->public_id = self::PUBLIC_ID_PREFIX . strtotime((new \DateTime('now'))->format('Y-m-d h:i:s'));
        $this->status = self::STATUS_ACTIVE;
        $this->user_id = Auth::user()->id;
        parent::__construct($attributes);
    }

    /**
     * Usuario creador del ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Todos los detalles de este ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ticketDetails()
    {
        return $this->hasMany('App\TicketDetail', 'ticket_id');
    }

    /**
     * Todos los sorteos diarios jugados en este ticket
     *
     * @return $this
     */
    public function dailySorts()
    {
        return $this->belongsToMany('App\DailySort', 'ticket_sort')
            ->withPivot(['ticket_id', 'daily_sort_id', 'pay_per_100']);
    }

    /**
     * Calcula el total jugado en el ticket
     *
     * @return int
     */
    public function total()
    {
        $total = 0;
        foreach ($this->ticketDetails as $detail) {
            $total += $detail->amount;
        }

        return ($total * count($this->dailySorts));
    }

    /**
     * Calcula el monto a pagar para este ticket
     *
     * @return int
     */
    public function gainAmount()
    {
        $gainAmount = 0;

        if ($this->status !== self::STATUS_ACTIVE) {
            return $gainAmount;
        }

        // Verifico resultados por cada sorteo
        foreach ($this->dailySorts as $dailySort) {

            // Busco todos los animalitos jugados
            foreach ($this->ticketDetails as $detail) {

                // Busco un resultado para la fecha, sorteo y animalito
                $result = Result::where('date', $this->created_at->format('Y-m-d'))
                    ->where('daily_sort_id', $dailySort->id)
                    ->where('animal_id', $detail->animal_id)
                    ->first()
                ;

                if ($result) {
                    // Obtengo cuanto pagaba el sorteo en ese pomento
                    $pay_per_100 = $dailySort->pivot->pay_per_100;
                    $gainAmount += ($detail->amount / 100 * $pay_per_100);
                }
            }
        }

        return $gainAmount;
    }

    /**
     * Indica si el ticket es ganador
     *
     * @return int
     */
    public function isGain()
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        // Verifico resultados por cada sorteo
        foreach ($this->dailySorts as $dailySort) {

            // Busco todos los animalitos jugados
            foreach ($this->ticketDetails as $detail) {

                // Busco un resultado para la fecha, sorteo y animalito
                $result = Result::where('date', $this->created_at->format('Y-m-d'))
                    ->where('daily_sort_id', $dailySort->id)
                    ->where('animal_id', $detail->animal_id)
                    ->first()
                ;

                if ($result) {
                    return true;
                }
            }
        }

        return false;
    }
}
