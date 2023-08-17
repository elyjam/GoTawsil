<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Typereclamation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class TypereclamationController extends Controller
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
        return view('back/typereclamation/list' ,
                                        [
                                            'records' => Typereclamation::all()->where('deleted',"0")
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
                Typereclamation::create($request->all());
                Redirect::to(route('typereclamation_list'))->send();
            }
        }
        $viewsData = [];

		$viewsData['roleRecords'] = \App\Models\Role::all()->where('deleted','0');

        return view('back/typereclamation/create', $viewsData);
    }

    public function update(Typereclamation $typereclamation, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $typereclamation->update($request->all());
                Redirect::to(route('typereclamation_list'))->send();
            }
        }
        $viewsData['record'] = $typereclamation;

		$viewsData['roleRecords'] = \App\Models\Role::all()->where('deleted','0');

        return view('back/typereclamation/update' , $viewsData);
    }

    public function delete(Typereclamation $typereclamation){
        $typereclamation->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

}
