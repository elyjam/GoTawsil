<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transporteur;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class TransporteurController extends Controller
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
        return view('back/transporteur/list' , 
                                        [
                                            'records' => Transporteur::all()->where('deleted',"0")
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
                Transporteur::create($request->all());
                Redirect::to(route('transporteur_list'))->send();
            }            
        }
        return view('back/transporteur/create', []);        
    }

    public function update(Transporteur $transporteur, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{              
                $transporteur->update($request->all());
                Redirect::to(route('transporteur_list'))->send();
            }            
        }
        return view('back/transporteur/update' , 
                                        [
                                            'record' => $transporteur
                                        ]
        );        
    }

    public function delete(Transporteur $transporteur){
        $transporteur->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}