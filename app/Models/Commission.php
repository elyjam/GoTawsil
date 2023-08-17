<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Ville;

class Commission extends Model
{
    protected $table = 'commissions';
    protected $guarded = [];

    public function livreurDetail(){
        return $this->belongsTo(Employe::class,'id_livreur');
    }

    public function villeDetail(){
        return $this->belongsTo(Ville::class,'id_ville');
    }
    public static function saveData($formData, $newData){

        foreach($newData as $key => $value){
            $value = str_replace(',','.',$value);
            if(isset($formData[$key])){
                if($formData[$key] != $newData[$key]){
                    $keys = explode('_',$key);
                    $taxation = Commission::where('id_livreur', $keys[0])
                                        ->where('id_ville', $keys[1]);
                    $taxation = $taxation->first();
                    if($taxation){
                        if(is_numeric($value)){
                            $taxation->coefficient = $value;
                            $taxation->save();
                        }
                        else{
                            $taxation->delete();
                        }
                    }
                }
            }
            elseif(is_numeric($value)){
                $keys = explode('_',$key);
                $commission = new Commission();
                    $commission->id_ville = $keys[1];
                    $commission->id_livreur = $keys[0];
                    $commission->coefficient = $value;
                $commission->save();
            }
            else{}
        }
    }

    public static function getFormData()
    {
        $ville = request('ville', '0');
        $livreur = request('livreur', '0');
        $formData = [];
        $records =  \DB::table("commissions")->select("*");
        if( $livreur != '0'){
            $records->where("id_livreur", '=', $livreur );
        }
        else{
            $records->where("id_livreur", '=', null )->orWhere("id_livreur", '=', 0 );
        }

        $data = $records->get();
        foreach($data as $row){
            if(!is_numeric($row->id_livreur)){$row->id_livreur = '0';}
            $formData[$row->id_livreur.'_'.$row->id_ville] = $row->coefficient;
        }
        return $formData;
    }

    public static function getFormRows($villes, $formData){

        $formRows=[];
        $villeId = request('ville', '0');
        $livreur = request('livreur', '0');
        $livreurtName = $livreur == '0' ? '' : Employe::find($livreur)->libelle ?? '';
        foreach($villes as $ville){
            if ($villeId ==0 || $villeId == $ville->id) {
                $formRows[]=[
                    'livreur' => $livreurtName,
                    'ville' => $ville->libelle,
                    'key' => $livreur.'_'.$ville->id,
                    'value' => $formData[$livreur.'_'.$ville->id] ?? '',

                ];
            }
        }
        return $formRows;
    }

    public function villeDetailDest(){
        return $this->belongsTo(\App\Models\Ville::class,'id_ville_dest');
    }


}
