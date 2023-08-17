<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caissepp;
use App\Models\Expedition;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use \PhpOffice\PhpSpreadsheet\IOFactory;



class CaisseppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function getRules()
    {
        return [
        ];
    }

    public function list(Request $request){

        $request->flash();
        $viewsData = [];
        $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
        $viewsData['agenceRecords'] = \App\Models\Ville::all()->where('deleted', '0')->where('id', "!=", 2);
        $viewsData['statutRecords'] = \App\Models\Statut::all()->where('deleted', '0')->where('code', 'EXPEDITION');
        $viewsData['commentRecords'] = \App\Models\Statut::all()->where('deleted', '0')
            ->where('code', 'ETAPE_EXPEDITION');
        $viewsData['records'] = Caissepp::getRecords($request->all());
        //dd(Caissepp::getRecords($request->all()));
        return view('back/caissepp/list' , $viewsData
        );
    }

    public function create(Request $request){

        if ($request->isMethod('post')) {
            $rules = [];
            $validator =  Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                Caissepp::create($request->all());
                Redirect::to(route('caissepp_list'))->send();
            }
        }
        $viewsData = [];


        return view('back/caissepp/create', $viewsData);
    }

    public function update(Caissepp $caissepp, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $caissepp->update($request->all());
                Redirect::to(route('caissepp_list'))->send();
            }
        }
        $viewsData['record'] = $caissepp;


        return view('back/caissepp/update' , $viewsData);
    }

    public function delete(Caissepp $caissepp){
        // dd($caissepp);
        $caissepp->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

    public function valid(Expedition $expedition){

        $expedition->update(['caissepp_statut' => 1, 'caissepp_date_recp' => date("Y-m-d H:i:s")]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

    public function export(Request $request){

        $records = Caissepp::getRecords($request->all());
        $rowsNbr = count($records)-1;

        $spreadsheet = IOFactory::load(storage_path('export/caissespp.xlsx'));
        $i = 2;
        if($rowsNbr != 0){
            $spreadsheet->getActiveSheet()->insertNewRowBefore(3,$rowsNbr);
        }

        foreach ($records as $record) {
            $date='';
            if (strlen($record->caissepp_date_recp)>2) {
                $date =date('d/m/Y H:i', strtotime($record->caissepp_date_recp));
            }
            $statut = $record->caissepp_statut == 1 ? 'Reçu' : 'En cours';

            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->num_expedition);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $date);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $record->name.' '.$record->first_name);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $record->ttc);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $statut);
            $i++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="export des caisses PP.xlsx"');
        $writer->save('php://output');
    }

}
