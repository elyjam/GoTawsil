<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reclamation extends Model
{
    protected $table = 'reclamations';
    protected $guarded = [];

    public function typereclamationDetail()
    {
        return $this->belongsTo(\App\Models\Typereclamation::class, 'typereclamation');
    }

    public function userDetail()
    {
        return $this->belongsTo(\App\User::class, 'user');
    }

    public function EmployeDetail()
    {
        return $this->belongsTo(\App\User::class, 'cloture_par');
    }



    public function ClotureeParDetail()
    {
        return $this->belongsTo(\App\Models\Employe::class, 'cloture_par');
    }

    public function reclamationSuivi(){
        return $this->hasMany(\App\Models\ReclamationSuivi::class, 'reclamation');
    }

    public function getStatut(){

        $statut = DB::table('statuts')->get()->where('code', 'RECLAMATION')->where('key', $this->statut)->first();
        return $statut->value;
    }



}
