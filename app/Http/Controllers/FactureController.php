<?php

namespace App\Http\Controllers;

use App\Models\Expedition;
use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;


class FactureController extends Controller
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

    public function list($type, Request $request){
        $request->flash();

        if ($request->isMethod('post')) {
            if($request->input('remis') != null){
                $factures = Facture::find($request->input('remis'));
                foreach($factures as $facture){
                    if( strlen($facture->date_remise) == 0 ){
                        $facture->remise = 1;
                        $facture->date_remise = date('Y-m-d H:i');
                        $facture->save();
                    }
                }
                if($type == 1){
                    Redirect::to(route('facture_encompte', [$type]))->send();
                }
                else{
                    Redirect::to(route('facture_remboursement', [$type]))->send();
                }
            }
        }
        $end_date = Carbon::parse(Carbon::now())->format("Y-m-d");
        $subweek = Carbon::parse(Carbon::now()->subWeek())->format("Y-m-d");

        $viewsData['type'] = $type;
        $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
        $viewsData['statutRecords'] = \App\Models\Statut::all()->where('deleted', '0')->where('code', 'FACTURE');
        $viewsData['star_date'] = $end_date;
        $viewsData['end_date'] = $subweek;
        $viewsData['records'] = Facture::getFactures($type);

        return view('back/facture/list' ,$viewsData);
    }


    public function list_client(){

        $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
        $viewsData['statutRecords'] = \App\Models\Statut::all()->where('deleted', '0')->where('code', 'FACTURE');
        $viewsData['records'] = Facture::where('client',Auth()->user()->ClientDetail->id)->where('remise', 1)->get();
        return view('client/facture/list' ,$viewsData);
    }

    public function detail(Facture $facture){
        $viewsData['record'] = $facture;

        return view('back/facture/detail', $viewsData);
    }

    public function genFact($type, Request $request){

        $request->flash();
        $clients = [];
        $date = $_GET['date'] ?? null;

        if( $date != null){
            $clients = Client::clientsFacture($date, $type);
        }
        if ($request->isMethod('post')) {
            $rules = ['clients' => ['required']];
            $validator =  Validator::make($request->all(),$rules, [
                'clients.required' => 'Le choix du client(s) est obligatoire !'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                Facture::generate($request->input('clients'), $type, $date);
                if($type == 1){
                    Redirect::to(route('facture_encompte', [$type]))->send();
                }
                else{
                    Redirect::to(route('facture_remboursement', [$type]))->send();
                }
            }
        }
        return view('back/facture/gen' , ['clients' => $clients, 'date' => $date]);
    }

    public function genRemFac(Request $request){
        $request->flash();
        if ($request->isMethod('post')) {
            dd('todo', $request->all()['clients'] ?? []);
        }
        return view('back/facture/gen-rem' ,['clients' => Client::all()->where('deleted',"0")]);
    }

    public function create(Request $request){

        if ($request->isMethod('post')) {
            $rules = [];
            $validator =  Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                Facture::create($request->all());
                Redirect::to(route('facture_list'))->send();
            }
        }
        $viewsData = [];
		$viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted','0');
        return view('back/facture/create', $viewsData);
    }

    public function update(Facture $facture, Request $request){

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            else{
                $facture->update($request->all());
                Redirect::to(route('facture_list'))->send();
            }
        }
        $viewsData['record'] = $facture;

		$viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted','0');

        return view('back/facture/update' , $viewsData);
    }

    public function delete(Facture $facture){
        $facture->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

    public function print(Facture $facture, $type){
      //  $client_id = Auth()->user()->ClientDetail->id;
        Facture::print($facture, $type);
    }

    public function printDetail(Facture $facture){

        $facture->printDetail($facture);
    }



}
