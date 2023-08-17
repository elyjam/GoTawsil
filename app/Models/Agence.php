<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    protected $table = 'agences';
    protected $guarded = [];


    	public function villeDetail(){
                return $this->belongsTo(\App\Models\Ville::class, 'ville');
        }

        public function related()
        {
            return $this->belongsToMany(Agence::class, 'agences_related', 'agence_id', 'related_id');
        }

        public function expeditiondetail(){
            return $this->hasMany(\App\Models\Expedition::class, 'agence_des');
        }

        public function expeditionRamasse(){
            return $this->expeditiondetail()->whereIn('etape',['2','3'])->where('deleted','0');;
        }
        public function expeditionChargement(){
            return $this->expeditiondetail()->whereIn('etape',['2',"3",'11','12'])->where('deleted','0');
        }
        public function expeditionRamasseAgence($agence){
            return $this->expeditionRamasse()->where('agence',$agence);
        }

}
