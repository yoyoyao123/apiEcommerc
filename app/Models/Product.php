<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'prix', 'quantite', 'image', 'categorie_id'];
    
    public function category()
    {
        return $this->belongsTo(categorie::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_products', 'product_id', 'cart_id')
                    ->withPivot('quantity', 'price')
                    ->using(CartProduct::class);
    }
}
