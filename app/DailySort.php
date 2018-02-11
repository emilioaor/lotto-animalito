<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Esta clase se utiliza para registrar durante el dia
 * varias horas del mismo sorteo. Tal vez el nombre
 * TimeSort hubiese sido mas acorde.
 *
 * Class DailySort
 * @package App
 */
class DailySort extends Model
{
    protected $table = 'daily_sort';

    protected $fillable = [
        'time', 'sort_id',
    ];

    /**
     * Sorteo al cual se le especifica la hora
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sort()
    {
        return $this->belongsTo('App\Sort', 'sort_id');
    }

    /**
     * Tickets jugados para este sorteo
     *
     * @return $this
     */
    public function tickets()
    {
        return $this->belongsToMany('App\Ticket', 'ticket_sort')
            ->withPivot(['daily_sort_id', 'ticket_id', 'pay_per_100']);
    }

    /**
     * Resultados de las distintas fechas del sorteo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany('App\Result', 'daily_sort_id');
    }

    /**
     * Retorna los resultados del sorteo para
     * una fecha determinada
     *
     * @param $date
     * @return \Illuminate\Support\Collection
     */
    public function getResultsByDate($date)
    {
        return
            Result::join($this->table, 'daily_sort.id', '=', 'results.daily_sort_id')
                ->where('daily_sort.id', $this->id)
                ->where('results.date', $date)
                ->get()
        ;
    }

    /**
     * Convierte la hora a \DateTime
     *
     * @return \DateTime
     */
    public function timeToDateTime()
    {
        return \DateTime::createFromFormat('H:i:s', $this->time);
    }

    /**
     * Tiempo en formato H:i:s a
     *
     * @return string
     */
    public function timeFormat()
    {
        return $this->timeToDateTime()->format('h:i:s a');
    }

    /**
     * Indica si el sorteo esta abierto
     *
     * @param \DateTime $date
     * @return bool
     */
    public function isOpen(\DateTime $date = null)
    {
        if (is_null($date)) {
            $date = new \DateTime();
        }
        $timeSort = $this->timeToDateTime()->modify('-5 minutes');

        if ($date->format('Y-m-d') !== $timeSort->format('Y-m-d')) {
            // Si es un dia diferente el sorteo esta cerrado
            return false;
        }

        if ($timeSort > $date) {
            // Si es el mismo dia y la hora es mayor sigue abierto el sorteo
            return true;
        }

        return false;
    }

    /**
     * Indica si el sorteo esta cerrado
     *
     * @param \DateTime $date
     * @return bool
     */
    public function isClose(\DateTime $date = null)
    {
        return ! $this->isOpen($date);
    }
}
