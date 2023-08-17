<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bon;
use App\Models\Ville;
use App\Models\Bonliv;
use App\Models\Client;
use App\Models\Employe;
use App\Models\Taxation;
use Barryvdh\DomPDF\PDF;
use App\Models\Commission;
use App\Models\Expedition;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use App\Models\Remboursement;
use App\Models\CaissesCheques;
use App\Models\CaissesExpeditions;
use App\Models\Commentaire;
use Illuminate\Support\Facades\DB;
use App\Models\CommissionExpeditions;
use App\Models\Processus_expedition;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EtatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(Request $request)
    {

        $request->flash();
        return view('back/etat/list', []);
    }

    // public function realisation(){
    //     return view('back.etat.realisation');
    // }

    public function realisation(Request $request)
    {
        // $record = Expedition::select('agence_des')->groupBy('agence_des')->get();
        $firstDayMonth = Carbon::now();
        $firstDayMonth->day = 1;
        $dateNow =  carbon::now();

        if ($request->isMethod('post')) {
            if ($request->has('realiseAgence')) {
                $titre =  "CHIFFRE D'AFFAIRES REALISE / AGENCE";
                if ($request->start_date == null || $request->end_date == null) {
                    $records = \DB::table("expeditions")
                        ->select('agence_des', DB::raw('SUM(ttc) as fond'), "agences_des.libelle as agence")
                        ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions.agence_des')
                        ->whereNotIn('etape', [1, 5])
                        ->whereDate("expeditions.created_at", '>=', $firstDayMonth)
                        ->groupBy('agence_des')->get();
                    // EtatController::pdfagence($records, $titre, $firstDayMonth, $dateNow);

                    $spreadsheet = IOFactory::load(storage_path('export/realiseAgence.xlsx'));
                    $i = 2;
                    foreach ($records as $record) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->agence);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->fond);
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="realiseAgence.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $records = \DB::table("expeditions")
                        ->select('agence_des', DB::raw('SUM(ttc) as fond'), "agences_des.libelle as agence")
                        ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions.agence_des')
                        ->whereNotIn('etape', [1, 5])
                        ->whereDate("expeditions.created_at", '>=', $star)
                        ->whereDate("expeditions.created_at", '<=', $end)
                        ->groupBy('agence_des')->get();

                    // EtatController::pdfagence($records, $titre, $star, $end);
                    $spreadsheet = IOFactory::load(storage_path('export/realiseAgence.xlsx'));
                    $i = 2;
                    foreach ($records as $record) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->agence);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->fond);
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="realiseAgence.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            } elseif ($request->has('EncaisseAgence')) {

                $titre =  "CHIFFRE D'AFFAIRES ENCAISSE / AGENCE";

                if ($request->start_date == null || $request->end_date == null) {
                    $records = \DB::table("expeditions")
                        ->select('agence_des', DB::raw('SUM(ttc) as fond'), "agences_des.libelle as agence")
                        ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions.agence_des')
                        ->leftJoin('processus_expeditions as processus', 'id_expedition', '=', 'expeditions.id')
                        ->whereDate("expeditions.created_at", '>=', $firstDayMonth)
                        ->where('processus.code', 'LIVRAISON')
                        ->where('processus.date_validation', '!=', null)
                        ->groupBy('agence_des')->get();


                    // EtatController::pdfagence($records, $titre, $firstDayMonth, $dateNow);
                    $spreadsheet = IOFactory::load(storage_path('export/encaisseAgence.xlsx'));
                    $i = 2;
                    foreach ($records as $record) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->agence);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->fond);
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="encaisseAgence.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $records = \DB::table("expeditions")
                        ->select('agence_des', DB::raw('SUM(ttc) as fond'), "agences_des.libelle as agence")
                        ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions.agence_des')
                        ->leftJoin('processus_expeditions as processus', 'id_expedition', '=', 'expeditions.id')
                        ->whereDate("expeditions.created_at", '>=', $star)
                        ->whereDate("expeditions.created_at", '<=', $end)
                        ->where('processus.code', 'LIVRAISON')
                        ->where('processus.date_validation', '!=', null)
                        ->groupBy('agence_des')->get();

                    // EtatController::pdfagence($records, $titre, $star, $end);
                    $spreadsheet = IOFactory::load(storage_path('export/encaisseAgence.xlsx'));
                    $i = 2;
                    foreach ($records as $record) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->agence);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->fond);
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="encaisseAgence.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            } elseif ($request->has('encaisseAgenceExp')) {

                $titre =  "CHIFFRE D'AFFAIRES ENCAISSE / AGENCE";
                if ($request->start_date == null || $request->end_date == null) {
                    $records = \DB::table("expeditions")
                        ->select('agence_des', DB::raw('SUM(fond) as fond'), "agences_des.libelle as agence",)
                        ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions.agence_des')
                        ->where('etape', 10)
                        ->whereDate("expeditions.created_at", '>=', $firstDayMonth)
                        ->groupBy('agence_des')->get();

                    EtatController::pdfagence($records, $titre, $firstDayMonth, $dateNow);
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $records = \DB::table("expeditions")
                        ->select('agence_des', DB::raw('SUM(fond) as fond'), "agences_des.libelle as agence")
                        ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions.agence_des')
                        ->where('etape', 10)
                        ->whereDate("expeditions.created_at", '>=', $star)
                        ->whereDate("expeditions.created_at", '<=', $end)
                        ->groupBy('agence_des')->get();

                    EtatController::pdfagence($records, $titre, $star, $end);
                }
            } elseif ($request->has('realiseClient')) {

                $titre =  "CHIFFRE D'AFFAIRES ENCAISSE / AGENCE";
                if ($request->start_date == null || $request->end_date == null) {
                    $records = \DB::table("clients")
                        ->select('clients.libelle as client', DB::raw('SUM(expeditions.ttc) as fond'),)
                        ->leftJoin('expeditions', 'expeditions.client', '=', 'clients.id')
                        ->whereNotIn('expeditions.etape', [1, 5])
                        ->whereDate("expeditions.created_at", '>=', $firstDayMonth)
                        ->groupBy('clients.id')->get();
                    // EtatController::pdfClient($records, $titre, $firstDayMonth, $dateNow);
                    $spreadsheet = IOFactory::load(storage_path('export/realiseClient.xlsx'));
                    $i = 2;
                    foreach ($records as $record) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->client);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->fond);
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="realiseClient.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $records = \DB::table("clients")
                        ->select('clients.libelle as client', DB::raw('SUM(expeditions.ttc) as fond'),)
                        ->leftJoin('expeditions', 'expeditions.client', '=', 'clients.id')
                        ->whereDate("expeditions.created_at", '>=', $star)
                        ->whereDate("expeditions.created_at", '<=', $end)
                        ->whereNotIn('expeditions.etape', [1, 5])
                        ->groupBy('clients.id')->get();
                    // EtatController::pdfClient($records, $titre, $star, $end);
                    $spreadsheet = IOFactory::load(storage_path('export/realiseClient.xlsx'));
                    $i = 2;
                    foreach ($records as $record) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->client);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->fond);
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="realiseClient.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            } elseif ($request->has('EncaisseClient')) {

                $titre =  "CHIFFRE D'AFFAIRES ENCAISSE / AGENCE";

                if ($request->start_date == null || $request->end_date == null) {
                    $records = \DB::table("clients")
                        ->select('clients.libelle as client', DB::raw('SUM(expeditions.ttc) as fond'),)
                        ->leftJoin('expeditions', 'expeditions.client', '=', 'clients.id')
                        ->leftJoin('processus_expeditions as processus', 'id_expedition', '=', 'expeditions.id')
                        ->where('processus.code', 'LIVRAISON')
                        ->where('processus.date_validation', '!=', null)
                        ->whereDate("expeditions.created_at", '>=', $firstDayMonth)
                        ->groupBy('clients.id')->get();
                    // EtatController::pdfClient($records, $titre, $firstDayMonth, $dateNow);
                    $spreadsheet = IOFactory::load(storage_path('export/encaisseClient.xlsx'));
                    $i = 2;
                    foreach ($records as $record) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->client);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->fond);
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="encaisseClient.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $records = \DB::table("clients")
                        ->select('clients.libelle as client', DB::raw('SUM(expeditions.ttc) as fond'),)
                        ->leftJoin('expeditions', 'expeditions.client', '=', 'clients.id')
                        ->leftJoin('processus_expeditions as processus', 'id_expedition', '=', 'expeditions.id')
                        ->where('processus.code', 'LIVRAISON')
                        ->where('processus.date_validation', '!=', null)
                        ->whereDate("expeditions.created_at", '>=', $star)
                        ->whereDate("expeditions.created_at", '<=', $end)
                        ->groupBy('clients.id')->get();
                    // EtatController::pdfClient($records, $titre, $star, $end);
                    $spreadsheet = IOFactory::load(storage_path('export/encaisseClient.xlsx'));
                    $i = 2;
                    foreach ($records as $record) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->client);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->fond);
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="encaisseClient.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            }
        }

        return view('back.etat.realisation');
    }












    public function remboursements(Request $request)
    {
        $firstDayMonth = Carbon::now();
        $firstDayMonth->day = 1;
        $dateNow =  carbon::now();

        if ($request->isMethod('post')) {
            if ($request->has('remboursementsEffectues')) {
                $titre =  "REMBOURSEMENTS EFFECTUES";

                if ($request->start_date == null || $request->end_date == null) {

                    $star = Carbon::now()->format('Y-m-01');
                    $end =  carbon::now()->format('Y-m-d');


                    $remboursements = Remboursement::all();

                    $record = new Remboursement();
                    $record->printDetailAll($remboursements, $star, $end);
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $remboursements = Remboursement::all()
                        ->where("created_at", '>=', $star->format('Y-m-d H:i:s'))
                        ->where("created_at", '<=', $end->format('Y-m-d 23:59:59'));

                    $record = new Remboursement();
                    $record->printDetailAll($remboursements, $star->format('Y-m-d'), $end->format('Y-m-d'));
                }
            } elseif ($request->has('remboursementsEffectues_Excel')) {

                if ($request->start_date == null || $request->end_date == null) {
                    $star = Carbon::now()->format('Y-m-01');
                    $end =  carbon::now()->format('Y-m-d');
                    $remboursements = Remboursement::all()
                    // ->where("created_at", '>=', Carbon::now()->format('Y-m-01 00:00:00'))
                    ;
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $remboursements = Remboursement::all()
                        ->where("created_at", '>=', $star->format('Y-m-d H:i:s'))
                        ->where("created_at", '<=', $end->format('Y-m-d 23:59:59'));
                }

                    $spreadsheet = new Spreadsheet();
                    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30, 'pt');

                    $spreadsheet->getActiveSheet(0)->mergeCells('A1:D1')->setCellValue("A" . 1, "REMBOURSEMENTS EFFECTUES");
                    $spreadsheet->getActiveSheet(0)->mergeCells('E1:K1')->setCellValue("E" . 1, "Du " . $star . " Au " . $end);

                    $spreadsheet->getActiveSheet(0)->getStyle('A1:K1')->applyFromArray(
                        array(
                            'font'  => array(
                                'bold'  =>  true,
                                'size'  =>  15,
                            ),
                            'alignment' => array(
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                'wrapText' => true,
                            ),
                            'fill' => array(
                                'fillType' => Fill::FILL_SOLID,
                                'color' => array('rgb' => 'e3f2fd')
                            ),
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        )
                    );
                    $spreadsheet->getActiveSheet(0)->getStyle('E1')->applyFromArray(
                        array(
                            'font'  => array(
                                'bold'  =>  true,
                                'size'  =>  14,
                            ),
                            'alignment' => array(
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                'wrapText' => true,
                            ),
                        )
                    );


                    $i = 3;

                    foreach ($remboursements as $remboursement) {

                        $expeditionsIds = $remboursement->expeditions()->allRelatedIds()->toArray();
                        $expeditions = Expedition::getExpeditionsByRemboursement($remboursement->id);
                        $cheques = CaissesCheques::getMntArray(null, $expeditionsIds);
                        $clients = Expedition::getClientsByRemboursement($expeditions);
                        $spreadsheet->getActiveSheet(0)->mergeCells('A' . $i . ':K' . $i);
                        $spreadsheet->getActiveSheet(0)->getStyle('A' . $i)->applyFromArray(
                            array(
                                'font'  => array(
                                    'bold'  =>  true,
                                    'size'  =>  15,
                                    'color' => array('rgb' => 'FFFFFF')
                                ),
                                'fill' => array(
                                    'fillType' => Fill::FILL_SOLID,
                                    'color' => array('rgb' => '14597d')
                                ),

                            )
                        );
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $remboursement->code);
                        $i++;

                        $i++;

                        foreach ($clients as $client) {
                            $spreadsheet->getActiveSheet(0)->mergeCells('A' . $i . ':E' . $i);
                            $spreadsheet->getActiveSheet(0)->mergeCells('F' . $i . ':K' . $i);
                            $spreadsheet->getActiveSheet(0)->getStyle('A' . $i)->applyFromArray(
                                array(
                                    'font'  => array(
                                        'bold'  =>  true,
                                    ),

                                )
                            );
                            $spreadsheet->getActiveSheet(0)->getStyle('F' . $i)->applyFromArray(
                                array(
                                    'font'  => array(
                                        'bold'  =>  true,
                                    ),
                                    'alignment' => array(
                                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                        'wrapText' => true,
                                    ),

                                )
                            );

                            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $client['name']);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, "RIB : " . $client['rib']);
                            $i++;
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, "N° Expéd");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, "Date");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, "Destinataire");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, "Destination");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, "Téléphone");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, "Colis");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . $i, "Port");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . $i, "Fond");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("I" . $i, "Chéque");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("J" . $i, "Frais");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("K" . $i, "Net");
                            $spreadsheet->getActiveSheet(0)->getStyle('A' . $i . ':K' . $i)->applyFromArray(
                                array(
                                    'font'  => array(
                                        'bold'  =>  true,
                                        'color' => array('rgb' => '000000')
                                    ),
                                    'fill' => array(
                                        'fillType' => Fill::FILL_SOLID,
                                        'color' => array('rgb' => 'D9D9D9')
                                    ),
                                    'borders' => [
                                        'allBorders' => [
                                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                            'color' => ['argb' => '00000000'],
                                        ],
                                    ],

                                )
                            );
                            $i++;
                            $expeditionsTotal = $colisTotal = $fondsTotal = $chequesTotal = $fraisTotal = $netsTotal = 0;
                            foreach ($expeditions as $expedition) {
                                if ($expedition->client_id == $client['client_id']) {
                                    $cheque = isset($cheques[$expedition->expedition_id]) ? $cheques[$expedition->expedition_id] : 0;
                                    $chequeVal = $cheque > 0 ? $cheque : '';
                                    $net = $expedition->fond - $expedition->ttc - $cheque;

                                    $expeditionsTotal++;
                                    $colisTotal += $expedition->colis;
                                    $fondsTotal += $expedition->fond;
                                    $chequesTotal += $cheque;
                                    $fraisTotal += $expedition->ttc;
                                    $netsTotal += $net;
                                    $spreadsheet->getActiveSheet(0)->getStyle('A' . $i . ':K' . $i)->applyFromArray(array(

                                        'borders' => [
                                            'allBorders' => [
                                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                'color' => ['argb' => '00000000'],
                                            ],
                                        ],

                                    ));
                                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $expedition->num_expedition);
                                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, date('Y/m/d', strtotime($expedition->created_at)));
                                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $expedition->destinataire);
                                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $expedition->destination);
                                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $expedition->telephone);
                                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $expedition->colis);
                                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . $i, $expedition->retour_fond);
                                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . $i, $expedition->fond);
                                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("I" . $i, $chequeVal);
                                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("J" . $i, $expedition->ttc);
                                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("K" . $i, $net);
                                    $i++;
                                }
                            }
                            $i++;
                            $spreadsheet->getActiveSheet(0)->mergeCells('B' . $i . ':C' . $i);
                            $spreadsheet->getActiveSheet(0)->mergeCells('D' . $i . ':E' . $i);
                            $spreadsheet->getActiveSheet(0)->mergeCells('F' . $i . ':G' . $i);
                            $spreadsheet->getActiveSheet(0)->mergeCells('H' . $i . ':I' . $i);
                            $spreadsheet->getActiveSheet(0)->mergeCells('J' . $i . ':K' . $i);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, "Total Exp");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, "Total Colis");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, "Total Fond");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, "Total Cheque");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . $i, "Total Frais");
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("J" . $i, "Total Net");
                            $spreadsheet->getActiveSheet(0)->getStyle('A' . $i . ':J' . $i)->applyFromArray(
                                array(
                                    'font'  => array(
                                        'bold'  =>  true,
                                        'color' => array('rgb' => '000000')
                                    ),
                                    'fill' => array(
                                        'fillType' => Fill::FILL_SOLID,
                                        'color' => array('rgb' => 'D9D9D9')
                                    ),
                                    'alignment' => array(
                                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                        'wrapText' => true,
                                    ),

                                )
                            );
                            $i++;
                            $spreadsheet->getActiveSheet(0)->mergeCells('B' . $i . ':C' . $i);
                            $spreadsheet->getActiveSheet(0)->mergeCells('D' . $i . ':E' . $i);
                            $spreadsheet->getActiveSheet(0)->mergeCells('F' . $i . ':G' . $i);
                            $spreadsheet->getActiveSheet(0)->mergeCells('H' . $i . ':I' . $i);
                            $spreadsheet->getActiveSheet(0)->mergeCells('J' . $i . ':K' . $i);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $expeditionsTotal);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $colisTotal);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $fondsTotal);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $chequesTotal);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . $i, $fraisTotal);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("J" . $i, $netsTotal);
                            $spreadsheet->getActiveSheet(0)->getStyle('A' . $i . ':J' . $i)->applyFromArray(
                                array(
                                    'font'  => array(
                                        'bold'  =>  true,
                                    ),

                                    'alignment' => array(
                                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                        'wrapText' => true,
                                    ),

                                )
                            );
                            $spreadsheet->getActiveSheet(0)->getStyle('A' . ($i-1) . ':K' . $i)->applyFromArray(
                                array(

                                    'borders' => [
                                        'allBorders' => [
                                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                            'color' => ['argb' => '00000000'],
                                        ],
                                    ],

                                )
                            );

                            $i++;
                        }
                        $i++;
                    }



                    $i++;
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="remboursementsEffectues.xlsx"');

                    $writer->save('php://output');
                    exit();

                    // foreach ($expeditions as $record) {

                    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->num_expedition);
                    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->created_at);
                    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $record->agenceDesDetail->libelle);
                    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $record->destinataire);
                    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->telephone);
                    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $record->colis);
                    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . $i, $record->port);
                    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . $i, $record->fond);
                    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("I" . $i, $record->ttc);
                    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("J" . $i, $record->ttc);
                    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("K" . $i, $record->ttc);
                    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("L" . $i, $record->clientDetail->libelle);
                    //     $i++;
                    // }

                    // $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    // header('Content-Type: application/vnd.ms-excel');
                    // header('Content-Disposition: attachment;filename="remboursementsEffectues.xlsx"');

                    // $writer->save('php://output');
                    // exit();

            } elseif ($request->has('remboursementsCLients')) {
                if ($request->start_date == null || $request->end_date == null) {
                    $clients = Client::all()->where('deleted', "0");
                    $spreadsheet = IOFactory::load(storage_path('export/remboursementClient.xlsx'));
                    $star = Carbon::now()->format('Y-m-01');
                    $end =  carbon::now()->format('Y-m-d');
                    $i = 2;
                    foreach ($clients as $record) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->client_total_remb($star, $end));
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="remboursementClient.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $clients = Client::all()->where('deleted', "0");
                    $spreadsheet = IOFactory::load(storage_path('export/remboursementClient.xlsx'));
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $i = 2;
                    foreach ($clients as $record) {

                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->client_total_remb($star, $end));

                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="remboursementClient.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            } elseif ($request->has('remboursementsVille')) {
                if ($request->start_date == null || $request->end_date == null) {
                    $villes = Ville::all()->whereNotIn('id', 1)->where('deleted', "0");
                    $spreadsheet = IOFactory::load(storage_path('export/RemboursementVille.xlsx'));
                    $star = Carbon::now()->format('Y-m-01');
                    $end =  carbon::now()->format('Y-m-d');
                    $i = 2;
                    foreach ($villes as $record) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->ville_total_remb($star, $end));
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="RemboursementVille.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $villes = Ville::all()->whereNotIn('id', 1)->where('deleted', "0");
                    $spreadsheet = IOFactory::load(storage_path('export/RemboursementVille.xlsx'));
                    $i = 2;
                    foreach ($villes as $record) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->ville_total_remb($star, $end));
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="RemboursementVille.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            }
        }
        return view('back.etat.remboursements');
    }


    //Commission
    public function commission(Request $request)
    {
        $firstDayMonth = Carbon::now();
        $firstDayMonth->day = 1;
        $dateNow =  carbon::now();
        if ($request->isMethod('post')) {
            if ($request->has('commission')) {
                if ($request->start_date == null || $request->end_date == null) {

                    $records = CommissionExpeditions::select('type', 'commission', 'livreur', 'id_ville_dest', DB::raw('sum(commission) as commissionCount'), DB::raw('count(commission) as count'))
                        ->whereDate("created_at", '>=', $firstDayMonth)
                        ->groupBy('livreur', 'id_ville_dest')
                        ->get();


                    $spreadsheet = IOFactory::load(storage_path('export/commission.xlsx'));
                    $i = 2;

                    foreach ($records as $record) {
                        if ($record->type == 'Livreur') {
                            $commissionVille = '';
                            $commissionLivreur = $record->commission;
                        } else {
                            $commissionVille = $record->commission;
                            $commissionLivreur = '';
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->livreurDetail->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->villeDetailDest->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $commissionVille);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $commissionLivreur);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->count);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $record->commissionCount);

                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="commission.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);

                    $records = CommissionExpeditions::select('type', 'commission', 'livreur', 'id_ville_dest', DB::raw('sum(commission) as commissionCount'), DB::raw('count(commission) as count'))
                        ->whereDate("created_at", '>=', $star)
                        ->whereDate("created_at", '<=', $end)
                        ->groupBy('livreur', 'id_ville_dest')
                        ->get();


                    $spreadsheet = IOFactory::load(storage_path('export/commission.xlsx'));
                    $i = 2;

                    foreach ($records as $record) {
                        if ($record->type == 'Livreur') {
                            $commissionVille = '';
                            $commissionLivreur = $record->commission;
                        } else {
                            $commissionVille = $record->commission;
                            $commissionLivreur = '';
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->livreurDetail->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->villeDetailDest->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $commissionVille);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $commissionLivreur);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->count);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $record->commissionCount);

                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="commission.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            } elseif ($request->has('commissionNew')) {
                if ($request->start_date == null || $request->end_date == null) {
                    $clients = Client::all();
                    $spreadsheet = IOFactory::load(storage_path('export/commission.xlsx'));
                    $i = 2;
                    foreach ($clients as $record) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="commissionNew.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            }
        }

        return view('back.etat.commission');
    }


    // indicateurs

    public function indicateurs(Request $request)
    {
        $firstDayMonth = Carbon::now();
        $firstDayMonth->day = 1;
        $dateNow =  carbon::now();

        if ($request->isMethod('post')) {
            if ($request->has('tauxLivraisonDestination')) {
                if ($request->start_date == null || $request->end_date == null) {
                    $villes = Ville::where('deleted', '0')->where('id', "!=", 1)->get();
                    $spreadsheet = IOFactory::load(storage_path('export/tauxLivraisonDestination.xlsx'));
                    $i = 2;
                    foreach ($villes as $record) {
                        $colis = $record->expeditionAllDes
                            ->where("etape", '!=', 5)
                            ->where('sens', 'Envoi')
                            ->where("created_at", '>=', $firstDayMonth)
                            ->count();
                        $livre = $record->expeditionDetail
                            ->where('sens', 'Envoi')
                            ->where("etape", '!=', 5)
                            ->where("created_at", '>=', $firstDayMonth)
                            ->whereIn('etape', [14, 7, 8])->count();

                        $encours = $record->expeditionDetail
                            ->where('sens', 'Envoi')
                            ->where("etape", '!=', 5)
                            ->where("created_at", '>=', $firstDayMonth)
                            ->whereNotIn('etape', [14, 7, 8])->count();

                        // $record->precessusExpedition->where('code','LIVRAISON')->where('date_validation','!=',null)->count();
                        if ($colis == 0) {
                            $taux = 0;
                        } else {
                            $taux =  $livre / $colis * 100;
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $colis);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $livre);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $encours);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, (int)$taux . '%');
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="tauxLivraisonDestination.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $villes = Ville::where('deleted', '0')->where('id', "!=", 1)->get();
                    $spreadsheet = IOFactory::load(storage_path('export/tauxLivraisonDestination.xlsx'));
                    $i = 2;
                    foreach ($villes as $record) {

                        if ($star == $end) {
                            $colis = $record->getExpeditionDateOne($star)
                                ->where("etape", '!=', 5)
                                ->where('sens', 'Envoi')
                                ->count();

                            $livre = $record->getExpeditionDateOne($star)
                                ->where('sens', 'Envoi')
                                ->where("etape", '!=', 5)
                                ->whereIn('etape', [14, 7, 8])->count();

                            $encours = $record->getExpeditionDateOne($star)
                                ->where('sens', 'Envoi')
                                ->where("etape", '!=', 5)
                                ->whereNotIn('etape', [14, 7, 8])->count();
                        } else {
                            $colis = $record->getExpeditionDateTow($star, $end)
                                ->where("etape", '!=', 5)
                                ->where('sens', 'Envoi')
                                ->count();

                            $livre = $record->getExpeditionDateTow($star, $end)
                                ->where('sens', 'Envoi')
                                ->where("etape", '!=', 5)
                                ->whereIn('etape', [14, 7, 8])->count();

                            $encours = $record->getExpeditionDateTow($star, $end)
                                ->where('sens', 'Envoi')
                                ->where("etape", '!=', 5)
                                ->whereNotIn('etape', [14, 7, 8])->count();
                        }



                        // $record->precessusExpedition->where('code','LIVRAISON')->where('date_validation','!=',null)->count();
                        if ($colis == 0) {
                            $taux = 0;
                        } else {
                            $taux =  $livre / $colis * 100;
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $colis);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $livre);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $encours);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, (int)$taux . '%');
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="tauxLivraisonDestination.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            } elseif ($request->has('tauxLivraisonClient')) {
                if ($request->start_date == null || $request->end_date == null) {
                    $clients = Client::all();
                    $spreadsheet = IOFactory::load(storage_path('export/tauxLivraisonClient.xlsx'));
                    $i = 2;

                    foreach ($clients as $record) {
                        $colis = $record->expeditionDetail
                            ->where("etape", '!=', 5)
                            ->where('sens', 'Envoi')
                            ->where("created_at", '>=', $firstDayMonth)
                            ->count();
                        $livre = $record->expeditionDetail
                            ->where("created_at", '>=', $firstDayMonth)
                            ->where('sens', 'Envoi')
                            ->whereIn('etape', [14, 7, 8])
                            ->count();
                        if ($colis == 0) {
                            $taux = 0;
                        } else {
                            $taux =  $livre / $colis * 100;
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $colis);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $livre);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $record->expeditionDetail->whereNotIn('etape', [14, 7, 8])->count());
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, (int)$taux . '%');
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="tauxLivraisonClient.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $clients = Client::all();
                    $spreadsheet = IOFactory::load(storage_path('export/tauxLivraisonClient.xlsx'));
                    $i = 2;

                    foreach ($clients as $record) {

                        if ($star == $end) {


                            $colis = $record->getExpeditionDateOne($star)->where("etape", '!=', 5)
                                ->where('sens', 'Envoi')
                                ->count();
                            $livre = $record->getExpeditionDateOne($star)
                                ->whereIn('etape', [14, 7, 8])->count();
                        } else {
                            $colis = $record->getExpeditionDateTow($star, $end)->where("etape", '!=', 5)
                                ->where('sens', 'Envoi')
                                ->count();
                            $livre = $record->getExpeditionDateTow($star, $end)
                                ->where('sens', 'Envoi')
                                ->whereIn('etape', [14, 7, 8])->count();
                        }

                        if ($colis == 0) {
                            $taux = 0;
                        } else {
                            $taux =  $livre / $colis * 100;
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $colis);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $livre);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $record->expeditionDetail->whereNotIn('etape', [14, 7, 8])->count());
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, (int)$taux . '%');
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="tauxLivraisonClient.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            } elseif ($request->has('tauxLivraisonLivreur')) {
                if ($request->start_date == null || $request->end_date == null) {
                    $bons = Bonliv::all()->where('deleted', '0')
                        ->where('created_at', '>=', $firstDayMonth);
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $bons = Bonliv::all()->where('deleted', '0')
                        ->where('created_at', '>=', $star)
                        ->where('created_at', '<=', $end);
                }

                $spreadsheet = IOFactory::load(storage_path('export/tauxLivraisonLivreur.xlsx'));

                $i = 2;
                foreach ($bons as $bon) {

                    $taux_liv = ($bon->relatedColis->where('statut', 14)->count() / $bon->relatedColis->count()) * 100;

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $bon->code);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $bon->created_at);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $bon->relatedColis->count());
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $bon->relatedColis->whereNotIn('statut', [14, 20])->count());
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $bon->relatedColis->where('statut', 14)->count());
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $bon->relatedColis->where('statut', 20)->count());
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . $i, $taux_liv . "%");
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . $i, $bon->employeDetail->libelle);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("I" . $i, $bon->agenceDetail->libelle);
                    $i++;
                }

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="tauxLivraisonLivreur.xlsx"');

                $writer->save('php://output');
                exit();
            } elseif ($request->has('tauxRetourDestination')) {
                if ($request->start_date == null || $request->end_date == null) {
                    $villes = Ville::all();
                    $spreadsheet = IOFactory::load(storage_path('export/tauxRetourDestination.xlsx'));
                    $i = 2;
                    foreach ($villes as $record) {
                        $colis = $record->expeditionAllDes
                            ->where("etape", '!=', 5)
                            ->where("created_at", '>=', $firstDayMonth)
                            ->count();
                        $envoi = $record->expeditionAllDes
                            ->where("etape", '!=', 5)
                            ->where("sens", 'Envoi')
                            ->where("created_at", '>=', $firstDayMonth)
                            ->count();
                        $retour = $record->expeditionAllDes
                            ->where("etape", '!=', 5)
                            ->where("sens", 'Retour')
                            ->where("created_at", '>=', $firstDayMonth)
                            ->count();
                        if ($colis == 0) {
                            $taux = 0;
                        } else {
                            $taux =  $retour / $colis * 100;
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $colis);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $envoi);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $retour);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, (int)$taux . '%');
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="tauxRetourDestination.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $villes = Ville::all();
                    $spreadsheet = IOFactory::load(storage_path('export/tauxRetourDestination.xlsx'));
                    $i = 2;
                    foreach ($villes as $record) {
                        if ($star == $end) {
                            $colis = $record->getExpeditionDateOne($star)
                                ->where("etape", '!=', 5)
                                ->count();
                            $envoi = $record->getExpeditionDateOne($star)
                                ->where("etape", '!=', 5)
                                ->where("sens", 'Envoi')
                                ->count();
                            $retour = $record->getExpeditionDateOne($star)
                                ->where("etape", '!=', 5)
                                ->where("sens", 'Retour')
                                ->count();
                        } else {
                            $colis = $record->getExpeditionDateTow($star, $end)
                                ->where("etape", '!=', 5)
                                ->count();
                            $envoi = $record->getExpeditionDateTow($star, $end)
                                ->where("etape", '!=', 5)
                                ->where("sens", 'Envoi')
                                ->count();
                            $retour = $record->getExpeditionDateTow($star, $end)
                                ->where("etape", '!=', 5)
                                ->where("sens", 'Retour')
                                ->count();
                        }

                        if ($colis == 0) {
                            $taux = 0;
                        } else {
                            $taux =  $retour / $colis * 100;
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $colis);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $envoi);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $retour);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, (int)$taux . '%');
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="tauxRetourDestination.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            } elseif ($request->has('tauxRetourClient')) {
                if ($request->start_date == null || $request->end_date == null) {
                    $clients = Client::all();
                    $spreadsheet = IOFactory::load(storage_path('export/tauxRetourClient.xlsx'));
                    $i = 2;
                    foreach ($clients as $record) {
                        $colis = $record->expeditionDetail
                            ->where("etape", '!=', 5)
                            ->where("created_at", '>=', $firstDayMonth)
                            ->count();
                        $envoi = $record->expeditionDetail
                            ->where("created_at", '>=', $firstDayMonth)
                            ->where('sens', 'Envoi')
                            ->count();

                        $retour = $record->expeditionDetail
                            ->where("created_at", '>=', $firstDayMonth)
                            ->where('sens', 'Retour')
                            ->count();
                        if ($colis == 0) {
                            $taux = 0;
                        } else {
                            $taux =    $retour / $colis * 100;
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $colis);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $envoi);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $retour);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, (int)$taux . '%');
                        $i++;
                    }
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="tauxRetourClient.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $clients = Client::all();
                    $spreadsheet = IOFactory::load(storage_path('export/tauxRetourClient.xlsx'));
                    $i = 2;


                    foreach ($clients as $record) {

                        if ($star == $end) {
                            $colis = $record->getExpeditionDateOne($star)
                                ->where("etape", '!=', 5)
                                ->count();
                            $envoi = $record->expeditionDetail
                                ->where('sens', 'Envoi')
                                ->count();

                            $retour = $record->getExpeditionDateOne($star)
                                ->where('sens', 'Retour')
                                ->count();
                        } else {
                            $colis = $record->getExpeditionDateTow($star, $end)
                                ->where("etape", '!=', 5)
                                ->count();
                            $envoi = $record->getExpeditionDateTow($star, $end)
                                ->where('sens', 'Envoi')
                                ->count();

                            $retour = $record->getExpeditionDateTow($star, $end)
                                ->where('sens', 'Retour')
                                ->count();
                        }

                        if ($colis == 0) {
                            $taux = 0;
                        } else {
                            $taux =    $retour / $colis * 100;
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $colis);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $envoi);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $retour);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, (int)$taux . '%');
                        $i++;
                    }
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="tauxRetourClient.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            } elseif ($request->has('tauxReclamation')) {
                if ($request->start_date == null || $request->end_date == null) {
                    $reclamation = Reclamation::all()->where("created_at", '>=', $firstDayMonth)->groupBy('user');

                    $spreadsheet = IOFactory::load(storage_path('export/tauxReclamation.xlsx'));
                    $i = 2;
                    foreach ($reclamation as  $user => $record) {

                        if ($record->where('statut', '2')->count() == 0) {
                            $taux = 0;
                        } else {
                            $taux =   $record->where('statut', '2')->count() / $record->count() * 100;
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->first()->userDetail->ClientDetail->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->count());
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $record->where('statut', '2')->count());
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $record->where('statut', '1')->count());
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->where('statut', '3')->count());
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, (int)$taux . "%");

                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="tauxReclamation.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date)->format("Y-m-d 00:00:00");
                    $end = Carbon::parse($request->end_date)->format("Y-m-d 23:59:59");

                    $reclamation = Reclamation::all()
                        ->where("created_at", '>=', $star)
                        ->where("created_at", '<=', $end)
                        ->groupBy('user');

                    $spreadsheet = IOFactory::load(storage_path('export/tauxReclamation.xlsx'));
                    $i = 2;
                    foreach ($reclamation as  $user => $record) {

                        if ($record->where('statut', '2')->count() == 0) {
                            $taux = 0;
                        } else {
                            $taux =   $record->where('statut', '2')->count() / $record->count() * 100;
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->first()->userDetail->ClientDetail->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->count());
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $record->where('statut', '2')->count());
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $record->where('statut', '1')->count());
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->where('statut', '3')->count());
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, (int)$taux . "%");

                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="tauxReclamation.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            } elseif ($request->has('delaiLivraison')) {
                if ($request->start_date == null || $request->end_date == null) {
                    $processus = Processus_expedition::all()->where("date_validation", '>=', $firstDayMonth)->groupBy('id_agence_dest');
                    $spreadsheet = IOFactory::load(storage_path('export/delaiLivraison.xlsx'));
                    $i = 2;

                    foreach ($processus as $ville => $proc) {
                        $detailLivraisiom = [];
                        foreach ($proc->groupBy('id_expedition') as $ex => $exped) {
                            foreach ($exped as $processus) {
                                if ($processus->code == "RAMASSAGE" && !empty($processus->date_validation)) {
                                    $ramassage = $processus->date_validation;
                                }
                                if (isset($ramassage)) {
                                    if ($processus->code == "LIVRAISON" && !empty($processus->date_validation)) {
                                        $livraison =  Carbon::parse($ramassage)->floatDiffInDays($processus->date_validation);
                                        array_push($detailLivraisiom, $livraison);
                                    }
                                }
                            }
                        }

                        $vil = Ville::where('id', $ville)->first();
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $vil->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, collect($detailLivraisiom)->avg());
                        $i++;
                    }





                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="delaiLivraison.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date)->format("Y-m-d 00:00:00");
                    $end = Carbon::parse($request->end_date)->format("Y-m-d 23:59:59");

                    $processus = Processus_expedition::all()
                        ->where("date_validation", '>=', $star)
                        ->where("date_validation", '<=', $end)
                        ->groupBy('id_agence_dest');
                    $spreadsheet = IOFactory::load(storage_path('export/delaiLivraison.xlsx'));
                    $i = 2;

                    foreach ($processus as $ville => $proc) {
                        $detailLivraisiom = [];
                        foreach ($proc->groupBy('id_expedition') as $ex => $exped) {
                            foreach ($exped as $processus) {
                                if ($processus->code == "RAMASSAGE" && !empty($processus->date_validation)) {
                                    $ramassage = $processus->date_validation;
                                }
                                if (isset($ramassage)) {
                                    if ($processus->code == "LIVRAISON" && !empty($processus->date_validation)) {
                                        $livraison =  Carbon::parse($ramassage)->floatDiffInDays($processus->date_validation);
                                        array_push($detailLivraisiom, $livraison);
                                    }
                                }
                            }
                        }

                        $vil = Ville::where('id', $ville)->first();
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $vil->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, collect($detailLivraisiom)->avg());
                        $i++;
                    }





                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="delaiLivraison.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            } elseif ($request->has('delaiLivraisonEnheure')) {
                if ($request->start_date == null || $request->end_date == null) {
                    $processus = Processus_expedition::all()->where("date_validation", '>=', $firstDayMonth)->groupBy('id_agence_dest');
                    $spreadsheet = IOFactory::load(storage_path('export/delaiLivraison.xlsx'));
                    $i = 2;

                    foreach ($processus as $ville => $proc) {
                        $detailLivraisiom = [];
                        foreach ($proc->groupBy('id_expedition') as $ex => $exped) {
                            foreach ($exped as $processus) {
                                if ($processus->code == "CHARGEMENT" && !empty($processus->date_reception)) {
                                    $CHARGEMENT = $processus->date_reception;
                                }
                                if (isset($CHARGEMENT)) {
                                    if ($processus->code == "LIVRAISON" && !empty($processus->date_validation)) {
                                        $livraison =  Carbon::parse($CHARGEMENT)->diffInHours($processus->date_validation);
                                        array_push($detailLivraisiom, $livraison);
                                    }
                                }
                            }
                        }

                        $vil = Ville::where('id', $ville)->first();
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $vil->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, collect($detailLivraisiom)->avg());
                        $i++;
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="delaiLivraison.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date)->format("Y-m-d 00:00:00");
                    $end = Carbon::parse($request->end_date)->format("Y-m-d 23:59:59");

                    $processus = Processus_expedition::all()
                        ->where("date_validation", '>=', $star)
                        ->where("date_validation", '<=', $end)
                        ->groupBy('id_agence_dest');

                    $spreadsheet = IOFactory::load(storage_path('export/delaiLivraison.xlsx'));
                    $i = 2;

                    foreach ($processus as $ville => $proc) {
                        $detailLivraisiom = [];
                        foreach ($proc->groupBy('id_expedition') as $ex => $exped) {
                            foreach ($exped as $processus) {
                                if ($processus->code == "CHARGEMENT" && !empty($processus->date_reception)) {
                                    $CHARGEMENT = $processus->date_reception;
                                }
                                if (isset($CHARGEMENT)) {
                                    if ($processus->code == "LIVRAISON" && !empty($processus->date_validation)) {
                                        $livraison =  Carbon::parse($CHARGEMENT)->diffInHours($processus->date_validation);
                                        array_push($detailLivraisiom, $livraison);
                                    }
                                }
                            }
                        }

                        $vil = Ville::where('id', $ville)->first();
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $vil->libelle);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, collect($detailLivraisiom)->avg());
                        $i++;
                    }





                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="delaiLivraison.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            } elseif ($request->has('tauxRetourCommercial')) {
                if ($request->start_date == null || $request->end_date == null) {

                    $exps_by_commercial = Client::all()->where('deleted', 0)->where('commerciale', '!=', '')->groupBy('commerciale');

                    $spreadsheet = IOFactory::load(storage_path('export/tauxRetourCommercial.xlsx'));
                    $i = 2;
                    foreach ($exps_by_commercial as $commerciale => $clients) {

                        foreach ($clients as $client) {
                            $exp_client =  Expedition::where('client', $client->id)->where('created_at', '>=', $firstDayMonth)->where('deleted', "0")->get();
                            $com = Employe::find($commerciale);

                            if ($exp_client->count() != 0) {
                                $taux = ($exp_client->where('sens', 'Retour')->count() / $exp_client->count()) * 100;
                            } else {
                                $taux = 0;
                            }


                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $com->libelle);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $client->libelle);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $exp_client->count());
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $exp_client->where('sens', 'Envoi')->count());
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $exp_client->where('sens', 'Retour')->count());
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $taux . '%');
                            $i++;
                        }
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="tauxRetourCommercial.xlsx"');

                    $writer->save('php://output');
                    exit();
                } else {
                    $star = Carbon::parse($request->start_date)->format("Y-m-d 00:00:00");
                    $end = Carbon::parse($request->end_date)->format("Y-m-d 23:59:59");

                    $exps_by_commercial = Client::all()->where('deleted', 0)->where('commerciale', '!=', '')->groupBy('commerciale');

                    $spreadsheet = IOFactory::load(storage_path('export/tauxRetourCommercial.xlsx'));
                    $i = 2;
                    foreach ($exps_by_commercial as $commerciale => $clients) {

                        foreach ($clients as $client) {
                            $exp_client =  Expedition::where('client', $client->id)
                                ->where('created_at', '>=', $star)
                                ->where('created_at', '<=', $end)
                                ->where('deleted', "0")->get();
                            $com = Employe::find($commerciale);

                            if ($exp_client->count() != 0) {
                                $taux = ($exp_client->where('sens', 'Retour')->count() / $exp_client->count()) * 100;
                            } else {
                                $taux = 0;
                            }


                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $com->libelle);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $client->libelle);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $exp_client->count());
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $exp_client->where('sens', 'Envoi')->count());
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $exp_client->where('sens', 'Retour')->count());
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, number_format($taux, 2) . '%');
                            $i++;
                        }
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="tauxRetourCommercial.xlsx"');

                    $writer->save('php://output');
                    exit();
                }
            }
        }
        return view('back.etat.indicateurs');
    }

    public function tarification(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($request->Destination != 0) {

                if (Taxation::where('id_clients', 0)->where('id_ville_exp', $request->Destination)->where('id_ville_dest', $request->depart)->first() != null) {
                    $taxt = Taxation::where('id_clients', 0)->where('id_ville_exp', $request->Destination)->where('id_ville_dest', $request->depart)->first();
                } elseif (Taxation::where('id_clients', 0)->where('id_ville_exp', 2)->where('id_ville_dest', $request->depart)->first() != null) {
                    $taxt = Taxation::where('id_clients', 0)->where('id_ville_exp', 2)->where('id_ville_dest', $request->depart)->first();
                } else {
                    $taxt = '';
                }
                $depart = Ville::where('id', $request->depart)->first();
                $Destination = Ville::where('id', $request->Destination)->first();
                $spreadsheet = IOFactory::load(storage_path('export/tarification.xlsx'));
                $i = 2;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i,  $depart->libelle);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $Destination->libelle);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $taxt->coefficient);

                $i++;


                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="tarification.xlsx"');

                $writer->save('php://output');
                exit();
            } elseif ($request->Destination == 0) {
                $taxations = Taxation::where('id_clients', null)->where('id_ville_dest', $request->depart)->orwhere('id_clients', 0)->where('id_ville_dest', $request->depart)->get();
                $spreadsheet = IOFactory::load(storage_path('export/tarification.xlsx'));
                $i = 2;
                foreach ($taxations as $taxation) {

                    $taxt = $taxation->coefficient;


                    $depart = Ville::where('id', $taxation->id_ville_dest)->first();
                    $Destination = Ville::where('id', $taxation->id_ville_exp)->first();

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i,  $depart->libelle);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $Destination->libelle);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $taxt);

                    $i++;
                }



                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="tarification.xlsx"');

                $writer->save('php://output');
                exit();
            }
        }
        $villes = Ville::where('deleted', '0')->where('id', "!=", 2)->get();
        return view('back.etat.tarification', [
            'villes' => $villes
        ]);
    }

    public function auditmodification(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($request->start_date == null || $request->end_date == null) {
                $commentaire = Commentaire::whereIn('code', ['Modification', 'MODIFICATION_FRAIS'])->get();
                $spreadsheet = IOFactory::load(storage_path('export/auditModification.xlsx'));
                $i = 2;
                foreach ($commentaire as $record) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->expDetails->num_expedition);
                    if ($record->userDetail->role == 3) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->userDetail->clientDetail->libelle);
                    } else {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->userDetail->EmployeDetail->libelle);
                    }
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $record->attribut);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $record->ancienne_valeur);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->nouvelle_valeur);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . $i, $record->created_at);
                    $i++;
                }

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="auditModification.xlsx"');

                $writer->save('php://output');
                exit();
            } else {
                $star = Carbon::parse($request->start_date)->format("Y-m-d 00:00:00");
                $end = Carbon::parse($request->end_date)->format("Y-m-d 23:59:59");

                $commentaire = Commentaire::whereIn('code', ['Modification', 'MODIFICATION_FRAIS'])
                    ->where("created_at", '>=', $star)
                    ->where("created_at", '<=', $end)->get();
                $spreadsheet = IOFactory::load(storage_path('export/auditModification.xlsx'));
                $i = 2;
                foreach ($commentaire as $record) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->expDetails->num_expedition);
                    if ($record->userDetail->role == 3) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->userDetail->clientDetail->libelle);
                    } else {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->userDetail->EmployeDetail->libelle);
                    }
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $record->attribut);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $record->ancienne_valeur);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->nouvelle_valeur);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . $i, $record->created_at);
                    $i++;
                }

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="auditModification.xlsx"');

                $writer->save('php://output');
                exit();
            }
        }
        return view("back.etat.auditmodification");
    }

    public function caisses(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($request->start_date == null || $request->end_date == null) {
                if ($request->ville == 1) {

                    $caisses = CaissesExpeditions::all();
                } else {

                    $caisses = CaissesExpeditions::where("id_agence", $request->ville)->get();
                }

                $spreadsheet = IOFactory::load(storage_path('export/caisses.xlsx'));
                $i = 2;
                foreach ($caisses as $record) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->agenceDetail->libelle);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->Caisse->numero);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->expeditionDetail->num_expedition);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $record->expeditionDetail->clientDetail->libelle);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . $i, $record->expeditionDetail->origineDetail->libelle);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . $i, $record->expeditionDetail->destinataire);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("I" . $i, $record->expeditionDetail->agenceDetail->libelle);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("J" . $i, $record->expeditionDetail->colis);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("K" . $i, $record->expeditionDetail->fond);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("L" . $i, $record->expeditionDetail->ttc);
                    $i++;
                }

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="caisses.xlsx"');

                $writer->save('php://output');
                exit();
            } else {
                $star = Carbon::parse($request->start_date)->format("Y-m-d 00:00:00");
                $end = Carbon::parse($request->end_date)->format("Y-m-d 23:59:59");

                if ($request->ville == 1) {

                    $caisses = CaissesExpeditions::where("created_at", '>=', $star)
                        ->where("created_at", '<=', $end)->get();
                } else {

                    $caisses = CaissesExpeditions::where("id_agence", $request->ville)->where("created_at", '>=', $star)
                        ->where("created_at", '<=', $end)->get();
                }

                $spreadsheet = IOFactory::load(storage_path('export/caisses.xlsx'));
                $i = 2;
                foreach ($caisses as $record) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->agenceDetail->libelle);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->Caisse->numero);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->expeditionDetail->num_expedition);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $record->expeditionDetail->clientDetail->libelle);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("G" . $i, $record->expeditionDetail->origineDetail->libelle);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("H" . $i, $record->expeditionDetail->destinataire);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("I" . $i, $record->expeditionDetail->agenceDetail->libelle);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("J" . $i, $record->expeditionDetail->colis);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("K" . $i, $record->expeditionDetail->fond);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("L" . $i, $record->expeditionDetail->ttc);
                    $i++;
                }

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="caisses.xlsx"');

                $writer->save('php://output');
                exit();
            }
        }
        $villes = Ville::where('deleted', 0)->get();
        return view("back.etat.caisses", [
            'villes' =>  $villes
        ]);
    }
    public function pdfagence($records, $titre, $startTime, $endTime)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) use ($titre, $endTime) {
            $header = '

                     <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                         <tr >
                             <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                             <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                             </td>
                             <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                                 <b>' . $titre . '</b>
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
        $pdf::AddPage('P', 'A4');

        // Set some content to print
        //Téléphone Adresse
        $html = '
             <p>' . $startTime->format('d-m-y') . ' Au ' . $endTime->format('d-m-y') . '</p>
             <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                     <tr>
                         <td width="30%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2">
                             <b>Agence</b>
                         </td>
                         <td width="60%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Total</b>
                         </td>

                     </tr>';
        foreach ($records as $record) {

            $html .= '<tr>
             <td width="30%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  >
             <b>' . $record->agence . '</b>
          </td>
             <td width="60%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" ><b>' . $record->fond . '</b>
             </td>




             </tr>';
        }






        $html .= '</table>';


        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Situation du stock.pdf', 'I');
    }





    public function pdfClient($records, $titre, $startTime, $endTime)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) use ($titre) {
            $header = '
                         <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                             <tr >
                                 <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                                 <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                                 </td>
                                 <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                                     <b>' . $titre . '</b>
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
        $pdf::AddPage('P', 'A4');

        // Set some content to print
        //Téléphone Adresse
        $html = '
                 <p>' . $startTime->format('d-m-y') . ' Au ' . $endTime->format('d-m-y') . '</p>
                 <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                         <tr>
                             <td width="30%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2">
                                 <b>Client</b>
                             </td>
                             <td width="60%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Total</b>
                             </td>

                         </tr>';
        foreach ($records as $record) {

            $html .= '<tr>
                 <td width="30%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  >
                 <b>' . $record->client . '</b>
              </td>
                 <td width="60%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" ><b>' . $record->fond . '</b>
                 </td>




                 </tr>';
        }






        $html .= '</table>';


        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Situation du stock.pdf', 'I');
    }
}
