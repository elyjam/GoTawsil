<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caissepp extends Model
{
    protected $table = 'caissepps';
    protected $guarded = [];

    public static function getRecords($formData){
        $query = \DB::table("expeditions")
            ->select(
                "*",
                \DB::raw('expeditions.id as id'),
                \DB::raw('expeditions.type as type'),
                \DB::raw('expeditions.num_expedition as num_expedition'),
                \DB::raw('expeditions.client as client'),
                \DB::raw('expeditions.fond as fond'),
                \DB::raw('expeditions.sens as sens'),
                \DB::raw('expeditions.port as port'),
                \DB::raw('expeditions.telephone as telephone'),
                \DB::raw('expeditions.colis as colis'),
                \DB::raw('expeditions.retour_fond as retour_fond'),
                \DB::raw('expeditions.deleted as deleted'),
                \DB::raw('expeditions.created_at as created_at'),
                \DB::raw('expeditions.deleted_at as deleted_at'),

                \DB::raw('bons.date_validation as bons_date_validation'),
                \DB::raw('clients.libelle as client'),
                \DB::raw('agences_des.libelle as destination'),
                \DB::raw('users.name as name'),

                \DB::raw('agences_exp.libelle as agence'),
                \DB::raw('statuts.value as statut_label')
            )
            ->leftJoin('bons', 'bons.id', '=', 'expeditions.id_bon')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
            ->leftJoin('users', 'users.id', '=', 'expeditions.caissepp_emp')

            
            ->leftJoin(
                'villes as agences_des',
                function ($join) {
                    $join->on('agences_des.id', '=', 'expeditions.agence_des');
                }
            )
            ->leftJoin(
                'villes as agences_exp',
                function ($join) {
                    $join->on('agences_exp.id', '=', 'expeditions.agence');
                }
            )
            ->leftJoin('statuts', function ($join) {
                $join->on('statuts.key', '=', 'expeditions.etape');
                $join->where('statuts.code', '=', 'ETAPE_EXPEDITION');
            });
        
        $query->where('expeditions.port', '=', 'PP');

        if (isset($formData['expediteur']) && $formData['expediteur'] != '0') {
            $query->where('expeditions.client', '=', $formData['expediteur']);
        }

        if (isset($formData['agence_des']) && $formData['agence_des'] != '0') {
            $query->where('expeditions.agence_des', '=', $formData['agence_des']);
        }

        if (isset($formData['agence_exp']) && $formData['agence_exp'] != '0') {
            $query->where('expeditions.agence', '=', $formData['agence_exp']);
        }

        if (isset($formData['start_date'])  && strlen(trim(($formData['start_date']))) > 0) {
            $query->whereDate("expeditions.created_at", '>=', $formData['start_date']);
        }
        if (isset($formData['end_date'])  && strlen(trim(($formData['end_date']))) > 0) {
            $query->whereDate("expeditions.created_at", '<=', $formData['end_date']);
        }
        if (isset($formData['etapes']) && $formData['etapes'] != '0') {
            $query->whereIn('etape', $formData['etapes']);
        }
        if (isset($formData['agence']) && $formData['agence'] != '0') {
            $query->where('expeditions.agence', '=', $formData['agence']);
        }
        if (isset($formData['n_colis']) && strlen(trim(($formData['n_colis']))) > 0) {
            $query->where('num_expedition', '=', $formData['n_colis']);
        }

        return $query->get();
    }
    
}