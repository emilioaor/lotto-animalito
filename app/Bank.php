<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'banks';

    protected $fillable = ['name', 'i_have'];

    public $timestamps = false;

    /**
     * Todos los usuarios que poseen configurado el banco
     * instanceado
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\User', 'bank_id');
    }

    /**
     * Todas las transferencias que poseen a este banco como
     * origen
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fromInTransfers()
    {
        return $this->hasMany('App\Transfer', 'from_id');
    }

    /**
     * Todas las transferencias que poseen este banco como
     * destino
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function toInTransfers()
    {
        return $this->hasMany('App\Transfer', 'to_id');
    }
}
