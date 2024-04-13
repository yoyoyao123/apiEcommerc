<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commands extends Model
{
    use HasFactory;
    protected $table = "Commands"; 

    protected $fillable = [
        'users_id',
        'total',
        'carts_id',
        'statut'
    ];

    protected static function boot()
    {
        parent::boot();

        // Écouter l'événement de création
        static::creating(function ($commands) {
            // Par défaut, définir le statut de la commande comme "en attente"
            $commands->statut = 'en attente';
        });
    }

    public function user()
    {
        return $this->belongsTo(Users::class); // Utiliser User::class au lieu de Users::class
    }

    public function cart()
    {
        return $this->belongsTo(carts::class);
    }
}
