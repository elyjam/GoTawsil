<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statut extends Model
{
    protected $table = 'statuts';
    protected $guarded = [];

    public static function fetchAllByCode($code){
        return \DB::table("statuts")->where('code', $code)->orderBy('ordre')->get();
    }

    public  function StatutColor()
    {
        $group_id =  \DB::table("Gstatut_Status")->where('id_statut', $this->id)->pluck('id_group')->first();
        $color = \DB::table("groupstatuts")->where('id', $group_id)->pluck('Color')->first();
        if($color != null){
            return $color;
        }else{
            return "dark";
        }

    }

    public function GroupDetail()
    {
        $group_id =  \DB::table("Gstatut_Status")->where('id_statut', $this->id)->pluck('id_group')->first();

        return Groupstatuts::where('id', $group_id)->first();
    }


}
