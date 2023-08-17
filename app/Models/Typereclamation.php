<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Typereclamation extends Model
{
    protected $table = 'typereclamations';
    protected $guarded = [];

    	public function roleDetail(){
                                         return $this->belongsTo(\App\Models\Role::class, 'type');
                                    }



}
