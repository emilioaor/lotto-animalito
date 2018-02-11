<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /** Niveles de usuario */
    const LEVEL_ADMIN = 1;
    const LEVEL_USER = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'identity_card', 'bank_id',
        'number_account', 'balance', 'block_balance', 'level',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->balance = 0;
        $this->block_balance = 0;
        $this->level = self::LEVEL_USER;
        parent::__construct($attributes);
    }

    /**
     * Todas las transferencias del usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transfers()
    {
        return $this->hasMany('App\Transfer', 'user_id');
    }

    /**
     * Banco configurado por el usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank()
    {
        return $this->belongsTo('App\Bank', 'bank_id');
    }

    /**
     * Lista de tickets registrados por el usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'user_id');
    }

    /**
     * Todos los retiros solicitados por el usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function withdraws()
    {
        return $this->hasMany('App\Withdraw', 'user_id');
    }

    /**
     * Calcula el balance real
     *
     * @return int
     */
    public function realBalance()
    {
        return $this->balance - $this->block_balance;
    }
}
