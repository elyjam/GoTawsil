<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReclamationHistory extends Model
{
    protected $table = 'reclamations_history';
    protected $guarded = [];


    public function reclamation(){
        return $this->belongsTo(\App\Models\Reclamation::class, 'reclamation');
    }

    public function userDetail(){
        return $this->belongsTo(\App\User::class, 'user');
    }

    public function getStatut(){
        $statut = DB::table('statuts')->get()->where('code', 'RECLAMATION')->where('key', $this->statut)->first();
        return $statut->value;
    }


}
