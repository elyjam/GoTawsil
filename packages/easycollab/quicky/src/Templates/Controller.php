<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Model};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class {Model}Controller extends Controller
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
        return view('back/{projetId}/list' , 
                                        [
                                            'records' => {Model}::all()->where('deleted',"0")
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
                {Model}::create($request->all());
                Redirect::to(route('{projetId}_list'))->send();
            }            
        }
        $viewsData = [];
        {viewsData}
        return view('back/{projetId}/create', $viewsData);        
    }

    public function update({Model} ${projetId}, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                ${projetId}->update($request->all());
                Redirect::to(route('{projetId}_list'))->send();
            }            
        }
        $viewsData['record'] = ${projetId};
        {viewsData}
        return view('back/{projetId}/update' , $viewsData);        
    }

    public function delete({Model} ${projetId}){
        ${projetId}->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}