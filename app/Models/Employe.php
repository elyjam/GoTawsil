<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    protected $table = 'employes';
    protected $guarded = [];

    public static function fetchAll()
    {
        return self::all()->where('deleted', "0")->where('statut',1);
    }

    public static function fetchAllLivreur()
    {
        return self::all()->where('deleted', "0")->where('role', "2")->where('statut',1);
    }


    public function agenceDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'agence');
    }


    public static function available_employe(){
        $employe_used = \App\User::all()->where('employe','!=',null)->pluck('employe');
        return self::all()->where('deleted', "0")->where('statut',1)->whereNotIn('id',$employe_used);
    }


    public function fonctionDetail()
    {
        return $this->belongsTo(\App\Models\Fonction::class, 'fonction');
    }
    public function typesemployeDetail()
    {
        return $this->belongsTo(\App\Models\Typesemploye::class, 'type', 'code');
    }

    public function relatedVilles()
    {
        return $this->belongsToMany(Ville::class, 'villes_employes', 'ville_id', 'employe_id');
    }

    public function commissionsDetails()
    {
        return $this->hasMany(Commission::class, 'id_livreur');
    }

    public function commission_livreur($expedition)
    {
        $commi = $expedition->bonLivsDetail->employeDetail->commissionsDetails->where('id_ville', $expedition->agence_des)->first();
        if (!isset($commi)) {
            $commi = $expedition->bonLivsDetail->employeDetail->commissionsDetails->where('id_ville', 1)->first();
        }
        if (!isset($commi)) {
            $commi = Commission::all()->where('id_ville', $expedition->agence_des)->where('id_livreur', 0)->first();
        }
        return $commi->coefficient ?? 0;
    }
    public function is_coff_ville($expedition)
    {
        $commi = $expedition->bonLivsDetail->employeDetail->commissionsDetails->where('id_ville', $expedition->agence_des)->first();
        $type = 'Livreur';
        if (!isset($commi)) {
            $commi = $expedition->bonLivsDetail->employeDetail->commissionsDetails->where('id_ville', 1)->first();
            $type = 'Ville';
        }

        if (!isset($commi)) {
            $commi = Commission::all()->where('id_ville', $expedition->agence_des)->where('id_livreur', 0)->first();
            $type = 'Ville';
        }
        return $type;
    }

    public function userDetail()
    {
        return $this->hasOne(\App\User::class,'employe');
    }
}
