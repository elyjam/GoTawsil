<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';
    protected $guarded = [];

     public function clientDetail()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client');
    }

}
