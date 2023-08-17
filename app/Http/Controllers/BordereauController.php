<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bordereau;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class BordereauController extends Controller
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
        return view('back/bordereau/list' , 
                                        [
                                            'records' => Bordereau::all()->where('deleted',"0")
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
                Bordereau::create($request->all());
                Redirect::to(route('bordereau_list'))->send();
            }            
        }
        $viewsData = [];
        

        return view('back/bordereau/create', $viewsData);        
    }

    public function update(Bordereau $bordereau, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $bordereau->update($request->all());
                Redirect::to(route('bordereau_list'))->send();
            }            
        }
        $viewsData['record'] = $bordereau;
        

        return view('back/bordereau/update' , $viewsData);        
    }

    public function delete(Bordereau $bordereau){
        $bordereau->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}