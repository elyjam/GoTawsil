<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemboursementPaiements extends Model
{
    protected $table = 'remboursements_paiements';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }

    public function typeDetail()
    {
        if($this->type == 1){
            $type = 'Chèque';
        }elseif($this->type == 2){
            $type = 'Espèce';
        }
        return $type;

    }

    public function clientDetail()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client');
    }

    public function rembDetail()
    {
        return $this->belongsTo(\App\Models\Remboursement::class, 'remboursement');
    }

}
