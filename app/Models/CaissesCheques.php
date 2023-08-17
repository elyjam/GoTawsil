<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaissesCheques extends Model
{
    protected $table = 'caisses_cheques';
    protected $guarded = [];

    public static function getRecordsArray($caisse){
        $cheques = CaissesCheques::where('caisse', $caisse)->leftJoin('expeditions', 'expeditions.id', '=', 'caisses_cheques.expedition')->get();
        $chequesArray = [];

        foreach($cheques as $cheque){
            $chequesArray[$cheque->expedition][] = $cheque; 
        }
        return $chequesArray;
    }

    public static function getMntArray($caisse, $expeditions = []){

        $chequesArray = [];

        if(is_numeric($caisse)){
            $cheques = CaissesCheques::where('caisse', $caisse)
                                ->leftJoin('expeditions', 'expeditions.id', '=', 'caisses_cheques.expedition')
                                ->get();
        }
        elseif(count($expeditions)>0){
            $cheques = CaissesCheques::whereIn('expedition', $expeditions)
                                ->leftJoin('expeditions', 'expeditions.id', '=', 'caisses_cheques.expedition')
                                ->get();
        }
        else{
            return $chequesArray;
        }

        foreach($cheques as $cheque){
            if(isset($chequesArray[$cheque->expedition])){
                $chequesArray[$cheque->expedition] += $cheque->montant;
            }
            else{
                $chequesArray[$cheque->expedition] = $cheque->montant;
            }
        }
        return $chequesArray;
    }
}