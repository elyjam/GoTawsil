<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Ville;
use App\Models\Taxation;
use App\Models\ChartsData;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\Models\Expedition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    /**
     * Show the application home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $results = user::whereIn('login', function ( $query ) {
        //     $query->select('login')->from('users')->groupBy('login')->havingRaw('count(*) > 1');
        // })->get();
        // foreach($results as $user){
        //     $user->update(['login' => strtolower($user->login)]);
        // }
        // $usersUnique = $results->unique('login');
        // $userDuplicates = $results->where('login','!=','admin')->diff($usersUnique);
        //       foreach($userDuplicates as $user) {
        //     $user->update(['login' => '___'.$user->login]);
        // }
        // $users = user::all();
        // foreach($users as $user){
        //     $user->update(['password' => Hash::make($user->password)]);
        // }
        // dd('fine');
        return view('front/home');
    }

    public function chart_data(){
        //charts
        $data = array();
        $caissess = \DB::table('expeditions')
            ->select(
                \DB::raw('YEAR(expeditions.created_at) as year'),
                \DB::raw('MONTH(expeditions.created_at) as month'),
                \DB::raw('SUM(expeditions.ttc) as sum')
            )
            ->where('expeditions.created_at', '>=', Carbon::now()->subMonth(11)->format('Y-m-01 00:00:00'))
            ->leftJoin('processus_expeditions', 'processus_expeditions.id_expedition', '=', 'expeditions.id')
            ->where('processus_expeditions.code', '=', 'LIVRAISON')

            ->whereNotNull('processus_expeditions.date_validation')
            ->groupBy(
                \DB::raw('YEAR(expeditions.created_at)'),
                \DB::raw('MONTH(expeditions.created_at)')
            )
            ->get();




        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::today()->subMonth($i);
            $year = Carbon::today()->subMonth($i)->format('Y');

            $start_date = Carbon::now()->subMonth($i)->format('Y-m-01 00:00:00');
            $end_date = Carbon::now()->subMonth($i)->format('Y-m-31 23:59:59');
            $nbr_exp_retour = \DB::table("expeditions")
                ->where('deleted', "0")
                ->where('sens', 'Retour')
                ->where('date_retour', '>=', $start_date)
                ->where('date_retour', '<=', $end_date)
                ->count();

            $chiffre_realise = \DB::table("expeditions")

                ->whereNotIn('etape', [1, 5])
                ->whereDate("expeditions.created_at", '>=', $start_date)
                ->whereDate("expeditions.created_at", '<=', $end_date)
                ->sum('ttc');

            // $chiffre_encaisse = \DB::table("expeditions")
            //     ->select('expeditions.ttc', 'expeditions.created_at')
            //     ->leftJoin('processus_expeditions as processus', 'processus.id_expedition', '=', 'expeditions.id')
            //     ->where('processus.code', 'LIVRAISON')
            //     ->where('processus.date_validation', '!=', null)
            //     ->whereDate("expeditions.created_at", '>=', $start_date)
            //     ->whereDate("expeditions.created_at", '<=', $end_date)->get()
            //     ->sum('expeditions.ttc');




            $nbr_exp_ECOM = \DB::table("expeditions")
                ->where('deleted', "0")
                ->where('sens', 'Envoi')
                ->where('type', 'ECOM')
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->count();






            $nbr_exp_CDP = \DB::table("expeditions")
                ->where('deleted', "0")
                ->where('sens', 'Envoi')
                ->where('type', 'CDP')
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->count();





            $qsdqsdqs = $caissess->where('month', $month->format('m'))->where('year', $year)->first();


            if ($qsdqsdqs != []) {
                $chiffre_encaisse =  $qsdqsdqs->sum;
            } else {
                $chiffre_encaisse = 0;
            }


            array_push($data, array(
                'month' => $month->shortMonthName,
                'year' => $year,
                'exp_retour' => $nbr_exp_retour,
                'exp_ECOM' => $nbr_exp_ECOM,
                'exp_CDP' => $nbr_exp_CDP,
                'chiffre_realise' => $chiffre_realise,
                'chiffre_encaisse' => $chiffre_encaisse,

            ));
        }


        $viewsData['data_date'] = '';
        $viewsData['data_retour'] = '';
        $viewsData['data_ECOM'] = '';
        $viewsData['data_CDP'] = '';
        $viewsData['chiffre_realise'] = '';
        $viewsData['chiffre_encaisse'] = '';

        foreach ($data as $date) {
            $viewsData['data_retour'] =  $viewsData['data_retour'] . "'" . $date['exp_retour'] . "',";
            $viewsData['chiffre_realise'] =  $viewsData['chiffre_realise'] . "'" . $date['chiffre_realise'] . "',";

            $viewsData['chiffre_encaisse'] =  $viewsData['chiffre_encaisse'] . "'" . $date['chiffre_encaisse'] . "',";


            $viewsData['data_ECOM'] =  $viewsData['data_ECOM'] . "'" . $date['exp_ECOM'] . "',";

            $viewsData['data_CDP'] =  $viewsData['data_CDP'] . "'" . $date['exp_CDP'] . "',";


            $viewsData['data_date'] = $viewsData['data_date'] . "'" . $date['month'] . " " . $date['year'] . "',";
        }

        ChartsData::create( [
            'Data_date' => '[' . $viewsData['data_date']  . ']' ,
            'Data_retour' => '[' . $viewsData['data_retour']  . ']' ,
            'Data_ecom' => '[' . $viewsData['data_ECOM']  . ']' ,
            'Data_cdp' => '[' . $viewsData['data_CDP']  . ']' ,
            'Chiffre_realise' => '[' . $viewsData['chiffre_realise']  . ']' ,
            'Chiffre_encaisse' => '[' . $viewsData['chiffre_encaisse']  . ']' ,
        ]);

        //end of charts
    }

    public function getFile($token, $type, $id, Request $request){

        $user = User::where('token', $token)->first();
        if($user){
            Auth::guard()->login($user);
            \App\User::storeUserData();
            switch ($type) {
                case 'f':
                    return redirect("/facture/print-detail/".$id);
                    break;
                case 'ov':
                    return redirect("/remboursement/ordre-virement/".$id);
                    break;
                default:
                    # code...
                    break;
            }
        }
        else{
            abort(403);
        }
    }

    public function tarifs()
    {
        $viewsData['villeRecords'] = \App\Models\Ville::where('id', "!=",2)->where('libelle', "!=",'')->orderBy('libelle', 'asc')->get();
        $viewsData['villeDepart'] = Ville::getVilles('DEPART');
        return view('front/tarifs', $viewsData);
    }

    public function tarif_list(Request $request)
    {

        $villeExp = $request->get('villeExp');
        $villeDest = $request->get('villeDest');
        $villeDesination = Ville::where('id',$villeDest)->first();

        // if ($villeDest == '0') {
        //     $tarifsRecords = \App\Models\Taxation::all()
        //         ->where('id_ville_exp', $villeExp)
        //         ->where('id_clients', null)
        //         ->where('mnt_min', '!=', null);
        // } else {
        //     $tarifsRecords = \App\Models\Taxation::all()
        //         ->where('id_ville_exp', $villeExp)
        //         ->where('id_ville_dest', $villeDest)
        //         ->where('id_clients', null)
        //         ->where('mnt_min', '!=', null);
        // }

        if($villeDest == '0'){

         $tarifsRecords = Taxation::where('id_clients', 0)->where('id_ville_dest', $villeExp)
         ->orwhere('id_clients',null)->where('id_ville_dest', $villeExp)
         ->get();

        }else{
            if (Taxation::where('id_clients', 0)->where('id_ville_exp', $villeDest)->where('id_ville_dest', $villeExp)->first() != null) {
                $tarifsRecords = Taxation::where('id_clients', 0)->where('id_ville_exp', $villeDest)->where('id_ville_dest', $villeExp)->first();

                // check if cities has all
            } elseif (Taxation::where('id_clients', 0)->where('id_ville_exp',2)->where('id_ville_dest', $villeExp)->first() != null) {
                $tarifsRecords = Taxation::where('id_clients', 0)->where('id_ville_exp',2)->where('id_ville_dest', $villeExp)->first();

            }
        }




        $list = ' <table class="table-fill mt-5">
        <thead>
            <tr>
                <th class="text-left">Depart</th>
                <th class="text-left">destination</th>
                <th class="text-left">Tarifs en Dhs</th>
            </tr>
        </thead>


        <tbody class="table-hover">';


        if($villeDest == '0'){
            foreach($tarifsRecords as $tarif){
                if($tarif->id_ville_exp != 2){
                    $list .= '<tr>
                    <td class="text-left">' . $tarif->villeDetailDest->libelle . '</td>
                    <td class="text-left">' . $tarif->villeDetailExp->libelle . '</td>
                    <td class="text-left">' . $tarif->coefficient . ' Dhs</td>
                </tr>';
                }



            }

        }else{
            $list .= '<tr>

            <td class="text-left">' . $tarifsRecords->villeDetailDest->libelle . '</td>
            <td class="text-left">' . $villeDesination->libelle . '</td>
            <td class="text-left">' . $tarifsRecords->coefficient . ' Dhs</td>
        </tr>';



        }


        $list .= '</tbody>
        </table>';

        return $list;
    }

    public function register()
    {
        return view('front/search');
    }

    public function search_exp(Request $request)
    {
        $exp = Expedition::all()->where('num_expedition', $request->search_exp)->first();
        $viewsData['expedition'] = $exp;

        return view('front/search', $viewsData);
    }

    public function conditionsUtilisation()
    {
        return view('front/conditions-utilisation');
    }

    public function login()
    {
        return view('auth/login');
    }


    public function successfull()
    {
        return view('front.successfull');
    }
}
