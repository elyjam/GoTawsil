<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Ville;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class RegionController extends Controller
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
        return view('back/region/list' ,
                                        [
                                            'records' => Region::all()->where('deleted',"0")
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
                Region::create($request->all());
                Redirect::to(route('region_list'))->send();
            }
        }
        $viewsData = [];


        return view('back/region/create', $viewsData);
    }

    public function update(Region $region, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails() ) {
                return redirect()->back()->withInput()->withErrors($validator);

            }
            else{
                $region->code = $request->all()["code"];
                $region->libelle = $request->all()["Libelle"];
                $region->statut = $request->all()["statut"];
                $region->save();

                if(isset($request->all()['villes'])){
                    $region->relatedVilles()->sync(Ville::find($request->all()['villes']));
                    Redirect::to(route('region_list'))->send();

                }else{

                    $region->relatedVilles()->sync([]);
                }


            }

            $region->update($request->all());

        }


        $viewsData['record'] = $region;
        $viewsData['villes'] =  \App\Models\Ville::all()->where('deleted','0');

        return view('back/region/update' , $viewsData);
    }

    public function delete(Region $region){
        $region->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}
