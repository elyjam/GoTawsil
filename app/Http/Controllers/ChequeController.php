<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cheque;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class ChequeController extends Controller
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
        return view('back/cheque/list' , 
                                        [
                                            'records' => Cheque::all()->where('deleted',"0")
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
                Cheque::create($request->all());
                Redirect::to(route('cheque_list'))->send();
            }            
        }
        $viewsData = [];
        

        return view('back/cheque/create', $viewsData);        
    }

    public function update(Cheque $cheque, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $cheque->update($request->all());
                Redirect::to(route('cheque_list'))->send();
            }            
        }
        $viewsData['record'] = $cheque;
        

        return view('back/cheque/update' , $viewsData);        
    }

    public function delete(Cheque $cheque){
        $cheque->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}