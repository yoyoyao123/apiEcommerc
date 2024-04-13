<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'cart_id',
        'statut'
    ];

    protected static function boot()
    {
        parent::boot();

        // Écouter l'événement de création
        static::creating(function ($commands) {
            $commands->statut = 'en attente';
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    public function cart()
    {
        return $this->belongsTo(cart::class);
    }
}
