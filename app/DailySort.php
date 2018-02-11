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
}
