<?php

namespace App\Http\Controllers;


use App\Models\Bon;
use App\Models\Ville;
use App\Models\Agence;
use App\Models\Expedition;
use App\Models\Commentaire;
use App\Models\etapeHistory;
use App\Models\Transporteur;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ChargementColis;
use App\Http\Controllers\Controller;
use App\Models\notificationWhatsapp;
use App\Models\Processus_expedition;

class ChargementColisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(Request $request)
    {
        $request->flash();
        $agences_des = Ville::all()->where('deleted', '0')->where('id', "!=", 2);
        // $agences = Ville::where('deleted','0')->get();
        $transporteur = Transporteur::all();
        $agence = Expedition::whereIn('etape', ['2', '3', '9', '15'])
            ->Orwhere('etape', '6')->where('sens', 'Retour')->get()
            ->groupBy('agence');

        $ageces_exp_ids = [];

        foreach ($agence as $agence_id => $agence) {
            array_push($ageces_exp_ids, $agence_id);
        }

        $agences = Ville::whereIn('id', $ageces_exp_ids)->where('deleted', '0')->where('id', "!=", 2)->get();
        $reqData = $request->all();

        $reqData['statut'] = 3;
        $expeditions = Expedition::getExpeditions($request->all());

        if (auth()->user()->role != '1') {

            $agences = $agences->whereIn('id', \Auth::user()::getUserVilles());
            $expeditions = $expeditions->whereIn('origine', \Auth::user()::getUserVilles());
        }

        return view('back.chargements.list', [
            'agence_des' => $agences_des,
            'agences' => $agences,
            'transporteurs' => $transporteur,
            'expeditions' => $expeditions
        ]);
    }

    public function create(Request $request)
    {

        $expeditions = Expedition::all()->whereIn('id', $request->expeditions)->groupBy('agence_des');

        foreach ($expeditions as $agence_id => $expedition) {

            $count = bon::all()->count();
            $bon = new Bon();
            $bon->code = ChargementColisController::getcode('F', $count);
            $bon->id_transporteur = $request->transporteur;
            $bon->id_agence_dest = $agence_id;
            $bon->id_agence_exp = $expedition[0]->agence;
            $bon->type = 'FCHARGE';
            $bon->save();

            foreach ($expedition as $exp) {
                //changement du statut de l'expedition deja ramasse et l'ajoute dans le processus chargement

                if ($exp->etape == 6) {
                    $processus = Processus_expedition::where('id_expedition', $exp->id)->where('code', 'CHARGEMENT')->latest()->first();
                    $processus->update([
                        'id_feuille_charge' => $bon->id,
                        'id_transporteur' => $request->transporteur,
                        'retour' => $exp->id,
                        'date_validation' =>  date('Y-m-d H:i:s'),

                    ]);
                } elseif ($exp->etape == 9) {
                    $processus = Processus_expedition::where('id_expedition', $exp->id)->where('code', 'CHARGEMENT')->latest()->first();
                    $processus->update([
                        'id_feuille_charge' => $bon->id,
                        'id_transporteur' => $request->transporteur,
                        'transfert' => $exp->id,
                        'date_validation' =>  date('Y-m-d H:i:s'),

                    ]);
                } elseif ($exp->etape == 15) {
                    $processus = Processus_expedition::where('id_expedition', $exp->id)->where('code', 'CHARGEMENT')->latest()->first();
                    $processus->update([
                        'id_feuille_charge' => $bon->id,
                        'id_transporteur' => $request->transporteur,
                        'transit' => $exp->id,
                        'date_validation' =>  date('Y-m-d H:i:s'),

                    ]);
                } else {
                    $processus = Processus_expedition::where('id_expedition', $exp->id)->where('code', 'CHARGEMENT');
                    $processus->update([
                        'id_feuille_charge' => $bon->id,
                        'id_transporteur' => $request->transporteur,
                        'date_validation' =>  date('Y-m-d H:i:s'),


                    ]);
                }
                $exp->etape = '4';
                $exp->save();
                if ($exp->sens == 'Envoi') {
                    $message = "Votre expédition " . $exp->num_expedition . " a été chargée, Vous pouvez toujours voir les mis à jour de l'expedition sur le lien : " . url('/search?&search_exp=' . $exp->num_expedition) . " ";
                    notificationWhatsapp::whatsappMessage($exp->telephone, $message);
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
                $commentaire->bon = $bon->id;
                $commentaire->id_utilisateur = auth()->user()->id;
                $commentaire->save();
            }
        }

        return redirect()->back()->with('validate', 'Les expéditions ont été chargées avec succès');
    }

    public function afficheVille(Request $request)
    {
        $cid = $request->post('cid');
        $agences = Ville::where('deleted', '0')->get();
        $list = '';
        foreach ($agences as $agence) {


            if ($agence->expeditionRamasseAgence($cid)->count()) {
                $list .= ' <tr>
            <td> <label>
                    <input type="checkbox"
                        name="updateFields[' . $agence->id . '][check]" />
                    <span>
                       ' . $agence->Libelle . '
                    </span>
                </label></td>
            <td>' . $agence->expeditionRamasseAgence($cid)->count() . '</td>
        </tr>';
            }
        }
        return $list;
    }

    public function feuilleList(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->flash();
            $star = Carbon::parse($request->start_date)->format("Y-m-d 00:00:00");
            $end = Carbon::parse($request->end_date)->format("Y-m-d 23:59:59");

            $agences = Ville::where('deleted', '0')->where('id', '!=', 1)->get();
            $records = bon::where('type', 'FCHARGE')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($request->start_date != null) {
                $records = $records->where("created_at", '>=', $star);
            }
            if ($request->end_date != null) {
                $records = $records->where("created_at", '<=', $end);
            }
            if ($request->code != null) {
                $records = $records->where("code", $request->code);
            }

            if ($request->Transporteur != null) {
                $records = $records->where("id_transporteur", $request->Transporteur);
            }

            if ($request->Destination != null) {
                $records = $records->where("id_agence_dest", $request->Destination);
            }


            if (auth()->user()->role == '7' || auth()->user()->role == '8') {
                $records = $records->whereIn('id_agence_exp', \Auth::user()::getUserVilles());
            }

            $statutRecords = \App\Models\Statut::all()->where('deleted', '0')->where('code', 'CHARGEMENT');
            $transporteur = Transporteur::all();

            return view('back.chargements.feuille_list', [
                'records' => $records,
                'transporteurs' => $transporteur,
                'agences' => $agences,
                'statutRecords' => $statutRecords,
                'star_date' =>  '',
                'end_date' =>   ''
            ]);
        }

        $now = Carbon::now();

        // dd( $subweek);
        $agences = Ville::where('deleted', '0')->where('id', '!=', 1)->get();
        $records = bon::where('type', 'FCHARGE')
            ->where("created_at", '>=', $now->subWeek())
            ->orderBy('created_at', 'desc')
            ->get();
        if (auth()->user()->role == '7' || auth()->user()->role == '8') {
            $records = $records->whereIn('id_agence_exp', \Auth::user()::getUserVilles());
        }

        $statutRecords = \App\Models\Statut::all()->where('deleted', '0')->where('code', 'CHARGEMENT');
        $transporteur = Transporteur::all();
        $end_date = Carbon::parse(Carbon::now())->format("Y-m-d");
        $subweek = Carbon::parse(Carbon::now()->subWeek())->format("Y-m-d");

        return view('back.chargements.feuille_list', [
            'records' => $records,
            'transporteurs' => $transporteur,
            'agences' => $agences,
            'statutRecords' => $statutRecords,
            'end_date' => $end_date,
            'star_date' => $subweek,
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

    public function printDetail($bn)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $bon = Bon::findOrFail($bn);

        $pdf::setHeaderCallback(function ($pdf) {
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>FEUILLE DE CHARGEMENT</b>
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
        $pdf::SetFooterMargin(14);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage();
        $record = Processus_expedition::all()->where('id_feuille_charge', $bn)->where('code', 'CHARGEMENT');

        $expeditions = etapeHistory::where('id_chargement', $bn)->get();

        $exp_table = '';
        $total_colis = 0;
        $total_fond = 0;

        foreach ($record as $exp) {
            $total_colis = $total_colis + $exp->ExpeditionDetail->colis;
            $total_fond = $total_fond + $exp->ExpeditionDetail->fond;
        }

        foreach ($expeditions as $exp) {
            if ($exp->expeditionDetail->etape != '5') {
                $exp_table = $exp_table . ' <tr>
            <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >' . $exp->num_expedition . '
            </td>
            <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >' . $exp->created_at . '
            </td>
            <td style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;"  >' . $exp->libelle . '
            </td>
            <td style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;"  >' . $exp->agenceDetail->libelle . '
            </td>
            <td style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;"  >' . $exp->libelle  . '
            </td>

            <td style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;"  >' . $exp->agenceDesDetail->libelle  . '</td>
            <td style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;"  >' . $exp->colis  . '</td>
            <td  style="height:18px !important; text-align: right !important; font-size: 8px !important; border:0,2px solid !important;"  >
            ' . $exp->fond  . '
            </td>

        </tr>';
            }
        }

        // Set some content to print
        $html = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                    <tr>
                        <td width="20%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2"><b>N° F. Chargement</b>
                        </td>
                        <td width="20%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Crée le</b>
                        </td>
                        <td width="20%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Départ</b>
                        </td>
                        <td width="20%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destination</b>
                        </td>
                        <td width="20%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Transporteur</b>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" >' . $bon->code . '
                        </td>
                        <td width="20%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" >' . $bon->created_at . '
                        </td>
                        <td width="20%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  >' . $bon->agenceDetail->libelle . '
                        </td>
                        <td width="20%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  >' . $bon->agenceDesDetail->libelle . '</td>
                        <td width="20%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  >' . $bon->transportDetail->libelle . '</td>

                    </tr>
                </table>
                <br><br><table style="width:100% !important;  height:100% !important; " cellpadding="2">
                    <tr>
                    <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" width="9%" bgcolor="#e2e2e2"><b>N° Expéd.</b>
                    </td>
                    <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" width="9%" bgcolor="#e2e2e2">
                    <b>Date</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Expéditeur</b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Origine</b></td>
                    <td width="22%" style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destinataire</b></td>
                    <td width="15%" style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destination</b></td>
                    <td width="5%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Colis</b>
                    </td>
                    <td width="8%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                    <b>Fond</b>
                    </td>
                </tr>
               ' . $exp_table . '
            </table>

            <br><br><table style="width:100% !important;  height:100% !important; " cellpadding="2">
                    <tr>
                        <td style="height:18px !important; font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" width="20%" bgcolor="#e2e2e2"><b>Total Expéditions </b>
                        </td>
                        <td style="height:18px !important; font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" width="20%" bgcolor="#e2e2e2"><b>Total Colis</b></td>
                        <td width="20%" style="height:18px !important;  font-size: 8px !important;"  ></td>
                        <td width="20%" style="height:18px !important;  font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>VISA ' . $bon->agenceDetail->libelle . '</b></td>
                        <td width="20%" style="height:18px !important;  font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>VISA ' . $bon->agenceDesDetail->libelle . '</b></td>
                    </tr>
                    <tr>
                        <td style="height:18px !important; font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" width="20%" >' . $record->count() . '
                        </td>
                        <td style="height:18px !important; font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" width="20%" >' . $total_colis . '</td>
                    </tr>

            </table>

                ';




        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Feuille chargement .pdf', 'I');
    }
}
