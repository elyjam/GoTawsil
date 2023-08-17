<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Ville;

class CommissionExpeditions extends Model
{
    protected $table = 'expeditions_commission';
    protected $guarded = [];

    public function livreurDetail(){
        return $this->belongsTo(Employe::class,'livreur');
    }

    public function villeDetailExp(){
        return $this->belongsTo(Ville::class,'id_ville_exp');
    }


    public function villeDetailDest(){
        return $this->belongsTo(\App\Models\Ville::class,'id_ville_dest');
    }


}
