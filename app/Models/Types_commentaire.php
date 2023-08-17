<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Types_commentaire extends Model
{
    protected $table = 'types_commentaires';
    protected $guarded = [];

    public static function getCommentaires($type = null){
        $records =  \DB::table("types_commentaires")
                        ->select("*")
                        ->where('deleted',"0")
                        ->where('statut',"1");
        if($type !== null){
            $records->where('type', $type);
        }
        return $records->orderBy('libelle', 'ASC')->get();
    }

}