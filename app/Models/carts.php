<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class carts extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'products_id',
        'quantity',
        'price'
    ];

    public function user()
    {
        return $this->belongsTo(Users::class);
    }

    public function products()
    {
        return $this->belongsToMany(Products::class, 'carts_product', 'carts_id', 'products_id')
                    ->withPivot('quantity', 'price')
                    ->using(carts_product::class);
    }

    public function orders()
    {
        return $this->hasMany(Commands::class);
    }
}