<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Auth;
use App\Mail\EmailConfirmation;
use App\Models\Bon;
use Illuminate\Support\Facades\Mail;
use App\Models\Expedition;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    //

    public function list()
    {

        $user = auth()->user();


        if ($user->role == 1) {


            $expeditions = Expedition::where('deleted', "0")->get()->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            });







            $data = "";

            foreach ($expeditions as $date => $expedition_group) {
                $nbr_exp = 0;
                $nbr_colis = 0;
                $CA = 0;
                foreach ($expedition_group as $expedition) {

                    $nbr_colis =  $nbr_colis + $expedition->colis;
                    $CA =  $CA + $expedition->fond;
                    $nbr_exp++;
                }
                $data = $data . "{title: 'CA : " . number_format($CA, 2) . " Dhs', start: '" . $date . "'},
               {title: 'Colis : " . $nbr_colis . "',start: '" . $date . "',},
                {title: 'Expéditions : " . $nbr_exp . "',start: '" . $date . "'},";
            }

            $viewsData['data'] = "[" . $data . "]";


            // [
            //     {title: 'CA : 1151.0Dhs', start: '2022-03-07'},
            //     {title: 'Colis : 28',start: '2022-03-07',},
            //     {title: 'Expéditions : 28',start: '2022-03-07'}
            // ]
            return view('back/Calendar/list', $viewsData);
        } elseif ($user->role == 3) {


            $bons_client = Bon::where('deleted', "0")
                ->get()
                ->where('id_client', Auth()->user()->ClientDetail->id)
                ->where('type', 'RAMASSAGE')
                ->where('date_validation', '!=', null)
                ->groupBy(function ($item) {
                    return $item->created_at->format('Y-m-d');
                });

            $data = "";


            foreach ($bons_client as $date => $bons_group) {
                $nbr_exp = 0;
                $nbr_colis = 0;
                $CA = 0;
                foreach ($bons_group as $bon) {
                    foreach ($bon->expeditionDetail as $expedition) {
                        $fond = $expedition->fond;
                        if($expedition->fond == null){
                            $fond = 0;
                        }
                        $nbr_colis =  $nbr_colis + $expedition->colis;
                        $CA =  $CA + $fond;
                        $nbr_exp++;
                    }
                }
                $data = $data . "{title: 'CA : " . number_format($CA, 2) . " Dhs', start: '" . $date . "'},
                   {title: 'Colis : " . $nbr_colis . "',start: '" . $date . "',},
                    {title: 'Expéditions : " . $nbr_exp . "',start: '" . $date . "'},";
            }

            $viewsData['data'] = "[" . $data . "]";

            return view('client/Calendar/list', $viewsData);
        }
    }
}
