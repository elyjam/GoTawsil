<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReclamationSuivi extends Model
{
    protected $table = 'reclamations_suivi';
    protected $guarded = [];


    public function reclamation(){
        return $this->belongsTo(\App\Models\Reclamation::class, 'reclamation');
    }

    public function userDetail(){
        return $this->belongsTo(\App\User::class, 'user');
    }


}
