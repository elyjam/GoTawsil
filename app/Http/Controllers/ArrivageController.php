<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Bon;
use App\Models\Agence;
use App\Models\Client;
use App\Models\Expedition;
use App\Models\Commentaire;
use App\Models\etapeHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Processus_expedition;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;
use App\Http\Controllers\ArrivageController as ControllersArrivageController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Ville;

class ArrivageController extends Controller
{

    public function list()
    {

        if (auth()->user()->role == '7' || auth()->user()->role == '8') {
            $records = Bon::where('type', "FCHARGE")->where('date_validation', null)
            ->whereIn('id_agence_dest', \Auth::user()::getUserVilles())
            ->orderby('created_at', 'desc')
            ->get();

        } else {
            $records = Bon::where('type', "FCHARGE")
            ->where('date_validation', null)
            ->orderby('created_at', 'desc')
            ->get();
        }


        return view('back.arrivage.list', [
            'records' => $records
        ]);
    }

    public function create(Request $request)
    {
        $bon = bon::where('id', $request->arrivage)->first();

        $processus = Processus_expedition::where('id_feuille_charge', $request->arrivage)->get();

        foreach ($processus as $proce) {

            if (!empty($request->expedition[$proce->ExpeditionDetail->id]['check'])) {

                Expedition::where('id', $proce->ExpeditionDetail->id)->update(['etape' => 13]);
                $proce->date_reception = date('Y-m-d H:i:s');
                $proce->save();

                $etapeHistory = new etapeHistory();
                $etapeHistory->expedition = $proce->ExpeditionDetail->id;
                $etapeHistory->etape = 13;
                $etapeHistory->save();

                //creation comment for the expedition
                $commentaire = new Commentaire();
                $commentaire->code = "TRANSIT";
                $commentaire->commentaires = "COLIS PERDU";
                $commentaire->id_expedition = $proce->ExpeditionDetail->id;
                $commentaire->id_utilisateur = auth()->user()->id;
                $commentaire->save();
                $bon->update([
                    'date_validation' => date('Y-m-d H:i:s'),
                ]);
            } else {

                $proce->date_reception = date('Y-m-d H:i:s');
                Expedition::where('id', $proce->ExpeditionDetail->id)->update(['etape' => 10]);
                $proce->save();

                $etapeHistory = new etapeHistory();
                $etapeHistory->expedition = $proce->ExpeditionDetail->id;
                $etapeHistory->etape = 10;
                $etapeHistory->save();

                //creation comment for the expedition
                $commentaire = new Commentaire();
                $commentaire->code = "ARRIVAGE";
                $commentaire->commentaires = "VALIDATION ARRIVAGE";
                $commentaire->id_expedition = $proce->ExpeditionDetail->id;
                $commentaire->id_utilisateur = auth()->user()->id;;
                $commentaire->save();
                $bon->update([
                    'date_validation' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return redirect()->back()->with('validate', 'La réception a été faite avec succès');
    }


    public function afficherBon(Request $request)
    {

        $cid = $request->post('cid');

        $processus = Processus_expedition::where('id_feuille_charge', $cid)->get();

        $list = '';
        foreach ($processus as $bon) {

            if ($bon->ExpeditionDetail->retour_fond == "CR") {
                $retour_fond = 'C. espèce';
            } else {
                $retour_fond = 'Simple';
            }


            $list .= ' <tr>
             <td style="padding-right: 18px;"> <label>
                                                    <input class="checkbox"
                                                        type="checkbox" name="expedition[' . $bon->ExpeditionDetail->id . '][check]" value = "' . $bon->ExpeditionDetail->num_expedition . '">
                                                    <span></span>
                                                </label> </td>
             <td>' . $bon->ExpeditionDetail->num_expedition . '</td>
             <td>' . $bon->ExpeditionDetail->created_at . '</td>
             <td>' . $bon->ExpeditionDetail->clientDetail->libelle . '</td>
             <td>' . $bon->ExpeditionDetail->agenceDetail->libelle . '</td>
             <td>' . $bon->ExpeditionDetail->destinataire . '</td>
             <td>' . $bon->ExpeditionDetail->agenceDesDetail->libelle . '</td>
             <td>' . $bon->ExpeditionDetail->telephone . '</td>
             <td>' . $bon->ExpeditionDetail->getEtape() . '</td>
             <td>' . $bon->ExpeditionDetail->colis . '</td>
             <td>' . $retour_fond . '</td>
             <td>' . $bon->ExpeditionDetail->fond . '</td>
             <td>' . $bon->ExpeditionDetail->port . '</td>
             <td>' . $bon->ExpeditionDetail->ttc . '</td>
         </tr>';
        }

        return $list;
    }

    public function stockList(Request $request)
    {

        $records = Expedition::getStockList();
        if (auth()->user()->role == '5' || auth()->user()->role == '7') {
            //   $records = Expedition::getStock()->whereIn('agence', \Auth::user()::getUserVilles());
            $records = Expedition::getStockList()
            ->whereIn('agence_des', \Auth::user()::getUserVilles());
        }
        $villes_des = $records->unique('agence_des')->pluck('agence_des')->toArray();
        // $ville_selected = 0;
        // $villes = $records->unique('agence')->pluck('agence')->toArray();
        // if ($request->isMethod('post')) {
        //     $data = $request->all();
        //     $rules = [
        //         'ville' => 'required',
        //     ];
        //     $selected =  $request->ville;
        //     if($selected != '0'){

        //         $records = Expedition::whereIn('etape', ['10', '16','20'])
        //         ->where('agence_des', $request->ville)
        //         ->OrWhere('agence',$request->ville)
        //         ->whereIn('etape',['2','3'])
        //         ->get();

        //     }else{
        //         $records = Expedition::getStockList();
        //     }

        // }


        // return view('back.arrivage.stock_list', [
        //     'records' => $records,
        //     'selected' => $selected ?? 0,
        //     'villes' => Ville::all()->whereIn('id', $villes),
        // ]);


        $request->flash();
        $viewsData = [];

        $viewsData['agenceRecords'] = \App\Models\Ville::all()->where('id', "!=", 2)->whereIn('id', $villes_des);
        $viewsData['selected'] =  $request->ville ?? 0;


        return view('back.arrivage.stock_list', $viewsData);
    }

    public function apiStock(Request $request)
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
            })
            ->whereIn('etape', ['2', '3', '10', '16','15', '20']);




        if (isset($formData['ville']) && $formData['ville'] != '0') {
            $query->whereIn('expeditions.etape', ['10', '16', '20'])
                ->where('expeditions.agence_des', '=', $formData['ville'])
                ->OrWhere('expeditions.agence', '=', $formData['ville'])
                ->whereIn('expeditions.etape', ['2', '3','15']);
        }

        if (auth()->user()->role == '5' || auth()->user()->role == '7') {
            //   $records = Expedition::getStock()->whereIn('agence', \Auth::user()::getUserVilles());
            $query = $query->whereIn('agence_des', \Auth::user()::getUserVilles());
        }





        return Datatables::of($query)->addIndexColumn()
            ->addColumn('typeicon', function ($record) {
                if ($record->type == 'CDP')
                    return '<i class="blue-text material-icons" title="Contre document">email</i>';
                elseif ($record->type == 'ECOM')
                    return '<i class="red-text material-icons" title="Contre espèce">inbox</i>';
                elseif ($record->type == 'COLECH')
                    return '<i class=" material-icons" title="Colis en échange" style="color: #d8a71d ">autorenew</i>';
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
                return $record->retour_fond == 'CR' ? 'C. espèce' : 'Simple';
            })

            ->addColumn('statut_label', function ($record) {
                if ($record->key == 1 || $record->key == 2 || $record->key == 3) {
                    return  '<p class="green-text" style="font-weight:900;text-align:center;">' . $record->value . ' </p>';
                } elseif ($record->key == 4 || $record->key == 15 || $record->key == 6) {
                    return  '<p class=" blue-text" style="font-weight:900;text-align:center;">' . $record->value . '</p>';
                } elseif ($record->key == 7 || $record->key == 8 || $record->key == 9) {
                    return  '<p class="red-text" style="font-weight:900;text-align:center;">' . $record->value . '</p>';
                } elseif ($record->key == 20 || $record->key == 13 || $record->key == 5) {
                    return  '<p class="grey-text" style="font-weight:900;text-align:center;">' . $record->value . '</p>';
                } else {
                    return  '<p class="purple-text" style="font-weight:900;text-align:center;">' . $record->value . '</p>';
                }
            })

            ->addColumn('client', function ($record) {
                return  $record->client . '<p> <span class=" badge grey" data-badge-caption="' . $record->agence . '"> </span> </p>';
            })


            ->addColumn('action', function ($record) {
                $action = '';
                if (\Auth::user()::hasRessource('SMenu stock - Button Retour')){
                    $action .= '<a href="#!" onclick="openRetourModal(' . $record->id . ')"><i class="material-icons" title="Retour">assignment_return</i></a>';
                }
                if (\Auth::user()::hasRessource('SMenu stock - Button Transfert')){
                    $action .= '<a href="#!" onclick="openTransfertModal( ' . $record->id . ')"><i class="material-icons green-text" title="Transfert">sync</i></a>';
                }
                return $action;
            })
            ->rawColumns(['client', 'statut_label', 'created_at', 'action', 'typeicon', 'num_expedition', 'destinataire'])
            ->make(true);
    }
    public function stockPerduList(Request $request)
    {
        if ($request->isMethod('post')) {
            $records = Expedition::where('etape', '13')
                ->where('deleted', '0')->get();

            $spreadsheet = IOFactory::load(storage_path('export/colisPerdu.xlsx'));
            $i = 2;

            foreach ($records as $record) {

                $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->num_expedition);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->clientDetail->libelle);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $record->type);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $record->destinataire);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->adresse_destinataire);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $record->telephone);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . $i, $record->retour_fond);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . $i, $record->fond);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("I" . $i, $record->port);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("J" . $i, $record->ttc);
                $i++;
            }

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="colisPerdu.xlsx"');

            $writer->save('php://output');
            exit();
        }
        $records = Expedition::where('etape', '13')
            ->where('deleted', '0')->get();

        if (auth()->user()->role == '5' || auth()->user()->role == '7' || auth()->user()->role == '8') {
            $records = $records->whereIn('agence', \Auth::user()::getUserVilles());
        }
        return view('back.arrivage.stock_list_perdu', [
            'records' => $records
        ]);
    }

    public function expRetour(Expedition $expedition)
    {

        ControllersArrivageController::etapeHistory($expedition->id, $expedition->etape, $expedition->agence, $expedition->agence_des, $expedition->fond, $expedition->ttc, $expedition->port, $expedition->retour_fond);
        $expedition->update([

            'agence_des' => $expedition->clientDetail->agenceDetail->id,
            'agence' => $expedition->agence_des,
            'etape' => 6,
            'fond' => 0,
            'ttc' => 10,
            'port' => 'PPNE',
            'sens' => 'Retour',
            'retour_fond' =>  'S',
            'date_retour' => date("Y-m-d H:i:s"),
        ]);
        ControllersArrivageController::etapeHistory($expedition->id, $expedition->etape);

        //creation comment for the expedition
        $commentaire = new Commentaire();
        $commentaire->code = "RETOUR";
        $commentaire->id_expedition = $expedition->id;
        $commentaire->id_utilisateur = auth()->user()->id;
        $commentaire->save();

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



        return redirect()->back()->with('success', "L'expédition a été retournée avec succès");
    }

    public function transfert($id)
    {
        $expedition = Expedition::findOrFail($id);

        $agenceRecords = Ville::where('deleted', '0')->where('id', "!=", 2)->get();
        return view('back.arrivage.transfert', [
            'expedition' => $expedition,
            'agenceRecords' => $agenceRecords
        ]);
    }

    public function retrouverCreate(Expedition $expedition, Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [
                'commentaire' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } elseif ($request->agence != $expedition->agence_des) {
                $agence = $expedition->agence;
                $expedition->update([
                    'etape' => 15,
                    'agence' => $request->agence,
                    'date_trouve' => date('Y-m-d H:i:s')
                ]);

                ControllersArrivageController::etapeHistory($expedition->id, $expedition->etape, $agence);
                //creation comment for the expedition
                $commentaire = new Commentaire();
                $commentaire->code = "Retrouvement à " . Ville::where('id', $request->agence)->first()->libelle;
                $commentaire->commentaires = $request->commentaire;
                $commentaire->id_expedition = $expedition->id;
                $commentaire->id_utilisateur = auth()->user()->id;
                $commentaire->save();

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


                return redirect()->route('stock_perdu_list')->with('success', "L'expédition a été retrouvée avec succès");
            } else {
                $agence = $expedition->agence;
                $expedition->update([
                    'etape' => 10,
                    'agence' => $request->agence,
                    'date_trouve' => date('Y-m-d H:i:s')
                ]);
                ControllersArrivageController::etapeHistory($expedition->id, $expedition->etape, $agence);
                $commentaire = new Commentaire();
                $commentaire->code = "Retrouvement  à " . Ville::where('id', $request->agence)->first()->libelle;
                $commentaire->commentaires = $request->commentaire;
                $commentaire->id_expedition = $expedition->id;
                $commentaire->id_utilisateur = auth()->user()->id;
                $commentaire->save();
                return redirect()->route('stock_perdu_list')->with('success', "L'expédition a été retrouvée avec succès");
            }
        }

        $agenceRecords = Ville::where('deleted', '0')->where('id', "!=", 2)->get();

        return view('back/expedition/update', [
            'agenceRecords' => $agenceRecords
        ]);
    }

    public function retrouver($id)
    {
        $expedition = Expedition::findOrFail($id);

        $agenceRecords = Ville::where('deleted', '0')->where('id', "!=", 2)->get();
        return view('back.arrivage.retrouver', [
            'expedition' => $expedition,
            'agenceRecords' => $agenceRecords
        ]);
    }

    public function transfertCreate(Expedition $expedition, Request $request)
    {

        $clientPort = $expedition->clientDetail->port;
        $data = $request->all();
        $rules = [
            'commentaire' => 'required',
            'destinataire' => 'required',
            'adresse_destinataire' => 'required',
            'telephone' => 'required',
            'colis' => 'required',
            'ttc' => 'required',
        ];
        $columns = [
            'destinataire' => 'Destinataire',
            'adresse_destinataire' => 'Adresse',
            'telephone' => 'Téléphone',
            'colis' => 'Nb. Colis',
            'fond' => 'Fond',
            'port' => 'Port',
            'ttc' => 'Prix colis',
            'retour_fond' => 'Type de livraison'

        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            return redirect()->back()->withInput()->withErrors($validator);
        } elseif ($request->port == "PP" && $clientPort == 'PD') {
            return redirect()->back()->withInput()->with('error', "LE CLIENT N'EST PAS AUTORISÉ DU PORT PAYÉ");
        } elseif ($request->port == 'PPNE' && $clientPort == 'PD') {
            return redirect()->back()->withInput()->with('error', "LE CLIENT N'EST PAS AUTORISÉ DU PPNE");
        } elseif ($request->port == 'PPNE' && $clientPort == 'PP') {
            return redirect()->back()->withInput()->with('error', "LE CLIENT N'EST PAS AUTORISÉ DU PPNE");
        } elseif ($request->port == 'PPE' && $clientPort == 'PD') {
            return redirect()->back()->withInput()->with('error', "LE CLIENT N'EST PAS AUTORISÉ DU PPE");
        } elseif ($request->port == 'PPE' && $clientPort == 'PP') {
            return redirect()->back()->withInput()->with('error', "LE CLIENT N'EST PAS AUTORISÉ DU PPE");
        } elseif ($expedition->agence_des == $request->agence_des) {
            return redirect()->back()->withInput()->with('error', 'La destination que vous avez choisi est la même destination actual');
        } elseif (($request->type == "CDP" && $expedition->clientDetail->factureMois == "Non") && $expedition->clientDetail->colisSimple == "Non") {


            return redirect()->back()->withInput()->with('error', "LE CLIENT N'EST PAS AUTORISÉ Du Colis déjà payer");
        } else {



            //creation comment for the expedition
            $commentaire = new Commentaire();
            $commentaire->code = "TRANSFERT";
            $commentaire->commentaires = $request->commentaire;
            $commentaire->id_expedition = $expedition->id;
            $commentaire->id_utilisateur = auth()->user()->id;
            $commentaire->save();

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

            if ($request->type == 'ECOM') {
                $retour_fond = 'CR';
            } elseif ($request->type == 'CDP') {
                $retour_fond = "s";
            }

            if ($expedition->port != 'PP' && $request->port == 'PP') {
                $caissepp_emp = auth()->user()->id;
            } else {
                $caissepp_emp = null;
            }
            $agence =  $expedition->agence;
            $expedition->update([
                'etape' => 9,
                'agence_des' => $request->agence_des,
                'des' => $request->agence_des,
                'agence' => $expedition->agence_des,
                'type' => $request->type,
                'destinataire' => $request->destinataire,
                'adresse_destinataire' => $request->adresse_destinataire,
                'telephone' => $request->telephone,
                'fond' => $request->fond,
                'port' => $request->port,
                'ttc' => $request->ttc,
                'colis' => $request->colis,
                'retour_fond' => $retour_fond,
                'caissepp_emp' => $caissepp_emp,
                'date_transfert' => date('Y-m-d H:i:s')
            ]);

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
            // ControllersArrivageController::etapeHistory($expedition->id, $expedition->etape, $expedition->agence, $expedition->agence_des);

            $etapeHistory = new etapeHistory();
            $etapeHistory->expedition = $expedition->id;
            $etapeHistory->etape = $expedition->etape;
            $etapeHistory->agence = $expedition->agence;
            $etapeHistory->agence_des = $expedition->agence_des;
            $etapeHistory->transfert_agence = $agence ;
            $etapeHistory->save();

            return redirect()->route('stock_list')->with('success', "L'expédition a été transférée avec succès");
        }
    }


    public function printStock($ville)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


        $records = Expedition::getStock();
        if ($ville != '0') {
            $records = $records->where('agence', $ville);
        }
        $total_colis = 0;
        $total_fond = 0;
        $total_frais = 0;
        foreach ($records as $exp) {
            $fond = $exp->fond;
            if ($fond == '' || $fond == null) {
                $fond = 0;
            }
            $total_colis = $total_colis + $exp->colis;
            $total_fond = $total_fond + $fond;
            $total_frais = $total_frais + $exp->ttc;
        }



        $pdf::setHeaderCallback(function ($pdf) {
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>SITUATION DE STOCK</b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                            ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                        </td>
                    </tr>
                </table>
                <hr>
            ';
            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function ($pdf) {
            $pdf->writeHTML('<hr> <table style="width:100% !important;  height:100% !important; " cellpadding="5"> <tr > <td style="height:25px !important; font-size: 10px !important;text-align:right !important;" width="100%"> ' . date('d/m/Y H:i') . ' </td></tr> </table>', true, false, false, false, '');
        });


        // set margins
        $pdf::SetMargins(7, PDF_MARGIN_TOP, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage('L', 'A4');

        // Set some content to print
        //Téléphone Adresse
        $html = '
        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                <tr>
                    <td width="6%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2">
                        <b>N° Expéd.</b>
                    </td>
                    <td width="8%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Emis le</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Expéditeur</b></td>

                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Origine</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destination</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destinataire</b>
                    </td>

                    <td width="7%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Téléphone</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Adresse</b>
                    </td>
                    <td width="3%" style="height:18px !important; text-align:center !important; font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Colis</b>
                    </td>

                    <td width="4%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Port</b></td>
                    <td width="5%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Fond</b></td>
                    <td width="5%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Reçu le</b></td>
                    <td width="5%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Délai</b></td>
                </tr>';
        foreach ($records as $exp) {
            $html .= '<tr>
                    <td style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" > ' . $exp->num_expedition . '</td>
                    <td style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" >' . $exp->created_at . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  >' . $exp->clientDetail->libelle . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  >' . $exp->agenceDetail->libelle . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  >' . $exp->agenceDesDetail->libelle . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  >' . $exp->destinataire . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  >' . $exp->telephone . '
                    </td>
                    <td  style="height:18px !important; text-align: left !important; font-size: 7px !important; border:0,2px solid !important;"  >' . $exp->adresse_destinataire . '
                    </td>
                    <td style="height:18px !important;text-align: center !important;  font-size: 7px !important; border:0,2px solid !important;" >' . $exp->colis . '</td>
                    <td style="height:18px !important;text-align: right !important;  font-size: 7px !important; border:0,2px solid !important;" >' . $exp->port . '</td>
                    <td style="height:18px !important;text-align: right !important;  font-size: 7px !important; border:0,2px solid !important;" >' . $exp->fond . '</td>
                    <td style="height:18px !important;text-align: right !important;  font-size: 7px !important; border:0,2px solid !important;" >' . $exp->date_recu() . '</td>
                    <td style="height:18px !important;text-align: right !important;  font-size: 7px !important; border:0,2px solid !important;" >' . $exp->ttc . '</td>
                </tr>';
        }
        $html .= '</table>
            <br><br><table style="width:100% !important;  height:100% !important; " cellpadding="2">
                <tr>
                    <td width="20%" style="height:18px !important; font-size: 9px !important;text-align:center !important;">
                    </td>
                    <td width="15%" style="height:18px !important; font-size: 9px !important;text-align:center !important;" >
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Total expéditions</b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Total colis</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Total Fonds</b>
                    </td>
                    <td width="18%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Total Frais</b>
                    </td>
                </tr>
                <tr>
                    <td width="20%" style="height:18px !important; font-size: 9px !important;text-align:center !important;" >
                    </td>
                    <td width="15%" style="height:18px !important; font-size: 9px !important;text-align:center !important;" >
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  ><b>' . $records->count() . '</b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  ><b>' . $total_colis . '</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  ><b>' . number_format($total_fond, 2) . ' Dhs</b>
                    </td>
                    <td width="18%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  ><b>' . number_format($total_frais, 2) . ' Dhs</b>
                    </td>

                </tr>
            </table>
        ';

        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Situation du stock.pdf', 'I');
    }

    public function getcode($letter, $object)
    {
        $code = $letter . sprintf("%06d", $object + 1);
        return $code;
    }

    public function etapeHistory($expedition, $etape, $agence = null, $agence_des = null, $fond = null, $ttc = null, $port = null, $retour_fond = null)
    {

        $etapeHistory = new etapeHistory();
        $etapeHistory->expedition = $expedition;
        $etapeHistory->etape = $etape;
        $etapeHistory->agence = $agence;
        $etapeHistory->agence_des = $agence_des;
        $etapeHistory->fond = $fond;
        $etapeHistory->ttc = $ttc;
        $etapeHistory->port = $port;
        $etapeHistory->retour_fond = $retour_fond;
        $etapeHistory->save();
    }


    public function export_stock(Request $request)
    {

        $spreadsheet = IOFactory::load(storage_path('export/exportStock.xlsx'));
        $i = 2;

        if ($request->ville == '0') {
            $records = Expedition::whereIn('etape', ['2', '3', '10', '16', '20'])->get();
        } else {
            $records = Expedition::whereIn('expeditions.etape', ['10', '16', '20'])
                ->where('expeditions.agence_des', '=', $request->ville)
                ->OrWhere('expeditions.agence', '=', $request->ville)
                ->whereIn('expeditions.etape', ['2', '3'])->get();
        }

        foreach ($records as $record) {
            $comnt = '';
            if ($record->etape == '10' || $record->etape == '16') {
                $comnt = 'En cours';
            } elseif ($record->etape == '20') {
                $comnt = Commentaire::findOrFail($record->id)->latest('created_at')->first()->commentaires;
            }



            $fond = $record->fond;
            if ($fond == '' || $fond == null) {
                $fond = 0;
            }

            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->num_expedition);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->created_at);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $record->clientDetail->libelle);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $record->origineDetail->libelle);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->DestinationDetail->libelle);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $record->destinataire);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . $i, $record->adresse_destinataire);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . $i, $record->telephone);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("I" . $i, $record->colis);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("J" . $i, number_format($fond, 2) . ' Dhs');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("K" . $i, $record->getEtape());
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("L" . $i, $comnt);

            $i++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="export_stock.xlsx"');

        $writer->save('php://output');
        exit();
    }
}
