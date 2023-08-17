<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chargement_Masse_history;
use Illuminate\Notifications\Notification;
use App\Http\Controllers\ChargementColisController;

class Expedition extends Model
{
    protected $table = 'expeditions';
    protected $guarded = [];

    public function clientDetail()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client');
    }

    public function colis_ech()
    {
        return $this->belongsTo(\App\Models\Expedition::class, 'echange_id');
    }



    public function CreatedByDetail()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }


    public function bonLivsDetail()
    {
        return $this->belongsTo(\App\Models\Bonliv::class, 'bl');

    }

    public function agenceDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'agence');
    }

    public function origineDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'origine');
    }
    public function DestinationDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'des');
    }
    public function agenceDesDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'agence_des');
    }
    public function bonRamassageDetail()
    {
        return $this->belongsTo(\App\Models\Bon::class, 'id_bon');
    }
    public function processusDetail()
    {
        return $this->hasMany(\App\Models\Processus_expedition::class, 'id_expedition');
    }


    public static function getMontantTotalByCaisse($caisse){
        return  \DB::table("caisses_expeditions")->leftJoin('expeditions', 'expeditions.id', '=', 'caisses_expeditions.id_expedition')->where("id_caisse", $caisse)->sum("fond");
    }

    public function date_recu()
    {
        $chargement = DB::table('processus_expeditions')->get()->where('code', 'CHARGEMENT')->where('id_expedition', $this->id)->first();
        return $chargement->date_reception ?? '';
    }


    public function get_type()
    {

        if ($this->type == 'COLECH') {
            return 'Colis en échange';
        }
    }

    public function getEtape()
    {
        $statut = DB::table('statuts')->get()->where('code', 'ETAPE_EXPEDITION')->where('key', $this->etape)->first();
        return $statut->value ?? '';
    }

    public static function getEtapeCommentaire($id)
    {
        $statut = DB::table('statuts')->get()->where('code', 'ETAPE_EXPEDITION')->where('key', $id)->first();
        return $statut->value ?? '';
    }

    public function etapeHistory()
    {
        return $this->hasMany(\App\Models\etapeHistory::class, 'expedition');
    }
    public function etapeHistoryclient()
    {
        return $this->etapeHistory()->whereNotIn('etape',[1,5,6,8,9,13,12]);
    }

    public static function getExpeditions($formData)
    {

        $query = \DB::table("expeditions")
            ->select(
                "*",
                \DB::raw('expeditions.id as id'),
                \DB::raw('expeditions.type as type'),
                \DB::raw('expeditions.num_expedition as num_expedition'),
                \DB::raw('expeditions.client as client'),
                \DB::raw('expeditions.fond as fond'),
                \DB::raw('expeditions.port as port'),
                \DB::raw('expeditions.telephone as telephone'),
                \DB::raw('expeditions.colis as colis'),
                \DB::raw('expeditions.retour_fond as retour_fond'),
                \DB::raw('expeditions.deleted as deleted'),
                \DB::raw('expeditions.deleted_at as deleted_at'),
                \DB::raw('expeditions.created_at as created_at_exp'),
                \DB::raw('bons.date_validation as bons_date_validation'),
                \DB::raw('clients.libelle as client'),
                \DB::raw('agences_des.libelle as destination'),
                \DB::raw('agences_exp.libelle as agence'),
                \DB::raw('statuts.value as statut_label')
            )
            ->leftJoin('bons', 'bons.id', '=', 'expeditions.id_bon')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
            ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions.agence_des')
            ->leftJoin('villes as agences_exp', 'agences_exp.id', '=', 'expeditions.agence')
            ->leftJoin('statuts', function ($join) {
                $join->on('statuts.key', '=', 'expeditions.etape');
                $join->where('statuts.code', '=', 'ETAPE_EXPEDITION');
            });
        if (isset($formData['expediteur']) && $formData['expediteur'] != '0') {
            $query->where('expeditions.client', '=', $formData['expediteur']);
        }
        if (isset($formData['agence_des']) && $formData['agence_des'] != '0') {
            $query->where('expeditions.agence_des', '=', $formData['agence_des']);
        }
        if (isset($formData['agence_exp']) && $formData['agence_exp'] != '0') {
            $query->where('expeditions.agence', '=', $formData['agence_exp']);
        }
        if (isset($formData['start_date']) && strlen(trim(($formData['start_date']))) > 0) {
            $query->whereDate("expeditions.created_at", '>=', $formData['start_date']);
        }
        if (isset($formData['end_date'])  && strlen(trim(($formData['end_date']))) > 0) {
            $query->whereDate("expeditions.created_at", '<=', $formData['end_date']);
        }
        if (isset($formData['comment']) && $formData['comment'] != '0') {
            $query->where('etape', '=', $formData['comment']);
        }
        if (isset($formData['agence']) && $formData['agence'] != '0') {
            $query->where('expeditions.agence', '=', $formData['agence']);
        }
        if (isset($formData['agences']) && is_array($formData['agences']) && count($formData['agences']) > 0) {
            $query->whereIn('expeditions.agence', $formData['agences']);
        }
        if (isset($formData['agence_des']) && is_array($formData['agence_des']) && count($formData['agence_des']) > 0) {
            $query->whereIn('expeditions.agence_des', $formData['agence_des']);
        };

        if (isset($formData['statut']) && is_numeric($formData['statut'])) {
            $query->where('expeditions.etape', $formData['statut']);
        };

        $query->whereIn('expeditions.etape', ['2', '3','9','15'])
            ->Orwhere('expeditions.etape', '6')->where('expeditions.sens', 'Retour');
        return $query->get();
    }

    public static function getExpeditionsEnLivraison()
    {
        $expeditions = \DB::table("expeditions")
            ->select(
                "*",
                \DB::raw('expeditions.id as id'),
                \DB::raw('expeditions.type as type'),
                \DB::raw('expeditions.num_expedition as num_expedition'),
                \DB::raw('expeditions.client as client'),
                \DB::raw('expeditions.fond as fond'),
                \DB::raw('expeditions.port as port'),
                \DB::raw('expeditions.telephone as telephone'),
                \DB::raw('expeditions.colis as colis'),
                \DB::raw('expeditions.retour_fond as retour_fond'),
                \DB::raw('expeditions.deleted as deleted'),
                \DB::raw('expeditions.deleted_at as deleted_at'),
                \DB::raw('expeditions.created_at as created_at'),
                \DB::raw('bons.date_validation as bons_date_validation'),
                \DB::raw('bonlivs.code as bl_code'),
                \DB::raw('bonlivs.id as bl_id'),
                \DB::raw('clients.libelle as client'),
                \DB::raw('agences_des.Libelle as destination'),
                \DB::raw('agences_exp.Libelle as agence'),
                \DB::raw('employes.Libelle as livreur'),
                \DB::raw('statuts.value as statut_label')
            )
            ->leftJoin('bons', 'bons.id', '=', 'expeditions.id_bon')
            ->leftJoin('bonlivs', 'bonlivs.id', '=', 'expeditions.bl')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
            ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions.agence_des')
            ->leftJoin('villes as agences_exp', 'agences_exp.id', '=', 'expeditions.agence')
            ->leftJoin('employes', 'employes.id', '=', 'bonlivs.livreur')
            ->leftJoin('statuts', function ($join) {
                $join->on('statuts.key', '=', 'expeditions.etape');
                $join->where('statuts.code', '=', 'ETAPE_EXPEDITION');
            });
            if(request()->input('num_bon') != null && strlen(request()->input('num_bon')) > 2){
                $expeditions->where('bonlivs.code', trim(request()->input('num_bon')));
            }
            if(request()->input('num_exp') != null && strlen(request()->input('num_exp')) > 2){
                $expeditions->where('expeditions.num_expedition', trim(request()->input('num_exp')));
            }
            if(request()->input('employe') != null && is_numeric(request()->input('employe'))){
                $expeditions->where('bonlivs.livreur', request()->input('employe'));
            }

            $expeditions->where('expeditions.deleted', 0);
            $expeditions->whereIn('expeditions.etape', [16, 17, 18]);


            if(auth()->user()->role == '2'){
                $bonlivs = DB::table('bonlivs')->get()->where('livreur',  auth()->user()->EmployeDetail->id);
                $bons_array =array();
                foreach ($bonlivs as $bon){
                    array_push($bons_array, array(
                        $bon->id
                    ));
                }
                $expeditions->whereIn('expeditions.bl', $bons_array);
            }elseif(auth()->user()->role == '5' || auth()->user()->role == '7'){
                $expeditions->whereIn('expeditions.agence', \Auth::user()::getUserVilles());
            }

            // $expeditions->whereIn('bl_id', [4, 6, 18]);
        return  $expeditions->get();
    }

    public static function getExpeditionByCaisse($caisse)
    {
        $expeditions = \DB::table("caisses_expeditions")
            ->select(
                "*",
                \DB::raw('expeditions.id as id'),
                \DB::raw('expeditions.type as type'),
                \DB::raw('expeditions.num_expedition as num_expedition'),
                \DB::raw('expeditions.client as client'),
                \DB::raw('expeditions.fond as fond'),
                \DB::raw('expeditions.port as port'),
                \DB::raw('expeditions.telephone as telephone'),
                \DB::raw('expeditions.colis as colis'),
                \DB::raw('expeditions.retour_fond as retour_fond'),
                \DB::raw('expeditions.deleted as deleted'),
                \DB::raw('expeditions.deleted_at as deleted_at'),
                \DB::raw('expeditions.created_at as created_at'),
                \DB::raw('bons.date_validation as bons_date_validation'),
                \DB::raw('bonlivs.code as bl_code'),
                \DB::raw('clients.libelle as client'),
                \DB::raw('agences_des.Libelle as destination'),
                \DB::raw('agences_exp.Libelle as agence'),
                \DB::raw('statuts.value as statut_label')
            )
            ->leftJoin('expeditions', 'expeditions.id', '=', 'caisses_expeditions.id_expedition')
            ->leftJoin('bons', 'bons.id', '=', 'expeditions.id_bon')
            ->leftJoin('bonlivs', 'bonlivs.id', '=', 'expeditions.bl')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
            ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions.agence_des')
            ->leftJoin('villes as agences_exp', 'agences_exp.id', '=', 'expeditions.agence')
            ->leftJoin('statuts', function ($join) {
                $join->on('statuts.key', '=', 'expeditions.etape');
                $join->where('statuts.code', '=', 'ETAPE_EXPEDITION');
            });
            $expeditions->where('expeditions.deleted', 0);
            $expeditions->where('caisses_expeditions.id_caisse', $caisse);
        return  $expeditions->get();
    }

    public static function getClientsByRemboursement($expeditions)
    {
        $clients = [];
        foreach($expeditions as $expedition){
            if(!isset($client[$expedition->client_id])){
                $clients[$expedition->client_id]['name'] = $expedition->client;
                $clients[$expedition->client_id]['client_id'] = $expedition->client_id;
                $clients[$expedition->client_id]['rib'] = $expedition->rib;
                $clients[$expedition->client_id]['factureMois'] = $expedition->factureMois;
            }
        }
        return $clients;
    }

    public static function getExpeditionsByRemboursement($remboursement)
    {
        $expeditions = \DB::table("remboursements_expeditions")
            ->select(
                "*",
                \DB::raw('expeditions.id as id'),
                \DB::raw('expeditions.type as type'),
                \DB::raw('expeditions.num_expedition as num_expedition'),
                \DB::raw('expeditions.client as client_id'),
                \DB::raw('expeditions.fond as fond'),
                \DB::raw('expeditions.port as port'),
                \DB::raw('expeditions.telephone as telephone'),
                \DB::raw('expeditions.colis as colis'),
                \DB::raw('expeditions.retour_fond as retour_fond'),
                \DB::raw('expeditions.deleted as deleted'),
                \DB::raw('expeditions.deleted_at as deleted_at'),
                \DB::raw('expeditions.created_at as created_at'),
                \DB::raw('clients.libelle as client'),
                \DB::raw('clients.rib as rib'),
                \DB::raw('clients.factureMois as factureMois'),
                \DB::raw('agences_des.Libelle as destination'),
                \DB::raw('agences_exp.Libelle as agence')
            )
            ->leftJoin('expeditions', 'expeditions.id', '=', 'remboursements_expeditions.expedition_id')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
            ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions.agence_des')
            ->leftJoin('villes as agences_exp', 'agences_exp.id', '=', 'expeditions.agence')
            ->orderBy('client_id', 'desc');
            $expeditions->where('expeditions.deleted', 0);
            $expeditions->where('remboursements_expeditions.remboursement_id', $remboursement);
        return  $expeditions->get();
    }


    public static function getExpeditionsByAllRemboursement()
    {
        $expeditions = \DB::table("remboursements_expeditions")
            ->select(
                "*",
                \DB::raw('expeditions.id as id'),
                \DB::raw('expeditions.type as type'),
                \DB::raw('expeditions.num_expedition as num_expedition'),
                \DB::raw('expeditions.client as client_id'),
                \DB::raw('expeditions.fond as fond'),
                \DB::raw('expeditions.port as port'),
                \DB::raw('expeditions.telephone as telephone'),
                \DB::raw('expeditions.colis as colis'),
                \DB::raw('expeditions.retour_fond as retour_fond'),
                \DB::raw('expeditions.deleted as deleted'),
                \DB::raw('expeditions.deleted_at as deleted_at'),
                \DB::raw('expeditions.created_at as created_at'),
                \DB::raw('clients.libelle as client'),
                \DB::raw('clients.rib as rib'),
                \DB::raw('agences_des.Libelle as destination'),
                \DB::raw('agences_exp.Libelle as agence')
            )
            ->leftJoin('expeditions', 'expeditions.id', '=', 'remboursements_expeditions.expedition_id')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
            ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions.agence_des')
            ->leftJoin('villes as agences_exp', 'agences_exp.id', '=', 'expeditions.agence')
            ->orderBy('client_id', 'desc');
        $expeditions->where('expeditions.deleted', 0);
        $expeditions->where('remboursements_expeditions.remboursement_id','!=' ,null);
        $expeditions->groupBy('remboursements_expeditions.remboursement_id');

        return  $expeditions->get();
    }


    public static function getStock($sens = null)
    {
        // $expeditions = Expedition::whereNotIn('etape', ['1', '16', '17', '18']);
        $expeditions = Expedition::whereIn('etape', ['10', '20']);

        if ($sens !== null) {
            $expeditions->where('sens', $sens);
        }

        return  $expeditions->get();
    }

    public static function getStockList($sens = null)
    {
        // $expeditions = Expedition::whereNotIn('etape', ['1', '16', '17', '18']);

        $expeditions = Expedition::whereIn('etape', ['2', '3', '10', '16','15','20']);
        if ($sens !== null) {
            $expeditions->where('sens', $sens);
        }

        return  $expeditions->get();
    }
    public static function addEtageSign($ExId){

        $etapeHistory  =  etapeHistory::where('expedition', $ExId)->get();
        $array_list = [];
        foreach ($etapeHistory as $key => $value) {
            array_push($array_list, $value->etape);
        }
        $count = count($array_list);

        $etape = etapeHistory::where('expedition', $ExId)->where('etape',$array_list[$count - 2])->first();
        $etape->update([
            'is_anul' => 1
        ]);

    }
    public static function saveImportedList($expeditions, $villes, $numExp)
    {

        $chargement_history = new Chargement_Masse_history();
        $chargement_history->code =  Chargement_Masse_history::getcode('M',Chargement_Masse_history::all()->count());
        $chargement_history->client = auth()->user()->ClientDetail->id;
        $chargement_history->save();

        if(Auth::user()->ClientDetail->factureMois == 'Oui'){
            $port = 'PPE';
        }else{
            $port = 'PD';
        }

        foreach ($expeditions as $expedition) {

            $prix = Taxation::getPrixColis(Auth::user()->ClientDetail->id,$villes[$expedition['ville']],auth()->user()->ClientDetail->agence);

            // if($expedition['nbr_colis']> 1){
            //     if(isset($expedition['valeur_declaree'])){
            //         $ttc =  ($expedition['nbr_colis'] * $prix) + ((Auth::user()->ClientDetail->valeur_declaree / 100) * $expedition['valeur_declaree']);
            //     }else{
            //         $ttc =  $expedition['nbr_colis'] * $prix ;
            //     }
            // }else{
            //     if(isset($expedition['valeur_declaree'])){
            //         $ttc = $prix +  (Auth::user()->ClientDetail->valeur_declaree / 100) * $expedition['valeur_declaree'];
            //     }else{
            //         $ttc =  $prix ;
            //     }
            // }


            if ((int)$expedition['nbr_colis']> 1) {

                if (isset($expedition['valeur_declaree'])) {
                    $ttc =  ($expedition['nbr_colis'] * $prix) + ((Auth::user()->ClientDetail->valeur_declaree / 100) * $expedition['valeur_declaree']);
                } else {

                    if (!empty(Auth::user()->ClientDetail->vplafond) && Auth::user()->ClientDetail->vplafond <= $expedition['montant_fond']) {
                        $ttc =  ($expedition['nbr_colis'] * $prix) + ((Auth::user()->ClientDetail->valeur_declaree / 100) * $expedition['montant_fond']);
                    } else {
                        $ttc =  $expedition['nbr_colis'] * $prix;
                    }
                }
            } else {
                if (isset($expedition['valeur_declaree']) && $expedition['valeur_declaree'] != 0) {

                    $ttc = $prix +  (Auth::user()->ClientDetail->valeur_declaree / 100) * $expedition['valeur_declaree'];
                } else {

                    if (!empty(Auth::user()->ClientDetail->vplafond) && Auth::user()->ClientDetail->vplafond <= $expedition['montant_fond']) {

                        $ttc =  $prix + ((Auth::user()->ClientDetail->valeur_declaree / 100) * $expedition['montant_fond']);
                    } else {

                        $ttc =  $prix;
                    }
                }
            }



            $record = new Expedition();
            $record->client = auth()->user()->ClientDetail->id;
            $record->retour_fond = 'CR';
            $record->type = 'ECOM';
            $record->etape = 1;
            $record->ttc = $ttc;
            $record->port = $port;
            $record->mass = $chargement_history->id;
            $record->sens = 'Envoi';
            $record->destinataire = $expedition['destinataire'];
            $record->agence = auth()->user()->ClientDetail->agence;;
            $record->agence_des = $villes[$expedition['ville']];
            $record->telephone = $expedition['tel'];
            $record->adresse_destinataire = $expedition['adresse'];
            $record->colis = $expedition['nbr_colis'];
            $record->fond = $expedition['montant_fond'];
            $record->origine = auth()->user()->ClientDetail->agence;
            $record->des = $villes[$expedition['ville']];
            if ($numExp != 'AUTO') {
                $record->num_expedition = $expedition['num_commande'];
            } else {
                $record->num_expedition =  'EX' . sprintf("%06d", Expedition::all()->count() + 2);
            }
            $record->vDeclaree = $expedition['valeur_declaree'];
            $record->created_by = auth()->user()->id;
            $record->paiementCheque = ($expedition['paiement_cheque'] == 'OUI') ? 'Oui' : 'Non';
            $record->ouvertureColis = ($expedition['ouverture_colis'] == 'OUI') ? 'Oui' : 'Non';
            $record->save();

            $etapeHistory = new etapeHistory();
            $etapeHistory->expedition = $record->id;
            $etapeHistory->etape = 1;
            $etapeHistory->save();

            $commentaire = new Commentaire();
            $commentaire->code = "TAXATION";
            $commentaire->id_expedition = $record->id;
            $commentaire->id_utilisateur = auth()->user()->id;
            $commentaire->save();



        }

    }

    public function Caisse(){
        return $this->hasOne(CaissesExpeditions::class, 'id_expedition');
    }

    // bring the row before the last one
    public static function beforeLastEtape($ExId){
        $etapeHistory  =  etapeHistory::where('expedition', $ExId)->where('is_anul',null)->get();
        $array_list = [];
        foreach ($etapeHistory as $key => $value) {
            array_push($array_list, $value->etape);
        }
        $count = count($array_list);
        return $array_list[$count - 2] ;
    }


}
