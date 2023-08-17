<?php

namespace App\Http\Controllers;

use Mail;
use App\User;
use DataTables;
use Notification;
use Carbon\Carbon;
use App\Models\Ville;
use App\Models\Agence;
use App\Models\Bonliv;
use App\Models\Caisse;
use App\Models\Client;
use App\Models\Statut;
use App\Models\Employe;
use App\Models\Taxation;
use App\Models\Expedition;
use App\Models\Commentaire;
use Illuminate\Support\Str;
use App\Models\etapeHistory;
use Illuminate\Http\Request;
use App\Models\ExpeditionImages;
use App\Models\ImportExpedition;
use App\Models\Types_commentaire;
use App\Models\CaissesExpeditions;
use Illuminate\Routing\Controller;
use App\Models\notificationWhatsapp;
use App\Models\Processus_expedition;
use Illuminate\Support\Facades\Auth;
use App\Models\CommissionExpeditions;
use Symfony\Component\Process\Process;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Chargement_Masse_history;
use App\Models\Groupstatuts;
use Illuminate\Support\Facades\Redirect;

use App\Notifications\noLivreeExpedition;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ExpeditionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function getRules()
    {
        return [];
    }

    public function detail(Request $request, Expedition $expedition)
    {
        ini_set('memory_limit', -1);
        $viewsData['record'] = $expedition;
        // $viewsData['ramassageRecords'] = \DB::table('processus_expeditions')
        //     ->select(
        //         '*',
        //         \DB::raw('processus_expeditions.id as id'),
        //         \DB::raw('bon.id as bon_id'),
        //         \DB::raw('bon.code as bon_code'),
        //     )
        //     ->where('processus_expeditions.code', 'RAMASSAGE')->where('processus_expeditions.id_expedition', $expedition->id)
        //     ->leftJoin('bons as bon', 'bon.id', '=', 'processus_expeditions.id_bon_ramassage')
        //     ->get();
        // $viewsData['chargementRecords'] = \DB::table('processus_expeditions')
        //     ->where('code', 'CHARGEMENT')
        //     ->where('id_expedition', $expedition->id)
        //     ->where('id_feuille_charge', '!=', null)
        //     ->get();
        // $viewsData['livraisonRecords'] = \DB::table('processus_expeditions')
        // ->where('code', 'LIVRAISON')
        // ->where('id_expedition', $expedition->id)
        // ->first();
        $viewsData['commentRecords'] = \DB::table('commentaires')
            ->select(
                '*',
                \DB::raw('commentaires.id as id'),
                \DB::raw('commentaires.code as code'),
                \DB::raw('commentaires.bon as bon'),
                \DB::raw('commentaires.created_at as created_at'),
                \DB::raw('bonliv.id as bon_id'),
                \DB::raw('bonliv.code as bon_code'),
                \DB::raw('bon.code as fc_code'),
            )
            ->where('commentaires.id_expedition', $expedition->id)
            ->leftJoin('users', 'users.id', '=', 'commentaires.id_utilisateur')
            ->leftJoin('bonlivs as bonliv', 'bonliv.id', '=', 'commentaires.bon')
            ->leftJoin('bons as bon', 'bon.id', '=', 'commentaires.bon')
            ->get();




        if ($request->isMethod('post')) {
            if ($request->has('submit')) {
                $rules = [];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $commentaire = new Commentaire();
                    $commentaire->code = "COMMENTAIRE_NORMAL";
                    $commentaire->commentaires = $request->input('insert_comment');
                    $commentaire->id_expedition = $expedition->id;
                    $commentaire->id_utilisateur = auth()->user()->id;
                    if (isset($request->file)) {
                        $fileName = time() . '.' . $request->file->extension();
                        $request->file->move(public_path('uploads/commentaire'), $fileName);
                        $commentaire->justif_path = $fileName;
                    }
                    $commentaire->save();
                    Redirect::to(route('expedition_detail', $expedition->id))->send();
                }
            } elseif ($request->has('annuler')) {
                $rules = [];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $expedition->update(
                        [
                            'etape' => 5,
                        ]
                    );
                    $commentaire = new Commentaire();
                    $commentaire->code = "ANNULATION";
                    $commentaire->commentaires = $request->input('insert_comment');
                    $commentaire->id_expedition = $expedition->id;
                    $commentaire->id_utilisateur = auth()->user()->id;
                    if (isset($request->file)) {
                        $fileName = time() . '.' . $request->file->extension();
                        $request->file->move(public_path('uploads/commentaire'), $fileName);
                        $commentaire->justif_path = $fileName;
                    }
                    $commentaire->save();
                    Redirect::to(route('expedition_detail', $expedition->id))->with('success', "L'expedition a été annulée avec succès")->send();
                }
            } elseif ($request->has('annuler_etape')) {

                $idExp = $expedition->id;
                $oldEtape = $expedition->etape;

                switch ($expedition->etape) {


                    case "2":
                        // En cas de ramassage


                        $processus =  Processus_expedition::where('id_expedition', $expedition->id)->get();
                        foreach ($processus as $proc) {
                            $proc->delete();
                        }
                        $expedition->update([
                            'etape' => 1,
                            'id_bon' => NULL,
                        ]);
                        Expedition::addEtageSign($expedition->id);
                        ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);

                        break;
                    case "3":
                        // En cas de réception
                        $expedition->update([
                            'etape' => 1,
                            'id_bon' => NULL,
                        ]);
                        $processus =  Processus_expedition::where('id_expedition', $expedition->id)->get();
                        foreach ($processus as $proc) {
                            $proc->delete();
                        }
                        Expedition::addEtageSign($expedition->id);
                        ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);
                        break;
                    case "4":

                        // En cas de chargement
                        $beforeLastExpe = Expedition::beforeLastEtape($expedition->id);
                        //   dd( $beforeLastExpe);


                        // En cas de chargement des expedition retour
                        if ($beforeLastExpe == 6) {
                            $processus = Processus_expedition::where('id_expedition', $expedition->id)->where('code', 'CHARGEMENT')->latest()->first();
                            $processus->update([
                                'id_feuille_charge' => NULL,
                                'id_transporteur' => NULL,
                                'retour' => NULL,
                                'date_validation' =>  NULL,
                            ]);
                            $expedition->update([
                                'etape' => $beforeLastExpe
                            ]);
                            Expedition::addEtageSign($expedition->id);
                            ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);

                            break;
                            // En cas de chargement des expedition tranferee
                        } elseif ($beforeLastExpe  == 9) {

                            $processus = Processus_expedition::where('id_expedition', $expedition->id)->where('code', 'CHARGEMENT')->latest()->first();
                            $processus->update([
                                'id_feuille_charge' => NULL,
                                'id_transporteur' => NULL,
                                'transfert' => NULL,
                                'date_validation' =>  NULL,

                            ]);
                            Expedition::addEtageSign($expedition->id);
                            $expedition->update([
                                'etape' => $beforeLastExpe,

                            ]);

                            ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);
                            break;
                            //En cas de chargement des des expedition transit
                        } elseif ($beforeLastExpe == 15) {
                            $processus = Processus_expedition::where('id_expedition', $expedition->id)->where('code', 'CHARGEMENT')->latest()->first();
                            $processus->update([
                                'id_feuille_charge' => NULL,
                                'id_transporteur' => NULL,
                                'transit' => NULL,
                                'date_validation' =>  NULL,

                            ]);
                            $expedition->update([
                                'etape' => $beforeLastExpe
                            ]);
                            Expedition::addEtageSign($expedition->id);
                            ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);
                            break;
                        } else {
                            $processus = Processus_expedition::where('id_expedition', $expedition->id)->where('code', 'CHARGEMENT');
                            $processus->update([
                                'id_feuille_charge' => NULL,
                                'id_transporteur' => NULL,
                                'date_validation' =>  NULL,

                            ]);
                            $expedition->update([
                                'etape' => $beforeLastExpe
                            ]);
                            Expedition::addEtageSign($expedition->id);
                            ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);
                            break;
                        }

                    case "10":
                        // En cas de arrivage

                        $beforeLastExpe = Expedition::beforeLastEtape($expedition->id);
                        if ($beforeLastExpe == 13) {
                            $colisPerduInfo = etapeHistory::where('expedition', $expedition->id)->latest()->first();
                            $expedition->update([
                                'etape' => $beforeLastExpe,
                                'agence' => $colisPerduInfo->agence,
                                'date_trouve' => null
                            ]);
                            Expedition::addEtageSign($expedition->id);
                            ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);
                            break;
                        } else {
                            $expedition->update([
                                'etape' => $beforeLastExpe,
                            ]);
                            $processus = Processus_expedition::where('id_expedition', $expedition->id)->where('code', 'CHARGEMENT')->latest()->first();
                            $processus->date_reception = null;
                            $processus->save();
                            Expedition::addEtageSign($expedition->id);
                            ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);
                            break;
                        }

                        //En cas de transfert
                    case "9":
                        $beforeLastExpe = Expedition::beforeLastEtape($expedition->id);
                        $oldAgence = etapeHistory::where('expedition', $expedition->id)->where('transfert_agence', '!=', null)->first();

                        $expedition->update([
                            'etape' => $beforeLastExpe,
                            'agence' => $oldAgence->transfert_agence,
                            'agence_des' => $expedition->agence,
                            'date_transfert' => null
                        ]);
                        Expedition::addEtageSign($expedition->id);
                        ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);
                        break;

                        //En cas de retour
                    case "6":
                        $beforeLastExpe = Expedition::beforeLastEtape($expedition->id);
                        $retourInfo = etapeHistory::where('expedition', $expedition->id)->latest()->first();
                        $expedition->update([
                            'etape' => $beforeLastExpe,
                            'agence_des' => $retourInfo->agence_des,
                            'agence' => $retourInfo->agence,
                            'date_transfert' => null,
                            'fond' => $retourInfo->fond,
                            'ttc' => $retourInfo->ttc,
                            'port' => $retourInfo->port,
                            'sens' => 'Envoi',
                            'retour_fond' =>  $retourInfo->retour_fond,
                            'date_retour' => null,
                        ]);
                        Expedition::addEtageSign($expedition->id);
                        ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);
                        break;
                        //En cas de colis perdu
                    case "13":
                        $beforeLastExpe = Expedition::beforeLastEtape($expedition->id);
                        $processus = Processus_expedition::where('id_expedition', $expedition->id)->where('code', 'CHARGEMENT')->latest()->first();
                        $processus->date_reception = null;
                        $processus->save();
                        $expedition->update([
                            'etape' => $beforeLastExpe,
                        ]);
                        Expedition::addEtageSign($expedition->id);
                        ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);
                        break;

                        //En cas de colis retrouver
                    case "15":
                        $beforeLastExpe = Expedition::beforeLastEtape($expedition->id);
                        $colisPerduInfo = etapeHistory::where('expedition', $expedition->id)->latest()->first();
                        $expedition->update([
                            'etape' => $beforeLastExpe,
                            'agence' => $colisPerduInfo->agence,
                            'date_trouve' => null
                        ]);
                        Expedition::addEtageSign($expedition->id);
                        ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);
                        break;

                        //En cas de colis livre
                    case "14":

                        Processus_expedition::where('code', 'LIVRAISON')->where('id_expedition', $expedition->id)->latest()->update([
                            'date_validation' => null
                        ]);
                        // $colisLivre = etapeHistory::where('expedition', $expedition->id)->latest()->first();
                        $expedition->update([
                            'etape' => '10',
                        ]);
                        $expedition->Caisse->delete();

                        Expedition::addEtageSign($expedition->id);
                        ExpeditionController::commentaireAnulation($idExp, $oldEtape, $expedition->etape);
                        break;

                    default:
                        echo "non";
                };
                Redirect::to(route('expedition_detail', $expedition->id))->with('success', "L'expedition a été annulée avec succès")->send();
            }
        }


        return view('back/expedition/detail', $viewsData);
    }

    public function list(Request $request)
    {
        $user_role = auth()->user()->role;
        if ($user_role != '3') {


            $request->flash();
            $viewsData = [];
            $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
            $viewsData['GroupStatutsRecords'] = \App\Models\Groupstatuts::all()->where('deleted', '0');
            $viewsData['agenceRecords'] = \App\Models\Ville::all()->where('deleted', '0')->where('id', "!=", 2);
            $viewsData['statutRecords'] = \App\Models\Statut::all()->where('deleted', '0')->where('code', 'EXPEDITION');
            $viewsData['commentRecords'] = \App\Models\Statut::all()->where('deleted', '0')
                ->where('code', 'ETAPE_EXPEDITION');
            // ->whereIn('key', ['1', '2', '3', '4', '5', '7', '8', '10']);

            return view('back/expedition/list', $viewsData);
        } elseif ($user_role == '3') {
            if ($request->isMethod('post')) {
                $request->flash();
                $star = Carbon::parse($request->start_date)->format("Y-m-d 00:00:00");
                $end = Carbon::parse($request->end_date)->format("Y-m-d 23:59:59");

                $records = Expedition::where('deleted', "0")
                    ->where('client', auth()->user()->ClientDetail->id)
                    ->get();
                if ($request->start_date != null) {
                    $records = $records->where("created_at", '>=', $star);
                }
                if ($request->end_date != null) {
                    $records = $records->where("created_at", '<=', $end);
                }
                if ($request->Destination != null) {
                    $records = $records->where("agence_des", $request->Destination);
                }
                if ($request->code != null) {
                    $records = $records->where("num_expedition", $request->code);
                };
                if ($request->statuts != null) {
                    $records = $records->where("etape", $request->statuts);
                };
                $agences = Ville::all()->where('deleted', '0')->where('id', "!=", 2);
                $statuts = Statut::where('code', 'ETAPE_EXPEDITION')->whereNotIn('key', [13, 15, 17, 18])->get();
                return view('client/expedition/list', [
                    'records' => $records,
                    'agences' => $agences,
                    'statuts' => $statuts
                ]);
            }
            $request->flash();

            $records = Expedition::all()->where('deleted', "0")->where('client', auth()->user()->ClientDetail->id);
            $agences = Ville::all()->where('deleted', '0')->where('id', "!=", 2);
            $statuts = Statut::where('code', 'ETAPE_EXPEDITION')->whereNotIn('key', [13, 15, 17, 18])->get();
            return view('client/expedition/list', [
                'records' => $records,
                'agences' => $agences,
                'statuts' => $statuts
            ]);
        }
    }

    public function api(Request $request)
    {

        $formData = array();
        parse_str($request->all()['form'], $formData);


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
        if (isset($formData['end_date']) && strlen(trim(($formData['end_date']))) > 0) {
            $query->whereDate("expeditions.created_at", '<=', $formData['end_date']);
        }
        if (isset($formData['etapes']) && $formData['etapes'] != '0') {
            $query->whereIn('etape', $formData['etapes']);
        }
        if (isset($formData['gEtapes']) && $formData['gEtapes'] != '0') {


            $gst = Groupstatuts::where('id',  $formData['gEtapes'])->first()->relatedStatuts()->allRelatedIds()->toArray();
            $etapes = Statut::whereIn('id', $gst)->pluck('key')->toArray();


            $query->whereIn('etape', $etapes);
        }
        if (isset($formData['agence']) && $formData['agence'] != '0') {
            $query->where('expeditions.agence', '=', $formData['agence']);
        }
        if (isset($formData['n_colis']) && strlen(trim(($formData['n_colis']))) > 0) {
            $query->where('num_expedition', '=', $formData['n_colis']);
        }


        if (auth()->user()->role == '2') {
            $bonlivs =  \DB::table('bonlivs')->get()->where('livreur',  auth()->user()->EmployeDetail->id);
            $bons_array = array();
            foreach ($bonlivs as $bon) {
                array_push($bons_array, array(
                    $bon->id
                ));
            }
            $query->whereIn('expeditions.bl', $bons_array);
        }
        if (auth()->user()->role == '6') {
            $clients =  \DB::table('clients')->get()->where('commerciale',  auth()->user()->EmployeDetail->id);
            $clients_array = array();
            foreach ($clients as $client) {
                array_push($clients_array, array(
                    $client->id
                ));
            }
            $query->whereIn('expeditions.client', $clients_array);
        }

        if (auth()->user()->role == '5' || auth()->user()->role == '7' || auth()->user()->role == '8') {
            $query->whereIn('expeditions.agence', \Auth::user()::getUserVilles());
        }








        return Datatables::of($query)->addIndexColumn()
            ->addColumn('typeicon', function ($record) {
                $icon = '';
                if (!empty($record->created_by)) {
                    if ($record->created_by_role == '3') {
                        $icon = '<i class="red-text material-icons" title="Saisie par client ' . $record->created_by_name . '">person</i>';
                    } else {
                        $icon = '<i class="blue-text material-icons" title="Saisie par ' . $record->created_by_name . ' ">settings</i>';
                    };
                }

                $colis_deja_enchange = \DB::table("expeditions")->where('echange_id', $record->id)->pluck('num_expedition')->first();
                $icon_ech ='';
                if($colis_deja_enchange){
                    $icon_ech = '<i class=" material-icons" title="Colis à été échangé contre ' . $colis_deja_enchange . '" style="color: #000000 ">compare_arrows</i>';
                }

                if ($record->type == 'CDP')
                    return '<i class="blue-text material-icons" title="Contre document">email</i>' . $icon . $icon_ech;
                elseif ($record->type == 'ECOM')
                    return '<i class="red-text material-icons" title="Contre espèce">inbox</i>' . $icon . $icon_ech;
                elseif ($record->type == 'COLECH')
                    $num_echange = \DB::table("expeditions")->where('id', $record->echange_id)->pluck('num_expedition')->first();
                return '<i class=" material-icons" title="Colis en échange de ' . $num_echange . '" style="color: #d8a71d ">autorenew</i>' . $icon;
                return '';
            })
            ->addColumn('created_at', function ($record) {
                return $record->created_at;
            })
            ->addColumn('num_expedition', function ($record) {
                // return '<span class="badge tooltipped gradient-45deg-blue-grey-blue"  data-position="bottom" data-tooltip=" Saisie le : '. $record->created_at . '<br/>  Ramasser le : ' . $record->bons_date_validation . '"> ' . $record->num_expedition . ' </span>';

                return '<span class="badge gradient-45deg-blue-grey-blue"> ' . $record->num_expedition . ' </span> <p style="font-size:80%;"> <span> Saisie le : </span> ' . $record->created_at . ' </p> <p style="font-size:80%;"> Ramasser le : ' . $record->bons_date_validation . ' </p>';
            })
            ->addColumn('destinataire', function ($record) {
                return  $record->destinataire . '<p> <span class=" badge grey" data-badge-caption="' . $record->destination . '"> </span> </p> <p> ' . $record->telephone . ' </p>';
            })
            ->addColumn('sense', function ($record) {
                return $record->sens;
            })
            ->addColumn('nature', function ($record) {
                if ($record->type == 'ECOM') {
                    return 'C. espèce';
                } elseif ($record->type == 'CDP') {
                    return 'Simple';
                } elseif ($record->type == 'COLECH') {
                    return 'Échange';
                }
            })

            ->addColumn('statut_label', function ($record) {
                $statut = Statut::where('code', 'ETAPE_EXPEDITION')->where('key', $record->etape)->first();
                return  '<p class="' . $statut->StatutColor() . '-text" style="font-weight:900;text-align:center;">' . $record->value . '</p>';
            })

            ->addColumn('client', function ($record) {
                return  $record->client . '<p> <span class=" badge grey" data-badge-caption="' . $record->agence . '"> </span> </p>';
            })


            ->addColumn('action', function ($record) {
                if (auth()->user()->role == '7' || auth()->user()->role == '8') {
                    return '
                    <a href="' . route('expedition_update', ['expedition' => $record->id]) . '"><i class="material-icons tooltipped" data-position="top" data-tooltip="Modifier">edit</i></a>
                    <a href="' . route('expedition_pdf', $record->id) . '" target="_blank"><i class="material-icons tooltipped" style="color: #bdbdbd;" data-position="top" data-tooltip="Imprimer">print</i></a>
                    <a href="#!" onclick="Detailsmodal(' . $record->id . ')"><i class="material-icons tooltipped" style="color: #01579b;" data-position="top" data-tooltip="Detail">comment</i></a>
        ';
                }
                else if (auth()->user()->role == '2' || auth()->user()->role == '5' || auth()->user()->role == '6') {
                    return '
                    <a href="' . route('expedition_pdf', $record->id) . '" target="_blank"><i class="material-icons tooltipped" style="color: #bdbdbd;" data-position="top" data-tooltip="Imprimer">print</i></a>
                    <a href="#!" onclick="Detailsmodal(' . $record->id . ')"><i class="material-icons tooltipped" style="color: #01579b;" data-position="top" data-tooltip="Detail">comment</i></a>
        ';
                }
                else {


                    return '
                                                    <a href="' . route('expedition_update', ['expedition' => $record->id]) . '"><i class="material-icons tooltipped" data-position="top" data-tooltip="Modifier">edit</i></a>
                                                    <a href="#!" onclick="openSuppModal(' . $record->id . ')"><i class="material-icons tooltipped" style="color: #c10027;" data-position="top" data-tooltip="Supprimer">delete</i></a>
                                                    <a href="' . route('expedition_pdf', $record->id) . '" target="_blank"><i class="material-icons tooltipped" style="color: #bdbdbd;" data-position="top" data-tooltip="Imprimer">print</i></a>
                                                    <a href="#!" onclick="Detailsmodal(' . $record->id . ')"><i class="material-icons tooltipped" style="color: #01579b;" data-position="top" data-tooltip="Detail">comment</i></a>
                                                    <a href="#!" onclick="Slidemodal(' . $record->id . ')"><i class="material-icons tooltipped" style="color: green;" data-position="top" data-tooltip="Piece Joindre">burst_mode</i></a>
                                        ';
                }
            })
            ->rawColumns(['client', 'statut_label', 'created_at', 'action', 'typeicon', 'num_expedition', 'destinataire'])
            ->make(true);
    }

    public function livraison(Request $request)
    {
        //dd(Expedition::getExpeditionsEnLivraison());
        $request->flash();

        return view('back/expedition/livraison', [
            'records' => Expedition::getExpeditionsEnLivraison(),
            'employes' => Employe::all()->where('deleted', '0')->where('statut', 1),
            'commentaires' => Types_commentaire::getCommentaires('LIVREUR')
        ]);
    }

    public function slider(Expedition $expedition)
    {
        $viewsData['record'] = $expedition;
        $viewsData['images'] = ExpeditionImages::all()->where('expedition_id', $expedition->id);

        return view('back/expedition/slider', $viewsData);
    }

    public function changeStatus(Expedition $expedition, $status, $comment, Request $request)
    {


        if ($status == 14) {
            $caisse = Caisse::getOpenedCaisseByExpedition($expedition);
            //dd($expedition, $status, $comment);
            $record = new CaissesExpeditions();
            $record->date_creation = date('Y-m-d H:i:s');
            $record->montant = $expedition->fond;
            $record->statut = 1;
            $record->type = $expedition->port;
            $record->id_agence = $expedition->agence;
            $record->id_caissier = \Auth::user()->id;
            $record->id_expedition = $expedition->id;
            $record->id_livreur = $expedition->bonLivsDetail->livreur ?? 0;
            $record->sens = $expedition->sens;
            /*$record->date_reception = $caisse->id ;
            $record->date_remise = $caisse->id ;
            $record->id_bordereau = $caisse->id ;
            $record->date_affectation = $caisse->id ;
            $record->id_mode_reglement = $caisse->id ;*/
            $record->id_utilisateur = \Auth::user()->id;
            $record->id_caisse = $caisse->id;
            $record->save();

            $expedition->caisse_id = $caisse->id;

            $commentaire = new Commentaire();
            $commentaire->code = "ENCAISSEMENT_LIVREUR";
            $commentaire->commentaires = "colis livré";
            $commentaire->id_expedition = $expedition->id;
            $commentaire->id_utilisateur = auth()->user()->id;
            $commentaire->save();

            $etapeHistory = new etapeHistory();
            $etapeHistory->expedition =  $expedition->id;
            $etapeHistory->etape = $status;
            $etapeHistory->save();

            $expeditionHes = etapeHistory::where('expedition', $expedition->id)->where('bl_id', $expedition->bl)->first();
            $expeditionHes->update(['etape' => $status]);

            $commission = new CommissionExpeditions();
            $commission->id_expedition = $expedition->id;
            $commission->livreur = $expedition->bonLivsDetail->livreur ?? 0;
            $commission->id_ville_exp = $expedition->agence;
            $commission->id_ville_dest = $expedition->agence_des;
            $commission->type = $expedition->bonLivsDetail->employeDetail->is_coff_ville($expedition);
            $commission->commission = $expedition->bonLivsDetail->employeDetail->commission_livreur($expedition);
            $commission->save();

            // si le colis est en echange on doit modifier l'ancien colis lors de livraision
            if ($expedition->type == 'COLECH') {
                $oldexpedition = Expedition::where('id', $expedition->echange_id)->first();

            $prix = Taxation::getPrixColis_Retour($oldexpedition->clientDetail->id , $oldexpedition->agence_des , $oldexpedition->agence );

                $oldexpedition->update([
                    'agence_des' => $oldexpedition->agence,
                    'agence' => $oldexpedition->agence_des,
                    'etape' => 6,
                    'fond' => 0,
                    'ttc' => $prix ?? null,
                    'port' => 'PPNE',
                    'sens' => 'Retour',
                    'retour_fond' =>  'S',
                    'date_retour' => date("Y-m-d H:i:s"),
                ]);

                $etapeHistory = new etapeHistory();
                $etapeHistory->expedition = $oldexpedition->id;
                $etapeHistory->etape = 6;
                $etapeHistory->save();

                $commentaire = new Commentaire();
                $commentaire->code = "Echange";
                $commentaire->commentaires = "échange de l'éxpedition avec : " . $expedition->num_expedition;
                $commentaire->id_expedition = $oldexpedition->id;
                $commentaire->id_utilisateur = auth()->user()->id;
                $commentaire->save();
            }


            Processus_expedition::where('code', 'LIVRAISON')->where('id_expedition', $expedition->id)->latest()->update([
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
                $texttantative = 're TENTATIVE NON LIVRÉE : ';
            } else {
                $texttantative = 'éme TENTATIVE NON LIVRÉE : ';
            }

            $commentaire = new Commentaire();
            $commentaire->code = "NON LIVRÉE";
            $commentaire->commentaires = $tantative . $texttantative . Types_commentaire::where('id', $comment)->first()->libelle;
            $commentaire->id_expedition = $expedition->id;
            $commentaire->id_utilisateur = auth()->user()->id;
            $commentaire->save();

            $etapeHistory = new etapeHistory();
            $etapeHistory->expedition = $expedition->id;
            $etapeHistory->etape = $status;
            $etapeHistory->libelle = $tantative . $texttantative . Types_commentaire::where('id', $comment)->first()->libelle;
            $etapeHistory->save();

            $expeditionHes = etapeHistory::where('expedition', $expedition->id)->where('bl_id', $expedition->bl)->first();

            $expeditionHes->update(['etape' => $status]);
            $user = $expedition->clientDetail;
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

        $blv = Bonliv::find($expedition->bl);
        $count_exp_lvr = $blv->ExpeditionDetail->whereNotIn('etape', [14, 20])->count();

        if ($count_exp_lvr == 0) {
            $blv->statut = 1;
            $blv->closed_at = Carbon::now()->format('Y-m-d H:i:s');
            $blv->save();
        }


        Redirect::to(route('expedition_livraison'))->send();
    }




    public function create(Request $request)
    {



        if ($request->isMethod('post')) {

            $client = Client::where('id', $request->client)->first();

            // if (isset(Client::where('id', $request->client)->first())) {
            //     $client = Client::where('id', $request->client)->first();
            //     $clientPort = Client::where('id', $request->client)->first()->port;
            // }

            $rules = [

                'client' => 'required',
                'type' => 'required',
                'agence' => 'required',
                'destinataire' => 'required',
                'adresse_destinataire' => 'required',
                'telephone' => 'required|digits:10|numeric',
                'agence_des' => 'required',
                'port' => 'required',
                'colis' => 'required',
                'fond' => 'nullable|numeric',
                'vDeclaree' => 'nullable|numeric'

            ];

            $validator = Validator::make($request->all(), $rules);

            // verification du port est autorisation
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } elseif ($client->port == 'PD') {
                if ($request->port != 'PD') {
                    return redirect()->back()->withInput()->with('fail', "Le client est autorisé que du PD ");
                }
            } elseif ($client->port == 'PP') {
                if ($request->port == 'PPE' || $request->port == 'PPNE') {
                    return redirect()->back()->withInput()->with('fail', "Le client est autorisé que du PD et PP ");
                }
            } elseif ($client->port == 'PPE') {
                if ($request->port != 'PPE') {
                    return redirect()->back()->withInput()->with('fail', "Le client est autorisé que du PPE ");
                }
            } elseif ($client->port == 'PPNE') {
                if ($request->port == 'PPE' || $request->port == 'PP') {
                    return redirect()->back()->withInput()->with('fail', "Le client est autorisé que du PD et PPNE ");
                }
            }
            // verification si le type est le port sont en regle
            if ($request->port == 'PD') {
                if ($request->type == 'CDP') {
                    return redirect()->back()->withInput()->with('fail', "L'ÉXPEDITION DOIT ÊTRE DE TYPE CONTRE REMBOURSEMENT");
                } elseif (empty($request->fond)) {
                    return redirect()->back()->withInput()->with('fail', "Le fond est obligatoire ");
                }
            } elseif ($request->port == 'PPNE') {
                if ($request->type == 'ECOM') {
                    return redirect()->back()->withInput()->with('fail', "L'ÉXPEDITION DOIT ÊTRE DE TYPE simple");
                } elseif (!empty($request->fond)) {
                    return redirect()->back()->withInput()->with('fail', "Le fond doit etre 0 ");
                }
            } elseif ($request->port == 'PPE' || $request->port == 'PP') {
                if ($request->type == 'ECOM' && empty($request->fond)) {
                    return redirect()->back()->withInput()->with('fail', "Le fond est obligatoire");
                } elseif ($request->type == 'CDP') {
                    if (!empty($request->fond)) {
                        return redirect()->back()->withInput()->with('fail', "Le fond doit etre 0 ");
                    }
                }
            }

            if ($request->type == 'COLECH') {
                if ($request->echange == 0) {
                    return redirect()->back()->withInput()->with('fail', "Vous devez choisir le colis á échangé");
                } else {
                    $echangeColis = $request->echange;
                }
            }

            $prixInitial = Taxation::getPrixColis($request->client, $request->agence_des, $request->agence);


            if (isset($request->vDeclaree)) {
                if ($request->colis > 1) {
                    $prix =  $prixInitial +  (($client->valeur_declaree  / 100) * $request->vDeclaree);
                } else {
                    $prix = ($request->colis *  $prixInitial) + (($client->valeur_declaree / 100) * $request->vDeclaree);
                }
            } else {
                if ($request->colis > 1) {
                    if ($client->vplafond <= $request->fond && $client->vplafond != null) {

                        $prix =   $prixInitial + (($client->valeur_declaree / 100) * $request->fond);
                    } else {

                        $prix =  $prixInitial;
                    }
                } else {
                    if ($client->vplafond <= $request->fond && $client->vplafond != null) {

                        $prix =   ($request->colis *  $prixInitial) + (($client->valeur_declaree / 100) * $request->fond);
                    } else {

                        $prix =   $request->colis *  $prixInitial;
                    }
                }
            }
            if (empty($prix)) {
                return redirect()->back()->withInput()->with('fail', "Il ya un problem dans le fond");
            } elseif ($prix > $request->ttc) {
                return redirect()->back()->withInput()->with('fail', "Le prix doit étre supérieur");
            } else {
                $prix =  $request->ttc;
            }
            if ($prix > $request->fond && $request->type == 'ECOM' || $prix  > $request->fond && $request->type == 'COLECH') {

                return redirect()->back()->withInput()->with('fail', 'Le montant doit être supérieur aux frais du colis');
            }


            $count = Expedition::all()->count();
            $expedition =  Expedition::create(array_merge($request->except('echange'), [
                'num_expedition' => ExpeditionController::getcode('EX', $count + 1),
                'sens' => 'Envoi',
                'origine' => $request->agence,
                'des' => $request->agence_des,
                'created_by' => auth()->user()->id,
                'ttc' => $prix,
                'echange_id' => $echangeColis ?? null

            ]));
            $etapeHistory = new etapeHistory();
            $etapeHistory->expedition = $expedition->id;
            $etapeHistory->etape = 1;
            $etapeHistory->save();

            $commentaire = new Commentaire();
            $commentaire->code = "TAXATION";
            $commentaire->id_expedition = $expedition->id;
            $commentaire->id_utilisateur = auth()->user()->id;
            $commentaire->save();
            Redirect::to(route('expedition_list'))->send();
        }
        $viewsData = [];

        $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
        $viewsData['agenceRecords'] = \App\Models\Ville::all()->where('deleted', '0')->where('id', "!=", 2);
        $viewsData['getVille'] = Ville::getVilles('DEPART');



        if (auth()->user()->role == '7') {
            $viewsData['agenceRecords'] = \App\Models\Ville::all()->where('deleted', '0')->where('id', "!=", 1)->whereIn('id',  \Auth::user()::getUserVilles());
        }

        return view('back/expedition/create', $viewsData);
    }

    public function insert(Request $request)
    {

        $user_role = auth()->user()->role;

        $client_id = auth()->user()->client;


        if ($user_role == '3') {


            if ($request->isMethod('post')) {

                if ($request->type == 'ECOM' || $request->type == 'CDP') {

                    $rules = [
                        'destinataire' => 'required',
                        'adresse_destinataire' => 'required',
                        'telephone' => 'required|digits:10|numeric',
                        'destinataire' => 'required',
                        'agence_des' => 'required',
                        'fond' => 'nullable|numeric',
                        'vDeclaree' => 'nullable|numeric'
                    ];
                } elseif ($request->type == 'COLECH') {
                    $rules = [
                        'destinataire' => 'required',
                        'adresse_destinataire' => 'required',
                        'telephone' => 'required|digits:10|numeric',
                        'vDeclaree' => 'nullable|numeric'

                    ];
                }


                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $count = Expedition::all()->count();
                    if ($request->type == 'CDP') {
                        $retour_fond = 'S';
                        $fond = 0;
                        $agence_des = $request->agence_des;
                        if (\Auth::user()->ClientDetail->num_exp == 'AUTO') {
                            $num_expedition = ExpeditionController::getcode('EX', $count+1);
                        } else {
                            if ($request->num_exp == null) {

                                $num_expedition = ExpeditionController::getcode('EX', $count+1);
                            } elseif (Expedition::where('num_expedition', $request->num_exp)->first() === null) {
                                $num_expedition = $request->num_exp;
                            } else {
                                return redirect()->back()->withInput()->withErrors($validator)->with('error', "Le numéro d'expédition est déjà existant");
                            }
                        }
                    } elseif ($request->type == 'ECOM') {
                        $retour_fond = 'CR';
                        $fond = $request->fond;
                        $agence_des = $request->agence_des;
                        if (\Auth::user()->ClientDetail->num_exp == 'AUTO') {
                            $num_expedition = ExpeditionController::getcode('EX', $count+1);
                        } else {
                            if ($request->num_exp == null) {
                                $num_expedition = ExpeditionController::getcode('EX', $count+1);
                            } elseif (Expedition::where('num_expedition', $request->num_exp)->first() === null) {
                                $num_expedition = $request->num_exp;
                            } else {
                                return redirect()->back()->withInput()->withErrors($validator)->with('error', "Le numéro d'expédition est déjà existant");
                            }
                        }
                    } else {
                        if (Expedition::where('id', $request->expedition_ech)->first() != null) {
                            $expedition_ech =  Expedition::where('id', $request->expedition_ech)->first();

                            $retour_fond = 'E';
                            $fond = $request->fond;
                            $agence_des = $expedition_ech->agence_des;
                            $num_expedition = ExpeditionController::getcode('EX', $count + 1);
                            $echange_id = $request->expedition_ech;
                        } else {
                            return redirect()->back()->withInput()->withErrors($validator)->with('error', "Il y a un error");
                        }
                    }

                    // declaration la nature de colis
                    if (Auth::user()->ClientDetail->factureMois == 'Oui') {
                        $port = 'PPE';
                    } elseif ($request->type == 'ECOM') {
                        $port = 'PD';
                    } elseif ($request->type == 'CDP') {
                        if (Auth::user()->ClientDetail->colisSimple == 'Non') {
                            return redirect()->back();
                        } elseif (Auth::user()->ClientDetail->colisSimple == 'Oui') {
                            if (Auth::user()->ClientDetail->ppSimple == 'PP') {
                                $port = 'PP';
                            } elseif (Auth::user()->ClientDetail->ppSimple == 'PPNE') {
                                $port = 'PPNE';
                            } else {
                                $port = 'PP';
                            }
                        }
                    } elseif ($request->type == 'COLECH') {
                        $port = 'PPNE';
                    }

                    $prix = Taxation::getPrixColis(Auth::user()->ClientDetail->id,  $agence_des, $request->agence);

                    if (empty($prix)) {
                        return redirect()->back()->withInput()->withErrors($validator)->with('error', "La destination que vous choisissez n'est pas disponible");
                    }
                    // si il y a plus de colis dans une expeditions et la valeur declaree

                    if ($request->colis > 1) {

                        if (isset($request->vDeclaree)) {
                            $ttc =  ($request->colis * $prix) + ((Auth::user()->ClientDetail->valeur_declaree / 100) * $request->vDeclaree);
                        } else {

                            if (!empty(Auth::user()->ClientDetail->vplafond) && Auth::user()->ClientDetail->vplafond <= $request->fond) {
                                $ttc =  ($request->colis * $prix) + ((Auth::user()->ClientDetail->valeur_declaree / 100) * $request->fond);
                            } else {
                                $ttc =  $request->colis * $prix;
                            }
                        }
                    } else {
                        if (isset($request->vDeclaree)) {

                            $ttc = $prix +  (Auth::user()->ClientDetail->valeur_declaree / 100) * $request->vDeclaree;
                        } else {

                            if (!empty(Auth::user()->ClientDetail->vplafond) && Auth::user()->ClientDetail->vplafond <= $request->fond) {

                                $ttc =  $prix + ((Auth::user()->ClientDetail->valeur_declaree / 100) * $request->fond);
                            } else {

                                $ttc =  $prix;
                            }
                        }
                    }
                    if ($ttc > $request->fond && $request->type == 'ECOM' || $ttc > $request->fond && $request->type == 'COLECH') {
                        return redirect()->back()->withInput()->with('error', 'Le montant doit être supérieur aux frais du colis');
                    }

                    if ($request->has('ouvertureColis')) {
                        $ouvertureColis = 'Oui';
                    } else {
                        $ouvertureColis = 'Non';
                    }

                    if ($request->has('paiementCheque')) {
                        $paiementCheque = 'Oui';
                    } else {
                        $paiementCheque = 'Non';
                    }




                    $expedition =  Expedition::create(array_merge($request->except('expedition_ech', 'num_exp'), [
                        'num_expedition' =>  $num_expedition,
                        'port' => $port,
                        'type' => $request->type,
                        'retour_fond' => $retour_fond,
                        'sens' => 'Envoi',
                        'ttc' => $ttc,
                        'fond' => $fond,
                        'created_by' => auth()->user()->id,
                        'origine' => $request->agence,
                        'des' => $agence_des,
                        'ouvertureColis' => $ouvertureColis,
                        'paiementCheque' => $paiementCheque,
                        'agence_des' => $agence_des,
                        'echange_id' =>  $echange_id ?? null
                    ]));
                    $etapeHistory = new etapeHistory();
                    $etapeHistory->expedition = $expedition->id;
                    $etapeHistory->etape = 1;
                    $etapeHistory->save();

                    $commentaire = new Commentaire();
                    $commentaire->code = "TAXATION";
                    $commentaire->id_expedition = $expedition->id;
                    $commentaire->id_utilisateur = auth()->user()->id;
                    $commentaire->save();
                    Redirect::to(route('expedition_list'))->send();
                }
            }

            $viewsData = [];
            $viewsData['mes_expedition'] = \App\Models\Expedition::all()->where('deleted', '0')
                ->where('client', $client_id)
                ->where('etape', '14')
                ->where('deleted', '0');
            $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
            $viewsData['agenceRecords'] = \App\Models\Ville::all()->where('deleted', '0')->where('id', "!=", 2)->where('statut', 'actif');

            return view('client/expedition/create_client', $viewsData);
        }
    }

    public function update_client(Expedition $expedition, Request $request)
    {

        $client_id = auth()->user()->client;

        if ($request->isMethod('post')) {

            if ($request->type == 'ECOM' || $request->type == 'CDP') {
                $rules = [
                    'destinataire' => 'required',
                    'adresse_destinataire' => 'required',
                    'telephone' => 'required|digits:10|numeric',
                    'destinataire' => 'required',
                    'agence_des' => 'required',
                    'fond' => 'nullable|numeric',
                    'vDeclaree' => 'nullable|numeric'
                ];
            } elseif ($request->type == 'COLECH') {
                $rules = [
                    'destinataire' => 'required',
                    'adresse_destinataire' => 'required',
                    'telephone' => 'required|digits:10|numeric',
                    'vDeclaree' => 'nullable|numeric'

                ];
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $count = Expedition::all()->count();
                if ($request->type == 'CDP') {
                    $retour_fond = 'S';
                    $fond = null;
                    if (\Auth::user()->ClientDetail->num_exp == 'AUTO') {
                        $num_expedition = ExpeditionController::getcode('EX', $count);
                    } else {

                        if ($request->num_exp == null) {
                            $num_expedition = ExpeditionController::getcode('EX', $count);
                        } elseif (Expedition::where('num_expedition', $request->num_exp)->first() === null) {
                            $num_expedition = $request->num_exp;
                        } else {
                            return redirect()->back()->withInput()->withErrors($validator)->with('error', "Le numéro d'expédition est déjà existant");
                        }
                    }
                } elseif ($request->type == 'ECOM') {
                    $retour_fond = 'CR';
                    $fond = $request->fond;
                    if (\Auth::user()->ClientDetail->num_exp == 'AUTO') {

                        $num_expedition = ExpeditionController::getcode('EX', $count);
                    } else {
                        if ($request->num_exp == null) {

                            $num_expedition = ExpeditionController::getcode('EX', $count);
                        } elseif (Expedition::where('num_expedition', $request->num_exp)->first() === null) {
                            $num_expedition = $request->num_exp;
                        } else {
                            return redirect()->back()->withInput()->withErrors($validator)->with('error', "Le numéro d'expédition est déjà existant");
                        }
                    }
                } else {
                    $retour_fond = 'E';
                    $fond = $request->fond;
                    $num_expedition = Expedition::where('id', $request->expedition_ech)->first()->num_expedition;
                }

                // declaration la nature de colis
                if (Auth::user()->ClientDetail->factureMois == 'Oui') {
                    $port = 'PPE';
                } elseif ($request->type == 'ECOM') {
                    $port = 'PD';
                } elseif ($request->type == 'CDP') {
                    if (Auth::user()->ClientDetail->colisSimple == 'Non') {
                        return redirect()->back();
                    } elseif (Auth::user()->ClientDetail->colisSimple == 'Oui') {
                        if (Auth::user()->ClientDetail->ppSimple == 'PP') {
                            $port = 'PP';
                        } elseif (Auth::user()->ClientDetail->ppSimple == 'PPNE') {
                            $port = 'PPNE';
                        } else {
                            $port = 'PP';
                        }
                    }
                } elseif ($request->type == 'COLECH') {
                    $port = 'PPNE';
                }
                $prix = Taxation::getPrixColis(Auth::user()->ClientDetail->id, $request->agence_des, $request->agence);
                // si il y a plus de colis dans une expeditions et la valeur declaree


                if ($request->colis > 1) {

                    if (isset($request->vDeclaree)) {
                        $ttc =  ($request->colis * $prix) + ((Auth::user()->ClientDetail->valeur_declaree / 100) * $request->vDeclaree);
                    } else {

                        if (!empty(Auth::user()->ClientDetail->vplafond) && Auth::user()->ClientDetail->vplafond <= $request->fond) {
                            $ttc =  ($request->colis * $prix) + ((Auth::user()->ClientDetail->valeur_declaree / 100) * $request->fond);
                        } else {
                            $ttc =  $request->colis * $prix;
                        }
                    }
                } else {
                    if (isset($request->vDeclaree)) {

                        $ttc = $prix +  (Auth::user()->ClientDetail->valeur_declaree / 100) * $request->vDeclaree;
                    } else {

                        if (!empty(Auth::user()->ClientDetail->vplafond) && Auth::user()->ClientDetail->vplafond <= $request->fond) {

                            $ttc =  $prix + ((Auth::user()->ClientDetail->valeur_declaree / 100) * $request->fond);
                        } else {

                            $ttc =  $prix;
                        }
                    }
                }
                if ($ttc > $request->fond && $request->type == 'ECOM' || $ttc > $request->fond && $request->type == 'COLECH') {
                    return redirect()->back()->withInput()->with('error', 'Le montant doit être supérieur aux frais du colis');
                }

                if ($request->has('ouvertureColis')) {
                    $ouvertureColis = 'Oui';
                } else {
                    $ouvertureColis = 'Non';
                }

                if ($request->has('paiementCheque')) {
                    $paiementCheque = 'Oui';
                } else {
                    $paiementCheque = 'Non';
                }
                // $columns = [
                //     // 'destinataire' => 'Destinataire',
                //     // 'adresse_destinataire' => 'Adresse',
                //     // 'telephone' => 'Téléphone',
                //     // 'agence_des' => 'Destination',
                //     // 'retour_fond' => 'Nature',
                //     'fond' => $fond,
                //     // 'colis' => 'Nb. Colis',
                //     'vDeclaree' => $request->vDeclaree,
                //     // 'Indication' => 'Indication',
                //     'type' => $request->type,

                // ];

                $columns = [

                    'destinataire' => 'Destinataire',
                    'adresse_destinataire' => 'Adresse',
                    'telephone' => 'Téléphone',
                    'agence_des' => 'Destination',
                    'retour_fond' => 'Nature',
                    'fond' => 'Fond',
                    'colis' => 'Nb. Colis',
                    'vDeclaree' => 'vDeclaree',
                    'Indication' => 'Indication',
                    'type' => 'Type de colis'

                ];
                foreach ($request->except('_token') as $column => $value) {
                    // dd($expedition->client);
                    if ($expedition[$column] != $request[$column]) {
                        // echo $columns[$column].' value changed, old value: ' . $expedition[$column] . ' , new value ' . $value;
                        if (isset($columns[$column]) || !empty($columns[$column])) {
                            $commentaire = new Commentaire();
                            $commentaire->code = "Modification";
                            if ($columns[$column] == "Destination") {
                                $ancienne = Ville::where('id', $expedition[$column])->first();
                                $nouvelle = Ville::where('id', $value)->first();
                                $commentaire->commentaires = "Modification " . $columns[$column] . " du : " . $ancienne->libelle . " ==> " . $nouvelle->libelle . " ";
                            } else {
                                $commentaire->commentaires = "Modification " . $columns[$column] . " du : " . $expedition[$column] . " ==> " . $value . " ";
                            }

                            $commentaire->id_expedition = $expedition->id;
                            $commentaire->id_utilisateur = auth()->user()->id;
                            $commentaire->attribut =  $columns[$column];
                            if ($columns[$column] == "Destination") {
                                $commentaire->ancienne_valeur =   $ancienne->libelle;
                                $commentaire->nouvelle_valeur =  $nouvelle->libelle;
                            }

                            $commentaire->save();
                        }
                    }
                }

                $expedition->update(array_merge($request->except('expedition_ech', 'num_exp'), [
                    'num_expedition' =>  $num_expedition,
                    'port' => $port,
                    'type' => $request->type,
                    'retour_fond' => $retour_fond,
                    'sens' => 'Envoi',
                    'ttc' => $ttc,
                    'fond' => $fond,
                    'origine' => $request->agence,
                    'des' => $request->agence_des,
                    'ouvertureColis' => $ouvertureColis,
                    'paiementCheque' => $paiementCheque
                ]));



                Redirect::to(route('expedition_list'))->send();
            }
        }
        $viewsData = [];
        $viewsData['mes_expedition'] = \App\Models\Expedition::all()->where('deleted', '0')
            ->where('client', $client_id)
            ->where('etape', '14')
            ->where('deleted', '0');
        $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
        $viewsData['agenceRecords'] = \App\Models\Ville::all()->where('deleted', '0')->where('id', "!=", 2);
        $viewsData['expedition'] = $expedition;
        return view('client.expedition.updateClient', $viewsData);
    }



    public function update(Expedition $expedition, Request $request)
    {

        $client = $expedition->clientDetail;

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [

                'type' => 'required',
                'agence' => 'required',
                'destinataire' => 'required',
                'adresse_destinataire' => 'required',
                'telephone' => 'required|digits:10|numeric',
                'port' => 'required',
                'colis' => 'required',
                'fond' => 'nullable|numeric',
                'vDeclaree' => 'nullable|numeric'

            ];
            $columns = [
                'client' => 'Expéditeur',
                'agence' => 'Origin',
                'destinataire' => 'Destinataire',
                'adresse_destinataire' => 'Adresse',
                'telephone' => 'Téléphone',
                'agence_des' => 'Destination',
                'retour_fond' => 'Nature',
                'fond' => 'Fond',
                'port' => 'Port',
                'colis' => 'Nb. Colis',
                'vDeclaree' => 'vDeclaree',
                'ttc' => 'Prix colis',
                'Indication' => 'Indication',
                'paiementCheque' => 'paiement Cheque',
                'type' => 'Type de colis'

            ];

            $validator = Validator::make($request->all(), $rules);



            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                //verification du port de l'expedition
                if ($expedition->port == 'PPE') {
                    if ($request->port != 'PPE' && $request->port != 'PP') {
                        return redirect()->back()->withInput()->with('fail', "L'ÉXPEDITION N'EST PAS AUTORISÉ QUE DU PPE ET PP");
                    }
                } elseif ($expedition->port == 'PP') {
                    if ($request->port != 'PP') {
                        return redirect()->back()->withInput()->with('fail', "L'ÉXPEDITION N'EST PAS AUTORISÉ QUE DU PP ");
                    }
                } elseif ($expedition->port == 'PD') {

                    if ($request->port == 'PP' && $client->port != 'PP') {
                        if (empty($request->fond) || $request->type != 'ECOM') {
                            return redirect()->back()->withInput()->with('fail', "LE CLIENT N'EST PAS AUTORISÉ PP");
                        }
                    } elseif ($request->port == 'PPNE' && $client->port != 'PPNE') {

                        return redirect()->back()->withInput()->with('fail', "LE CLIENT N'EST PAS AUTORISÉ PPNE");
                    } elseif ($request->port == 'PPE' && $client->port != 'PPE') {

                        return redirect()->back()->withInput()->with('fail', "LE CLIENT N'EST PAS AUTORISÉ PPE ");
                    }
                } elseif ($expedition->port == 'PPNE') {
                    if ($request->port == 'PPE'  && $client->port != 'PPE') {
                        return redirect()->back()->withInput()->with('fail', "LE CLIENT N'EST PAS AUTORISÉ PPE ");
                    } elseif ($request->port == 'PP' && $client->port != 'PP') {
                        if (empty($request->fond) || $request->type != 'ECOM') {
                            return redirect()->back()->withInput()->with('fail', "LE CLIENT N'EST PAS AUTORISÉ PP");
                        }
                    }
                }
                // verification si le type est le port sont en regle
                if ($request->port == 'PD') {
                    if ($request->type == 'CDP') {
                        return redirect()->back()->withInput()->with('fail', "L'ÉXPEDITION DOIT ÊTRE DE TYPE CONTRE REMBOURSEMENT");
                    } elseif (empty($request->fond)) {
                        return redirect()->back()->withInput()->with('fail', "Le fond est obligatoire ");
                    }
                } elseif ($request->port == 'PPNE') {
                    if ($request->type == 'ECOM') {
                        return redirect()->back()->withInput()->with('fail', "L'ÉXPEDITION DOIT ÊTRE DE TYPE simple");
                    } elseif (!empty($request->fond)) {
                        return redirect()->back()->withInput()->with('fail', "Le fond doit etre 0 ");
                    }
                } elseif ($request->port == 'PPE' || $request->port == 'PP') {
                    if ($request->type == 'ECOM' && empty($request->fond)) {
                        return redirect()->back()->withInput()->with('fail', "Le fond est obligatoire");
                    } elseif ($request->type == 'CDP') {
                        if (!empty($request->fond)) {
                            return redirect()->back()->withInput()->with('fail', "Le fond doit etre 0 ");
                        }
                    }
                }
                if ($request->type == 'COLECH') {
                    if (empty($request->echangecolis)) {
                        return redirect()->back()->withInput()->with('fail', "Vous devez choisir le colis á échangé");
                    } else {
                        $echangeColis = $request->echangecolis;
                    }
                }
            }

            foreach ($request->except('_token') as $column => $value) {
                // dd($expedition->client);
                if ($expedition[$column] != $request[$column]) {
                    // echo $columns[$column].' value changed, old value: ' . $expedition[$column] . ' , new value ' . $value;
                    if (isset($columns[$column]) || !empty($columns[$column])) {
                        $commentaire = new Commentaire();
                        $commentaire->code = "Modification";
                        if ($columns[$column] == "Destination") {
                            $ancienne = Ville::where('id', $expedition[$column])->first();
                            $nouvelle = Ville::where('id', $value)->first();
                            $commentaire->commentaires = "Modification " . $columns[$column] . " du : " . $ancienne->libelle . " ==> " . $nouvelle->libelle . " ";
                            $commentaire->ancienne_valeur = $ancienne->libelle;
                            $commentaire->nouvelle_valeur = $nouvelle->libelle;
                        } else {
                            $commentaire->commentaires = "Modification " . $columns[$column] . " du : " . $expedition[$column] . " ==> " . $value . " ";
                            $commentaire->ancienne_valeur = $expedition[$column];
                            $commentaire->nouvelle_valeur =  $value;
                        }

                        $commentaire->id_expedition = $expedition->id;
                        $commentaire->id_utilisateur = auth()->user()->id;
                        $commentaire->attribut =  $columns[$column];
                        if ($columns[$column] == "Destination") {
                            $commentaire->ancienne_valeur =   $ancienne->libelle;
                            $commentaire->nouvelle_valeur =  $nouvelle->libelle;
                        }

                        $commentaire->save();
                    }
                }
            }



            $prixInitial = Taxation::getPrixColis($request->client, $expedition->agence_des, $request->agence);

            if (isset($request->vDeclaree)) {
                if ($request->colis > 1) {
                    $prix =  $prixInitial +  (($client->valeur_declaree  / 100) * $request->vDeclaree);
                } else {
                    $prix = ($request->colis *  $prixInitial) + (($client->valeur_declaree / 100) * $request->vDeclaree);
                }
            } else {
                if ($request->colis > 1) {
                    if ($client->vplafond <= $request->fond && $client->vplafond != null) {

                        $prix =   $prixInitial + (($client->valeur_declaree / 100) * $request->fond);
                    } else {

                        $prix =  $prixInitial;
                    }
                } else {
                    if ($client->vplafond <= $request->fond && $client->vplafond != null) {

                        $prix =   ($request->colis *  $prixInitial) + (($client->valeur_declaree / 100) * $request->fond);
                    } else {

                        $prix =   $request->colis *  $prixInitial;
                    }
                }
            }

            if (empty($prix)) {
                return redirect()->back()->withInput()->with('fail', "Il ya un problem dans le fond");
            } elseif ($prix > $request->ttc) {
                return redirect()->back()->withInput()->with('fail', "Le prix doit étre supérieur");
            } else {
                $prix =  $request->ttc;
            }
            if ($prix > $request->fond && $request->type == 'ECOM' || $prix  > $request->fond && $request->type == 'COLECH') {

                return redirect()->back()->withInput()->with('fail', 'Le montant doit être supérieur aux frais du colis');
            }

            if ($expedition->port != 'PP' && $request->port == 'PP') {
                $expedition->update(array_merge($request->except('commentaire', 'echangecolis'), [
                    'caissepp_emp' => auth()->user()->id,
                    'ttc' =>  $prix,
                    'echange_id' => $echangeColis ?? null
                ]));
            } else {
                $expedition->update(array_merge($request->except('commentaire', 'echangecolis'), [
                    'ttc' =>  $prix,
                    'echange_id' => $echangeColis ?? null
                ]));
            }



            Redirect::to(route('expedition_list'))->send();
        }
        $viewsData['record'] = $expedition;
        $viewsData['getVille'] = Ville::getVilles('DEPART');
        $viewsData['expeditionEchange'] = \App\Models\Expedition::where('deleted', '0')
            ->where('client', $expedition->client)
            ->where('etape', '14')->get();
        // $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
        $viewsData['agenceRecords'] = \App\Models\Ville::all()->where('deleted', '0')->where('id', "!=", 2);

        $user_role = auth()->user()->role;


        if ($user_role == '3') {
            $viewsData['mes_expedition'] = \App\Models\Expedition::all()->where('deleted', '0')->where('client', auth()->user()->id)->where('etape', '!=', '1');

            return view('client/expedition/update', $viewsData);
        } else {

            return view('back/expedition/update', $viewsData);
        }
    }

    public function affectation(Request $request, $type, $livreur = null)
    {
        $expeditions = [];
        if ($request->isMethod('post')) {
            $rules = ['employe' => ['required'], 'colis' => ['required']];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if (1 == 2 && is_numeric(request()->input('bl'))) {
                    $bon = Bonliv::find(request()->input('bl'));
                    $bon->relatedColis()->attach(Expedition::find($request->all()['colis']));
                    $expeditionBefor = Expedition::whereIn('id', $request->all()['colis'])->get();
                    Expedition::whereIn('id', $request->all()['colis'])->update(['bl' => $bon->id, 'etape' => 16]);
                    ExpeditionController::commentaireAffictation($expeditionBefor, $bon->employeDetail->libelle);
                    ExpeditionController::etapeHistory($expeditionBefor, $bon->id);
                } else {
                    $bon = new Bonliv();
                    $bon->code = "L" . sprintf("%05d", Bonliv::all()->count() + 1);
                    $bon->livreur = $request->all()['employe'];
                    $livreur = Employe::find($request->all()['employe']);
                    $bon->id_agence = $livreur->agence;
                    $bon->type = $type;
                    $bon->save();

                    if (isset($request->all()['colis'])) {
                        $bon->relatedColis()->sync(Expedition::find($request->all()['colis']));
                        $expeditionBefor = Expedition::whereIn('id', $request->all()['colis'])->get();
                        Expedition::whereIn('id', $request->all()['colis'])->update(['bl' => $bon->id, 'etape' => 16]);
                        ExpeditionController::commentaireAffictation($expeditionBefor, $bon->employeDetail->libelle);
                        ExpeditionController::etapeHistory($expeditionBefor, $bon->id);
                        ExpeditionController::expeditionNotification($expeditionBefor, $bon->employeDetail);
                    } else {
                        $bon->relatedColis()->sync([]);
                    }
                }
                Redirect::to(route('bonliv_list'))->send();
            }
        }
        $bls = [];

        if (is_numeric($livreur)) {
            $sens = ($type == 1) ? 'Envoi' : "Retour";
            if (auth()->user()->role == '5' || auth()->user()->role == '7' || auth()->user()->role == '8') {
                $expeditions = Expedition::getStock($sens)->whereIn('agence', \Auth::user()::getUserVilles());
            } else {
                $expeditions = Expedition::getStock($sens)->where('agence_des', Employe::find($livreur)->agence);
                $empl = Employe::find($livreur);

                $user_emp = User::get()->where('employe', $livreur)->first();
                $villes_liv = $user_emp->relatedVilles()->allRelatedIds()->toArray();

                if (empty($villes_liv)) {
                    $expeditions = Expedition::getStock($sens)->where('agence_des', $empl->agence);
                } else {

                    $expeditions = Expedition::getStock($sens)->whereIn('agence_des', $villes_liv);
                }
            }
            $bls = Bonliv::getRecords($livreur, $type, 2);
        }

        if (auth()->user()->role == '5' || auth()->user()->role == '7' || auth()->user()->role == '8') {

            $regions = \Auth::user()->relatedRegions()->allRelatedIds()->toArray();
            $livreur_dispo_region = \DB::table("regions_users")->whereIn('region_id', $regions)->pluck('user_id')->toArray();
            $livreur_dispo = \DB::table("villes_users")->whereIn('ville_id', \Auth::user()::getUserVilles())->pluck('user_id')->toArray();
            $livreur_pas_des_villes_affecter = \DB::table("employes")->whereIn('agence', \Auth::user()::getUserVilles())->pluck('id')->toArray();
            $users_pva =  \DB::table("users")->where('deleted', '0')->where('validated', 1)->whereIn('employe', $livreur_pas_des_villes_affecter)->pluck('id')->toArray();
            $all_users_dispo = array_merge($users_pva, $livreur_dispo, $livreur_dispo_region);
            $employes = User::get()->where('deleted', '0')->where('validated', 1)->where('role', '!=', '3')->where('employe', '!=', null)->whereIn('id', $all_users_dispo);
        } else {


            $employes = User::get()->where('deleted', '0')->where('validated', 1)->where('role', '!=', '3')->where('employe', '!=', null);
        }


        return view('back/expedition/affectation', [
            'employes' => $employes,
            'colis' => $expeditions,
            'type' => $type,
            'livreur' => $livreur,
            'bls' => $bls,
        ]);
    }

    public function map(Request $request, Commentaire $comment)
    {
        return view('back/expedition/map', [
            'record' => $comment
        ]);
    }

    public function delete(Expedition $expedition)
    {
        $expedition->update(
            [
                'etape' => 5,
            ]
        );
        $commentaire = new Commentaire();
        $commentaire->code = "ANNULATION";
        $commentaire->commentaires = "ANNULATION PAR LE CLIENT";
        $commentaire->id_expedition = $expedition->id;
        $commentaire->id_utilisateur = auth()->user()->id;
        $commentaire->save();

        $etapeHistory = new etapeHistory();
        $etapeHistory->expedition = $expedition->id;
        $etapeHistory->etape = 5;
        $etapeHistory->save();

        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }




    public function suiviCommercial()
    {
        $viewsData = [];
        $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
        $viewsData['agenceRecords'] = \App\Models\Agence::all()->where('deleted', '0');
        $viewsData['statutRecords'] = \App\Models\Statut::all()->where('deleted', '0')->where('code', 'EXPEDITION');

        return view('back.expedition.suiviCommercial', $viewsData);
    }

    public static function export(Request $request)
    {

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
            })
            ->leftJoin('bons', function ($join) {
                $join->on('bons.id', '=', 'expeditions.id_bon');
                $join->where('bons.type', '=', 'RAMASSAGE');
            });

        if (isset($request->expediteur) && $request->expediteur != '0') {
            $query->where('expeditions.client', '=', $request->expediteur);
        }

        if (isset($request->agence_des) && $request->agence_des != '0') {
            $query->where('expeditions.agence_des', '=', $request->agence_des);
        }

        if (isset($request->agence_exp) && $request->agence_exp != '0') {
            $query->where('expeditions.agence', '=', $request->agence_exp);
        }

        if (isset($request->start_date) && strlen(trim(($request->start_date))) > 0) {
            $query->whereDate("expeditions.created_at", '>=', $request->start_date);
        }
        if (isset($request->end_date) && strlen(trim(($request->end_date))) > 0) {
            $query->whereDate("expeditions.created_at", '<=', $request->end_date);
        }
        if (isset($request->etapes) && $request->etapes != '0') {
            $query->whereIn('etape', $request->etapes);
        }
        if (isset($request->agence) && $request->agence != '0') {
            $query->where('expeditions.agence', '=', $request->agence);
        }
        if (isset($request->n_colis) && strlen(trim(($request->n_colis))) > 0) {
            $query->where('num_expedition', '=', $request->n_colis);
        }


        $spreadsheet = IOFactory::load(storage_path('export/expedition.xlsx'));
        $i = 2;
        foreach ($query->get() as $record) {

            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->num_expedition);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->created_at);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $record->bons_date_validation);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $record->client);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->agence);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $record->destination);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . $i, $record->telephone);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . $i, $record->colis);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("I" . $i, $record->retour_fond);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("J" . $i, $record->fond);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("k" . $i, $record->statut_label);

            $i++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="export.xlsx"');

        $writer->save('php://output');
    }

    public static function pdf(Expedition $expedition)
    {
        $pdf = new \PDF('P', 'mm', 'A4');
        $pdf::SetTitle('');
        // set margins
        $pdf::SetMargins(2, 2, 2, true);
        $pdf::SetPrintHeader(false);
        $pdf::SetPrintFooter(false);
        $pdf::SetAutoPageBreak(TRUE, 0);


        $qrcode = '<img  src="@' . base64_encode(QrCode::format('png')->size(100)->generate($expedition->num_expedition)) . '" width="77px">';

        // $qrcode = '';

        $pdf::AddPage('L', 'A6');
        $colis_deja_enchange = \DB::table("expeditions")->where('echange_id', $expedition->id)->pluck('num_expedition')->first();

        $echange = '';
        if ($expedition->type == 'COLECH') {
            $colis_ech = $expedition->colis_ech->num_expedition;
            $echange = 'Colis en échange de ' . $colis_ech;
        }elseif($colis_deja_enchange){
            $echange = 'Colis à été échangé contre ' . $colis_deja_enchange;
        }




        $header = '<br><br>
            <table style="width:100% !important;border:1px solid   !important; height:100% !important; " cellpadding="5">
                <tr >
                    <td style="height:50px !important; font-size: 10px !important;" >
                     <img src="/assets/front/logo-hori.png" height="40px"  width="180px" style="padding: 5px !important;" >
                    </td>

                    <td style="height:50px !important; padding=10px !important; text-align:center !important; font-size: 11px !important;" width="50%">
                    <br><br> <b> ' . $expedition->agenceDesDetail->libelle . ' </b> <br>
                    </td>
                </tr>
            </table>

            <table style="width:100% !important;border-left:1px solid !important; border-right:1px solid !important; height:100% !important; " cellpadding="5">
                <tr >
                    <td style="height:20px !important; font-size: 10px !important;text-align:left !important;" width="20%"><b>Expéditeur </b>
                    </td>

                    <td width="30%" style="height:20px !important;  font-size: 10px !important;" >' . $expedition->clientDetail->libelle . '
                    </td>

                    <td style="height:20px !important; font-size: 10px !important;text-align:left !important;" width="20%"><b>Téléphone </b>
                    </td>

                    <td width="30%" style="height:20px !important;  font-size: 10px !important;" >' . $expedition->telephone . '
                    </td>
                </tr>


                <tr >
                <td style=" border-bottom-color:#d8d8d8; height:20px !important; font-size: 10px !important; border-bottom: 0.2px red  !important;border-collapse: collapse !important;" width="20%"><b>Destinataire </b>
            </td>

                    <td width="80%" style=" border-bottom-color:#d8d8d8; height:20px !important; font-size: 10px !important; border-bottom: 0.2px red  !important;border-collapse: collapse !important;" >' . $expedition->destinataire . ' - ' . $expedition->adresse_destinataire . '
                    </td>
                </tr>
            </table>

            <table style="width:100% !important;border-left:1px solid !important; border-right:1px solid !important;  border-top-color:#d8d8d8;border-top: 0.2px red  !important;" cellpadding="5">
                <tr >
                    <td style="height:20px !important; font-size: 10px !important;" width="50%" >

        <table style="width:100% !important; border-color:#d8d8d8; border:0.1px solid !important; height:100% !important; " cellpadding="5">
        <tr >
            <td style="height:25px !important; font-size: 10px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important;" width="50%"><b>N° Expédition </b>
            </td>

            <td width="50%" style="height:25px !important;  font-size: 10px !important;" >' . $expedition->num_expedition . '
            </td>
        </tr>

        <tr >
            <td style="height:25px !important; font-size: 10px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" width="50%"><b>Montant </b>
            </td>

            <td width="50%" style="height:25px !important;  font-size: 10px !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" >' . number_format($expedition->fond, 2) . ' DH
            </td>
        </tr>

        <tr >
            <td style="height:25px !important; font-size: 8px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" width="50%"><b>Ouverture de colis</b>
            </td>

            <td width="50%" style="height:25px !important;  font-size: 10px !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" >' . $expedition->ouvertureColis . '
            </td>
        </tr>

        <tr >
        <td style="height:25px !important; font-size: 8px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" width="50%"><b>Paiement par chèque</b>
        </td>

        <td width="50%" style="height:25px !important;  font-size: 10px !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" >' . $expedition->paiementCheque . '
        </td>
    </tr>

        </table>
                    </td>
                    <td style="height:20px!important; text-align:center !important; border-bottom-color:#d8d8d8; border-left:0.2px solid !important;"  width="25%">
                    <br><br>' . $qrcode . '<br>
                    </td>
                    <td style="height:20px !important; border-bottom-color:#d8d8d8; border-left:0.2px solid !important;"  width="25%">




                    <table style="width:100% !important; border-color:#d8d8d8; border:0.1px solid !important; height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:center !important; border-right-color:#d8d8d8; border-right:0.1px solid !important;" width="100%"><b>Indication</b>
                        </td>
                    </tr>
                    </table> <h5>
                    ' . Str::limit($expedition->Indication, 40) . $echange . '</h5>
                    </td>

                </tr>
            </table>

            <table style="width:100% !important;border:1px solid   !important; height:100% !important; " cellpadding="5">
                <tr >
                <td style="height:20px !important; font-size: 10px !important;" width="80%"><b>' . $expedition->agenceDetail->libelle . ' le : ' . $expedition->created_at . '</b>
                </td>
                    <td style="height:100% !important; padding=10px !important; text-align:right !important; font-size: 10px !important;" width="20%"> <b>Colis : ' . $expedition->colis . '/1 </b>
                    </td>
                </tr>
            </table>
        ';
        $pdf::WriteHTML($header, true, 0, true, 0);

        $pdf::Output("Etiquette.pdf");
    }



    public function getcode($letter, $object)
    {
        return $letter . sprintf("%06d", $object + 1);
    }

    public function import(Request $request)
    {
        $client = Client::find(auth()->user()->client);
        $request->flash();
        $request->session()->forget('error');
        $request->session()->forget('success');
        $expeditions = null;
        $errors = [];
        $villes = \App\Models\Ville::getArrayVilles();
        if ($request->isMethod('post')) {

            if (request()->input('destinataire') !== null) {
                $expeditions = ImportExpedition::getExpeditionsDataFromForm($request->all());
                $errors = ImportExpedition::getErrors($expeditions, $villes, $client->num_exp ?? 'AUTO');
                if (count($errors) === 0) {
                    Expedition::saveImportedList($expeditions, $villes, $client->num_exp ?? 'AUTO');
                    $request->session()->flash('success', 'Fichier charger avec succès');
                    $expeditions = null;
                }
            } else {

                if ($request->file('destinataire') == null && ($request->file('file') === null || strtolower($request->file('file')->getClientOriginalExtension()) !== 'xlsx')) {
                    $request->session()->flash('error', 'Merci de choisir un fichier de chargement valide');
                } else {

                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $reader->setReadDataOnly(true);
                    $spreadsheet = $reader->load($_FILES["file"]['tmp_name']);
                    $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
                    $data = $sheet->toArray();
                    array_shift($data);
                    $expeditions = ImportExpedition::getExpeditionsData($data);
                }
            }
        }
        $chargementMass = Chargement_Masse_history::where('client', auth()->user()->ClientDetail->id)->get();

        return view('back.expedition.import', ['expeditions' => $expeditions, 'errors' => $errors, 'villes' => $villes, 'chargementMass' => $chargementMass]);
    }

    public function expeditionEchange(Request $request)
    {

        $cid = $request->post('cid');
        $client_id = auth()->user()->client;

        $expedition = Expedition::where('id', $cid)->first();
        $data = [];
        $data['destinataire'] = $expedition->destinataire;
        $data['adresse_destinataire'] = $expedition->adresse_destinataire;
        $data['telephone'] = $expedition->telephone;
        $data['colis'] = $expedition->colis;
        $data['fond'] = $expedition->fond;
        $data['destination'] = $expedition->agenceDesDetail->libelle;

        return $data;
    }


    public function printChargementDetail($mass)
    {
        $pdf = new \PDF('P', 'mm', 'A4');
        $pdf::SetTitle('');
        // set margins
        $pdf::SetMargins(2, 2, 2, true);
        $pdf::SetPrintHeader(false);
        $pdf::SetPrintFooter(false);
        $pdf::SetAutoPageBreak(TRUE, 0);

        $record = Chargement_Masse_history::where('id', $mass)->first();

        foreach ($record->expeditionMassDetail as $expedition) {
            $qrcode = '<img  src="@' . base64_encode(QrCode::format('png')->size(100)->generate($expedition->num_expedition)) . '" width="77px">';
            // $qrcode = '';
            $header = '<br><br>
            <table style="width:100% !important;border:1px solid   !important; height:100% !important; " cellpadding="5">
                <tr >
                    <td style="height:50px !important; font-size: 10px !important;" >
                     <img src="/assets/front/logo-hori.png" height="40px"  width="180px" style="padding: 5px !important;" >
                    </td>

                    <td style="height:50px !important; padding=10px !important; text-align:center !important; font-size: 11px !important;" width="50%">
                    <br><br> <b> ' . $expedition->agenceDesDetail->libelle . ' </b> <br>
                    </td>
                </tr>
            </table>

            <table style="width:100% !important;border-left:1px solid !important; border-right:1px solid !important; height:100% !important; " cellpadding="5">
                <tr >
                    <td style="height:20px !important; font-size: 10px !important;text-align:left !important;" width="20%"><b>Expéditeur </b>
                    </td>

                    <td width="30%" style="height:20px !important;  font-size: 10px !important;" >' . $expedition->clientDetail->libelle . '
                    </td>

                    <td style="height:20px !important; font-size: 10px !important;text-align:left !important;" width="20%"><b>Téléphone </b>
                    </td>

                    <td width="30%" style="height:20px !important;  font-size: 10px !important;" >' . $expedition->telephone . '
                    </td>
                </tr>


                <tr >
                <td style=" border-bottom-color:#d8d8d8; height:20px !important; font-size: 10px !important; border-bottom: 0.2px red  !important;border-collapse: collapse !important;" width="20%"><b>Destinataire </b>
            </td>

                    <td width="80%" style=" border-bottom-color:#d8d8d8; height:20px !important; font-size: 10px !important; border-bottom: 0.2px red  !important;border-collapse: collapse !important;" >' . $expedition->destinataire . ' - ' . $expedition->adresse_destinataire . '
                    </td>
                </tr>
            </table>

            <table style="width:100% !important;border-left:1px solid !important; border-right:1px solid !important;  border-top-color:#d8d8d8;border-top: 0.2px red  !important;" cellpadding="5">
                <tr >
                    <td style="height:20px !important; font-size: 10px !important;" width="50%" >

        <table style="width:100% !important; border-color:#d8d8d8; border:0.1px solid !important; height:100% !important; " cellpadding="5">
        <tr >
            <td style="height:25px !important; font-size: 10px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important;" width="50%"><b>N° Expédition </b>
            </td>

            <td width="50%" style="height:25px !important;  font-size: 10px !important;" >' . $expedition->num_expedition . '
            </td>
        </tr>

        <tr >
            <td style="height:25px !important; font-size: 10px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" width="50%"><b>Montant </b>
            </td>

            <td width="50%" style="height:25px !important;  font-size: 10px !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" >' . number_format($expedition->fond, 2) . ' DH
            </td>
        </tr>

        <tr >
            <td style="height:25px !important; font-size: 8px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" width="50%"><b>Ouverture de colis</b>
            </td>

            <td width="50%" style="height:25px !important;  font-size: 10px !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" >' . $expedition->ouvertureColis . '
            </td>
        </tr>

        <tr >
        <td style="height:25px !important; font-size: 8px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" width="50%"><b>Paiement par chèque</b>
        </td>

        <td width="50%" style="height:25px !important;  font-size: 10px !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" >' . $expedition->paiementCheque . '
        </td>
    </tr>

        </table>
                    </td>
                    <td style="height:20px!important; text-align:center !important; border-bottom-color:#d8d8d8; border-left:0.2px solid !important;"  width="25%">
                    <br><br>' . $qrcode . '<br>
                    </td>
                    <td style="height:20px !important; border-bottom-color:#d8d8d8; border-left:0.2px solid !important;"  width="25%">




                    <table style="width:100% !important; border-color:#d8d8d8; border:0.1px solid !important; height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:center !important; border-right-color:#d8d8d8; border-right:0.1px solid !important;" width="100%"><b>Indication</b>
                        </td>
                    </tr>
                    </table> <h5>
                    ' . Str::limit($expedition->Indication, 40) . '</h5>
                    </td>

                </tr>
            </table>

            <table style="width:100% !important;border:1px solid   !important; height:100% !important; " cellpadding="5">
                <tr >
                <td style="height:20px !important; font-size: 10px !important;" width="80%"><b>' . $expedition->agenceDetail->libelle . ' le : ' . $expedition->created_at . '</b>
                </td>
                    <td style="height:100% !important; padding=10px !important; text-align:right !important; font-size: 10px !important;" width="20%"> <b>Colis : ' . $expedition->colis . '/1 </b>
                    </td>
                </tr>
            </table>
        ';

            $pdf::AddPage('L', 'A6');
            $pdf::WriteHTML($header, true, 0, true, 0);
        }
        $pdf::Output("Etiquettes_bon.pdf");
    }

    public function getPrixColis(Request $request)
    {
        $prixInitial = Taxation::getPrixColis($request->client, $request->agence_des, $request->agence);
        $client = Client::where('id', $request->client)->first();

        if (isset($request->vDeclaree)) {
            if (!isset($request->colis)) {
                $prix =  $prixInitial +  (($client->valeur_declaree  / 100) * $request->vDeclaree);
            } else {
                $prix = ($request->colis *  $prixInitial) + (($client->valeur_declaree / 100) * $request->vDeclaree);
            }
        } else {
            if (!isset($request->colis)) {
                if ($client->vplafond <= $request->fond && $client->vplafond != null) {

                    $prix =   $prixInitial + (($client->valeur_declaree / 100) * $request->fond);
                } else {

                    $prix =  $prixInitial;
                }
            } else {
                if ($client->vplafond <= $request->fond && $client->vplafond != null) {

                    $prix =   ($request->colis *  $prixInitial) + (($client->valeur_declaree / 100) * $request->fond);
                } else {

                    $prix =   $request->colis *  $prixInitial;
                }
            }
        }



        return $prix;
    }
    public function commentaireAnulation($expedition, $oldetape, $newetape)
    {
        $commentaire = new Commentaire();
        $commentaire->code = "Annulation de l'Étape";
        $commentaire->commentaires = 'du : ' . Expedition::getEtapeCommentaire($oldetape) . ' ==> ' . Expedition::getEtapeCommentaire($newetape);
        $commentaire->id_expedition = $expedition;
        $commentaire->id_utilisateur = auth()->user()->id;
        $commentaire->save();
    }

    public function commentaireAffictation($expeditions, $livreur)
    {
        foreach ($expeditions as $expedition) {
            $commentaire = new Commentaire();
            $commentaire->code = "AFFECTATION";
            $commentaire->commentaires = "Affectation au livreur " . $livreur;
            $commentaire->id_expedition = $expedition->id;
            $commentaire->bon = $expedition->bl;
            $commentaire->id_utilisateur = auth()->user()->id;
            $commentaire->save();
        }
    }
    public function expeditionNotification($expeditions, $employer)
    {

        foreach ($expeditions as $expedition) {
            if ($expedition->sens == 'Envoi') {
            $message = "Votre expédition " . $expedition->num_expedition . " est en cours de livraison par le livreur " . $employer->libelle . ", Num : " . $employer->telephone . ", Vous pouvez toujours voir les mis à jour de l'expedition sur le lien : " . url('/search?&search_exp=' . $expedition->num_expedition) . " ";
            notificationWhatsapp::whatsappMessage($expedition->telephone, $message);
            }
        }
    }
    public function etapeHistory($expeditions, $bon_id)
    {
        foreach ($expeditions as $expedition) {

            // this is for bon livraison to show etape statut in pdf
            $etapeHistory = new etapeHistory();
            $etapeHistory->expedition = $expedition->id;
            $etapeHistory->bl_id = $bon_id;
            $etapeHistory->agence  = $expedition->agence;
            $etapeHistory->agence_des  = $expedition->agence_des;
            $etapeHistory->fond  = $expedition->fond;
            $etapeHistory->etape  = "16";
            $etapeHistory->save();
            // this is for client history
            $etapeHistory = new etapeHistory();
            $etapeHistory->expedition = $expedition->id;
            $etapeHistory->agence  = $expedition->agence;
            $etapeHistory->agence_des  = $expedition->agence_des;
            $etapeHistory->etape  = "16";
            $etapeHistory->save();
        }
    }

    public function historyExpeditionShow(Request $request)
    {

        $cid = $request->post('cid');

        $expedition = Expedition::where('id', $cid)->first();

        $data = "";
        foreach ($expedition->etapeHistory as $history) {
            if ($history->etape != '13' &&  $history->etape != '15' && $history->etape != null && $history->bl_id == null) {
                $data .= '<tr>';
                if (($history->etape == 20)) {
                    $data .= "<td>" . $history->created_at . "</td>
                    <td>$history->libelle </td>";
                } else {
                    $data .= "<td>" . $history->created_at . "</td>
                    <td> " . $history->getEtape() . " </td>";
                }
                $data .= '</tr>';
            }
        }

        $list = "
        <div class='modal-content'>
            <h4> Historique du statut</h4>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>" . $data . "
                    </tbody>
                    </table>



                </div>
                <input type='hidden' name='delId' id='delId'>
            </div>
            <div class='modal-footer'>
                <a href='#!' class='modal-close waves-effect waves-green btn red'>Fermer</a>

            </div>
        ";
        return $list;
    }

    public function colisEnEchangeList(Request $request)
    {

        $cid = $request->post('cid');

        $expeditions = Expedition::all()->where('deleted', '0')
            ->where('client', $cid)
            ->where('etape', '14')
            ->where('deleted', '0');

        // $list = '';
        // foreach ($processus as $bon) {

        //     if ($bon->ExpeditionDetail->retour_fond == "CR") {
        //         $retour_fond = 'C. espèce';
        //     } else {
        //         $retour_fond = 'Simple';
        //     }


        //     $list .= ' <tr>
        //      <td style="padding-right: 18px;"> <label>
        //                                             <input class="checkbox"
        //                                                 type="checkbox" name="expedition[' . $bon->ExpeditionDetail->id . '][check]" value = "' . $bon->ExpeditionDetail->num_expedition . '">
        //                                             <span></span>
        //                                         </label> </td>
        //      <td>' . $bon->ExpeditionDetail->num_expedition . '</td>
        //      <td>' . $bon->ExpeditionDetail->created_at . '</td>
        //      <td>' . $bon->ExpeditionDetail->clientDetail->libelle . '</td>
        //      <td>' . $bon->ExpeditionDetail->agenceDetail->libelle . '</td>
        //      <td>' . $bon->ExpeditionDetail->destinataire . '</td>
        //      <td>' . $bon->ExpeditionDetail->agenceDesDetail->libelle . '</td>
        //      <td>' . $bon->ExpeditionDetail->telephone . '</td>
        //      <td>' . $bon->ExpeditionDetail->getEtape() . '</td>
        //      <td>' . $bon->ExpeditionDetail->colis . '</td>
        //      <td>' . $retour_fond . '</td>
        //      <td>' . $bon->ExpeditionDetail->fond . '</td>
        //      <td>' . $bon->ExpeditionDetail->port . '</td>
        //      <td>' . $bon->ExpeditionDetail->ttc . '</td>
        //  </tr>';
        // }

        //        " <div class='col col m12 s12 px-5 input-field' id='Expeditions'>
        //         <select name='expedition_ech' id='mes_expedition'
        //             class='select2 browser-default'>
        //             <option value=''></option>
        // "
        $list = " ";
        foreach ($expeditions as $row) {

            $list .= " <option class='option'
                 $row->id == old('mes_expeditions') ? 'selected' : ''
                value='$row->id '> $row->num_expedition
            </option>";
        }



        // "    </select>


        if (isset($expeditions)) {
            return $list;
        } else {
            return 0;
        }
    }
}
