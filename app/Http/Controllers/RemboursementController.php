<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remboursement;
use App\Models\Client;
use App\Models\Expedition;
use App\Models\RemboursementPaiements;
use App\Models\CaissesCheques;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Rules\ClientsCountCheck;

class RemboursementController extends Controller
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

        if (auth()->user()->role == '1') {
            return view(
                'back/remboursement/list',
                [
                    'records' => Remboursement::all()->where('deleted', "0")
                ]
            );
        } elseif (auth()->user()->role == '3') {

            return view(
                'client/remboursement/list',
                [
                    'records' => RemboursementPaiements::all()->where('client', auth()->user()->ClientDetail->id)
                ]
            );
        }
    }

    public function paiements($remboursement, Request $request)
    {
        $request->flash();
        if ($request->isMethod('post')) {
            if ($request->input('remis') != null) {
                $paiements = RemboursementPaiements::find($request->input('remis'));
                foreach ($paiements as $paiement) {
                    if (strlen($paiement->date_remise) == 0) {
                        $paiement->remise = 1;
                        $paiement->date_remise = date('Y-m-d H:i');
                        $paiement->save();
                    }
                }
            }
            Redirect::to(route('remboursement_paiements', $remboursement))->send();
        }
        return view(
            'back/remboursement/paiements',
            [
                'records' => RemboursementPaiements::all()->where('remboursement', $remboursement)
            ]
        );
    }

    public function create(Request $request)
    {

        // dd(Expedition::getExpeditionsByAllRemboursement());

        $request->flash();
        $clients = [];
        $date = $_GET['date'] ?? null;

        if ($date != null) {
            $clients = Client::clientsRemboursement($date);
        }




        if ($request->isMethod('post')) {

            $rules = ['clients' => ['required', new ClientsCountCheck()]];
            $validator =  Validator::make($request->all(), $rules, [
                'clients.required' => 'Le choix du client(s) est obligatoire !'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                Remboursement::generate($request->input('clients'), $request->input('type'), $date);
                Redirect::to(route('remboursement_list'))->send();
            }
        }

        return view('back/remboursement/create', ['clients' => $clients, 'date' => $date]);
    }

    public function delete(Remboursement $remboursement)
    {
        $remboursement->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

    public function ordreVirement(Remboursement $remboursement)
    {

        $expeditionsIds = $remboursement->expeditions()->allRelatedIds()->toArray();
        $expeditions = Expedition::getExpeditionsByRemboursement($remboursement->id);
        $cheques = CaissesCheques::getMntArray(null, $expeditionsIds);
        $clients = Expedition::getClientsByRemboursement($expeditions);
        $record = new Remboursement();
        $record->ordreVirement($clients, $remboursement, $expeditions, $cheques);
    }

    public function printDetail(Remboursement $remboursement, RemboursementPaiements $paiement)
    {

        $expeditionsIds = $remboursement->expeditions()->allRelatedIds()->toArray();
        $expeditions = Expedition::getExpeditionsByRemboursement($remboursement->id);
        $cheques = CaissesCheques::getMntArray(null, $expeditionsIds);
        $clients = Expedition::getClientsByRemboursement($expeditions);

        //dd($clients, $expeditions, $cheques, $remboursement);
        $record = new Remboursement();
        $record->printDetail($clients, $remboursement, $expeditions, $cheques, $paiement);
    }
}
