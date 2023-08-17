<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Processus_expedition extends Model
{
    protected $table = 'processus_expeditions';
    protected $guarded = [];

    public function BonRamassageDetail()
    {
        return $this->belongsTo(\App\Models\Bon::class, 'id_bon_ramassage');
    }

    public function BonLivraisonDetail()
    {
        return $this->belongsTo(\App\Models\Bon::class, 'id_bon_livraison');
    }

    public function BonChargementDetail()
    {
        return $this->belongsTo(\App\Models\Bon::class, 'id_feuille_charge');
    }

    public function ExpeditionDetail()
    {
        return $this->belongsTo(\App\Models\Expedition::class, 'id_expedition');
    }

    public function RecuPar()
    {
        return $this->belongsTo(\App\Models\Employe::class, 'recu_par');
    }

    public function agenceDesDetail()
    {
        return $this->belongsTo(\App\Models\ville::class, 'id_agence_dest');
    }
    public function agenceDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'id_agence_exp');
    }

    public function transporteurDetail(){
        return $this->belongsTo(\App\Models\transporteur::class,'id_transporteur');
    }



}
