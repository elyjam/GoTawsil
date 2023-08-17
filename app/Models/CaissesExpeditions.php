<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaissesExpeditions extends Model
{
    protected $table = 'caisses_expeditions';
    protected $guarded = [];

    public function Caisse(){
        return $this->belongsTo(Caisse::class, 'id_caisse');
    }
    public function agenceDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'id_agence');
    }

    public function Caissier()
    {
        return $this->belongsTo(\App\User::class, 'id_caissier');
    }

    public function livreur()
    {
        return $this->belongsTo(\App\Models\Employe::class, 'id_livreur');
    }
    public function expeditionDetail()
    {
        return $this->belongsTo(\App\Models\Expedition::class, 'id_expedition');
    }

}
