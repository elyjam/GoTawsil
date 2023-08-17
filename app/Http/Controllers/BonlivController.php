<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bonliv;
use App\Models\Statut;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class BonlivController extends Controller
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
        $end_date = Carbon::parse(Carbon::now())->format("Y-m-d");
        $subweek = Carbon::parse(Carbon::now()->subWeek())->format("Y-m-d");
        return view('back/bonliv/list' ,
                                        [
                                            'status' => Statut::fetchAllByCode('BLS'),
                                            'employes' => Employe::all()->where('deleted','0')->where('statut',1),
                                            'records' => Bonliv::getRecords(),
                                            'star_date' =>  $subweek,
                                            'end_date' =>   $end_date
                                        ]
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
                Bonliv::create($request->all());
                Redirect::to(route('bonliv_list'))->send();
            }
        }
        $viewsData = [];

		$viewsData['employeRecords'] = \App\Models\Employe::all()->where('deleted','0')->where('statut',1);
		$viewsData['typeblRecords'] = \App\Models\Typebl::all()->where('deleted','0');

        return view('back/bonliv/create', $viewsData);
    }

    public function update(Bonliv $bonliv, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $bonliv->update($request->all());
                Redirect::to(route('bonliv_list'))->send();
            }
        }
        $viewsData['record'] = $bonliv;
		$viewsData['employeRecords'] = \App\Models\Employe::all()->where('deleted','0')->where('statut',1);
		$viewsData['typeblRecords'] = \App\Models\Typebl::all()->where('deleted','0');
        return view('back/bonliv/update' , $viewsData);
    }

    public function delete(Bonliv $bonliv){
        $bonliv->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

    public function download($id)
    {
        Bonliv::print(Bonliv::find($id));
    }

}
