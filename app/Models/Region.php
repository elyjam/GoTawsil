<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regions';
    protected $guarded = [];


    public function relatedVilles()
    {
         return $this->belongsToMany(Ville::class, 'region_villes', 'id_region', 'id_ville');
    }

    public static function getRegions(){
        return self::all()->where('deleted',"0")->where('statut',1);
    }


}
