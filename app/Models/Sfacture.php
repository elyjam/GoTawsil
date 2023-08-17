<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sfacture extends Model
{
    protected $table = 'sfactures';
    protected $guarded = [];

    	public function clientDetail(){
            return $this->belongsTo(\App\Models\Client::class, 'client');
        } 

}