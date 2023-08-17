<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Typebl;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class TypeblController extends Controller
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
        return view('back/typebl/list' , 
                                        [
                                            'records' => Typebl::all()->where('deleted',"0")
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
                Typebl::create($request->all());
                Redirect::to(route('typebl_list'))->send();
            }            
        }
        $viewsData = [];
        

        return view('back/typebl/create', $viewsData);        
    }

    public function update(Typebl $typebl, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $typebl->update($request->all());
                Redirect::to(route('typebl_list'))->send();
            }            
        }
        $viewsData['record'] = $typebl;
        

        return view('back/typebl/update' , $viewsData);        
    }

    public function delete(Typebl $typebl){
        $typebl->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}