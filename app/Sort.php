<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sort extends Model
{
    protected $table = 'sorts';

    protected $fillable = [
        'name', 'pay_per_100',
    ];

    /**
     * Retorna todas las horas en que se juega este sorteo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dailySorts()
    {
        return $this->hasMany('App\DailySort', 'sort_id');
    }

    /**
     * Lista de animalitos para este sorteo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function animals()
    {
        return $this->hasMany('App\Animal', 'sort_id');
    }
}
