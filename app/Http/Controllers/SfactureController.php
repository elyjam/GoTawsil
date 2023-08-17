<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sfacture;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class SfactureController extends Controller
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
        return view('back/sfacture/list' , 
                                        [
                                            'records' => Sfacture::all()->where('deleted',"0")
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
                Sfacture::create($request->all());
                Redirect::to(route('sfacture_list'))->send();
            }            
        }
        $viewsData = [];
        
		$viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted','0');

        return view('back/sfacture/create', $viewsData);        
    }

    public function update(Sfacture $sfacture, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $sfacture->update($request->all());
                Redirect::to(route('sfacture_list'))->send();
            }            
        }
        $viewsData['record'] = $sfacture;
        
		$viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted','0');

        return view('back/sfacture/update' , $viewsData);        
    }

    public function delete(Sfacture $sfacture){
        $sfacture->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}