<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Promotion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class PromotionController extends Controller
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
            'back/promotion/list',
            [
                'records' => Promotion::all()->where('deleted', "0")
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
                $fileName = '';
                if (isset($request->file)) {
                    $fileName = time() . '.' . $request->file->extension();
                    $request->file->move(public_path('uploads/promotions'), $fileName);
                }



                Promotion::create($request->except(['file']) + [ 'imgUrl' => $fileName ]);
                Redirect::to(route('promotion_list'))->send();
            }
        }
        return view('back/promotion/create', [
            'clientRecords' => Client::all()->where('deleted', "0")
        ]);
    }

    public function update(Promotion $promotion, Request $request)
    {

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $promotion->update($request->all());
                Redirect::to(route('promotion_list'))->send();
            }
        }
        return view(
            'back/promotion/update',
            [
                'record' => $promotion,
                'clientRecords' => Client::all()->where('deleted', "0")
            ]
        );
    }

    public function delete(Promotion $promotion)
    {
        $promotion->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

    public function promotion_seen($id)
    {

        $promotion = Promotion::find($id);

        $promotion->update(['seen' => $promotion->seen . '|' . \Auth::user()->ClientDetail->id . '|']);
        return redirect()->back();
    }
}
