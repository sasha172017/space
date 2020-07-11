<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class)->with('user');
    }

    public function tags(){
        return $this->hasMany(Tag::class);
    }

    public function shops(){
        return $this->belongsToMany(Shop::class)->withPivot('price');
    }

    public function images(){
        return $this->hasMany(Image::class)->orderBy('sort');
    }
}
