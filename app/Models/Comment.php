<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment';
    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:m',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
