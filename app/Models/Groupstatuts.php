<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groupstatuts extends Model
{
    protected $table = 'groupstatuts';
    protected $guarded = [];

    public function relatedStatuts()
    {
         return $this->belongsToMany(Statut::class, 'Gstatut_Status', 'id_group', 'id_statut');
    }


}
