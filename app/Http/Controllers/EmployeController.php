<?php

namespace App\Http\Controllers;

use App\Models\Ville;
use App\Models\Employe;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class EmployeController extends Controller
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
        $records =  DB::table('employes')
        ->select(
            "*",
            \DB::raw('employes.email as email'),
            \DB::raw('villes.libelle as ville'),
            \DB::raw('employes.libelle as libelle'),
            \DB::raw('employes.id as employeId'))
        ->leftJoin('users', 'users.employe', '=', 'employes.id')
        ->leftJoin('villes', 'villes.id', '=', 'employes.agence')
        ->get();
        if($request->isMethod('post')){
            $spreadsheet = IOFactory::load(storage_path('export/employes.xlsx'));
            $i = 2;

            foreach ($records as $record) {
                if($record->validated == 1){
                    $statut = 'Actif';
                }else{
                    $statut = 'Inactif';
                }
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->adresse);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $record->telephone);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $statut);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->email);

                $i++;
            }

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="employes.xlsx"');

            $writer->save('php://output');
            exit();
        }

        return view('back/employe/list' ,
                                        [
                                            'records' => $records
                                        ]
        );
    }

    public function create(Request $request){

        if ($request->isMethod('post')) {

            $rules = [
                'libelle' => 'required',
                'adresse' => 'required',
                'agence' => 'required',
                'type' => 'required',

            ];
            $validator =  Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                Employe::create($request->all());
                Redirect::to(route('employe_list'))->send();
            }
        }
        $viewsData = [];

		$viewsData['agenceRecords'] = \App\Models\Ville::all()->where('deleted','0');
		$viewsData['fonctionRecords'] = \App\Models\Fonction::all()->where('deleted','0');
		$viewsData['typesemployeRecords'] = \App\Models\Typesemploye::all()->where('deleted','0');

        return view('back/employe/create', $viewsData);
    }

    public function update(Employe $employe, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [
                'libelle' => 'required',
                'adresse' => 'required',
                'agence' => 'required',
                'type' => 'required',
            ];

            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $employe->update($request->all());
                if($request->statut == 1){
                    $user = \App\user::where('employe',$employe->id)->first()->update(['validated' => 1]);
                }else{
                    $user = \App\user::where('employe',$employe->id)->first()->update(['validated' => 0]);
                }
                Redirect::to(route('employe_list'))->send();
            }
        }
        $viewsData['record'] = $employe;

		$viewsData['agenceRecords'] = \App\Models\Ville::all()->where('deleted','0');
		$viewsData['fonctionRecords'] = \App\Models\Fonction::all()->where('deleted','0');
		$viewsData['typesemployeRecords'] = \App\Models\Typesemploye::all()->where('deleted','0');

        return view('back/employe/update' , $viewsData);
    }

    public function affectLivaison(Employe $employe, Request $request){

        if ($request->isMethod('post')) {
            if(isset($request->all()['villes'])){
                $employe->relatedVilles()->sync(Ville::find($request->all()['villes']));
            }
            else{
                $employe->related()->sync([]);
            }
        }
        return view('back/employe/affectLiv', [
                                                'employe' => $employe, 'employes' => Employe::all()->where('deleted','0'),
                                                'villes' => \App\Models\Ville::getVilles()
                                            ]);
    }

    public function delete(Employe $employe){
        $employe->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }



}
