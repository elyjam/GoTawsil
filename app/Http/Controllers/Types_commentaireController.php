<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Types_commentaire;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class Types_commentaireController extends Controller
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
        return view('back/types_commentaire/list' , 
                                        [
                                            'records' => Types_commentaire::all()->where('deleted',"0")
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
                Types_commentaire::create($request->all());
                Redirect::to(route('types_commentaire_list'))->send();
            }            
        }
        return view('back/types_commentaire/create', []);        
    }

    public function update(Types_commentaire $types_commentaire, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{              
                $types_commentaire->update($request->all());
                Redirect::to(route('types_commentaire_list'))->send();
            }            
        }
        return view('back/types_commentaire/update' , 
                                        [
                                            'record' => $types_commentaire
                                        ]
        );        
    }

    public function delete(Types_commentaire $types_commentaire){
        $types_commentaire->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}