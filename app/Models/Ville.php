<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Parameter;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ville extends Model
{
    protected $table = 'villes';
    protected $guarded = [];

    public static function getArrayVilles(){
        $villes = self::all()->where('deleted','0');
        $array = [];
        foreach($villes as $ville){
            $array[strtoupper(trim($ville->libelle))] = $ville->id;
        }
        return $array;
    }

    public function ville_total_remb($star, $end)
    {
        $Exps_remb =  Expedition::getExpeditionsByAllRemboursement();
        $Exps_remb = $Exps_remb->where("created_at", '>=', $star)
            ->where("created_at", '<=', $end)
            ->where('ville', $this->id);
            $netsTotal = 0;
            foreach ($Exps_remb as $expedition) {
                $cheque = isset($cheques[$expedition->expedition_id]) ? $cheques[$expedition->expedition_id] : 0;
                $chequeVal = $cheque > 0 ? Util::moneyFormat($cheque) : '';
                $net = $expedition->fond - $expedition->ttc - $cheque;
                $netsTotal += $net;
            }
        return $netsTotal;
    }

    public static function getVilles($type=null){
        if($type === 'DEPART'){
            $parameters = Parameter::find(1);
            $villesDepart = strlen($parameters->villes_depart)>1 ? explode(",", $parameters->villes_depart) : '0';
            return self::all()->where('deleted',"0")->where('statut',"actif")->whereIn('id', $villesDepart);

        }

        return self::all()->where('deleted',"0")->where('statut',"actif");
    }

    public function expeditionDetail(){
        return $this->hasMany(Expedition::class,'agence_des','id');
    }

    public function expeditionAllDes(){
        return $this->hasMany(Expedition::class,'des','id');
    }

    public function precessusExpedition(){
        return $this->hasMany(Processus_expedition::class,'id_agence_dest','id');
    }
    public function expeditionLivree(){
        return $this->precessusExpedition()->where('code','LIVRAISON')->where('date_validation','!=',null);
    }
    public function getExpeditionDateOne($start){
        return $this->expeditionAllDes()->whereDate("created_at",$start);
    }
    public function getExpeditionDateTow($start,$end){
        return $this->expeditionAllDes()->whereBetween("created_at",[$start,$end]);
    }


    public function RegionDetail()
    {
        $region_id =  \DB::table("region_villes")->where('id_ville', $this->id)->pluck('id_region')->first();

        return Region::where('id', $region_id)->first();
    }

}
