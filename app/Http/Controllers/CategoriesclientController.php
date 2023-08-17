<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoriesclient;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class CategoriesclientController extends Controller
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
        return view('back/categoriesclient/list' , 
                                        [
                                            'records' => Categoriesclient::all()->where('deleted',"0")
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
                Categoriesclient::create($request->all());
                Redirect::to(route('categoriesclient_list'))->send();
            }            
        }
        $viewsData = [];
        

        return view('back/categoriesclient/create', $viewsData);        
    }

    public function update(Categoriesclient $categoriesclient, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $categoriesclient->update($request->all());
                Redirect::to(route('categoriesclient_list'))->send();
            }            
        }
        $viewsData['record'] = $categoriesclient;
        

        return view('back/categoriesclient/update' , $viewsData);        
    }

    public function delete(Categoriesclient $categoriesclient){
        $categoriesclient->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}