<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fonction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class FonctionController extends Controller
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
        return view('back/fonction/list' , 
                                        [
                                            'records' => Fonction::all()->where('deleted',"0")
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
                Fonction::create($request->all());
                Redirect::to(route('fonction_list'))->send();
            }            
        }
        $viewsData = [];
        

        return view('back/fonction/create', $viewsData);        
    }

    public function update(Fonction $fonction, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $fonction->update($request->all());
                Redirect::to(route('fonction_list'))->send();
            }            
        }
        $viewsData['record'] = $fonction;
        

        return view('back/fonction/update' , $viewsData);        
    }

    public function delete(Fonction $fonction){
        $fonction->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}