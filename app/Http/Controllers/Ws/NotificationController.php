<?php

namespace App\Http\Controllers\Ws;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Controllers\Controller;
use App\Models\Bon;
use App\Models\Reclamation;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newSubscribersCount(Request $request){

        echo json_encode(['newSub'=> count(Client::getNewClients())]);
    }

    public function newRamassageCount(Request $request){
        $bonRam = Bon::where('type','RAMASSAGE')
        ->where('date_validation',null)
        ->where('deleted','0')
        ->get();

        echo json_encode(['newRam'=> count($bonRam)]);
    }

    public function reclamationCount(Request $request){
        $reclamation = Reclamation::where('statut','1')
        ->where('deleted','0')
        ->get();

        echo json_encode(['newRec'=> count($reclamation)]);
    }
    public function reclamationClientCount(Request $request){

        $reclamation = Reclamation::where('statut','1')
        ->where('deleted','0')
        ->where('user',auth()->user()->id)
        ->where('read',1)
        ->get();

        // $reclamation = ReclamationSuivi::where('read',1)
        // ->where('user',auth()->user()->id)

        // ->get();
        echo json_encode(['newRec'=> count($reclamation)]);
    }
}
