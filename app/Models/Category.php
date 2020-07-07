<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    public function parent(){
        return $this->belongsTo(Category::class, 'category_id')->with('parent');

    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function children(){
        return $this->hasMany(Category::class, 'category_id')->with('children');
    }
}
