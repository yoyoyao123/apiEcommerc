<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;


    class carts_product extends Pivot
{
    protected $table = 'carts_product';

    protected $fillable = [
        'carts_id',
        'products_id',
        'quantity',
        'price'
    ];

    public function cart()
    {
        return $this->belongsTo(carts::class, 'carts_id');
    }

    public function product()
    {
        return $this->belongsTo(products::class, 'products_id');
    }
}
