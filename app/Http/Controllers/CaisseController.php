<?php

namespace App\Http\Controllers;

use App\Models\CaissesCheques;
use App\Models\CaissesJustifs;
use App\Models\CaissesVersements;
use App\Models\Ville;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use App\Models\Statut;
use App\Models\Caisse;
use App\Models\Expedition;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use DataTables;
use \PhpOffice\PhpSpreadsheet\Calculation\Calculation;

class CaisseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function getRules()
    {
        return [];
    }

    public function list(Request $request)
    {
        $request->flash();
        if (auth()->user()->role == '5' || auth()->user()->role == '7' || auth()->user()->role == '8') {
            $caisses = Caisse::getRecords()->whereIn('id_agence', \Auth::user()::getUserVilles());
        } else {
            $caisses = Caisse::getRecords();
        }
        return view(
            'back/caisse/globals',
            [
                'records' => $caisses,
                'villes' => Ville::getVilles()->where('id', "!=", 1),
                'status' => Statut::fetchAllByCode('CAISSE')
            ]
        );
    }

    public function api(Request $request)
    {
        $formData = array();
        parse_str($request->all()['form'] ?? '', $formData);
        $query = Caisse::getBaseQuery($formData);



        return Datatables::of($query)->addIndexColumn()

            ->addColumn('code', function ($record) {
                return  '<span class=" badge grey">' . $record->numero . '</span>';
            })
            ->addColumn('created_at_td', function ($record) {
                if (strlen($record->created_at) > 6) {
                    return date('Y/m/d H:i:s', strtotime($record->created_at));
                }
                return '';
            })
            ->addColumn('validate_at_td', function ($record) {
                if ($record->validate_at != null)
                    return '<span class="tooltipped" data-position="bottom"
                                data-tooltip="Par : ' . $record->validate_by . '"
                                style="font-size: 14px;">'
                        . date('Y/m/d H:i:s', strtotime($record->validate_at)) . '
                            </span>';
            })
            ->addColumn('montant', function ($record) {
                return Caisse::getMontantTotal($record->id);
            })
            ->addColumn('received_at_td', function ($record) {
                if ($record->date_reception != null)
                    return '<span class="tooltipped" data-position="bottom"
                                data-tooltip="Par : ' . $record->recu_par . '"
                                style="font-size: 14px;">'
                        . date('Y/m/d H:i:s', strtotime($record->date_reception)) . '
                            </span>';
            })
            ->addColumn('date_fin_td', function ($record) {
                if (strlen($record->date_fin) > 6) {
                    return date('Y/m/d H:i:s', strtotime($record->date_fin));
                }
                return "";
            })
            ->addColumn('statut_td', function ($record) {
                if ($record->statut_id == 1) {
                    return '<p class="statut-badge lime darken-2 valign-wrapper">
                        ' . $record->statut_label . ' <i
                            style="margin-left:4px;font-size: 24px;"
                            class="material-icons center">lock_open</i></p>';
                } elseif ($record->statut_id == 2) {
                    return '<p class="statut-badge orange valign-wrapper">
                    ' . $record->statut_label . ' <i
                            style="margin-left:4px;font-size: 24px;"
                            class="material-icons">lock_outline</i>
                    </p>';
                } elseif ($record->statut_id == 3) {
                    return '<p class="statut-badge blue valign-wrapper">
                    ' . $record->statut_label . ' <i
                                style="margin-left:4px;font-size: 24px;"
                                class="material-icons">subdirectory_arrow_right</i>
                        </p>';
                } elseif ($record->statut_id == 4) {
                    return '<p class="statut-badge green valign-wrapper">
                    ' . $record->statut_label . ' <i
                            style="margin-left:4px;font-size: 24px;"
                            class="material-icons">done</i>
                    </p>';
                }
            })
            ->addColumn('action', function ($record) {
                $actions = '';
                if (\Auth::user()::hasRessource('Menu Caisse : Button Versement et Depense')) {
                    $actions .= '<a href="#!"
                        onclick="Detailsmodal(\'' . route('caisse_versements', ['caisse' => $record->id]) . '\')"
                        style="text-align: center">
                        <i class="material-icons green-text tooltipped"
                            data-tooltip="Versements & Dépenses"
                            data-position="top">payment</i>
                    </a>';
                }
                if ($record->statut == 1 && \Auth::user()::hasRessource('Menu Caisse : Fermeture caisse')) {
                    $actions .= '<a href="#!" onclick="openCloseModal(\'' . $record->id . '\')"
                            style="text-align: center">

                            <i class="material-icons orange-text tooltipped"
                                data-tooltip="Fermeture caisse" data-position="top">check</i>
                        </a>';
                }
                if ($record->statut == 2 && \Auth::user()::hasRessource('Menu Caisse : Réception caisse')) {
                    $actions .= '<a href="#!" onclick="openRecuModal(\'' . $record->id . '\')"
                        style="text-align: center">
                        <i class="material-icons blue-text tooltipped"
                            data-tooltip="Réception caisse"
                            data-position="top">check_box</i>
                        </a>';
                }
                if ($record->statut == 3 && \Auth::user()::hasRessource('Menu Caisse : Validation caisse')) {
                    $actions .= '<a href="#!" onclick="openValidModal(\'' . $record->id . '\')"
                        style="text-align: center">
                        <i class="material-icons green-text tooltipped"
                            data-tooltip="Validation caisse"
                            data-position="top">check_circle</i>
                        </a>';
                }
                return $actions;
            })
            ->addColumn('print', function ($record) {

                return '
                        <a target="_blank"
                        href="' . route('caisse_print', ['caisse' => $record->id]) . '">
                        <i class="material-icons tooltipped" data-tooltip="Imprimer caisse"
                            data-position="top">picture_as_pdf</i>
                    </a> <a target="_blank"
                        href="' . route('caisse_print_detail', ['caisse' => $record->id]) . '">
                        <i class="material-icons tooltipped"
                            data-tooltip="Imprimer détail caisse"
                            data-position="top">picture_as_pdf</i>
                    </a>
                ';
            })
            ->rawColumns(['code', 'action', 'created_at', 'date_fin', 'print', 'statut_td', 'validate_at_td', 'received_at_td'])
            ->make(true);
    }

    public function globals(Request $request)
    {
        $request->flash();
        return view(
            'back/caisse/globals',
            [
                'records' => Caisse::getRecords(),
                'villes' => Ville::getVilles(),
                'status' => Statut::fetchAllByCode('CAISSE')
            ]
        );
    }

    public static function export(Request $request)
    {
        $caisses = Caisse::getBaseQuery($request->all())->get();
        $rowsNbr = count($caisses) - 1;
        $spreadsheet = IOFactory::load(storage_path('export/rapports_caisses.xlsx'));
        $i = 2;
        if ($rowsNbr != 0) {
            $spreadsheet->getActiveSheet()->insertNewRowBefore(3, $rowsNbr);
        }

        foreach ($caisses as $caisse) { //dd($caisse);
            $moins = $plus = '0';
            $totalCheque = 0;
            $montantTotal = Expedition::getMontantTotalByCaisse($caisse->id);
            $expeditions = Expedition::getExpeditionByCaisse($caisse->id);
            $cheques = CaissesCheques::where('caisse', $caisse->id)->leftJoin('expeditions', 'expeditions.id', '=', 'caisses_cheques.expedition')->get();
            $versements = CaissesVersements::getVersementsArray($caisse->id);
            $versementsMtn = isset($versements['VERSEMENT']) ? $versements['VERSEMENT']->montant : '0';
            $manqueMtn = CaissesVersements::getManqueMtn($montantTotal, $versements);

            if ($montantTotal > $versementsMtn) {
                $moins = $montantTotal - $versementsMtn;
            }
            if ($montantTotal < $versementsMtn) {
                $plus = $versementsMtn - $montantTotal;
            }
            foreach ($cheques as $cheque) {
                $totalCheque += $cheque->montant;
            }
            //if(count($cheques)>0)
            //dd($cheques);
            $versementDate = '';
            if (isset($versements['VERSEMENT']) && strlen($versements['VERSEMENT']->versement_date) > 2) {
                $versementDate = date('d/m/Y H:i', strtotime($versements['VERSEMENT']->versement_date));
            }

            $confirmationDate = '';
            if (strlen($caisse->date_confirmation) > 2) {
                $confirmationDate = date('d/m/Y H:i', strtotime($caisse->date_confirmation));
            }


            $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $caisse->numero);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $caisse->agence);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $montantTotal);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $versementsMtn);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $moins);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . $i, $plus);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . $i, count($expeditions));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("I" . $i, $versements['DEPENSE_3']->montant ?? '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("J" . $i, $versements['DEPENSE_4']->montant ?? '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("K" . $i, $versements['DEPENSE_5']->montant ?? '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("L" . $i, $versements['DEPENSE_6']->montant ?? '');

            $spreadsheet->setActiveSheetIndex(0)->setCellValue("M" . $i, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("N" . $i, $totalCheque);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("O" . $i, $versements['DEPENSE_7']->montant ?? '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("P" . $i, $manqueMtn - $totalCheque);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("Q" . $i, $confirmationDate);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("R" . $i, $versementDate);

            $i++;
        }
        $i--;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . ($i + 1), "=sum(D2:D" . ($i + 1) . ")");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . ($i + 1), "=sum(E2:E" . ($i + 1) . ")");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . ($i + 1), "=sum(F2:F" . ($i + 1) . ")");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . ($i + 1), "=sum(G2:G" . ($i + 1) . ")");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . ($i + 1), "=sum(H2:H" . ($i + 1) . ")");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("I" . ($i + 1), "=sum(I2:I" . ($i + 1) . ")");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("J" . ($i + 1), "=sum(J2:J" . ($i + 1) . ")");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("K" . ($i + 1), "=sum(K2:K" . ($i + 1) . ")");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("L" . ($i + 1), "=sum(L2:L" . ($i + 1) . ")");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("N" . ($i + 1), "=sum(N2:N" . ($i + 1) . ")");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("O" . ($i + 1), "=sum(O2:O" . ($i + 1) . ")");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("P" . ($i + 1), "=sum(P2:P" . ($i + 1) . ")");

        Calculation::getInstance($spreadsheet)->disableCalculationCache();

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Rapport des caisses.xlsx"');

        $writer->save('php://output');
    }

    public function changeStatus(Caisse $caisse, $status, Request $request)
    {
        if ($status == 2) { // Fermée
            $caisse->confirme_par = \Auth::user()->name . " " . \Auth::user()->first_name;
            $caisse->date_fin = date('Y-m-d H:i:s');
            $caisse->date_confirmation = date('Y-m-d H:i:s');
        } elseif ($status == 3) { // Reçu
            $expeditions = Expedition::getExpeditionByCaisse($caisse->id);
            foreach ($expeditions as $expedition) {
                $expeditionRow = Expedition::find($expedition->id);
                $expeditionRow->etape = 7;
                $expeditionRow->save();
                $commentaire = new Commentaire();
                $commentaire->code = "En cours de paiement";
                $commentaire->commentaires = "En cours de paiement";
                $commentaire->id_expedition = $expedition->id;
                $commentaire->id_utilisateur = auth()->user()->id;
                $commentaire->save();
            }
            $caisse->recu_par = \Auth::user()->name . " " . \Auth::user()->first_name;
            $caisse->date_reception = date('Y-m-d H:i:s');
        } elseif ($status == 4) { // Validée
            $caisse->valide_par = \Auth::user()->name . " " . \Auth::user()->first_name;
            $caisse->date_validation = date('Y-m-d H:i:s');
        }
        $caisse->statut = $status;
        $caisse->save();
        Redirect::to(route('caisse_list'))->send();
    }

    public function create(Request $request)
    {

        if ($request->isMethod('post')) {
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                Caisse::create($request->all());
                Redirect::to(route('caisse_list'))->send();
            }
        }
        $viewsData = [];
        return view('back/caisse/create', $viewsData);
    }

    public function update(Caisse $caisse, Request $request)
    {

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $caisse->update($request->all());
                Redirect::to(route('caisse_list'))->send();
            }
        }
        $viewsData['record'] = $caisse;
        return view('back/caisse/update', $viewsData);
    }


    public function versements(Caisse $caisse, $type = 'DEPENSE', $rub = '3', Request $request)
    {

        if ($request->isMethod('post')) {
            $data = $request->all();
            switch ($data['type']) {
                case 'DEPENSE':
                    $versement = CaissesVersements::where('id_caisse', $caisse->id)
                        ->where('type', $type)
                        ->where('id_type_depense', $data['rubrique'])
                        ->first();
                    if ($versement === null) {
                        $versement = new CaissesVersements();
                    }
                    $versement->id_caisse = $caisse->id;
                    $versement->type = $type;
                    $versement->montant = $data['montant'];
                    $versement->id_type_depense = $data['rubrique'];
                    $versement->reference = $data['reference'];
                    $versement->observation = $data['observation'];
                    $versement->save();
                    break;
                case 'VERSEMENT':
                    $versement = CaissesVersements::where('id_caisse', $caisse->id)
                        ->where('type', $type)
                        ->first();
                    if ($versement === null) {
                        $versement = new CaissesVersements();
                    }
                    $versement->id_caisse = $caisse->id;
                    $versement->type = $type;
                    $versement->montant = $data['montant'];
                    $versement->id_type_depense = $data['rubrique'];
                    $versement->reference = $data['reference'];
                    $versement->observation = $data['observation'];
                    $versement->versement_date = date('Y-m-d H:i:s');

                    $versement->save();
                    break;
                case 'JUSTIFICATIF':
                    if (isset($request->file)) {
                        if (isset($request->file)) {
                            $justif = new CaissesJustifs();
                            $justif->caisse = $caisse->id;
                            $justif->commentaire = $data['comment_justif'];
                            $fileName = time() . '.' . $request->file->extension();
                            $request->file->move(public_path('uploads/caisses'), $fileName);
                            $justif->image = $fileName;
                            $justif->save();
                        }
                    }
                    break;
                case 'CHEQUE':
                    $cheque = new CaissesCheques();
                    $cheque->caisse = $caisse->id;
                    $cheque->expedition = $data['expedition'];
                    $cheque->montant = $data['montant_cheque'];
                    $cheque->numero = $data['numero_cheque'];
                    $cheque->save();
                    break;

                default:
                    # code...
                    break;
            }
        }

        $viewsData['types'] = \App\Models\Typesdepense::where('type', $type)->get();
        $viewsData['record'] = $caisse;
        $viewsData['montant_total'] = Expedition::getMontantTotalByCaisse($caisse->id);
        $viewsData['expeditions'] = Expedition::getExpeditionByCaisse($caisse->id);
        $viewsData['type'] = $type;
        $viewsData['rub'] = $rub;
        $viewsData['cheques'] = CaissesCheques::where('caisse', $caisse->id)->leftJoin('expeditions', 'expeditions.id', '=', 'caisses_cheques.expedition')->get();
        $viewsData['justifs'] = CaissesJustifs::where('caisse', $caisse->id)->get();
        $versements = $viewsData['versements'] = CaissesVersements::getVersementsArray($caisse->id);
        $viewsData['versementsMtn'] = isset($versements['VERSEMENT']) ? $versements['VERSEMENT']->montant : '0';
        $viewsData['manqueMtn'] = CaissesVersements::getManqueMtn($viewsData['montant_total'], $versements);
        //dd($viewsData['manqueMtn']);
        //dd(CaissesCheques::getRecordsArray($caisse->id));
        return view('back/caisse/versements', $viewsData);
    }

    public function delete(Caisse $caisse)
    {
        $caisse->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

    public function print($caisseId)
    {
        $caisse = Caisse::getDetail($caisseId);
        $cheques = CaissesCheques::getMntArray($caisse->id);
        $expeditions = Expedition::getExpeditionByCaisse($caisse->id);
        $versements = CaissesVersements::getVersementsArray($caisse->id);
        $record = new Caisse();
        $record->print($caisse, $expeditions, $cheques, $versements);
    }

    public function printDetail($caisseId)
    {
        $caisse = Caisse::getDetail($caisseId);
        $cheques = CaissesCheques::getMntArray($caisse->id);
        $expeditions = Expedition::getExpeditionByCaisse($caisse->id);
        $record = new Caisse();
        $record->printDetail($caisse, $expeditions, $cheques);
    }
}
