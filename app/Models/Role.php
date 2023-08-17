<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $guarded = [];

    public function droits()
    {
        return $this->belongsToMany(Droit::class, 'roles_droits');
    }

    public static function fetchAll(){
        return self::all()->where('deleted',"0");
   }

    public function fonctionnalites()
    {
        return $this->belongsToMany(Fonctionnalite::class, 'roles_fonctionnalites');
    }
}
