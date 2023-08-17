<?php

namespace App\Http\Controllers;

use App\Models\Bon;
use App\Models\Bonliv;
use App\Models\Caisse;
use App\Models\CaissesExpeditions;
use App\Models\ChartsData;
use App\Models\CommissionExpeditions;
use Illuminate\Http\Request;
use App\Models\Mission;
use App\User;
use App\Models\Expedition;
use App\Models\Processus_expedition;
use App\Models\Promotion;
use App\Models\Reclamation;
use App\Models\Ville;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\Models\Client;
use App\Models\Employe;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // $results = user::whereIn('login', function ( $query ) {
        //     $query->select('login')->from('users')->groupBy('login')->havingRaw('count(*) > 1');
        // })->get();

        // foreach($results as $user){
        //     $user->update(['login' => strtolower($user->login)]);
        // }
        // $usersUnique = $results->unique('login');
        // $userDuplicates = $results->where('login','!=','admin')->diff($usersUnique);
        // dd('done');
        // foreach($userDuplicates as $user) {
        //     $user->update(['login' => '___'.$user->login]);
        // }
        $user = Auth()->user();
        ini_set('memory_limit', -1);
        //Caisses non validées
        $star = Carbon::now()->format('Y-m-01 00:00:00');
        $end =  carbon::now()->format('Y-m-d H:i:s');
        $viewsData['date_start_taux_livraison'] = Carbon::now()->format('Y-m-01');
        $viewsData['date_end_taux_livraison'] = carbon::now()->format('Y-m-d');

        if ($request->isMethod('post')) {

            $rules = [
                'start_date' => 'required',
                'end_date' => 'required',
            ];

            $star = Carbon::parse($request->start_date_dashboard)->format('Y-m-d 00:00:00');
            $end =  Carbon::parse($request->end_date_dashboard)->format('Y-m-d 23:59:59');

            $viewsData['date_start_taux_livraison'] = $request->start_date_dashboard;
            $viewsData['date_end_taux_livraison'] = $request->end_date_dashboard;

            $validator = Validator::make($request->all(), $rules);
        }



        $viewsData['count_caisse_nonvalide'] = \DB::table("caisses")
            ->where('date_debut', '<=', Carbon::now()->subHour(12))
            ->where('statut', 2)
            ->count();


        //Expéditions livrées non remboursées (+48H)

        $viewsData['count_non_remboursées'] = \DB::table('processus_expeditions')
            ->select(\DB::raw('COUNT(*) as count'))
            ->leftjoin('expeditions', 'processus_expeditions.id_expedition', '=', 'expeditions.id')
            ->where('processus_expeditions.code', '=', 'LIVRAISON')
            ->whereIn('expeditions.etape', [7, 14])
            ->first()->count;

        // Suivi chargement par ville d'envoi (24H) count
        $viewsData['count_exp_24h'] = \DB::table('expeditions')
        ->where('deleted',0)
        ->where('created_at','>=', carbon::now()->subday(1)->format('Y-m-d H:m:i'))->count();

        //Remb. en attente

        $caisses = \DB::table("caisses")->where('statut', 3)
            ->where('date_fin', '>=', $star)
            ->where('date_fin', '<=', $end);


        $sum_Remb = 0;
        foreach ($caisses->get() as $caisse) {
            $cs = Caisse::find($caisse->id);
            foreach ($cs->getexpedition() as $exp) {
                $sum_Remb = $sum_Remb + $exp->montant;
            }
        }

        $viewsData['sum_Remb'] = $sum_Remb;
        //end of Remb. en attente

        //Total a facturer

        $viewsData['total_a_facture'] = \DB::table("expeditions")->where('etape', 14)
            ->where('created_at', '>=', $star)
            ->where('created_at', '<=', $end)->sum('ttc');

        //end Total a facturer
        //prix



        $prix_exp_ttc = \DB::table("expeditions")->where('sens', '!=', 'Retour')
            ->where('expeditions.created_at', '>=', $star)
            ->where('expeditions.created_at', '<=', $end);

        $count_exp_ttc = \DB::table("expeditions")->where('sens', '!=', 'Retour')
            ->where('created_at', '>=', $star)
            ->where('created_at', '<=', $end)
            ->count();

        if ($count_exp_ttc == 0) {
            $viewsData['prix'] = 0;
        } else {
            $viewsData['prix'] = $prix_exp_ttc->sum('ttc')  / $count_exp_ttc;
        }


        $viewsData['prix_villes'] = $prix_exp_ttc->select(
            '*',
            \DB::raw('agences_des.libelle as destination'),
            \DB::raw('agences_exp.libelle as agence_dep'),
        )
            ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions.agence_des')
            ->leftJoin('villes as agences_exp', 'agences_exp.id', '=', 'expeditions.agence')
            ->get()->groupby('agence');


        $viewsData['ville_prix'] = \DB::table("villes")->get()->whereIn('id', $prix_exp_ttc->pluck('agence')->unique('agence')->toArray());

        //Commission Moyenne

        $commission_grouped = \DB::table("expeditions_commission")->get()
        ->where('created_at', '>=', $star)
            ->where('created_at', '<=', $end)
            ->where('commission', '!=', 0)
            ->groupBy('commission');

        $all_commissions_count = \DB::table("expeditions_commission")
            ->where('created_at', '>=', $star)
            ->where('created_at', '<=', $end)
            ->where('commission', '!=', 0)
            ->count();

        $commission_grouped_ville_exp = \DB::table("expeditions_commission")
            ->where('expeditions_commission.created_at', '>=', $star)
            ->where('expeditions_commission.created_at', '<=', $end)
            ->where('commission', '!=', 0)
            ->select(
                '*',
                \DB::raw('agences_des.libelle as destination'),
                \DB::raw('agences_exp.libelle as agence_exp'),
            )
                ->leftJoin('villes as agences_des', 'agences_des.id', '=', 'expeditions_commission.id_ville_dest')
                ->leftJoin('villes as agences_exp', 'agences_exp.id', '=', 'expeditions_commission.id_ville_exp')
                ->get()->groupBy('id_ville_exp');



        $viewsData['commission_grouped_ville_exp'] = $commission_grouped_ville_exp;
        $data_commissions = [];
        foreach ($commission_grouped as $com => $com_group) {

            array_push($data_commissions, array(
                'commission' => $com,
                'taux' => number_format(($com_group->count() / $all_commissions_count) * 100, 2),
                'fond' => $com_group->count() * $com,
            ));
        }


        $viewsData['moyenne_commission'] = collect($data_commissions)->sortBy('count')->toArray();

        if ($all_commissions_count != 0) {
            $viewsData['moyenne_commissions'] = collect($data_commissions)->sum('fond') / $all_commissions_count;
        } else {
            $viewsData['moyenne_commissions'] = 0;
        }




        //Delai de livraison

        $exp_processus = \DB::table("processus_expeditions")
            ->select('code', 'date_validation')
            ->whereIn('code', ['RAMASSAGE', 'LIVRAISON'])
            ->where('date_validation', '!=', null)
            ->where('created_at', '>=', $star)
            ->where('created_at', '<=', $end)
            ->get();


        $delai_livraison = [];

        foreach ($exp_processus->groupBy('id_expedition') as $exp => $process_group) {
            $date_ramassage = Carbon::parse($process_group->where('code', 'RAMASSAGE')->pluck('date_validation')->first());
            $date_livraison = Carbon::parse($process_group->where('code', 'LIVRAISON')->pluck('date_validation')->first());
            array_push($delai_livraison, $date_livraison->diffInHours($date_ramassage));
        }

        $average = collect($delai_livraison)->avg();


        CarbonInterval::macro('forHumansWithoutWeeks', function ($syntax = null, $short = false, $parts = -1, $options = null) {
            $factors = CarbonInterval::getCascadeFactors();
            CarbonInterval::setCascadeFactors([
                'week' => [99999999999, 'days'],
            ]);
            $diff = $this->forHumans($syntax, $short, $parts, $options);
            CarbonInterval::setCascadeFactors($factors);

            return $diff;
        });
        $viewsData['delai_livraison'] = CarbonInterval::hours($average)->cascade()->forHumansWithoutWeeks();

        //Taux de livraison



        $count_ram = \DB::table("expeditions")
            ->select(
                \DB::raw('expeditions.id as id'),
            )
            ->leftJoin('bons', 'bons.id', '=', 'expeditions.id_bon')
            ->where('bons.type', '=', 'RAMASSAGE')
            ->where('bons.created_at', '>=', $star)
            ->where('bons.created_at', '<=', $end)
            ->where('bons.date_validation', '!=', null)->count();


        $count_livraison = \DB::table("expeditions")
            ->select(
                \DB::raw('expeditions.id as id'),
            )
            ->leftJoin('bonlivs', 'bonlivs.id', '=', 'expeditions.bl')
            ->where('bonlivs.statut', '=', 2)
            ->where('bonlivs.created_at', '>=', $star)
            ->where('bonlivs.created_at', '<=', $end)
            ->count();




        // $viewsData['list_livreur'] = Employe::get()->whereIn('id', $bonslivraison->pluck('livreur')->unique('livreur')->toArray());
        // $viewsData['villes_taux_livraison'] = Ville::get()->whereIn('id', $bonslivraison->pluck('id_agence')->unique('id_agence')->toArray());

        //Taux de Retour
        $Expedition_retours = \DB::table("expeditions")->where('deleted', "0")
            ->where('sens', 'Retour')
            ->where('date_retour', '>=', $star)
            ->where('date_retour', '<=', $end)->count();


        if ($count_ram != 0) {
            $viewsData['taux_livraison'] = (int)(($count_livraison / $count_ram) * 100);
            $viewsData['taux_retour'] = (int)(($Expedition_retours / $count_ram) * 100);
        } else {
            $viewsData['taux_livraison'] = 0;
            $viewsData['taux_retour'] = 0;
        }


        //Souffrance

        //Rammassage

        $count_souf_ram = \DB::table("expeditions")
            ->select(
                \DB::raw('expeditions.id as id'),
            )
            ->leftJoin('bons', 'bons.id', '=', 'expeditions.id_bon')
            ->where('bons.date_validation', null)
            ->where('bons.created_at', '<=', Carbon::now()->subday(1))
            ->count();
        $viewsData['count_ramassage'] =  $count_souf_ram;

        //Chargement

        $bons_chargement = \DB::table("processus_expeditions")->where('code', 'CHARGEMENT')->where('date_reception', null)->where('date_validation', null)->where('created_at', '<=', Carbon::now()->subday(1));
        $viewsData['count_chargement'] = $bons_chargement->count();

        //Arrivage

        $bons_arrivage = \DB::table("processus_expeditions")->where('code', 'CHARGEMENT')->where('date_reception', '!=', null)->where('date_validation', null)->where('created_at', '<=', Carbon::now()->subday(1));
        $viewsData['count_arrivage'] = $bons_arrivage->count();
        //livraison





        $count_souf_livraison = \DB::table("expeditions")
            ->select(
                \DB::raw('expeditions.id as id'),
            )
            ->leftJoin('bonlivs', 'bonlivs.id', '=', 'expeditions.bl')
            ->where('bonlivs.statut', '=', 2)
            ->where('bonlivs.created_at', '<=', Carbon::now()->subHour(5))
            ->count();

        $viewsData['count_livraison'] = $count_souf_livraison;

        //End Souffrance

        //charts

       $data_charts = \DB::table("charts_dashboard_data")->latest('created_at')->first();

        $viewsData['data_date'] = $data_charts->Data_date;
        $viewsData['data_retour'] = $data_charts->Data_retour;
        $viewsData['data_ECOM'] = $data_charts->Data_ecom;
        $viewsData['data_CDP'] = $data_charts->Data_cdp;
        $viewsData['chiffre_realise'] = $data_charts->Chiffre_realise;
        $viewsData['chiffre_encaisse'] = $data_charts->Chiffre_encaisse;

        //end of charts

        $exp_prs =  \DB::table("processus_expeditions")->where('code', "RAMASSAGE")->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->get()->groupBy('id_agence_exp');

        // $exp =  Expedition::where('deleted', "0")->get()->groupBy('agence');

        $viewsData['arr'] = [];
        $i = 0;
        foreach ($exp_prs as $ville => $expedition_group) {
            $ville_detail = \DB::table("villes")->where('deleted', "0")->where('id', $ville)->first();
            $viewsData['arr'][$i] = [
                "ville" => $ville_detail->libelle,
                "nbr_exp" => $expedition_group->count(),
            ];
            $i++;
        }




        return view('back/home', $viewsData);
    }



    public function taux_livraison_filre()
    {


        $star = Carbon::now()->format('Y-m-01 00:00:00');
        $end =  carbon::now()->format('Y-m-d H:i:s');

        $bonsramassage = Bon::all()->where('type', 'RAMASSAGE')
            ->where('date_validation', '!=', null)
            ->where('created_at', '>=', $star)
            ->where('created_at', '<=', $end);
        $count_ram = 0;

        foreach ($bonsramassage as $bon) {
            $count_ram = $count_ram + $bon->expeditionDetail->count();
        }

        $bonslivraison = Bonliv::all()->where('statut', 2)
            ->where('created_at', '>=', $star)
            ->where('created_at', '<=', $end);
        $count_livraison = 0;

        foreach ($bonslivraison as $bon) {
            $count_livraison = $count_livraison + $bon->ExpeditionDetail->count();
        }

        if ($count_ram != 0) {
            $viewsData['taux_livraison'] = (int)(($count_livraison / $count_ram) * 100);
        } else {
            $viewsData['taux_livraison'] = 0;
        }


        return '<table>hello</h2>';
    }


    public function dashboard_client()
    {

        //promotions
        $promotions = Promotion::where('deleted', "0")
            ->where('date_debut', '<=', Carbon::now()->format('Y-m-d'))
            ->where('date_fin', '>=', Carbon::now()->format('Y-m-d'))
            ->whereIn('client', [Auth()->user()->ClientDetail->id, '0'])
            ->where('seen', 'not like', '%|' . Auth()->user()->ClientDetail->id . '|%')
            ->get();



        $viewsData['promotions'] = $promotions;

        //Retours de ce mois
        $Expedition_retours = Expedition::all()->where('deleted', "0")
            ->where('sens', 'Retour')
            ->where('client', Auth()->user()->ClientDetail->id)
            ->where('date_retour', '>=', Carbon::now()->subMonth(1)->format('Y-m-01'))
            ->where('date_retour', '<=', Carbon::now()->format('Y-m-31'))->count();

        $viewsData['Expedition_retours'] = $Expedition_retours;
        //Réclamations en cours
        $Reclamation_encours = Reclamation::all()->where('deleted', "0")
            ->where('statut', 1)
            ->where('user', Auth()->user()->ClientDetail->id)
            ->count();

        $viewsData['Reclamation_encours'] = $Reclamation_encours;

        // Envois de ce mois

        $bons = Bon::where('deleted', "0")
            ->where('type', 'RAMASSAGE')
            ->where('id_client', Auth()->user()->ClientDetail->id)
            ->where('date_validation', '>=', Carbon::now()->format('Y-m-01'))
            ->where('date_validation', '<=', Carbon::now()->format('Y-m-31'))->Get();
        $epx_cemois = 0;
        if (isset($bons)) {
            foreach ($bons as $bon) {
                $epx_cemois = $epx_cemois + $bon->expeditionDetail->count();
            }
        }
        $viewsData['epx_cemois'] = $epx_cemois;

        // Colis en cours

        $expedition_encours = Expedition::where('deleted', "0")
            ->where('client', Auth()->user()->ClientDetail->id)
            ->where('etape', '!=', 14)
            ->where('etape', '!=', 5)
            ->where('etape', '!=', 8)
            ->count();

        $viewsData['expedition_encours'] = $expedition_encours;

        // Taux de retour du mois
        if (($epx_cemois + $Expedition_retours) == 0) {
            $taux = 0;
        } else {
            $taux = ($Expedition_retours / ($epx_cemois + $Expedition_retours)) * 100;
        }



        $viewsData['taux_retour'] = (int)$taux;
        //chart
        $data = array();
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::today()->subMonth($i);
            $year = Carbon::today()->subMonth($i)->format('Y');
            $bons = Bon::where('deleted', "0")
                ->where('type', 'RAMASSAGE')
                ->where('id_client', Auth()->user()->ClientDetail->id)
                ->where('date_validation', '>=', Carbon::now()->subMonth($i)->format('Y-m-01'))
                ->where('date_validation', '<=', Carbon::now()->subMonth($i)->format('Y-m-31'))->Get();
            $count_exp = 0;
            if (isset($bons)) {
                foreach ($bons as $bon) {

                    $count_exp = $count_exp + $bon->expeditionDetail->count();
                }
            }
            array_push($data, array(
                'month' => $month->shortMonthName,
                'year' => $year,
                'count_exp' => $count_exp,
            ));
        }



        $viewsData['data_date'] = '';
        $viewsData['count_exp'] = '';

        foreach ($data as $date) {
            $viewsData['count_exp'] =  $viewsData['count_exp'] . "'" . $date['count_exp'] . "',";

            $viewsData['data_date'] = $viewsData['data_date'] . "'" . $date['month'] . " " . $date['year'] . "',";
        }


        $viewsData['data_date'] = '[' . $viewsData['data_date']  . ']';
        $viewsData['count_exp'] = '[' . $viewsData['count_exp']  . ']';
        //endchart

        // Exp Non livrée
        $exp_non_livree = Expedition::all()
            ->where('deleted', "0")
            ->where('client', Auth()->user()->ClientDetail->id)
            ->where('etape', '20');

        $viewsData['exp_non_livree'] = $exp_non_livree;
        return view('client/home', $viewsData);
    }

    public function pilotage(Request $request)
    {

        ini_set('memory_limit', -1);
        $user = Auth()->user();




        $star = Carbon::now()->format('Y-m-01 00:00:00');
        $end =  carbon::now()->format('Y-m-d 23:59:59');
        $viewsData['date_start_taux_livraison'] = Carbon::now()->format('Y-m-01');
        $viewsData['date_end_taux_livraison'] = carbon::now()->format('Y-m-d');

        $viewsData['charger_de_comptes'] = User::get()->where('role', 8);





        if ($user->role == '8' || $user->role == '7') {

            $viewsData['expeditions'] = \Auth::user()::getExp_pilotage(\Auth::user());
            $viewsData['villes'] = Ville::get()->whereIn('id',\Auth::user()->relatedVilles()->allRelatedIds()->toArray());

        //    dd(implode('|', $viewsData['expeditions']->pluck('num_expedition')->toArray()));

        }


        // if ($request->isMethod('post')) {
        //     $rules = [
        //         'start_date' => 'required',
        //         'end_date' => 'required',
        //     ];
        //     $star = Carbon::parse($request->start_date_dashboard)->format('Y-m-d 00:00:00');
        //     $end =  Carbon::parse($request->end_date_dashboard)->format('Y-m-d 23:59:59');
        //     $viewsData['date_start_taux_livraison'] = $request->start_date_dashboard;
        //     $viewsData['date_end_taux_livraison'] = $request->end_date_dashboard;

        //     $validator = Validator::make($request->all(), $rules);
        // }


        if ($user->role != 3) {
            return view('back/pilotage', $viewsData);
        }
    }

    public function pilotageLivreur(Request $request)
    {
        $user = \Auth::user();


        $star = Carbon::now()->format('Y-m-01 00:00:00');
        $end =  carbon::now()->format('Y-m-d 23:59:59');
        $viewsData['date_start_taux_livraison'] = Carbon::now()->format('Y-m-01');
        $viewsData['date_end_taux_livraison'] = carbon::now()->format('Y-m-d');

        if ($request->isMethod('post')) {
            $rules = [
                'start_date' => 'required',
                'end_date' => 'required',
            ];
            $star = Carbon::parse($request->start_date_dashboard)->format('Y-m-d 00:00:00');
            $end =  Carbon::parse($request->end_date_dashboard)->format('Y-m-d 23:59:59');
            $viewsData['date_start_taux_livraison'] = $request->start_date_dashboard;
            $viewsData['date_end_taux_livraison'] = $request->end_date_dashboard;

            $validator = Validator::make($request->all(), $rules);
        }

        //En cours de livraison

        $bons = Bonliv::all()->where('statut', 2)->where('livreur', $user->EmployeDetail->id)->where('deleted', 0);
        $count = 0;


        foreach ($bons as $bon) {
            $count = $count + $bon->relatedColis->where('etape', '!=', 14)->count();
        }
        $viewsData['nbr_colis'] =  $count;

        //Taux de livraison

        $bons = Bonliv::all()
            ->where('created_at', '>=', $star)
            ->where('created_at', '<=', $end)
            ->where('livreur', $user->EmployeDetail->id)
            ->where('deleted', 0);
        $count_liv = 0;
        $count_all_colis = 0;

        foreach ($bons as $bon) {
            $count_liv = $count_liv + $bon->relatedColis->where('etape', 14)->count();
            $count_all_colis = $count_all_colis + $bon->relatedColis->count();
        }


        if ($count_all_colis == 0) {
            $viewsData['taux_livraison'] =  0;
        } else {
            $viewsData['taux_livraison'] =  (int)(($count_liv / $count_all_colis) * 100);
        }


        //Commissions globale

        $viewsData['commissions_globale'] = CommissionExpeditions::all()
            ->where('livreur', $user->EmployeDetail->id)
            ->where('created_at', '>=', $star)
            ->where('created_at', '<=', $end)
            ->sum('commission');

        return view('back/pilotageLivreur', $viewsData);
    }

    public function commercial()
    {
        $user = Auth()->user();
        $viewsData['client_commercial'] = Client::all()->where('commerciale', Auth()->user()->EmployeDetail->id);
        return view('back/commercial', $viewsData);
    }

    public function pdf_souf_chargement()
    {
        $user = Auth()->user();
        if ($user->role == '1') {
            $bons_chargement = Processus_expedition::all()->where('code', 'CHARGEMENT')->where('date_reception', null)->where('date_validation', null)->where('created_at', '<=', Carbon::now()->subday(1));
        } else {
            $bons_chargement = Processus_expedition::all()
                ->where('code', 'CHARGEMENT')
                ->whereIn('id_agence_exp', \Auth::user()::getUserVilles())
                ->where('date_reception', null)
                ->where('date_validation', null)
                ->where('created_at', '<=', Carbon::now()->subday(1));
        }



        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);




        $pdf::setHeaderCallback(function ($pdf) {
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>Souffrance chargement (24h)</b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                            ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                        </td>
                    </tr>
                </table>
                <hr>
            ';
            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function ($pdf) {
            $pdf->writeHTML('<hr> <table style="width:100% !important;  height:100% !important; " cellpadding="5"> <tr > <td style="height:25px !important; font-size: 10px !important;text-align:right !important;" width="100%"> ' . date('d/m/Y H:i') . ' </td></tr> </table>', true, false, false, false, '');
        });


        // set margins
        $pdf::SetMargins(7, PDF_MARGIN_TOP, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage('L', 'A4');

        // Set some content to print
        //Téléphone Adresse
        $html = '
        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                <tr>
                    <td width="9%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2">
                        <b>N° Expéd.</b>
                    </td>
                    <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Agence</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>
                    Date saisie</b></td>

                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Expéditeur</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Origine</b>
                    </td>

                    <td width="12%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destinataire</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destination</b>
                    </td>
                    <td width="10%" style="height:18px !important; text-align:center !important; font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>N° Tél.</b>
                    </td>

                    <td width="5%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Colis</b></td>
                    <td width="7%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Fond</b></td>

                </tr>';
        foreach ($bons_chargement as $pro_charg) {

            $html .= '<tr>
        <td width="9%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  >
        <b>' . $pro_charg->ExpeditionDetail->num_expedition . '</b>
     </td>
        <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" ><b>' . $pro_charg->ExpeditionDetail->agenceDetail->libelle . '</b>
        </td>
        <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  ><b>
        ' . $pro_charg->ExpeditionDetail->created_at . '</b></td>


        <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" ><b>' . $pro_charg->ExpeditionDetail->clientDetail->libelle . '</b>
        </td>
        <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  ><b>' . $pro_charg->ExpeditionDetail->origineDetail->libelle . '</b>
        </td>

        <td width="12%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $pro_charg->ExpeditionDetail->destinataire . '</b>
        </td>
        <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" ><b>' . $pro_charg->ExpeditionDetail->adresse_destinataire . '</b>
        </td>
        <td width="10%" style="height:18px !important; font-size: 7px !important; border:0,2px solid !important;"><b>' . $pro_charg->ExpeditionDetail->telephone . '</b>
        </td>

        <td width="5%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $pro_charg->ExpeditionDetail->colis . '</b></td>
        <td width="7%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $pro_charg->ExpeditionDetail->fond . '</b></td>

        </tr>';
        }



        $html .= '</table>';


        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Situation du stock.pdf', 'I');
    }


    public function pdf_caisses_nonvalide()
    {

        if (Auth()->user()->role == '1') {
            $caisses_non = $viewsData['count_caisse_nonvalide'] = Caisse::all()
                ->where('date_debut', '<=', Carbon::now()->subHour(12))
                ->where('statut', 2);
        } else {
            $caisses_non = $viewsData['count_caisse_nonvalide'] = Caisse::all()
                ->whereIn('id_agence', \Auth::user()::getUserVilles())
                ->where('date_debut', '<=', Carbon::now()->subHour(12))
                ->where('statut', 2);
        }






        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);




        $pdf::setHeaderCallback(function ($pdf) {
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>Caisses non validées (+24H)</b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                            ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                        </td>
                    </tr>
                </table>
                <hr>
            ';
            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function ($pdf) {
            $pdf->writeHTML('<hr> <table style="width:100% !important;  height:100% !important; " cellpadding="5"> <tr > <td style="height:25px !important; font-size: 10px !important;text-align:right !important;" width="100%"> ' . date('d/m/Y H:i') . ' </td></tr> </table>', true, false, false, false, '');
        });


        // set margins
        $pdf::SetMargins(7, PDF_MARGIN_TOP, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage('L', 'A4');

        // Set some content to print
        //Téléphone Adresse
        $html = '
        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                <tr>
                    <td width="9%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2">
                        <b>Numéro</b>
                    </td>
                    <td width="16%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Du</b>
                    </td>
                    <td width="16%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>
                    Au</b></td>

                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Générée par</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Férmée par</b>
                    </td>

                    <td width="12%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Validée le</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Validée par</b>
                    </td>
                    <td width="10%" style="height:18px !important; text-align:center !important; font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Statut</b>
                    </td>


                </tr>';
        foreach ($caisses_non  as $record) {

            $html .= '<tr>
        <td width="9%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  >
        <b>' .  $record->numero . '</b>
     </td>
        <td width="16%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" ><b>' . date('Y/m/d H:i:s', strtotime($record->date_debut)) . '</b>
        </td>
        <td width="16%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  ><b>
        ' . date('Y/m/d H:i:s', strtotime($record->date_fin))  . '</b></td>


        <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" ><b>' . $record->genBy->libelle . '</b>
        </td>
        <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  ><b>' . $record->confirme_par . '</b>
        </td>

        <td width="12%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . date('Y/m/d H:i:s', strtotime($record->date_validation)) . '</b>
        </td>
        <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" ><b>' . $record->valide_par . '</b>
        </td>
        <td width="10%" style="height:18px !important; font-size: 7px !important; border:0,2px solid !important;"><b>' . $record->getStatuts() . '</b>
        </td>
        </tr>';
        }



        $html .= '</table>';


        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Situation du stock.pdf', 'I');
    }

    public function pdf_exp_nonremb()
    {

        if (Auth()->user()->role == '1') {
            $bons = \DB::table("processus_expeditions")
                ->where('code', 'LIVRAISON')
                ->where('date_validation', '<=', Carbon::now()->subday(2))
                ->leftJoin('expeditions', 'expeditions.id', '=', 'processus_expeditions.id_expedition')
                ->whereIn('expeditions.etape', [7, 14])
                ->get();
        } else {
            $bons = \DB::table("processus_expeditions")
                ->where('code', 'LIVRAISON')
                ->whereIn('id_agence_exp', \Auth::user()::getUserVilles())
                ->where('date_validation', '<=', Carbon::now()->subday(2))
                ->leftJoin('expeditions', 'expeditions.id', '=', 'processus_expeditions.id_expedition')
                ->whereIn('expeditions.etape', [7, 14])
                ->get();
        }





        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);




        $pdf::setHeaderCallback(function ($pdf) {
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>Expéditions livrées non remboursées (+48H)</b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                            ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                        </td>
                    </tr>
                </table>
                <hr>
            ';
            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function ($pdf) {
            $pdf->writeHTML('<hr> <table style="width:100% !important;  height:100% !important; " cellpadding="5"> <tr > <td style="height:25px !important; font-size: 10px !important;text-align:right !important;" width="100%"> ' . date('d/m/Y H:i') . ' </td></tr> </table>', true, false, false, false, '');
        });


        // set margins
        $pdf::SetMargins(7, PDF_MARGIN_TOP, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage('L', 'A4');

        // Set some content to print
        //Téléphone Adresse
        $html = '
        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                <tr>
                    <td width="9%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2">
                        <b>N° Expéd.</b>
                    </td>
                    <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Caisse</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>
                    Date saisie</b></td>

                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Expéditeur</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Origine</b>
                    </td>

                    <td width="12%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destinataire</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destination</b>
                    </td>
                    <td width="8%" style="height:18px !important; text-align:center !important; font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>N° Tél.</b>
                    </td>

                    <td width="3%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Colis</b></td>
                    <td width="4%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Fond</b></td>
                    <td width="7%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Delai</b></td>

                </tr>';
        foreach ($bons as $bon) {


            $ExpeditionDetail = Expedition::find($bon->id_expedition);
            $expcaisse = CaissesExpeditions::where('id_expedition',$bon->id_expedition)->first();
            $datelivraison = $bon->date_validation;
            $datenow = Carbon::now();


           $delai = $datenow->diffInHours($datelivraison);

            $html .= '<tr>
        <td width="9%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  >
        <b>' . $ExpeditionDetail->num_expedition . '</b>
     </td>
        <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" ><b>' . $expcaisse->Caisse->numero . '</b>
        </td>
        <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  ><b>
        ' . $ExpeditionDetail->created_at . '</b></td>


        <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" ><b>' . $ExpeditionDetail->clientDetail->libelle . '</b>
        </td>
        <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  ><b>' . $ExpeditionDetail->origineDetail->libelle . '</b>
        </td>

        <td width="12%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $ExpeditionDetail->destinataire . '</b>
        </td>
        <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" ><b>' . $ExpeditionDetail->adresse_destinataire . '</b>
        </td>
        <td width="8%" style="height:18px !important; font-size: 7px !important; border:0,2px solid !important;"><b>' . $ExpeditionDetail->telephone . '</b>
        </td>

        <td width="3%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $ExpeditionDetail->colis . '</b></td>
        <td width="4%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $ExpeditionDetail->fond . '</b></td>
        <td width="7%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $delai . ' heures</b></td>

        </tr>';
        }



        $html .= '</table>';


        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Situation du stock.pdf', 'I');
    }

    public function suvi_parville(){
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


        $pdf::setHeaderCallback(function ($pdf) {
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>Suivi chargement par ville d\'envoi </b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                            ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                        </td>
                    </tr>
                </table>
                <hr>
            ';
            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function ($pdf) {
            $pdf->writeHTML('<hr> <table style="width:100% !important;  height:100% !important; " cellpadding="5"> <tr > <td style="height:25px !important; font-size: 10px !important;text-align:right !important;" width="100%"> ' . date('d/m/Y H:i') . ' </td></tr> </table>', true, false, false, false, '');
        });



        $expeditions = Expedition::get()
        ->where('deleted',0)
        ->where('created_at','>=', carbon::now()->subday(1)->format('Y-m-d H:m:i'))
        ->groupby('agence');


        // set margins
        $pdf::SetMargins(7, PDF_MARGIN_TOP, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);
        $html ='';
        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        foreach($expeditions as $ville => $exp){
        $html .= '<h2>'.$exp->first()->agenceDetail->libelle.'</h2>   <table style="width:100% !important;  height:100% !important; " cellpadding="2">
        <tr>
            <td width="50%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2">
                <b>Agence de destination</b>
            </td>
            <td width="50%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Nombre d\'expedition </b>
            </td>
        </tr>  ';
        foreach($exp->groupby('agence_des') as $ville_des => $expd){
            $html .='    <tr>
            <td width="50%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;">
                <b>'.$expd->first()->agenceDesDetail->libelle.'</b>
            </td>
            <td width="50%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" ><b>'.$expd->count().'</b>
            </td>
        </tr> ';
        }
        $html .='</table>';
    }
        // Add a page
        $pdf::AddPage('L', 'A4');
        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Situation du stock.pdf', 'I');

    }

    public function pdf_souf_arrivage()
    {
        $bons_arrivage = Processus_expedition::all()->where('code', 'CHARGEMENT')->where('date_reception', '!=', null)->where('date_validation', null)->where('created_at', '<=', Carbon::now()->subday(1));

        $user = Auth()->user();
        if ($user->role == '1') {
            $bons_arrivage = Processus_expedition::all()->where('code', 'CHARGEMENT')->where('date_reception', '!=', null)->where('date_validation', null)->where('created_at', '<=', Carbon::now()->subday(1));
        } else {

            $bons_arrivage = Processus_expedition::all()
                ->where('code', 'CHARGEMENT')
                ->whereIn('id_agence_exp', \Auth::user()::getUserVilles())
                ->where('date_reception', '!=', null)
                ->where('date_validation', null)
                ->where('created_at', '<=', Carbon::now()->subday(1));
        }
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


        $pdf::setHeaderCallback(function ($pdf) {
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>Souffrance arrivage (24h)</b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                            ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                        </td>
                    </tr>
                </table>
                <hr>
            ';
            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function ($pdf) {
            $pdf->writeHTML('<hr> <table style="width:100% !important;  height:100% !important; " cellpadding="5"> <tr > <td style="height:25px !important; font-size: 10px !important;text-align:right !important;" width="100%"> ' . date('d/m/Y H:i') . ' </td></tr> </table>', true, false, false, false, '');
        });


        // set margins
        $pdf::SetMargins(7, PDF_MARGIN_TOP, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage('L', 'A4');

        // Set some content to print
        //Téléphone Adresse
        $html = '
        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                <tr>
                    <td width="9%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2">
                        <b>N° Expéd.</b>
                    </td>
                    <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Agence</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>
                    Date saisie</b></td>

                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Expéditeur</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Origine</b>
                    </td>

                    <td width="12%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destinataire</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destination</b>
                    </td>
                    <td width="10%" style="height:18px !important; text-align:center !important; font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>N° Tél.</b>
                    </td>

                    <td width="5%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Colis</b></td>
                    <td width="7%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Fond</b></td>

                </tr>';
        foreach ($bons_arrivage as $pro_arrivage) {

            $html .= '<tr>
        <td width="9%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  >
        <b>' . $pro_arrivage->ExpeditionDetail->num_expedition . '</b>
     </td>
        <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" ><b>' . $pro_arrivage->ExpeditionDetail->agenceDetail->libelle . '</b>
        </td>
        <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  ><b>
        ' . $pro_arrivage->ExpeditionDetail->created_at . '</b></td>


        <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" ><b>' . $pro_arrivage->ExpeditionDetail->clientDetail->libelle . '</b>
        </td>
        <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  ><b>' . $pro_arrivage->ExpeditionDetail->origineDetail->libelle . '</b>
        </td>

        <td width="12%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $pro_arrivage->ExpeditionDetail->destinataire . '</b>
        </td>
        <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" ><b>' . $pro_arrivage->ExpeditionDetail->adresse_destinataire . '</b>
        </td>
        <td width="10%" style="height:18px !important; font-size: 7px !important; border:0,2px solid !important;"><b>' . $pro_arrivage->ExpeditionDetail->telephone . '</b>
        </td>

        <td width="5%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $pro_arrivage->ExpeditionDetail->colis . '</b></td>
        <td width="7%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $pro_arrivage->ExpeditionDetail->fond . '</b></td>

        </tr>';
        }



        $html .= '</table>';


        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Situation du stock.pdf', 'I');
    }

    public function pdf_souf_livraison()
    {

        $user = Auth()->user();
        if ($user->role == '1') {
            $bons_livraison = Bonliv::all()->where('statut', 2)->where('created_at', '<=', Carbon::now()->subday(1));
        } else {

            $bons_livraison = Bonliv::all()
                ->whereIn('id_agence', \Auth::user()::getUserVilles())
                ->where('statut', 2)
                ->where('created_at', '<=', Carbon::now()->subday(1));
        }
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


        $pdf::setHeaderCallback(function ($pdf) {
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>Souffrance en cours de livraison (24h) </b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                            ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                        </td>
                    </tr>
                </table>
                <hr>
            ';
            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function ($pdf) {
            $pdf->writeHTML('<hr> <table style="width:100% !important;  height:100% !important; " cellpadding="5"> <tr > <td style="height:25px !important; font-size: 10px !important;text-align:right !important;" width="100%"> ' . date('d/m/Y H:i') . ' </td></tr> </table>', true, false, false, false, '');
        });


        // set margins
        $pdf::SetMargins(7, PDF_MARGIN_TOP, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage('L', 'A4');

        // Set some content to print
        //Téléphone Adresse
        $html = '
        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                <tr>
                    <td width="9%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2">
                        <b>N° Expéd.</b>
                    </td>
                    <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Agence</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>
                    Date saisie</b></td>

                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Expéditeur</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Origine</b>
                    </td>

                    <td width="12%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destinataire</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destination</b>
                    </td>
                    <td width="10%" style="height:18px !important; text-align:center !important; font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>N° Tél.</b>
                    </td>

                    <td width="5%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Colis</b></td>
                    <td width="7%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Fond</b></td>

                </tr>';


        foreach ($bons_livraison as $bon) {
            foreach ($bon->ExpeditionDetail as $exp) {




                $html .= '<tr>
            <td width="9%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  >
            <b>' . $exp->num_expedition . '</b>
             </td>
            <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" ><b>' . $exp->agenceDetail->libelle . '</b>
            </td>
                <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  ><b>
            ' . $exp->created_at . '</b></td>
            <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" ><b>' . $exp->clientDetail->libelle . '</b>
            </td>
            <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  ><b>' . $exp->origineDetail->libelle . '</b>
            </td>

            <td width="12%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $exp->destinataire . '</b>
            </td>
            <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" ><b>' . $exp->adresse_destinataire . '</b>
            </td>
            <td width="10%" style="height:18px !important; font-size: 7px !important; border:0,2px solid !important;"><b>' . $exp->telephone . '</b>
            </td>
            <td width="5%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $exp->colis . '</b></td>
            <td width="7%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $exp->fond . '</b></td>
            </tr>';
            }
        }



        $html .= '</table>';


        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Situation du stock.pdf', 'I');
    }

    public function pdf_souf_ramassage()
    {
        $user = Auth()->user();
        if ($user->role == '1') {
            $bons_ramassage = Bon::all()->where('type', 'RAMASSAGE')->where('date_validation', null)->where('created_at', '<=', Carbon::now()->subday(1));
        } else {
            $bons_ramassage = Bon::all()
                ->where('type', 'RAMASSAGE')
                ->whereIn('id_agence_exp', \Auth::user()::getUserVilles())
                ->where('date_validation', null)
                ->where('created_at', '<=', Carbon::now()->subday(1));
        }
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


        $pdf::setHeaderCallback(function ($pdf) {
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>Souffrances ramassage (+24H)</b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                            ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                        </td>
                    </tr>
                </table>
                <hr>
            ';
            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function ($pdf) {
            $pdf->writeHTML('<hr> <table style="width:100% !important;  height:100% !important; " cellpadding="5"> <tr > <td style="height:25px !important; font-size: 10px !important;text-align:right !important;" width="100%"> ' . date('d/m/Y H:i') . ' </td></tr> </table>', true, false, false, false, '');
        });


        // set margins
        $pdf::SetMargins(7, PDF_MARGIN_TOP, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage('L', 'A4');

        // Set some content to print
        //Téléphone Adresse
        $html = '
        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                <tr>
                    <td width="9%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2">
                        <b>N° Expéd.</b>
                    </td>
                    <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Agence</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>
                    Date saisie</b></td>

                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Expéditeur</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Origine</b>
                    </td>

                    <td width="12%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destinataire</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destination</b>
                    </td>
                    <td width="10%" style="height:18px !important; text-align:center !important; font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>N° Tél.</b>
                    </td>

                    <td width="5%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Colis</b></td>
                    <td width="7%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Fond</b></td>

                </tr>';


        foreach ($bons_ramassage as $bon) {
            foreach ($bon->ExpeditionDetail as $exp) {




                $html .= '<tr>
            <td width="9%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  >
            <b>' . $exp->num_expedition . '</b>
             </td>
            <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" ><b>' . $exp->agenceDetail->libelle . '</b>
            </td>
                <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  ><b>
            ' . $exp->created_at . '</b></td>
            <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" ><b>' . $exp->clientDetail->libelle . '</b>
            </td>
            <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  ><b>' . $exp->origineDetail->libelle . '</b>
            </td>

            <td width="12%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $exp->destinataire . '</b>
            </td>
            <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" ><b>' . $exp->adresse_destinataire . '</b>
            </td>
            <td width="10%" style="height:18px !important; font-size: 7px !important; border:0,2px solid !important;"><b>' . $exp->telephone . '</b>
            </td>
            <td width="5%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $exp->colis . '</b></td>
            <td width="7%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  ><b>' . $exp->fond . '</b></td>
            </tr>';
            }
        }



        $html .= '</table>';


        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Situation du stock.pdf', 'I');
    }
}
