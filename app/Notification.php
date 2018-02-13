<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /** Estatus de las notificaciones */
    const STATUS_READ = 1;
    const STATUS_UNREAD = 2;

    protected $table = 'notifications';

    protected $fillable = [
        'message', 'url', 'user_id', 'status',
    ];

    /**
     * Usuario al que pertenece esta notification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Verifica si la notificacion no ha sido leida
     *
     * @return bool
     */
    public function isUnread()
    {
        return $this->status === self::STATUS_UNREAD;
    }
}
