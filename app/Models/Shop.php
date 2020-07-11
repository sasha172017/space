<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shop';
    //
    public function products(){
        return $this->belongsToMany(Product::class, 'product_shop');
    }
}
