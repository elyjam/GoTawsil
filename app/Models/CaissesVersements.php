<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaissesVersements extends Model
{
    protected $table = 'caisses_versements';
    protected $guarded = [];

    public static function getVersementsArray($caisse){

        $versements = self::where('id_caisse', $caisse)
                            ->leftJoin('types_depenses', 'types_depenses.id', '=', 'caisses_versements.id_type_depense')
                            ->get();
        $versementsArray = [];
        foreach($versements as $versement){
            if($versement->type == 'VERSEMENT'){
                $versementsArray[$versement->type] = $versement;
            }
            else{
                $versementsArray[$versement->type.'_'.$versement->id_type_depense] = $versement;
            }
        }
        return $versementsArray;
    }

    public static function getManqueMtn($mtotal, $versements){

        $mverse = isset($versements['VERSEMENT']) ? $versements['VERSEMENT']->montant : 0;
        $commission = isset($versements['DEPENSE_3']) ? $versements['DEPENSE_3']->montant : 0;
        $taxation = isset($versements['DEPENSE_4']) ? $versements['DEPENSE_4']->montant : 0;
        $tronsport = isset($versements['DEPENSE_5']) ? $versements['DEPENSE_5']->montant : 0;
        $ramassage = isset($versements['DEPENSE_6']) ? $versements['DEPENSE_6']->montant : 0;
        $autre = isset($versements['DEPENSE_7']) ? $versements['DEPENSE_7']->montant : 0;
        return $mtotal - $mverse - $commission - $taxation - $tronsport - $ramassage - $autre;
    }

}