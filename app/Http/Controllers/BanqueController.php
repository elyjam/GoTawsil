<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banque;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class BanqueController extends Controller
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
        return view('back/banque/list' , 
                                        [
                                            'records' => Banque::all()->where('deleted',"0")
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
                Banque::create($request->all());
                Redirect::to(route('banque_list'))->send();
            }            
        }
        $viewsData = [];
        

        return view('back/banque/create', $viewsData);        
    }

    public function update(Banque $banque, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $banque->update($request->all());
                Redirect::to(route('banque_list'))->send();
            }            
        }
        $viewsData['record'] = $banque;
        

        return view('back/banque/update' , $viewsData);        
    }

    public function delete(Banque $banque){
        $banque->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}