<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agence;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class AgenceController extends Controller
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
        return view('back/agence/list' , 
                                        [
                                            'records' => Agence::all()->where('deleted',"0")
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
                Agence::create($request->all());
                Redirect::to(route('agence_list'))->send();
            }            
        }
        $viewsData = [];
        
		$viewsData['villeRecords'] = \App\Models\Ville::all()->where('deleted','0');

        return view('back/agence/create', $viewsData);        
    }

    public function affectLivaison(Agence $agence, Request $request){

        if ($request->isMethod('post')) {
            if(isset($request->all()['agences'])){
                $agence->related()->sync(Agence::find($request->all()['agences']));
            }
            else{
                $agence->related()->sync([]);
            }
        }

        return view('back/agence/affectLiv', ['agence' => $agence, 'agences' => \App\Models\Agence::all()->where('deleted','0')]);

    }

    public function update(Agence $agence, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $agence->update($request->all());
                Redirect::to(route('agence_list'))->send();
            }            
        }
        $viewsData['record'] = $agence;
        
		$viewsData['villeRecords'] = \App\Models\Ville::all()->where('deleted','0');

        return view('back/agence/update' , $viewsData);        
    }

    public function delete(Agence $agence){
        $agence->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}