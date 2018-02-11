<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $table = 'animals';

    protected $fillable = [
        'code', 'name', 'sort_id',
    ];

    public $timestamps = false;

    /**
     * Sorteo al que pertenece este animalito
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sort()
    {
        return $this->belongsTo('App\Sort', 'sort_id');
    }

    /**
     * Todos los detalles de tickets que apostaron por este
     * animalito
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ticketDetails()
    {
        return $this->hasMany('App\TicketDetail', 'animal_id');
    }

    /**
     * Limpia el nombre de caracteres especiales
     *
     * @return string
     */
    public function cleanName()
    {
        $name = strtolower($this->name);
        $name = str_replace('á', 'a', $name);
        $name = str_replace('é', 'e', $name);
        $name = str_replace('í', 'i', $name);
        $name = str_replace('ó', 'o', $name);
        $name = str_replace('ú', 'u', $name);
        $name = str_replace('Á', 'a', $name);

        return $name;
    }

    /**
     * Todos los resultados que ha ganado este animalito
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany('App\Result', 'animal_id');
    }
}
