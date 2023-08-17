<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Typesemploye;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class TypesemployeController extends Controller
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
        return view('back/typesemploye/list' , 
                                        [
                                            'records' => Typesemploye::all()->where('deleted',"0")
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
                Typesemploye::create($request->all());
                Redirect::to(route('typesemploye_list'))->send();
            }            
        }
        $viewsData = [];
        

        return view('back/typesemploye/create', $viewsData);        
    }

    public function update(Typesemploye $typesemploye, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $typesemploye->update($request->all());
                Redirect::to(route('typesemploye_list'))->send();
            }            
        }
        $viewsData['record'] = $typesemploye;
        

        return view('back/typesemploye/update' , $viewsData);        
    }

    public function delete(Typesemploye $typesemploye){
        $typesemploye->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}