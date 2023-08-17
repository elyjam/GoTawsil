<?php

namespace App\Http\Controllers;

use DB;
use DataTables;
use App\Models\Bon;
use App\Models\Client;
use App\Models\Expedition;
use App\Models\Commentaire;
use App\Models\etapeHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\Processus_expedition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Console\Input\Input;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class BonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function getRules()
    {
        return [];
    }

    public static function pdf_bon(Bon $bon)
    {
        $pdf = new \PDF('P', 'mm', 'A4');
        $pdf::SetTitle('');
        // set margins
        $pdf::SetMargins(2, 2, 2, true);
        $pdf::SetPrintHeader(false);
        $pdf::SetPrintFooter(false);
        $pdf::SetAutoPageBreak(TRUE, 0);

        $record = Expedition::all()->where('id_bon', $bon->id);
        foreach ($record as $expedition) {
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


    public function list(Request $request)
    {
        // if ($request->isMethod('post')) {
        //     $request->flash();
        //     $star = Carbon::parse($request->start_date)->format("Y-m-d 00:00:00");
        //     $end = Carbon::parse($request->end_date)->format("Y-m-d 23:59:59");

        //     $records  = Bon::where('type', null)
        //         ->get();
        //         if($request->start_date != null){
        //             $records= $records->where("created_at", '>=', $star);
        //         }
        //         if($request->end_date != null){
        //             $records= $records->where("created_at", '<=', $end);
        //         }
        //     if ($request->code != null) {
        //         $records = $records->where("code", $request->code);
        //     }

        //     if (auth()->user()->role == '7') {
        //         $records = $records->whereIn('id_agence_exp', \Auth::user()::getUserVilles());
        //     }

        //     return view(
        //         'back/bon/list',
        //         [
        //             'records' => $records,
        //             'star_date' =>  '',
        //             'end_date' =>   ''
        //         ]
        //     );
        // }


        // $now = Carbon::now();


        $request->flash();
        // $records  = Bon::where('deleted', "0")
        //     ->where("created_at", '>=',  $now->subWeek())

        //     ->get();

        // if (auth()->user()->role == '7') {
        //     $records = $records->whereIn('id_agence_exp', \Auth::user()::getUserVilles());
        // }


        return view(
            'back/bon/list'
        );
    }

    public function bonApi(Request $request)
    {

        $formData = array();
        parse_str($request->all()['form'], $formData);

        $query =  DB::table("bons")
            ->select(
                "*",
                \DB::raw('bons.id as id'),
                \DB::raw('bons.created_at as created_at'),
                \DB::raw('bons.code as code'),
                \DB::raw('bons.date_validation as date_validation'),
                \DB::raw('clients.libelle as libelle'),


            )
            ->leftJoin('clients', 'clients.id', '=', 'bons.id_client')
            ->where('bons.type','=', 'RAMASSAGE');
        if (auth()->user()->role == '7') {
            $query = $query->whereIn('id_agence_exp', \Auth::user()::getUserVilles());
        }


        if (isset($formData['code']) && strlen(trim(($formData['code']))) > 0) {
            $query->where('bons.code', '=', $formData['code']);
        }
        if (isset($formData['start_date']) && strlen(trim(($formData['start_date']))) > 0) {
            $query->whereDate("bons.created_at", '>=', $formData['start_date']);
        }
        if (isset($formData['end_date']) && strlen(trim(($formData['end_date']))) > 0) {
            $query->whereDate("bons.created_at", '<=', $formData['end_date']);
        }

        return Datatables::of($query)->addIndexColumn()
            ->addColumn('etiquette', function ($record) {
                return ' <a target="_blank" href="' . route('pdf_bon', $record->id) . '"><i
                class="material-icons tooltipped" style="color: #2196f3;"
                data-position="top"
                data-tooltip="Imprimer etiquette">print</i></a>';
            })->addColumn('editer', function ($record) {
                return ' <a href="' . route('bon_print_detail', $record->id) . '" target="_blank"><i
                class="material-icons tooltipped" style="color: #2196f3;"
                data-position="top"
                data-tooltip="Imprimer bon N° ' . $record->code . ' ">print</i></a>';
            })->addColumn('partiel', function ($record) {
                return '<a href="' . route('modif_bon', $record->id) . '"><i
                class="material-icons tooltipped" style="color: #2196f3;"
                data-position="top"
                data-tooltip="Détail du bon ">library_books</i></a> ';
            })->addColumn('valider', function ($record) {

                if ($record->date_validation) {
                    return '<i class="material-icons green-text tooltipped"
                    data-position="top"
                   data-tooltip="Caisse déjà validé ">verified_user</i>';
                } else {
                    return '<a href="#!" onclick="openValideModal(' . $record->id . ')"><i
                class="material-icons tooltipped" style="color: #2196f3;"
                data-position="top"
                data-tooltip="Valider la caisse ">verified_user</i></a>';
                }
            })

            ->rawColumns(['etiquette', 'editer', 'partiel', 'valider'])
            ->make(true);
    }

    public function create(Request $request)
    {

        if ($request->isMethod('post')) {
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                Bon::create($request->all());
                Redirect::to(route('bon_list'))->send();
            }
        }
        $viewsData = [];


        return view('back/bon/create', $viewsData);
    }

    public function update(Bon $bon, Request $request)
    {

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $bon->update($request->all());
                Redirect::to(route('bon_list'))->send();
            }
        }
        $viewsData['record'] = $bon;


        return view('back/bon/update', $viewsData);
    }

    public function delete(Bon $bon)
    {
        $bon->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

    public function listClient(Request $request)
    {


        $idClientauth = auth()->user()->ClientDetail->id;
        $request->flash();
        return view(
            'client.Ramassage.list_ram',
            [
                'records' => Expedition::all()->where('deleted', "0")->where('client', $idClientauth)->where('etape', '1')->where('id_bon', NULL)
            ]
        );
    }

    // demande de ramassage pour le client
    public function demandeRamClient(Request $request)
    {

        $idClientauth = auth()->user()->ClientDetail->id;
        $seuilcoli = auth()->user()->ClientDetail->seuil_colis;
        // $expeditions =  Expedition::where('client', $idClientauth)->where("id_bon", null)->get();
        //check if the client able to do Ramassage

        switch ($request->input('action')) {
            case 'print':
                if ($request->expeditions == null) {
                    return redirect()->back()->with('unsuccess', "Vous devez sélectionner des expéditions pour faire l'impression");
                } else {
                $expeditions =  Expedition::whereIn('id', $request->expeditions)->get();
                Bon::printDetailClient($expeditions);
                }
                break;

            case 'dem_ram':

                if ($request->expeditions == null) {
                    return redirect()->back()->with('unsuccess', "Vous devez selectionner minimum $seuilcoli colis pour faire une demande de rammasage");
                } else {
                    $expeditions = Expedition::whereIn('id', $request->expeditions)->get();
                    if (intval($seuilcoli) <=  count($expeditions)) {

                        $count = Bon::all()->count();
                        $bon = bon::create([
                            'code' => BonController::getcode('R', $count),
                            'id_client' => $idClientauth,
                            'type' => 'RAMASSAGE',
                            'id_agence_exp' => auth()->user()->ClientDetail->agenceDetail->id
                        ]);


                        foreach ($expeditions as $expedition) {
                            Expedition::where('id', $expedition->id)->update([
                                'id_bon' => $bon->id,
                            ]);
                        }
                        return redirect()->back()->with('success', "Votre demande de ramassage a été fait avec succès");
                    } else {
                        return redirect()->back()->with('unsuccess', "Vous devez passer $seuilcoli colis pour faire le ramassage");
                    }
                }





                break;
        }
    }




    public function validateRam($id)
    {

        $bon = bon::find($id);
        // $expUpdate = $bon->expeditionDetail()->update([
        //     'etape' => '2'
        // ]);

        foreach ($bon->expeditionDetail as $expedition) {

            if ($expedition->port == 'PP') {

                $expedition->etape = '2';
                $expedition->caissepp_emp = auth()->user()->id;
                $expedition->save();
            } else {

                $expedition->etape = '2';
                $expedition->save();
            }
        }
        $bon->update([
            'date_validation' => date('Y-m-d H:i:s')
        ]);

        // creation des processus expedition
        BonController::addProcessusRam($bon->expeditionDetail, $bon);
        // creation des historique de l'etape
        BonController::etapeHistory($bon->expeditionDetail, 2);
        // creationg comment for expedition
        BonController::addCommentaire($bon->expeditionDetail, 'RAMASSAGE', 'VALIDATION RAMASSAGE');

        return redirect()->back()->with('validate', "Le ramassage a été validé");
    }

    // list force ramassage
    public function listFrocer()
    {




        if (auth()->user()->role != '1') {
            $clients = DB::table('clients')
                ->select('*', DB::raw('sum(expeditions.colis) as colissum'), DB::raw('count(expeditions.id) as expcount'))
                ->leftJoin('expeditions', 'clients.id', '=', 'expeditions.client')
                ->where('expeditions.id_bon', null)
                ->where('expeditions.etape', '=', '1')
                ->whereIn('clients.agence', \Auth::user()::getUserVilles())
                ->groupBy('clients.id')
                ->get();
        } else {
            $clients = DB::table('clients')
                ->select('*', DB::raw('sum(expeditions.colis) as colissum'), DB::raw('count(expeditions.id) as expcount'))
                ->leftJoin('expeditions', 'clients.id', '=', 'expeditions.client')
                ->where('expeditions.id_bon', null)
                ->where('expeditions.etape', '=', '1')
                ->groupBy('clients.id')
                ->get();
        }




        return view('back.bon.list_force_ram', [
            'records' =>  $clients
        ]);
    }

    // validation du bon sur forcer ramassage
    public function validateForceRam($id)
    {

        $count = Bon::all()->count();
        $bon = bon::create([
            'code' => BonController::getcode('R', $count),
            'id_client' => $id,
            'type' => 'RAMASSAGE',
            'date_validation' => date('Y-m-d H:i:s')

        ]);

        $expeditionForce =  Expedition::where('client', $id)
            ->where("id_bon", null)
            ->where('etape', '=', '1')
            ->get();
        foreach ($expeditionForce as $expedition) {
            if ($expedition->port == 'PP') {
                $expedition->id_bon =  $bon->id;
                $expedition->etape = "3";
                $expedition->caissepp_emp = auth()->user()->id;
                $expedition->save();

            } else {

                $expedition->id_bon =  $bon->id;
                $expedition->etape = "3";
                $expedition->save();
            }
        }



        $expeditions = Expedition::where('id_bon', $bon->id)->get();

        // creation des processus expedition
        BonController::addProcessusRam($expeditions, $bon);
        // creation des historique de l'etape
        BonController::etapeHistory($expeditions, 3);
        // creationg comment for expedition
        BonController::addCommentaire($expeditions, 'RAMASSAGE', 'VALIDATION FORCER RAMASSAGE' , $bon->id);

        return redirect()->back()->with('validate', "Le ramassage a été validé avec succès");
    }

    // modification des expedition du bon
    public function modifBon($id)
    {
        $bon = bon::find($id);

        return view('back.bon.modif_bon', [
            'records' => $bon
        ]);
    }


    public function insertBon($id, Request $request)
    {

        switch ($request->submitbutton) {
            case 'Modifier Frais':
                $bon = bon::where('id', $id)->first();
                foreach ($bon->expeditionDetail as $expedition) {
                    $ttc = $expedition->ttc;
                    $expedition->ttc = $request->updateFields[$expedition->id]['ttc'];
                    $expedition->save();
                    //creation comment for the expedition
                    $commentaire = new Commentaire();
                    $commentaire->code = "MODIFICATION_FRAIS";
                    $commentaire->commentaires = "Modification TTC du : " . $ttc . " ==>" . $request->updateFields[$expedition->id]['ttc'];
                    $commentaire->id_expedition = $expedition->id;
                    $commentaire->id_utilisateur = auth()->user()->id;
                    $commentaire->save();
                }

                return redirect()->back()->with('validate', "Les colis ont été modifiées");
                break;

            case 'Réception colis':
                //check if select all

                if ($request->selectall) {
                    //validation colis selected
                    $bon = bon::find($id);

                    foreach ($bon->expeditionDetail as $expedition) {

                        if ($expedition->port == 'PP') {

                            $expedition->etape = '2';
                            $expedition->caissepp_emp = auth()->user()->id;
                            $expedition->save();
                        } else {

                            $expedition->etape = '2';
                            $expedition->save();
                        }
                    }
                    // creation processus de l'expediton
                    BonController::addProcessusRam($bon->expeditionDetail, $bon);
                    // creation des historique de l'etape
                    BonController::etapeHistory($bon->expeditionDetail, 2);
                    // $bon->update([
                    //     'date_validation' => date('Y-m-d H:i:s')
                    // ]);

                    // creationg comment for expedition
                    BonController::addCommentaire($bon->expeditionDetail, 'RAMASSAGE', "VALIDATION RAMASSAGE");
                } else {
                    // ajouter que les expedition checkee dans le bon
                    $bon = bon::where('id', $id)->first();
                    foreach ($bon->expeditionDetail as $expedition) {
                        if (!empty($request->updateFields[$expedition->id]['check'])) {
                            if ($expedition->port == 'PP') {
                                $expedition->etape = '2';
                                $expedition->caissepp_emp = auth()->user()->id;
                                $expedition->save();
                            } else {

                                $expedition->etape = '2';
                                $expedition->save();
                            }

                            // creation processus de l'expediton
                            BonController::addProcessusRam($expedition, $bon, 'one');
                            // creation des historique de l'etape
                            $etapeHistory = new etapeHistory();
                            $etapeHistory->expedition = $expedition->id;
                            $etapeHistory->etape = 2;
                            $etapeHistory->save();
                            //creation comment for the expedition
                            $commentaire = new Commentaire();
                            $commentaire->code = "RAMASSAGE";
                            $commentaire->commentaires = "VALIDATION RAMASSAGE";
                            $commentaire->id_expedition = $expedition->id;
                            $commentaire->id_utilisateur = auth()->user()->id;
                            $commentaire->save();
                        }
                    }

                }

                if($bon->expeditionDetail->where('etape','=','1')->count() == 0){
                    $bon->update([
                        'date_validation' => date('Y-m-d H:i:s')
                    ]);
                }

                return redirect()->back()->with('validate', "Le ramassage a été validé avec succès");
                break;
        }
    }

    // affichage des list client et expedition dans le blade
    public function modifforce($id)
    {
        $client = Client::where('id', $id)->first();

        return view('back.bon.modif_force_ram', [
            'records' => $client
        ]);
    }

    //force ramassage insertion des colis dans le bon et modification les prix des colis
    public function insertForce($id, Request $request)
    {
        switch ($request->submitbutton) {
            case 'Modifier Frais':
                $client = Client::where('id', $id)->first();
                foreach ($client->expeditionDetail as $expedition) {
                    if (!empty($request->updateFields[$expedition->id]['ttc'])) {
                        $expedition->ttc = $request->updateFields[$expedition->id]['ttc'];
                        $expedition->save();
                    }
                }

                return redirect()->back()->with('validate', "Les colis ont été modifiées avec succès");
                break;

            case 'Réception colis':
                //check if select all
                if ($request->selectall) {
                    $count = Bon::all()->count();
                    $bon = bon::create([
                        'code' => BonController::getcode('R', $count),
                        'id_client' => $id,
                        'type' => 'RAMASSAGE',
                        'date_validation' => date('Y-m-d H:i:s')
                    ]);
                    $client = Client::find($id);
                    BonController::addProcessusRam($client->expeditionBonNull, $bon);
                    // creation des historique de l'etape
                    BonController::etapeHistory($client->expeditionBonNull, 3);
                    // creationg comment for expedition
                    BonController::addCommentaire($client->expeditionBonNull, 'RAMASSAGE', 'VALIDATION FORCER RAMASSAGE',$bon->id);




                    foreach ($client->expeditionBonNull as $expedition) {
                        if ($expedition->port == 'PP') {
                            $expedition->id_bon =  $bon->id;
                            $expedition->etape = "3";
                            $expedition->caissepp_emp = auth()->user()->id;
                            $expedition->save();
                        } else {
                            $expedition->id_bon =  $bon->id;
                            $expedition->etape = "3";
                            $expedition->save();
                        }
                    }
                }elseif($request->selectField == null){
                    return redirect()->back()->with('error', "Vous devez choisir un colis");
                }else {

                    $client = Client::find($id);
                    $count = Bon::all()->count();
                    $bon = bon::create([
                        'code' => BonController::getcode('R', $count),
                        'id_client' => $id,
                        'type' => 'RAMASSAGE',
                        // 'date_validation' => date('Y-m-d H:i:s')
                    ]);


                    foreach ($client->expeditionBonNull as $expedition) {
                        if(in_array($expedition->id,$request->selectField)){
                            if ($expedition->port == 'PP') {
                                $expedition->id_bon =  $bon->id;
                                $expedition->etape = "3";
                                $expedition->caissepp_emp = auth()->user()->id;
                                $expedition->save();
                            } else {
                                $expedition->id_bon =  $bon->id;
                                $expedition->etape = "3";
                                $expedition->save();
                            }

                            BonController::addProcessusRam($expedition, $bon, 'one');
                            // creation des historique de l'etape
                            $etapeHistory = new etapeHistory();
                            $etapeHistory->expedition = $expedition->id;
                            $etapeHistory->etape = 3;
                            $etapeHistory->save();

                            //creation comment for the expedition
                            $commentaire = new Commentaire();
                            $commentaire->code = "RAMASSAGE";
                            $commentaire->commentaires = 'VALIDATION FORCER RAMASSAGE';
                            $commentaire->id_expedition = $expedition->id;
                            $commentaire->bon = $bon->id;
                            $commentaire->id_utilisateur = auth()->user()->id;
                            $commentaire->save();
                        }
                    }
                }

                if($bon->expeditionDetail->where('etape','=','1')->count() == 0){
                    $bon->update([
                        'date_validation' => date('Y-m-d H:i:s')
                    ]);
                }

                return view('back.bon.list', [
                    'records' => Bon::all()->where('deleted', "0")
                ])->with('validate', "Le ramassage a été validé");
                break;
        }
    }



    // fonctiob pour ajouter processus expedition de ramassage
    public function addProcessusRam($data, $bon, $one = null)
    {


        if ($one == null) {
            foreach ($data as $expedition) {

                if ($expedition->processusDetail->count() == 0) {

                    $ramassge = new Processus_expedition();
                    $ramassge->code = 'RAMASSAGE';
                    $ramassge->id_expedition = $expedition->id;
                    $ramassge->id_bon_ramassage = $bon->id;
                    $ramassge->date_validation = $bon->date_validation;
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
                }
            }
        } else {
            if ($data->processusDetail->count() == 0) {
                // creation processus de l'expediton
                $ramassge = new Processus_expedition();
                $ramassge->code = 'RAMASSAGE';
                $ramassge->id_expedition = $data->id;
                $ramassge->id_bon_ramassage = $bon->id;
                $ramassge->date_validation = $bon->date_validation;
                $ramassge->id_agence_dest = $data->agence_des;
                $ramassge->id_agence_exp = $data->agence;
                $ramassge->save();

                $chargement = new Processus_expedition();
                $chargement->code = 'CHARGEMENT';
                $chargement->id_expedition = $data->id;
                $chargement->id_agence_dest = $data->agence_des;
                $chargement->id_agence_exp = $data->agence;
                $chargement->save();

                $livraison = new Processus_expedition();
                $livraison->code = 'LIVRAISON';
                $livraison->id_expedition = $data->id;
                $livraison->id_agence_dest = $data->agence_des;
                $livraison->save();
            }
        }
    }

    public function clientRmBon()
    {
        $bons = Bon::where('type', 'RAMASSAGE')->where('id_client', auth()->user()->ClientDetail->id)->where('deleted', 0)->get();

        return view('client.bon.list', [
            'records' => $bons
        ]);
    }

    public function getcode($letter, $object)
    {
        $code = $letter . sprintf("%06d", $object + 1);
        return $code;
    }

    public function etapeHistory($expeditions, $etape)
    {

        foreach ($expeditions as $expedition) {
            $etapeHistory = new etapeHistory();
            $etapeHistory->expedition = $expedition->id;
            $etapeHistory->etape = $etape;
            $etapeHistory->save();
        }
    }

    public function addCommentaire($expeditions, $code, $message = null,$bon = null)
    {

        foreach ($expeditions as $expedition) {
            $commentaire = new Commentaire();
            $commentaire->code = $code;
            $commentaire->id_expedition = $expedition->id;
            $commentaire->commentaires = $message;
            $commentaire->bon = $bon;
            $commentaire->id_utilisateur = auth()->user()->id;
            $commentaire->save();
        }
    }

    public function printDetail(Bon $bon)
    {
        Bon::printDetail($bon);
    }
}
