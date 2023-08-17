<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    protected $table = 'commentaires';
    protected $guarded = [];

    public function userDetail()
    {
        return $this->belongsTo(\App\User::class, 'id_utilisateur');
    }
    public function employeDetails()
    {
        return $this->belongsTo(\App\Models\Employe::class, 'id_utilisateur');
    }

    public function commenttype()
    {
        return $this->belongsTo(\App\Models\Types_commentaire::class, 'id_type_comment');
    }

    public function bonliv()
    {
        return $this->belongsTo(\App\Models\Bonliv::class, 'bl');
    }

    public function expedition_chargement()
    {

        $charg_bons = Processus_expedition::get()->where('code','CHARGEMENT')->where('id_expedition',$this->id_expedition);
        return $charg_bons;
    }

    public function expDetails()
    {
        return $this->belongsTo(\App\Models\Expedition::class, 'id_expedition');
    }


}
