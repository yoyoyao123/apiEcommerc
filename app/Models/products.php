<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    
        protected $fillable = ['nom', 'description', 'prix', 'quantite', 'image', 'categorie_id'];
    
        public function category()
        {
            return $this->belongsTo(categorie::class);
        }

        public function carts()
        {
            return $this->belongsToMany(Carts::class, 'carts_product', 'products_id', 'carts_id')
                        ->withPivot('quantity', 'price')
                        ->using(carts_product::class);
        }
    
}
