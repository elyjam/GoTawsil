<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ville;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class VilleController extends Controller
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
        return view('back/ville/list' , 
                                        [
                                            'records' => Ville::all()->where('deleted',"0")
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
                Ville::create($request->all());
                Redirect::to(route('ville_list'))->send();
            }            
        }
        return view('back/ville/create', []);        
    }

    public function update(Ville $ville, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{              
                $ville->update($request->all());
                Redirect::to(route('ville_list'))->send();
            }            
        }
        return view('back/ville/update' , 
                                        [
                                            'record' => $ville
                                        ]
        );        
    }

    public function delete(Ville $ville){
        $ville->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}