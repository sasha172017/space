<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'image';
    public $timestamps = false;
    protected $fillable = ['name', 'sort'];

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
}
