<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tttest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class TttestController extends Controller
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
        return view('back/tttest/list' , 
                                        [
                                            'records' => Tttest::all()->where('deleted',"0")
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
                Tttest::create($request->all());
                Redirect::to(route('tttest_list'))->send();
            }            
        }
        $viewsData = [];
        

        return view('back/tttest/create', $viewsData);        
    }

    public function update(Tttest $tttest, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $tttest->update($request->all());
                Redirect::to(route('tttest_list'))->send();
            }            
        }
        $viewsData['record'] = $tttest;
        

        return view('back/tttest/update' , $viewsData);        
    }

    public function delete(Tttest $tttest){
        $tttest->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}