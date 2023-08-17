<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chargement_Masse_history extends Model
{
    protected $table = 'chargement_masse_history';
    protected $guarded = [];

    public function expeditionMassDetail(){
        return $this->hasMany(\App\Models\Expedition::class, 'mass');
    }
    public static function getcode($letter, $object)
    {
        return $letter . sprintf("%06d", $object + 1);
    }
}
