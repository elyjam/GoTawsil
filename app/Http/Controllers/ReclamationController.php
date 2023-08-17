<?php

namespace App\Http\Controllers;

use App\Models\Expedition;
use App\Models\ReclamationHistory;
use App\Models\ReclamationSuivi;
use Illuminate\Http\Request;
use App\Models\Reclamation;
use App\Models\Statut;
use App\Models\Typereclamation;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class ReclamationController extends Controller
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
        $userRole = auth()->user()->role;
        if ($userRole == '3') {
            $request->flash();
            return view(
                'client/reclamation/list',
                [
                    'records' => Reclamation::all()->where('deleted', "0")->where('user', auth()->user()->id)
                ]
            );
        } else {
            $request->flash();
            $viewsData = [];

            // filter pour reclamation page
            if ($request->isMethod('post')) {
                $records = Reclamation::where('deleted', "0");
                $star = Carbon::parse($request->start_date)->format("Y-m-d 00:00:00");
                $end = Carbon::parse($request->end_date)->format("Y-m-d 23:59:59");

                if (isset($request->client) && $request->client != 0) {
                    $user = User::where('client', $request->client)->first();

                    if (Reclamation::find($user)) {
                        $records->where('user', $user->id);
                    }else{
                        $records->where('user', 0);
                    }
                }
                if (isset($request->Statut) && $request->Statut != 0) {

                    $records->where('statut', $request->Statut);
                }
                if (isset($request->type) && $request->type != 0) {
                    $records->where('typereclamation', $request->type);
                }

                if ($request->start_date != null) {
                    $records = $records->where("created_at", '>=', $star);
                }
                if ($request->end_date != null) {
                    $records = $records->where("created_at", '<=', $end);
                }
                $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
                $statuts = Statut::where('code', 'RECLAMATION')->get();
                return view(
                    'back/reclamation/list',
                    [
                        'records' => $records->get(),
                        'typeReclamation' => Typereclamation::all(),
                        'statuts' => $statuts,
                        'star_date' =>  '',
                        'end_date' =>   ''
                    ],
                    $viewsData
                );
            }

            // affichage de page reclamation
            $now = Carbon::now();
            $end_date = Carbon::parse(Carbon::now())->format("Y-m-d");
            $subweek = Carbon::parse(Carbon::now()->subWeek())->format("Y-m-d");
            $records = Reclamation::all()->where('deleted', "0")->where("created_at", '>=',  $now->subWeek());
            $statuts = Statut::where('code', 'RECLAMATION')->get();
            $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
            return view(
                'back/reclamation/list',
                [
                    'records' => $records,
                    'typeReclamation' => Typereclamation::all(),
                    'statuts' => $statuts,
                    'star_date' =>  $subweek,
                    'end_date' =>   $end_date,
                ],
                $viewsData
            );
        }
    }

    public function create(Request $request)
    {
        $userRole = auth()->user()->role;
        if ($userRole == '3') {
            if ($request->isMethod('post')) {
                $rules = [
                    'description' => 'required',
                    'typereclamation' => 'required',
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $count = "R" . sprintf("%05d", Reclamation::all()->count() + 1);
                    Reclamation::create($request->all() + ['user' => auth()->user()->id] + ['statut' => 1] + ['code' => $count]);
                    Redirect::to(route('reclamation_list'))->send();
                }
            }
            $viewsData = [];

            $viewsData['typereclamationRecords'] = \App\Models\Typereclamation::all()->where('deleted', '0');
            $viewsData['userRecords'] = \App\User::all()->where('deleted', '0');
            $viewsData['employeRecords'] = \App\Models\Employe::all()->where('deleted', '0')->where('statut',1);

            return view('client/reclamation/create', $viewsData);
        }
    }

    public function detail(Reclamation $reclamation)
    {
        $user_role =  auth()->user()->role;
        $viewsData = [];
        if ($user_role == '1') {
            $viewsData['record'] = $reclamation;
            $viewsData['typereclamationRecords'] = \App\Models\Typereclamation::all()->where('deleted', '0');
            $viewsData['userRecords'] = \App\User::all()->where('deleted', '0');
            $viewsData['employeRecords'] = \App\Models\Employe::all()->where('deleted', '0')->where('statut',1);
            $viewsData['messagesRecords'] = \App\Models\ReclamationSuivi::all()->where('reclamation', $reclamation->id);
            $viewsData['historyRecords'] = \App\Models\ReclamationHistory::all()->where('reclamation', $reclamation->id);
            return view('back/reclamation/details', $viewsData)->with('message', 'Votre message est bien enregistrée');
        } elseif ($user_role == '3') {
            $reclamation->update([
                'read' => 0
            ]);
            $viewsData['record'] = $reclamation;
            $viewsData['typereclamationRecords'] = \App\Models\Typereclamation::all()->where('deleted', '0');
            $viewsData['userRecords'] = \App\User::all()->where('deleted', '0');
            $viewsData['employeRecords'] = \App\Models\Employe::all()->where('deleted', '0')->where('statut',1);
            $viewsData['messagesRecords'] = \App\Models\ReclamationSuivi::all()->where('reclamation', $reclamation->id);

            return view('client/reclamation/details', $viewsData);
        }
    }

    public function reclamation_message(Request $request)
    {

        $user =  auth()->user()->id;
        $user_role =  auth()->user()->role;
        if ($request->isMethod('post')) {
            if ($user_role == '1') {
                ReclamationSuivi::create(
                    ['description' => $request->input('message')] +
                        ['reclamation' => $request->input('reclamation_id')] +
                        ['user' => $user]
                );

                ReclamationHistory::create(
                    ['user' => \Auth::user()->id] +
                        ['reclamation' => $request->input('reclamation_id')] +
                        ['statut' => 2] +
                        ['motif' => '*Ajout d\'un message']
                );
                $reclamation = Reclamation::where('id', $request->input('reclamation_id'))->first();
                $reclamation->update([
                    'read' => 1
                ]);
            } elseif ($user_role == '3') {
                ReclamationSuivi::create(
                    ['description' => $request->input('message')] +
                        ['reclamation' => $request->input('reclamation_id')] +
                        ['user' => $user]
                );

                ReclamationHistory::create(
                    ['user' => \Auth::user()->id] +
                        ['reclamation' => $request->input('reclamation_id')] +
                        ['statut' => 2] +
                        ['motif' => '*Ajout d\'un message']
                );
                $reclamation = Reclamation::where('id', $request->input('reclamation_id'))->first();
                $reclamation->update([
                    'read' => 0
                ]);
            }



            Redirect::to(route('reclamation_detail', $request->input('reclamation_id')))->with('message', 'Votre message est bien enregistrée')->send();
        }
    }


    public function cloturer_reclamation($id)
    {
        $reclamation = \App\Models\Reclamation::where('id', $id)->first();

        $reclamation->update(
            [
                'cloture' => 1,
                'statut' => 2,
                'cloture_par' => \Auth::user()->id,
                'cloture_at' => date("Y-m-d H:i:s"),
            ]
        );

        ReclamationHistory::create(
            ['user' => \Auth::user()->id] +
                ['reclamation' => $id] +
                ['statut' => 2] +
                ['motif' => '*Statut changé en Cloturée']
        );

        return redirect()->back()->with('success', "la réclamation est bien clôturée");
    }

    public function cancel_reclamation($id)
    {
        $reclamation = \App\Models\Reclamation::where('id', $id)->first();

        $reclamation->update(
            [
                'cloture' => 0,
                'statut' => 3,
            ]
        );

        ReclamationHistory::create(
            ['user' => \Auth::user()->id] +
                ['reclamation' => $id] +
                ['statut' => 3] +
                ['motif' => '*Annuler la reclamation']
        );

        return redirect()->back()->with('success', "la réclamation est bien annuler");
    }

    public function reopen_reclamation($id)
    {
        $reclamation = \App\Models\Reclamation::where('id', $id)->first();

        $reclamation->update(
            [
                'cloture' => 0,
                'statut' => 1
            ]
        );

        ReclamationHistory::create(
            ['user' => \Auth::user()->id] +
                ['reclamation' => $id] +
                ['statut' => 1] +
                ['motif' => '*Statut rouvrir']
        );



        return redirect()->back()->with('reopen', "La réclamation est ouvre encore");
    }

    public function delete(Reclamation $reclamation)
    {
        $reclamation->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

    public static function export()
    {
        $reclamations = Reclamation::all()->where('deleted', "0");
        $spreadsheet = IOFactory::load(storage_path('export/reclamation.xlsx'));
        $i = 2;
        foreach ($reclamations as $record) {

            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->userDetail->ClientDetail->libelle);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->typereclamationDetail->libelle);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $record->description);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $record->cloture_par);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->date_cloture);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("F" . $i, $record->statut);
            $i++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="export.xlsx"');

        $writer->save('php://output');
    }
}
