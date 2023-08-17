<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Groupstatuts;
use App\Models\Statut;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class GroupstatutsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function getRules()
    {
        return [];
    }

    public function list(Request $request)
    {

        $request->flash();
        return view(
            'back/groupstatuts/list',
            [
                'records' => Groupstatuts::all()->where('deleted', "0")
            ]
        );
    }

    public function create(Request $request)
    {

        if ($request->isMethod('post')) {
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                Groupstatuts::create($request->all());
                Redirect::to(route('groupstatuts_list'))->send();
            }
        }
        $viewsData = [];


        return view('back/groupstatuts/create', $viewsData);
    }

    public function update(Groupstatuts $groupstatuts, Request $request)
    {

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $groupstatuts->code = $request->all()["code"];
                $groupstatuts->libelle = $request->all()["libelle"];
                $groupstatuts->save();

                if (isset($request->all()['statuts'])) {

                    $groupstatuts->relatedStatuts()->sync(Statut::find($request->all()['statuts']));
                    Redirect::to(route('groupstatuts_list'))->send();
                } else {
                    $groupstatuts->relatedStatuts()->sync([]);
                }
            }
            $groupstatuts->update($request->all());

        }

        $viewsData['record'] = $groupstatuts;
        $viewsData['statuts'] =  \App\Models\Statut::all()->where('code', 'ETAPE_EXPEDITION')->where('deleted', '0');

        return view('back/groupstatuts/update', $viewsData);
    }

    public function delete(Groupstatuts $groupstatuts)
    {
        $groupstatuts->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }
}
