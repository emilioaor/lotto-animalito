<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = 'results';

    protected $fillable = [
        'date', 'daily_sort_id', 'animal_id',
    ];

    /**
     * Sorteo al que apunta este resultado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dailySort()
    {
        return $this->belongsTo('App\DailySort', 'daily_sort_id');
    }

    /**
     * Animal marcado como ganador
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function animal()
    {
        return $this->belongsTo('App\Animal', 'animal_id');
    }
}
