<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class etapeHistory extends Model
{
    protected $table = 'etape_history';
    protected $guarded = [];

    public function getEtape(){
        $statut = DB::table('statuts')->get()->where('code', 'ETAPE_EXPEDITION')->where('key', $this->etape)->first();
        return $statut->value;
    }

    public function agenceDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'agence');
    }
    public function agenceDesDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'agence_des');
    }

    public function clientDetail()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client');
    }


    public function expeditionDetail()
    {
        return $this->belongsTo(\App\Models\Expedition::class, 'expedition');
    }


}
