<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Employe;
use App\Models\Client;
use App\Models\TaxationRegions;
use Illuminate\Http\Request;
use App\Models\Ville;
use App\Models\Region;
use App\Models\Taxation;
use App\Models\Commission;
use App\Models\TaxationRegion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class TaxationController extends Controller
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

    public function taxations(Request $request){

        $request->flash();
        $villes = Ville::getVilles();
        if ($request->isMethod('post')) {
            // dd(Taxation::getFormData(), request()->input('prix'));
            Taxation::saveData(Taxation::getFormData(), request()->input('prix'));
        }

        return view('back/taxation/taxations' ,
                                        [
                                            'records' => Taxation::getFormRows($villes, Taxation::getFormData()),
                                            'villes' => Ville::getVilles(),
                                            'ClientRecords' => Client::all()->where('deleted',"0"),
                                            'AgenceRecords' => Agence::all()->where('deleted',"0")
                                        ]
        );
    }

    public function taxations_region(Request $request){

        $rg = region::first();


        $request->flash();
        $villes = Ville::getVilles();
        $regions = Region::getRegions();

        if ($request->isMethod('post')) {
            TaxationRegions::saveData(TaxationRegions::getFormData(), request()->input('prix'));
        }

        return view('back/taxation/taxationsRegion' ,
                                        [
                                            'records' => TaxationRegions::getFormRows($villes,$regions, TaxationRegions::getFormData()),
                                            'villes' =>  $villes,
                                            'regions' => $regions,
                                            'ClientRecords' => Client::all()->where('deleted',"0"),
                                            'AgenceRecords' => Agence::all()->where('deleted',"0")
                                        ]
        );
    }

    public function taxations_type(){
        return view('back/taxation/TaxationType');
    }

    public function commissions(Request $request){

        $request->flash();
        $villes = Ville::getVilles();
        if ($request->isMethod('post')) {
            Commission::saveData(Commission::getFormData(), request()->input('prix'));
        }
        //dd(Commission::getFormRows($villes, Commission::getFormData()));
        return view('back/taxation/commissions' ,
                                        [
                                            'records' => Commission::getFormRows($villes, Commission::getFormData()),
                                            'villes' => Ville::getVilles(),
                                            'livreurs' => Employe::fetchAll(),
                                        ]
        );
    }

    public function list(Request $request){

        $request->flash();
        return view('back/taxation/list' ,
                                        [
                                            'records' => Taxation::all()->where('deleted',"0"),
                                            'ClientRecords' => Client::all()->where('deleted',"0"),
                                            'AgenceRecords' => Agence::all()->where('deleted',"0")

                                        ]
        );
    }

    public function create($idville,Request $request){

        if ($request->isMethod('post')) {
            $rules = [];
            $validator =  Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                Taxation::create($request->all());
                Redirect::to(route('taxation_list'))->send();
            }
        }
        $viewsData = [];
        $viewsData['record'] = \App\Models\Taxation::all()->where('deleted','0')->where('id_ville_exp', $idville);
        $viewsData['idville'] = $idville;


		$viewsData['villeRecordsExp'] = \App\Models\Ville::all()->where('deleted','0');
		$viewsData['villeRecordsDest'] = \App\Models\Ville::all()->where('deleted','0');

        return view('back/taxation/create', $viewsData);
    }

    public function update(Taxation $taxation, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $taxation->update($request->all());
                Redirect::to(route('taxation_list'))->send();
            }
        }
        $viewsData['record'] = $taxation;

		$viewsData['villeRecordsExp'] = \App\Models\Ville::all()->where('deleted','0');
		$viewsData['villeRecordsDest'] = \App\Models\Ville::all()->where('deleted','0');

        return view('back/taxation/details' , $viewsData);
    }

    public function delete(Taxation $taxation){
        $taxation->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}
