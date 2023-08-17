<?php

namespace App\Http\Controllers;

use \App\User;
use App\Mouse;
use Carbon\Carbon;
use App\Models\Bon;
use App\Models\Ville;
use App\Models\Bonliv;
use App\Models\Caisse;
use App\Models\Client;
use App\Models\Employe;
use App\Models\Facture;
use App\Models\Taxation;
use App\Models\Expedition;
use App\Models\Commentaire;
use App\Models\Reclamation;
use App\Models\etapeHistory;
use App\Models\Transporteur;
use Illuminate\Http\Request;
use App\Models\ExpeditionImages;
use \App\Models\Types_commentaire;
use App\Models\CaissesExpeditions;
use App\Models\Processus_expedition;
use App\Models\CommissionExpeditions;
use App\Models\RemboursementPaiements;
use App\Notifications\noLivreeExpedition;
use App\Models\notificationWhatsapp;

class MapiController extends Controller
{
    public function __construct(Request $request)
    {
    }

    public function login(Request $request)
    {
        $data = json_decode($request->getContent());
        $credentials = ['login' => $data->{"login"}, 'password' => $data->{"password"}];

        if (\Auth::attempt($credentials)) {
            $user = user::where('login', $credentials['login'])->first();

            if ($user->validated == 0) {
                return response()->json([
                    'error' => 404, 'message' => 'Votre profile est <strong>désactivée</strong> '
                ]);
            }else{

                return response()->json(User::where('login', $credentials['login'])->first()->toArray());

            }


        } else {
            return response()->json(['error' => 401 , 'message' => 'Utilisateur ou mot de passe non reconnu !']);
        }
    }

    public function changeStatus(Request $request)
    {
        $data = json_decode($request->getContent());
        $expeditionId = $data->{"expeditionId"};
        $status = $data->{"status"};
        $comment = $data->{"comment"};
        $userId = $data->{"userId"};
        // $lat = $data->{"lat"};
        // $long = $data->{"long"};
        $photos = $data->{"photos"};

        for ($i = 0; $i < count($photos); $i++) {
            $filename = uniqid() . uniqid() . uniqid() . ".jpg";
            $content = str_replace("data:image/png;base64,", "", $photos[$i]);
            $content = str_replace("data:image/jpeg;base64,", "", $content);
            $binary = base64_decode($content);
            $file = fopen(public_path('uploads/expeditions/') . $filename, 'wb');
            fwrite($file, $binary);
            fclose($file);
            $image = new ExpeditionImages();
            $image->expedition_id = $expeditionId;
            $image->name = $filename;
            $image->save();
        }




        $expedition = Expedition::find($expeditionId);
        if ($status == 14) {
            $caisse = Caisse::getOpenedCaisseByExpedition($expedition, $userId);
            //dd($expedition, $status, $comment);
            $record = new CaissesExpeditions();
            $record->date_creation = date('Y-m-d H:i:s');
            $record->montant = $expedition->fond;
            $record->statut = 1;
            $record->type = $expedition->port;
            $record->id_agence = $expedition->agence;
            $record->id_caissier = $userId;
            $record->id_expedition = $expedition->id;
            $record->id_livreur = $expedition->bonLivsDetail->livreur ?? 0;
            $record->sens = $expedition->sens;
            $record->id_utilisateur = $userId;
            $record->id_caisse = $caisse->id;
            $record->save();

            $expedition->caisse_id = $caisse->id;

            $commentaire = new Commentaire();
            $commentaire->code = "ENCAISSEMENT_LIVREUR";
            // if($lat != 0 && $long != 0){
                $commentaire->commentaires = "colis livré";
            // }else{
            //     $commentaire->commentaires = "colis livré - localisation pas activé";
            // }
            $commentaire->id_expedition = $expedition->id;
            $commentaire->id_utilisateur = $userId;
            $commentaire->lat = 0;
            $commentaire->long = 0;
            $commentaire->source = 2;
            $commentaire->save();

            $etapeHistory = new etapeHistory();
            $etapeHistory->expedition = $expedition->id;
            $etapeHistory->etape = $status;
            $etapeHistory->save();

            $commission = new CommissionExpeditions();
            $commission->id_expedition = $expedition->id;
            $commission->livreur = $expedition->bonLivsDetail->livreur ?? 0;
            $commission->id_ville_exp = $expedition->agence;
            $commission->id_ville_dest = $expedition->agence_des;
            $commission->type = $expedition->bonLivsDetail->employeDetail->is_coff_ville($expedition);
            $commission->commission = $expedition->bonLivsDetail->employeDetail->commission_livreur($expedition);
            $commission->save();

            Processus_expedition::where('code', 'LIVRAISON')->where('id_expedition', $expedition->id)->latest()->first()->update([
                'date_validation' => Carbon::now()
            ]);
        } elseif ($status == 20) {

            $countComment = Commentaire::where('code', 'NON LIVRÉE')->where('id_expedition', $expedition->id)->get()->count();

            if ($countComment == 0) {
                $tantative = 1;
            } else {
                $tantative =  $countComment + 1;
            };

            if ($tantative == 1) {
                $texttantative = 're TENTATIVE : ';
            } else {
                $texttantative = 'éme TENTATIVE : ';
            }

            $commentaire = new Commentaire();
            $commentaire->code = "NON LIVRÉE";
            // if($lat != 0 && $long != 0){
                $commentaire->commentaires = $tantative . $texttantative . Types_commentaire::where('id', $comment)->first()->libelle;
            // }else{
            //     $commentaire->commentaires = $tantative . $texttantative . Types_commentaire::where('id', $comment)->first()->libelle . '- localisation pas activé';
            // }

            $commentaire->id_expedition = $expedition->id;
            $commentaire->id_utilisateur = $userId;
            $commentaire->lat = 0;
            $commentaire->long = 0;
            $commentaire->source = 2;
            $commentaire->save();

            $etapeHistory = new etapeHistory();
            $etapeHistory->expedition = $expedition->id;
            $etapeHistory->etape = $status;
            $etapeHistory->libelle = $tantative . $texttantative . Types_commentaire::where('id', $comment)->first()->libelle;
            $etapeHistory->save();


            if (isset($expedition->clientDetail->email_nolivre)) {
                $email = $expedition->clientDetail->email_nolivre;
            } else {
                $email = $expedition->clientDetail->email;
            }

            if ($expedition->sens == 'Envoi') {
                Notification::route('mail', $email)->notify(new noLivreeExpedition($expedition->num_expedition, $commentaire->commentaires,));

                $message = "Nous vous informons que le colis " . $expedition->num_expedition . " n'est pas livré cause du motif suivant: " . $commentaire->commentaires . ". Priére de prendre contact avec votre client pour augmenter les chances de livraison lors des prochaines tentatives";
                notificationWhatsapp::whatsappMessage($expedition->clientDetail->telephone, $message);
                // $user->notify(new noLivreeExpedition($expedition->num_expedition, $commentaire->commentaires));
            }
        }

        $expedition->etape = $status;
        $expedition->save();
        return response()->json(['updated' => 1]);
    }

    public function expeditions(Request $request)
    {

        $data = json_decode($request->getContent());

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
                \DB::raw('agences_exp.libelle as agence'),
                \DB::raw('statuts.value as statut_label')
            )
            ->leftJoin('bons', 'bons.id', '=', 'expeditions.id_bon')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
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
        $query->where('expeditions.client', User::find($data->{"userId"})->client);
        return response()->json($query->get()->toArray());
    }

    public static function commentaires()
    {
        return response()->json(Types_commentaire::getCommentaires('LIVREUR')->toArray());
    }
    public static function livreurs(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        $user = User::find($userId);

        $regions = $user->relatedRegions()->allRelatedIds()->toArray();
        $livreur_dispo_region = \DB::table("regions_users")->whereIn('region_id', $regions)->pluck('user_id')->toArray();

        // if($user->role == '1'){
        //     $employes = User::where('deleted', '0')->where('validated',1)->where('role','!=', '3')->where('employe', '!=', null)->get();

        // }else{
            $livreur_dispo = \DB::table("villes_users")->whereIn('ville_id', $user::getUserVilles_api($user))->distinct('user_id')->pluck('user_id')->toArray();
            $livreur_pas_des_villes_affecter = \DB::table("employes")->whereIn('agence', $user::getUserVilles_api($user))->pluck('id')->toArray();
            $users_pva =  \DB::table("users")->where('deleted', '0')->where('validated', 1)->whereIn('employe', $livreur_pas_des_villes_affecter)->pluck('id')->toArray();
            $all_users_dispo = array_merge($users_pva, $livreur_dispo,$livreur_dispo_region);
            $employes = User::where('deleted', '0')->where('validated',1)->where('role','!=', '3')->where('role','!=', '1')->where('employe', '!=', null)->whereIn('id', $all_users_dispo)->get();

        // }

        return response()->json($employes->toArray());
    }

    public static function villes(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        //$user = User::find($userId);
        //return response()->json($user->relatedVilles()->get()->toArray());
        return response()->json(Ville::where('deleted', "0")->where('id', '!=', 999)->orderBy('libelle', 'asc')->get()->toArray());
    }

    public static function villesChargement(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        $user = User::find($userId);
        //$user = User::find($userId);
        //return response()->json($user->relatedVilles()->get()->toArray());
        return response()->json(Ville::where('deleted', "0")->whereIn('id', $user::getUserVilles_api($user))->orderBy('libelle', 'asc')->get()->toArray());
    }


    public static function transporteurs()
    {
        return response()->json(Transporteur::where('deleted', "0")->get()->toArray());
    }

    public static function encaissements(Request $request)
    {
        $data = json_decode($request->getContent());

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
        $expeditions->where('expeditions.deleted', 0);
        $expeditions->whereIn('expeditions.etape', [16, 17, 18]);
        //echo User::find($data->{"userId"})->employe; die;
        $expeditions->where('bonlivs.livreur', User::find($data->{"userId"})->employe);
        return response()->json($expeditions->get()->toArray());
    }

    public function expeditionDetail(Request $request)
    {

        $data = json_decode($request->getContent());
        $numExpedition = $data->{"numExpedition"};
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
                \DB::raw('agences_exp.libelle as agence'),
                \DB::raw('statuts.value as statut_label')
            )
            ->leftJoin('bons', 'bons.id', '=', 'expeditions.id_bon')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
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
        $query->where('expeditions.num_expedition', $numExpedition);
        return response()->json($query->first());
    }

    public function expeditionDetail_affectation(Request $request)
    {
        $data = json_decode($request->getContent());
        $numExpedition = $data->{"numExpedition"};
        $userId = $data->{"userId"};
        $user = User::find($userId);
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
                \DB::raw('agences_exp.libelle as agence'),
                \DB::raw('statuts.value as statut_label')
            )
            ->leftJoin('bons', 'bons.id', '=', 'expeditions.id_bon')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
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
        $query->where('expeditions.num_expedition', $numExpedition);
        $query->whereIn('expeditions.agence_des', $user::getUserVilles_api($user));
            return response()->json($query->first());
    }



    public function expeditionDetail_chargement(Request $request)
    {


        $data = json_decode($request->getContent());
        $numExpedition = $data->{"numExpedition"};
        $userId = $data->{"userId"};
        $user = User::find($userId);
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
                \DB::raw('expeditions.agence as agence_dep'),
                \DB::raw('expeditions.telephone as telephone'),
                \DB::raw('expeditions.colis as colis'),
                \DB::raw('expeditions.retour_fond as retour_fond'),
                \DB::raw('expeditions.deleted as deleted'),
                \DB::raw('expeditions.created_at as created_at'),
                \DB::raw('expeditions.deleted_at as deleted_at'),
                \DB::raw('bons.date_validation as bons_date_validation'),
                \DB::raw('clients.libelle as client'),
                \DB::raw('agences_des.libelle as destination'),
                \DB::raw('agences_exp.libelle as agence'),
                \DB::raw('statuts.value as statut_label')
            )
            ->leftJoin('bons', 'bons.id', '=', 'expeditions.id_bon')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
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
        $query->where('expeditions.num_expedition', $numExpedition);

        if($query->first()->etape == 4){
            return response()->json('302');
        }

        if($query->first()->etape != 15){
            $query->whereIn('expeditions.agence', $user::getUserVilles_api($user));

        }else{
            $Comment = Commentaire::where('id_expedition',strval($query->first()->id))->where('code', 'TRANSIT')->first();

            $transit_by = User::where('id', $Comment->id_utilisateur)->first();

            $ville_transit = $transit_by->EmployeDetail->agence;

            if(!in_array($ville_transit, $user::getUserVilles_api($user))){
                return response()->json('301');
            }
        }


            return response()->json($query->first());
    }


    public function expeditionDetail_ramassage(Request $request)
    {
        $data = json_decode($request->getContent());
        $numExpedition = $data->{"numExpedition"};
        $userId = $data->{"userId"};
        $user = User::find($userId);
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
                \DB::raw('expeditions.agence as agence_id'),
                \DB::raw('expeditions.created_at as created_at'),
                \DB::raw('expeditions.created_by as created_by'),
                \DB::raw('expeditions.deleted_at as deleted_at'),
                \DB::raw('bons.date_validation as bons_date_validation'),
                \DB::raw('clients.libelle as client'),
                \DB::raw('users.name as created_by_name'),
                \DB::raw('users.role as created_by_role'),
                \DB::raw('agences_des.libelle as destination'),
                \DB::raw('agences_exp.libelle as agence'),
                \DB::raw('statuts.value as statut_label')
            )
            ->leftJoin('bons', 'bons.id', '=', 'expeditions.id_bon')
            ->leftJoin('users', 'users.id', '=', 'expeditions.created_by')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
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
        $query->where('expeditions.num_expedition', $numExpedition);
        $query->whereIn('expeditions.agence', $user::getUserVilles_api($user));
            return response()->json($query->first());
    }

    public function chargements(Request $request)
    {

        $data = json_decode($request->getContent());
        $expeditionsIds = [];
        $expeditions = $data->{"expeditions"};
        for ($i = 0; $i < count($expeditions); $i++) {
            $expeditionsIds[] = $expeditions[$i]->{"id"};
        }
        $userId = $data->{"userId"};
        $transporteur = $data->{"transporteur"};

        $expeditions = Expedition::all()->whereIn('id', $expeditionsIds)->groupBy('agence_des');

        foreach ($expeditions as $agence_id => $expedition) {

            $bon = new Bon();
            $bon->code = 'F' . sprintf("%06d", Bon::all()->count() + 1);
            $bon->id_transporteur = $transporteur;
            $bon->id_agence_dest = $agence_id;
            $bon->id_agence_exp = $expedition[0]->agence;
            $bon->type = 'FCHARGE';
            $bon->save();
            $response = '';
            foreach ($expedition as $exp) {
                //changement du statut de l'expedition deja ramasse et l'ajoute dans le processus chargement

                if ($exp->etape == 6) {
                    $processus = Processus_expedition::where('id_expedition', $exp->id)->where('code', 'CHARGEMENT')->latest()->first();
                    $processus->update([
                        'id_feuille_charge' => $bon->id,
                        'id_transporteur' => $transporteur,
                        'retour' => $exp->id,
                        'date_validation' =>  date('Y-m-d H:i:s'),
                    ]);
                } elseif ($exp->etape == 9) {
                    $processus = Processus_expedition::where('id_expedition', $exp->id)->where('code', 'CHARGEMENT')->latest()->first();
                    $processus->update([
                        'id_feuille_charge' => $bon->id,
                        'id_transporteur' => $transporteur,
                        'transfert' => $exp->id,
                        'date_validation' =>  date('Y-m-d H:i:s'),
                    ]);
                } elseif ($exp->etape == 15) {
                    $processus = Processus_expedition::where('id_expedition', $exp->id)->where('code', 'CHARGEMENT')->latest()->first();
                    $processus->update([
                        'id_feuille_charge' => $bon->id,
                        'id_transporteur' => $transporteur,
                        'transit' => $exp->id,
                        'date_validation' =>  date('Y-m-d H:i:s'),
                    ]);
                } else {
                    $processus = Processus_expedition::where('id_expedition', $exp->id)->where('code', 'CHARGEMENT');
                    $processus->update([
                        'id_feuille_charge' => $bon->id,
                        'id_transporteur' => $transporteur,
                        'date_validation' =>  date('Y-m-d H:i:s'),
                    ]);
                }


                $exp->etape = '4';
                $exp->save();

                if ($exp->sens == 'Envoi') {
                    $message = "Votre expédition " . $exp->num_expedition . " a été chargée, Vous pouvez toujours voir les mis à jour de l'expedition sur le lien : " . url('/search?&search_exp=' . $exp->num_expedition) . " ";

                    $response = notificationWhatsapp::whatsappMessage($exp->telephone, $message);

                }


                // creation des historique de l'etape
                $etapeHistory = new etapeHistory();
                $etapeHistory->expedition = $exp->id;
                $etapeHistory->agence = $exp->agence;
                $etapeHistory->agence_des = $exp->agence_des;
                $etapeHistory->fond = $exp->fond;
                $etapeHistory->libelle = $exp->ClientDetail->libelle;
                $etapeHistory->colis = $exp->colis;
                $etapeHistory->etape = 4;
                $etapeHistory->num_expedition = $exp->num_expedition;
                $etapeHistory->id_chargement = $bon->id;
                $etapeHistory->save();

                $commentaire = new Commentaire();
                $commentaire->code = "CHARGEMENT";
                $commentaire->commentaires = "VALIDATION CHARGEMENT";
                $commentaire->id_expedition = $exp->id;
                $commentaire->source = 2;
                $commentaire->bon = $bon->id;
                $commentaire->id_utilisateur = $userId;
                $commentaire->save();

            }
        }

        return response()->json($response);
    }

    public function affectations(Request $request)
    {

        $data = json_decode($request->getContent());
        $expeditionsIds = [];
        $expeditions = $data->{"expeditions"};
        for ($i = 0; $i < count($expeditions); $i++) {
            $expeditionsIds[] = $expeditions[$i]->{"id"};
        }
        $userId = $data->{"userId"};
        $employe = $data->{"livreur"};

        $bon = new Bonliv();
        $bon->code = "L" . sprintf("%05d", Bonliv::all()->count() + 1);
        $bon->livreur = $employe;
        $livreur = Employe::find($employe);
        $bon->id_agence = $livreur->agence ?? 0;
        $bon->type = 1;
        $bon->save();

        if (count($expeditionsIds) > 0) {
            $bon->relatedColis()->sync(Expedition::find($expeditionsIds));
            $expeditionRecords = Expedition::whereIn('id', $expeditionsIds)->get();
            Expedition::whereIn('id', $expeditionsIds)->update(['bl' => $bon->id, 'etape' => 16]);
            foreach ($expeditionRecords as $expedition) {
                $commentaire = new Commentaire();
                $commentaire->code = "AFFECTATION";
                $commentaire->commentaires = "Affectation au livreur " . $bon->employeDetail->libelle;
                $commentaire->id_expedition = $expedition->id;
                $commentaire->source = 2;
                $commentaire->bon = $bon->id;
                $commentaire->id_utilisateur = $userId;
                $commentaire->save();
            }
            foreach ($expeditionRecords as $expedition) {
                $etapeHistory = new etapeHistory();
                $etapeHistory->expedition = $expedition->id;
                $etapeHistory->bl_id = $bon->id;
                $etapeHistory->agence  = $expedition->agence;
                $etapeHistory->agence_des  = $expedition->agence_des;
                $etapeHistory->fond  = $expedition->fond;
                $etapeHistory->save();
            }
        } else {
            $bon->relatedColis()->sync([]);
        }
        return response()->json(['code' => 200]);
    }

    public function arrivages(Request $request)
    {

        $data = json_decode($request->getContent());
        $expeditionsIds = [];
        $expeditions = $data->{"expeditions"};
        for ($i = 0; $i < count($expeditions); $i++) {
            $expeditionsIds[] = $expeditions[$i]->{"id"};
        }
        $userId = $data->{"userId"};
        $user = User::find($userId);

        $expeditionRecords = Expedition::whereIn('id', $expeditionsIds)->get();
        $user_villes = $user::getUserVilles_api($user);
        foreach ($expeditionRecords as $expedition) {
            if(in_array($expedition->agence_des, $user_villes)){
                $expedition->etape = 10;
                $expedition->save();
                Processus_expedition::where('id_expedition', $expedition->id)->where('code', 'CHARGEMENT')->where('date_validation', null)->update([
                    'date_validation' => date('Y-m-d H:i:s')
                ]);

                $etapeHistory = new etapeHistory();
                $etapeHistory->expedition = $expedition->id;
                $etapeHistory->etape = 10;
                $etapeHistory->save();

                $commentaire = new Commentaire();
                $commentaire->code = "ARRIVAGE";
                $commentaire->commentaires = "VALIDATION ARRIVAGE";
                $commentaire->id_expedition = $expedition->id;
                $commentaire->source = 2;
                $commentaire->id_utilisateur = $userId;
                $commentaire->save();
            }else{
                $expedition->etape = 15;
                $expedition->save();
                Processus_expedition::where('id_expedition', $expedition->id)->where('code', 'CHARGEMENT')->where('date_validation', null)->update([
                    'date_validation' => date('Y-m-d H:i:s')
                ]);

                $etapeHistory = new etapeHistory();
                $etapeHistory->expedition = $expedition->id;
                $etapeHistory->etape = 15;
                $etapeHistory->save();

                $commentaire = new Commentaire();
                $commentaire->code = "TRANSIT";
                $commentaire->commentaires = "VALIDATION TRANSIT";
                $commentaire->id_expedition = $expedition->id;
                $commentaire->source = 2;
                $commentaire->id_utilisateur = $userId;
                $commentaire->save();
            }
        }

        return response()->json(['code' => 200]);
    }

    public function ramassages(Request $request)
    {

        $data = json_decode($request->getContent());
        $expeditionsIds = [];
        $expeditions = $data->{"expeditions"};
        $exp_err = '';
        for ($i = 0; $i < count($expeditions); $i++) {
            $expeditionsIds[] = $expeditions[$i]->{"id"};
        }
        $userId = $data->{"userId"};

        $expeditionRecords = Expedition::whereIn('id', $expeditionsIds)->get();

        foreach ($expeditionRecords as $expedition) {
            if ($expedition->etape == '1') {
                $expedition->etape = 2;
                $expedition->save();

                if ($expedition->id_bon == null) {

                    $count = Bon::all()->count();
                    $bon = bon::create([
                        'code' => MapiController::getcode('R', $count),
                        'id_client' => $expedition->clientDetail->id,
                        'type' => 'RAMASSAGE',
                        'date_validation' => date('Y-m-d H:i:s')

                    ]);

                    $expedition->id_bon = $bon->id;
                    $expedition->etape = 3;
                    if ($expedition->port == 'PP') {
                        $expedition->caissepp_emp = $userId;
                    }

                    $expedition->save();

                    $etapeHistory = new etapeHistory();
                    $etapeHistory->expedition = $expedition->id;
                    $etapeHistory->etape = 3;
                    $etapeHistory->save();

                    $bonId = $bon->id;
                } else {
                    $expedition->bonRamassageDetail->date_validation = date('Y-m-d H:i:s');
                    $expedition->bonRamassageDetail->save();
                    $etapeHistory = new etapeHistory();
                    $etapeHistory->expedition = $expedition->id;
                    $etapeHistory->etape = 2;
                    $etapeHistory->save();
                    $bonId = $expedition->id_bon;
                }

                $commentaire = new Commentaire();
                $commentaire->code = "RAMASSAGE";
                $commentaire->commentaires = 'VALIDATION RAMASSAGE';
                $commentaire->bon = $bonId;
                $commentaire->id_expedition = $expedition->id;
                $commentaire->source = 2;
                $commentaire->id_utilisateur = $userId;
                $commentaire->save();

                $ramassge = new Processus_expedition();
                $ramassge->code = 'RAMASSAGE';
                $ramassge->id_expedition = $expedition->id;
                $ramassge->id_bon_ramassage = $expedition->id_bon;
                $ramassge->date_validation = $expedition->bonRamassageDetail->date_validation;
                $ramassge->id_agence_dest = $expedition->agence_des;
                $ramassge->id_agence_exp = $expedition->agence_des;
                $ramassge->save();

                $chargement = new Processus_expedition();
                $chargement->code = 'CHARGEMENT';
                $chargement->id_expedition = $expedition->id;
                $chargement->id_agence_dest = $expedition->agence_des;
                $chargement->id_agence_exp = $expedition->agence;
                $chargement->save();

                $livraison = new Processus_expedition();
                $livraison->code = 'LIVRAISON';
                $livraison->id_expedition = $expedition->id;
                $livraison->id_agence_dest = $expedition->agence_des;
                $livraison->save();
            } else {
                $exp_err = $exp_err . $expedition->num_expedition . ', ';
            }
        }

        if ($exp_err != '') {
            return response()->json(['code' => 400, 'message' => 'Impossible de ramassé ses expéditions :<strong> ' . $exp_err . '</strong><br>  Vous ne pouvez pas scanner des colis qui ne sont pas a l\'étape saisie']);
        } else {
            return response()->json(['code' => 200]);
        }
    }

    public function demandeRamClient(Request $request)
    {

        $data = json_decode($request->getContent());
        $client = User::find($data->{"userId"})->ClientDetail;

        $seuilcoli = $client->seuil_colis;

        $countExp =  Expedition::where('client',  $client->id)->where('etape', '1')->where("deleted", 0)->where("id_bon", null)->count();
        //check if the client able to do Ramassage
        if ($seuilcoli <= $countExp) {
            if ($data->{"expedition"} != null) {
                $count = Bon::all()->count();
                $bon = bon::create([
                    'code' => MapiController::getcode('R', $count),
                    'id_client' => $client->id,
                    'type' => 'RAMASSAGE',
                    'id_agence_exp' => $client->agenceDetail->id
                ]);
            } else {
                return response()->json(['code' => 500]);
            }

            if (!empty($data->{"expedition"})) {
                foreach ($data->{"expedition"} as $exp) {
                    Expedition::where('id', $exp)->update([
                        'id_bon' => $bon->id,
                    ]);
                }
                return response()->json(['code' => 200]);
            }
        } else {
            return response()->json([
                'seuil' => $seuilcoli,
                'code' => 300

            ]);
        }
    }

    public function tracking(Request $request)
    {
    }

    public function getcode($letter, $object)
    {
        $code = $letter . sprintf("%06d", $object + 1);
        return $code;
    }

    public static function factures(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        $user = User::find($userId);
        return response()->json(Facture::where('client', $user->client)->where('remise', 1)->where('deleted', "0")->get()->toArray());
    }

    public static function reclamations(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        return response()->json(\App\Models\Reclamation::all()->where('deleted', "0")->where('user', $userId)->toArray());
    }

    public static function listRamassages(Request $request)
    {

        $data = json_decode($request->getContent());

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
                \DB::raw('agences_exp.libelle as agence'),
                \DB::raw('statuts.value as statut_label')
            )
            ->leftJoin('bons', 'bons.id', '=', 'expeditions.id_bon')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
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
        $query->where('expeditions.client', User::find($data->{"userId"})->client)->where('etape', '1')->where('id_bon', NULL);
        return response()->json($query->get()->toArray());
    }



    public static function exp_delete_client(Request $request)
    {

        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        $expedition = Expedition::where('id', $data->{"expId"})->first();

        $expedition->update(
            [
                'etape' => 5,
            ]
        );
        $commentaire = new Commentaire();
        $commentaire->code = "ANNULATION";
        $commentaire->commentaires = "ANNULATION PAR LE CLIENT";
        $commentaire->id_expedition = $expedition->id;
        $commentaire->source = 2;
        $commentaire->id_utilisateur = $userId;
        $commentaire->save();

        $etapeHistory = new etapeHistory();
        $etapeHistory->expedition = $expedition->id;
        $etapeHistory->etape = 5;
        $etapeHistory->save();


        return response()->json(['code' => 'Votre expedition N°' . $expedition->num_expedition . ' est bien annuler']);
    }



    public static function remboursements(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        $user = User::find($userId);



        return response()->json(RemboursementPaiements::where('client', $user->client)->get()->toArray());
    }

    public static function homeClient(Request $request)
    {


        $data = json_decode($request->getContent());

        $userId = $data->{"userId"};
        $client = User::find($data->{"userId"});



        //Retours de ce mois
        $Expedition_retours = Expedition::all()->where('deleted', "0")
            ->where('sens', 'Retour')
            ->where('client', $client->ClientDetail->id)
            // ->where('date_retour', '>=', Carbon::now()->subMonth(1)->format('Y-m-01'))
            // ->where('date_retour', '<=', Carbon::now()->format('Y-m-31'))
            ->count();
        //Réclamations en cours
        $Reclamation_encours = Reclamation::all()->where('deleted', "0")
            ->where('statut', 1)
            ->where('user', $client->ClientDetail->id)
            ->count();

        $viewsData['Reclamation_encours'] = $Reclamation_encours;

        // // Envois de ce mois

        $bons = Bon::where('deleted', "0")
            ->where('type', 'RAMASSAGE')
            ->where('id_client', $client->ClientDetail->id)
            // ->where('date_validation', '>=', Carbon::now()->format('Y-m-01'))
            // ->where('date_validation', '<=', Carbon::now()->format('Y-m-31'))
            ->Get();
        $epx_cemois = 0;
        if (isset($bons)) {
            foreach ($bons as $bon) {
                $epx_cemois = $epx_cemois + $bon->expeditionDetail->count();
            }
        }
        // $viewsData['epx_cemois'] = $epx_cemois;

        // // Colis en cours

        $expedition_encours = Expedition::where('deleted', "0")
            ->where('client', $client->ClientDetail->id)
            ->where('etape', '!=', 14)
            ->where('etape', '!=', 8)
            ->where('etape', '!=', 5)
            // ->where('date_validation', '>=', Carbon::now()->format('Y-m-01'))
            // ->where('date_validation', '<=', Carbon::now()->format('Y-m-31'))
            ->count();

        // $viewsData['expedition_encours'] = $expedition_encours;

        // Taux de retour du mois
        if (($epx_cemois + $Expedition_retours) == 0) {
            $taux = 0;
        } else {
            $taux = ($Expedition_retours / ($epx_cemois + $Expedition_retours)) * 100;
        }


        return response()->json([
            'expedition_retour' => $Expedition_retours,
            'Reclamation_encours' => $Reclamation_encours,
            'expedition_encours' => $expedition_encours,
            'epx_cemois'  => $epx_cemois,
            'tauxRetour'  => (int)$taux
        ]);
    }


    public static function reclamationsClient(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};

        $query = \DB::table("reclamations")
            ->select(
                "*",
                \DB::raw('reclamations.id as id'),
                \DB::raw('reclamations.code as code'),
                \DB::raw('reclamations.description as description'),
                \DB::raw('reclamations.user as user'),
                \DB::raw('reclamations.deleted as deleted'),
                \DB::raw('reclamations.cloture_par as cloture_par'),
                \DB::raw('reclamations.statut as statut'),
                \DB::raw('reclamations.created_at as created_at'),
                \DB::raw('reclamations.typereclamation as typereclamation'),
                \DB::raw('reclamations.cloture as cloture'),
                \DB::raw('reclamations.cloture_at as cloture_at'),
                \DB::raw('statuts.value as statut_label'),
                \DB::raw('users.name as cloture_name'),
                \DB::raw('users.first_name as cloture_fname'),
                \DB::raw('typereclamations.libelle as type_label'),

            )->leftJoin('users', 'users.id', '=', 'reclamations.cloture_par')
            ->leftJoin('typereclamations', 'typereclamations.id', '=', 'reclamations.typereclamation')
            ->leftJoin('statuts', function ($join) {
                $join->on('statuts.key', '=', 'reclamations.statut');
                $join->where('statuts.code', '=', 'RECLAMATION');
            });
        $query->where('reclamations.user', $userId)->where('reclamations.deleted', 0);

        return response()->json($query->get()->toArray());
    }

    public static function getreclamationTypes(Request $request)
    {
        return response()->json(\App\Models\Typereclamation::where('deleted', '0')->get()->toArray());
    }


    public static function createReclamation(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        $count = "R" . sprintf("%05d", Reclamation::all()->count() + 1);
        Reclamation::create(['description' => $data->{"description"}] + ['typereclamation' => $data->{"type"}] + ['user' => $userId] + ['statut' => 1] + ['code' => $count]);
        return response()->json(300);
    }

    public static function getReclaMessages(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        $reclaID = $data->{"reclaID"};
        $reclamation = Reclamation::where('id', $reclaID)->first();
        $reclamation->update([
            'read' => 0
        ]);
        $query = \DB::table("reclamations_suivi")
            ->select(
                "*",
                \DB::raw('reclamations_suivi.id as id'),
                \DB::raw('reclamations_suivi.description as description'),
                \DB::raw('reclamations_suivi.user as user'),
                \DB::raw('reclamations_suivi.created_at as created_at'),
                \DB::raw('reclamations_suivi.reclamation as reclamation'),
                \DB::raw('users.name as cloture_name'),
                \DB::raw('users.first_name as cloture_fname'),
                \DB::raw('users.role as user_role'),
            )->leftJoin('users', 'users.id', '=', 'reclamations_suivi.user');

        $query->where('reclamations_suivi.reclamation', $reclaID);


        return response()->json($query->get()->toArray());
    }


    public static function deleteReclamation(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        $reclaID = $data->{"reID"};
        $reclamation = Reclamation::where('id', $reclaID);
        $reclamation->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => $userId]);
        return response()->json(300);
    }

    public static function addMessage(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        $reclaID = $data->{"reID"};
        $message = $data->{"message"};

        \App\Models\ReclamationSuivi::create(
            ['description' => $message] +
                ['reclamation' => $reclaID] +
                ['user' => $userId]
        );

        \App\Models\ReclamationHistory::create(
            ['user' =>  $userId] +
                ['reclamation' => $reclaID] +
                ['statut' => 2] +
                ['motif' => '*Ajout d\'un message']
        );


        return response()->json(300);
    }

    public static function saisierExpedition(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        $type = $data->{"type"};
        $expeditions = $data->{"expedition"};
        $user = User::find($userId);


        $prix = Taxation::getPrixColis($user->ClientDetail->id, $expeditions->agence_Des, $user->ClientDetail->agence);

        // si il y a plus de colis dans une expeditions et la valeur declaree

        // if ($expeditions->colis > 1) {

        //     if ($expeditions->vDeclaree != '') {

        //         $ttc =  ($expeditions->colis * $prix) + (($user->ClientDetail->valeur_declaree / 100) * $expeditions->vDeclaree);
        //     } else {

        //         $ttc =  $expeditions->colis * $prix;
        //     }
        // } else {

        //     if ($expeditions->vDeclaree != '') {

        //         $ttc = $prix +  ($user->ClientDetail->valeur_declaree  / 100) * $expeditions->vDeclaree;
        //     } else {

        //         $ttc =  $prix;
        //     }
        // }



        if ($expeditions->colis > 1) {

            if ($expeditions->vDeclaree != '') {
                $ttc =  ($expeditions->colis * $prix) + (($user->ClientDetail->valeur_declaree / 100) * $expeditions->vDeclaree);
            } else {

                if (!empty($user->ClientDetail->vplafond) && $user->ClientDetail->vplafond <= $expeditions->fond) {
                    $ttc =  ($expeditions->colis * $prix) + (($user->ClientDetail->valeur_declaree / 100) * $expeditions->fond);
                } else {
                    $ttc =  $expeditions->colis * $prix;
                }
            }
        } else {
            if (isset($expeditions->vDeclaree) && $expeditions->vDeclaree != 0) {

                $ttc = $prix +  ($user->ClientDetail->valeur_declaree / 100) * $expeditions->vDeclaree;
            } else {

                if (!empty($user->ClientDetail->vplafond) && $user->ClientDetail->vplafond <= $expeditions->fond) {

                    $ttc =  $prix + (($user->ClientDetail->valeur_declaree / 100) * $expeditions->fond);
                } else {

                    $ttc =  $prix;
                }
            }
        }





        if ($ttc > $expeditions->fond && $type == 'ECOM' || $ttc > $expeditions->fond &&  $type == 'COLECH') {
            return response()->json([
                'prix' => 'less',

            ]);
        }

        if ($user->ClientDetail->factureMois == 'Oui') {
            $port = 'PPE';
        } elseif ($type == 'ECOM') {
            $port = 'PD';
        } elseif ($type == 'CDP') {
            if ($user->ClientDetail->colisSimple == 'Non') {
                return redirect()->back();
            } elseif ($user->ClientDetail->colisSimple == 'Oui') {
                if ($user->ClientDetail->ppSimple == 'PP') {
                    $port = 'PP';
                } elseif ($user->ClientDetail->ppSimple == 'PPNE') {
                    $port = 'PPNE';
                } else {
                    $port = 'PP';
                }
            }
        } elseif ($type == 'COLECH') {
            $port = 'PPNE';
        }

        if ($type == "ECOM") {
            $retour_fond = 'CR';
        } elseif ($type == "CDP") {
            $retour_fond = 'S';
        } elseif ($type == "COLECH") {
            $retour_fond = 'E';
        }

        if ($data->{"paiementCheque"} == true) {
            $paiementCheque = "Oui";
        } else {
            $paiementCheque = "Non";
        }

        if ($data->{"ouvertureColis"} == true) {
            $ouvertureColis = "Oui";
        } else {
            $ouvertureColis = "Non";
        }

        $count = Expedition::all()->count();

        $expeditions = $data->{"expedition"};
        $expedition = new expedition();
        $expedition->destinataire = $expeditions->destinataire;
        $expedition->adresse_destinataire = $expeditions->adresse_destinataire;
        $expedition->agence_des = $expeditions->agence_Des;
        $expedition->telephone = $expeditions->telephone;
        $expedition->ttc = $ttc;
        $expedition->fond = $expeditions->fond;
        $expedition->sens = 'Envoi';
        $expedition->vDeclaree = $expeditions->vDeclaree;
        $expedition->colis = $expeditions->colis;
        $expedition->agence = $user->ClientDetail->agence;
        $expedition->origine = $user->ClientDetail->agence;
        $expedition->des = $expeditions->agence_Des;
        $expedition->type = $type;
        $expedition->etape = '1';
        $expedition->client = $user->ClientDetail->id;
        $expedition->port = $port;
        $expedition->Indication = $expeditions->indication;
        $expedition->num_expedition = 'EX' . sprintf("%06d", $count + 2);
        $expedition->retour_fond = $retour_fond;
        $expedition->paiementCheque = $paiementCheque;
        $expedition->ouvertureColis = $ouvertureColis;
        $expedition->save();

        $etapeHistory = new etapeHistory();
        $etapeHistory->expedition = $expedition->id;
        $etapeHistory->etape = 1;
        $etapeHistory->save();

        $commentaire = new Commentaire();
        $commentaire->code = "TAXATION";
        $commentaire->id_expedition = $expedition->id;
        $commentaire->source = 2;
        $commentaire->id_utilisateur = $userId;
        $commentaire->save();
        return response()->json([
            'rr' => 'rr',

        ]);
    }

    public static function userExPermession(Request $request)
    {
        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        $user = User::find($userId);
        // $permession = 'this is';
        $permession = 0;
        $colisEnchange = 0;
        if ($user->ClientDetail->factureMois == 'Oui') {
            $permession = 1;
        } elseif ($user->ClientDetail->colisSimple == 'Oui') {
            $permession = 2;
        }

        if ($user->ClientDetail->colisEchange == 'Oui') {
            $colisEnchange = 1;
        }
        return response()->json([
            'permission' => $permession,
            'colisEnchange' => $colisEnchange
        ]);
    }

    public static function getExpeditionLivree(Request $request)
    {

        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};
        $user = User::find($userId);
        return response()->json(Expedition::where('client', $user->ClientDetail->id)->where('etape', '14')->where('deleted', '0')->get()->toArray());
    }

    public static function reclamationClientNotification(Request $request)
    {

        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};

        $reclamation = Reclamation::where('statut', '1')
            ->where('deleted', '0')
            ->where('read', 1)
            ->where('user', $userId)
            ->get();
        return json_encode(['newRec' => count($reclamation)]);
    }

    public static function getReclamationClient(Request $request)
    {

        $data = json_decode($request->getContent());
        $userId = $data->{"userId"};

        $reclamation = Reclamation::where('statut', '1')
            ->where('deleted', '0')
            ->where('read', 1)
            ->where('user', $userId)
            ->get();
        return json_encode($reclamation);
    }
}
