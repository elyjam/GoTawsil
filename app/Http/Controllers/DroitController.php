<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Droit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class DroitController extends Controller
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
        return view('back/droit/list' , 
                                        [
                                            'records' => Droit::all()->where('deleted',"0")
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
                Droit::create($request->all());
                Redirect::to(route('droit_list'))->send();
            }            
        }
        return view('back/droit/create', []);        
    }

    public function update(Droit $droit, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{              
                $droit->update($request->all());
                Redirect::to(route('droit_list'))->send();
            }            
        }
        return view('back/droit/update' , 
                                        [
                                            'record' => $droit
                                        ]
        );        
    }

    public function delete(Droit $droit){
        $droit->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}