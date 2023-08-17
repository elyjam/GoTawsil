<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Caisse extends Model
{
    protected $table = 'caisses';
    protected $guarded = [];
    public $caisse;


    public static function getRecords($ville = null, $numero = null, $statut = null)
    {
        $records =  \DB::table("caisses")
        ->select("*",
         \DB::raw('caisses.date_debut as date_debut'),
         \DB::raw('caisses.id as id'),
         \DB::raw('caisses.statut as statut'),
         \DB::raw('caisses.numero as numero'),
         \DB::raw('caisses.valide_par as validate_by'),
         \DB::raw('statuts.id as statut_id'),
         \DB::raw('statuts.value as statut_label'),
         \DB::raw('id_agence.libelle as agence'),
         \DB::raw('id_utilisateur_gen.libelle as generee_par'),
         \DB::raw('caisses.confirme_par as fermer_par'),
         \DB::raw('caisses.date_validation as validate_at'),
         \DB::raw('caisses.date_reception as date_reception'),
         \DB::raw('caisses.created_at as created_at')
        )
        ->leftJoin('villes as id_agence', 'id_agence.id', '=', 'caisses.id_agence')
        ->leftJoin('employes as id_utilisateur_gen', 'id_utilisateur_gen.id', '=', 'caisses.id_utilisateur_gen')
        ->leftJoin('statuts',function($join){
            $join->on('statuts.key', '=', 'caisses.statut');
            $join->where('statuts.code', '=', 'CAISSE');
        });


        if( is_numeric($ville) ){
            $records->where("caisses.id_agence", '=', $ville );
        }
        if( is_numeric($numero) ){
            $records->where("caisses.numero", '=', $numero );
        }
        if( is_numeric($statut) ){
            $records->where("caisses.statut", '=', $statut );
        }

        if(request()->input('numero') !== null && strlen(trim((request()->input('numero'))))>0 ){
            $records->where("caisses.numero", '=', request()->input('numero') );
        }

        if(request()->input('statut') !== null && is_numeric(request()->input('statut')) ){
            $records->where("caisses.statut", '=', request()->input('statut') );
        }

        if(request()->input('ville') !== null && is_numeric(request()->input('ville')) ){
            $records->where("caisses.id_agence", '=', request()->input('ville') );
        }

        if(request()->input('start_date') !== null && strlen(trim((request()->input('start_date'))))>0 ){
            $records->whereDate("caisses.date_debut", '>=', Carbon::parse(request()->input('start_date'))->format('Y-m-d 00:00:00') );
        }

        if(request()->input('end_date') !== null && strlen(trim((request()->input('end_date'))))>0 ){
            $records->whereDate("caisses.date_fin", '<=',  Carbon::parse(request()->input('end_date'))->format('Y-m-d 23:59:59'));
        }

        if(auth()->user()->role == '2'){
            $records->where('id_utilisateur_gen', auth()->user()->EmployeDetail->id);
        }


        return  $records->get();



    }
    public static function getMontantTotal($caisse){
        return Util::moneyFormat(CaissesExpeditions::where('id_caisse', $caisse)->sum('montant'));
    }

    public static function getBaseQuery($formData){
        $records = \DB::table("caisses")
        ->select("*",
         \DB::raw('caisses.date_debut as date_debut'),
         \DB::raw('caisses.id as id'),
         \DB::raw('caisses.statut as statut'),
         \DB::raw('caisses.numero as numero'),
         \DB::raw('caisses.valide_par as validate_by'),
         \DB::raw('statuts.id as statut_id'),
         \DB::raw('statuts.value as statut_label'),
         \DB::raw('id_agence.libelle as agence'),
         \DB::raw('id_utilisateur_gen.name as generee_par'),
         \DB::raw('caisses.confirme_par as fermer_par'),
         \DB::raw('caisses.date_validation as validate_at'),
         \DB::raw('caisses.date_reception as date_reception'),
         \DB::raw('caisses.created_at as created_at')
        )
        ->leftJoin('villes as id_agence', 'id_agence.id', '=', 'caisses.id_agence')
        ->leftJoin('users as id_utilisateur_gen', 'id_utilisateur_gen.id', '=', 'caisses.id_utilisateur_gen')
        ->leftJoin('statuts',function($join){
            $join->on('statuts.key', '=', 'caisses.statut');
            $join->where('statuts.code', '=', 'CAISSE');
        });

        if($formData['numero'] !== null && strlen(trim(($formData['numero'])))>0 ){
            $records->where("caisses.numero", '=', $formData['numero'] );
        }

        if($formData['statut'] !== null && is_numeric($formData['statut']) ){
            $records->where("caisses.statut", '=', $formData['statut'] );
        }

        if($formData['ville'] !== null && is_numeric($formData['ville']) ){
            $records->where("caisses.id_agence", '=', $formData['ville'] );
        }

        if( $formData['start_date'] !== null && strlen(trim(($formData['start_date'])))>0 ){
            $records->whereDate("caisses.date_debut", '>=', Carbon::parse($formData['start_date'])->format('Y-m-d 00:00:00') );
        }

        if($formData['end_date'] !== null && strlen(trim(($formData['end_date'])))>0 ){
            $records->whereDate("caisses.date_fin", '<=',  Carbon::parse($formData['end_date'])->format('Y-m-d 23:59:59'));
        }

        if (auth()->user()->role != '1') {
            $records = $records->whereIn('id_agence', \Auth::user()::getUserVilles());
        }
        return $records;
    }

    public static function getDetail($id)
    {
        return \DB::table("caisses")
            ->select(
                "*",
                \DB::raw('caisses.id as id'),
                \DB::raw('statuts.value as statut_label'),
                \DB::raw('villes.libelle as ville_label')
            )
            ->leftJoin('villes', 'villes.id', '=', 'caisses.id_agence')
            ->leftJoin('users', 'users.id', '=', 'caisses.id_utilisateur_gen')
            ->leftJoin('statuts', function ($join) {
                $join->on('statuts.key', '=', 'caisses.statut');
                $join->where('statuts.code', '=', 'CAISSE');
            })
            ->where('caisses.deleted', "0")
            ->where('caisses.id', $id)
            ->first();
    }

    public static function getOpenedCaisseByExpedition($expedition, $userId = null)
    {
        $userId = is_numeric($userId) ? $userId : \Auth::user()->id;
        $caisse = Caisse::where("id_utilisateur_gen", $userId)
            ->where("id_agence", $expedition->agence_des)
            ->where('statut', 1)->first();
        if ($caisse) {
            return $caisse;
        }
        $caisse = new Caisse();
        $caisse->id_utilisateur_gen = $userId;
        $caisse->id_agence = $expedition->agence_des;
        $caisse->statut = 1;
        $caisse->numero = Caisse::all()->where('id_agence', $caisse->id_agence)->count() + 1 . '/' . sprintf("%03d", $caisse->id_agence);
        $caisse->date_creation = date('Y-m-d H:i:s');
        $caisse->date_debut = date('Y-m-d H:i:s');
        $caisse->save();
        return $caisse;
    }
    public static function getCaisseTotal($expeditions, $cheques)
    {
        $total = 0;
        foreach ($expeditions as $expedition) {
            $total += $expedition->fond;
        }
        foreach ($cheques as $cheque) {
            $total -= $cheque;
        }
        return $total;
    }
    public function printDetail($caisse, $expeditions, $cheques)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->caisse = $caisse;
        $total = self::getCaisseTotal($expeditions, $cheques);
        $pdf::setHeaderCallback(function ($pdf) {
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>DETAIL CAISSE N° : ' . $this->caisse->numero . ' / ' . $this->caisse->ville_label . '</b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                            ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                        </td>
                    </tr>
                </table>
                <hr>
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                <tr >
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="30%">
                    Statut : ' . $this->caisse->statut_label . '
                    </td>
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="15%">
                    </td>

                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="15%">
                        <b>Date caisse :</b>
                    </td>
                    <td style="height:25px !important; font-size: 10px !important;text-align:center !important;" width="20%">
                    <b> Du :</b> ' . $this->caisse->date_debut . '
                    </td>
                    <td style="height:25px !important; font-size: 10px !important;text-align:center !important;" width="20%">
                    <b> Au :</b> ' . $this->caisse->date_fin . '
                    </td>
                </tr>
            </table>

            ';
            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function ($pdf) {
            $pdf->writeHTML('<hr> <span style="font-size: 9px !important; text-align: center !important;"> <span style="margin:20px !important">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SARL CAPITAL : 50000 dhs Email: contact@gotawsil.ma Siège : 54, Route Ouled Ziane, 20320 Casablanca </span><br><span> Site: www.gotawsil.ma RC: 452277 ICE: 002385062000074 CNSS: 1856623 Numéro de tel: 0522444471 If: 39471647 </span></span><br><br>s', true, false, false, false, '');
        });


        // set margins
        $pdf::SetMargins(7, PDF_MARGIN_TOP, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage();

        // Set some content to print
        //dd($expeditions);
        $expeditionRows = "";
        foreach ($expeditions as $expedition) {

            $expeditionRows .= '
                            <tr>
                                    <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >
                                    ' . $expedition->num_expedition . '
                                    </td>
                                    <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >
                                    ' . date('d/m/Y', strtotime($expedition->created_at)) . '
                                    </td>
                                    <td style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;"  >
                                    ' . $expedition->client . '
                                    </td>
                                    <td style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;"  >
                                    ' . $expedition->agence . '
                                    </td>
                                    <td style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;"  >' . $expedition->destinataire . '
                                    </td>
                                    <td style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;"  >
                                    ' . $expedition->destination . '
                                    </td>
                                    <td  style="height:18px !important; text-align: center !important; font-size: 8px !important; border:0,2px solid !important;"  >
                                    ' . $expedition->colis . '
                                    </td>
                                    <td style="height:18px !important;text-align: center !important;  font-size: 8px !important; border:0,2px solid !important;" >
                                    ' . $expedition->port . '
                                    </td>
                                    <td style="height:18px !important;text-align: right !important;  font-size: 8px !important; border:0,2px solid !important;" >
                                    ' . \App\Models\Util::moneyFormat($expedition->fond) . '
                                    </td>
                                    <td style="height:18px !important;text-align: right !important;  font-size: 8px !important; border:0,2px solid !important;" >
                                    ' . \App\Models\Util::moneyFormat($cheques[$expedition->id] ?? 0) . '
                                    </td>
                                </tr>';
        }
        $html = '
                <br><br>

                    <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                    <tr >
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="30%">Caisse par : ' . $caisse->name . ' ' . $caisse->first_name . '
                    </td>
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="45%">
                    </td>

                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="10%">
                        <b></b>
                    </td>
                    <td style="height:25px !important; font-size: 10px !important;text-align:right !important;" width="15%">
                    </td>
                </tr>
            </table>

        <table style="width:100% !important;  height:100% !important; " cellpadding="2">

                <tr>
                    <td width="10%" style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                        <b>N° Expéd.</b>
                    </td>
                    <td width="9%" style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                        <b>Date</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" >
                    <b>Expéditeur</b>
                    </td>
                    <td width="12%" style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" >
                    <b>Origine</b>
                    </td>
                    <td width="13%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                    <b>Destinataire</b>
                    </td>
                    <td width="12%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                    <b>Destination</b>
                    </td>
                    <td width="6%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                    <b>Colis</b>
                    </td>
                    <td width="6%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                    <b>Port</b>
                    </td>
                    <td width="8%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                    <b>Montant</b>
                    </td>
                    <td width="8%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                    <b>Chéque</b>
                    </td>
                </tr>
                ' . $expeditionRows . '


            </table>
            <br>
            <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                <tr>
                    <td width="73%" style="height:18px !important;  font-size: 8px !important; border:none !important;" >
                    </td>
                    <td width="12%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#d3edfb" >
                    <b>Total</b>
                    </td>
                    <td width="16%" style="text-align:right !important;height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#d3edfb">
                    <b>' . \App\Models\Util::moneyFormat($total) . ' Dhs</b>
                    </td>
                </tr>
                </table>
        ';

        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Facture.pdf', 'I');
    }


    public function print($caisse, $expeditions, $cheques, $versements)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $livreur = $caisse->name . ' ' . $caisse->first_name;
        $pdCount = $pdTotal = $ppCount = $ppTotal = $ppeCount = $ppeTotal = $pdeCount = $pdeTotal = 0;

        foreach ($expeditions as $expedition) {
            switch ($expedition->port) {
                case 'PD':
                    $pdCount++;
                    $pdTotal += $expedition->fond;
                    if (isset($cheques[$expedition->id])) {
                        $pdTotal -= $cheques[$expedition->id];
                    }
                    break;
                case 'PP':
                    $ppCount++;
                    $ppTotal += $expedition->fond;
                    if (isset($cheques[$expedition->id])) {
                        $ppTotal -= $cheques[$expedition->id];
                    }
                    break;
                case 'PPE':
                    $ppeCount++;
                    $ppeTotal += $expedition->fond;
                    if (isset($cheques[$expedition->id])) {
                        $ppeTotal -= $cheques[$expedition->id];
                    }
                    break;
                case 'PDE':
                    $pdeCount++;
                    $pdeTotal += $expedition->fond;
                    if (isset($cheques[$expedition->id])) {
                        $pdeTotal -= $cheques[$expedition->id];
                    }
                    break;
            }
        }


        $this->caisse = $caisse;
        $pdf::setHeaderCallback(function ($pdf) {
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>DETAIL CAISSE N° : ' . $this->caisse->numero . ' / ' . $this->caisse->ville_label . '</b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                            ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                        </td>
                    </tr>
                </table>
                <hr>
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                <tr >
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="30%">Statut : ' . $this->caisse->statut_label . '
                    </td>
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="15%">
                    </td>

                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="15%">
                    </td>
                    <td style="height:25px !important; font-size: 10px !important;text-align:center !important;" width="20%">
                    </td>
                    <td style="height:25px !important; font-size: 10px !important;text-align:center !important;" width="20%">
                    </td>
                </tr>
            </table>

            ';
            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function ($pdf) {
            $pdf->writeHTML('<hr> <span style="font-size: 9px !important; text-align: center !important;"> <span style="margin:20px !important">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SARL CAPITAL : 50000 dhs Email: contact@gotawsil.ma Siège : 54, Route Ouled Ziane, 20320 Casablanca </span><br><span> Site: www.gotawsil.ma RC: 452277 ICE: 002385062000074 CNSS: 1856623 Numéro de tel: 0522444471 If: 39471647 </span></span><br><br>s', true, false, false, false, '');
        });


        // set margins
        $pdf::SetMargins(7, PDF_MARGIN_TOP, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage();

        $versementRows = "";
        $totalVersements = 0;
        foreach ($versements as $versement) {
            $totalVersements += $versement->montant;

            $versementRows .= '
                <tr>
                    <td width="15%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" >' . $versement->libelle . '
                    </td>
                    <td width="15%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" >' . date('d/m/Y', strtotime($versement->created_at)) . '
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  >' . date('d/m/Y', strtotime($versement->created_at)) . '
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  >' . $versement->reference . '
                    </td>
                    <td width="30%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  > ' . $versement->observation . '
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:right !important;border:0,2px solid !important;"  >' . \App\Models\Util::moneyFormat($versement->montant) . ' Dhs
                    </td>
                </tr>
            ';
        }


        // Set some content to print
        $html = ' <br><br>

        <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr>
                        <td width="100%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb" >
        <b>Port Dû</b></td>
            </tr>

        </table>

        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
        <tr>
            <td width="50%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2"><b>Caissier / Livreur</b>
            </td>
            <td width="25%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Nbre Expéditions</b>
            </td>
            <td width="25%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Montant</b>
            </td>
        </tr>
        <tr>
            <td width="50%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" >' . $livreur . '
            </td>
            <td width="25%" style="height:18px !important; font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" >' . $pdCount . '</td>
            <td width="25%" style="height:18px !important;  font-size: 9px !important;text-align:right !important;border:0,2px solid !important;"  >' . \App\Models\Util::moneyFormat($pdTotal) . ' Dhs</td>
        </tr>
        </table>
        <br><br>



        <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr>
                        <td width="100%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb" >
        <b>Port Payé</b></td>
            </tr>

        </table>

        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
        <tr>
            <td width="50%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2"><b>Caissier / Livreur</b>
            </td>
            <td width="25%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Nbre Expéditions</b>
            </td>
            <td width="25%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Montant</b>
            </td>
        </tr>
        <tr>
            <td width="50%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" >' . $livreur . '
            </td>
            <td width="25%" style="height:18px !important; font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" >' . $ppCount . '</td>
            <td width="25%" style="height:18px !important;  font-size: 9px !important;text-align:right !important;border:0,2px solid !important;"  >' . \App\Models\Util::moneyFormat($ppTotal) . ' Dhs</td>
        </tr>
        </table>
        <br><br>


        <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr>
                        <td width="100%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb" >
        <b>Port Payé Enc</b></td>
            </tr>

        </table>

        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
        <tr>
            <td width="50%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2"><b>Caissier / Livreur</b>
            </td>
            <td width="25%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Nbre Expéditions</b>
            </td>
            <td width="25%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Montant</b>
            </td>
        </tr>
        <tr>
            <td width="50%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" >' . $livreur . '
            </td>
            <td width="25%" style="height:18px !important; font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" >' . $ppeCount . '</td>
            <td width="25%" style="height:18px !important;  font-size: 9px !important;text-align:right !important;border:0,2px solid !important;"  >' . \App\Models\Util::moneyFormat($ppeTotal) . ' Dhs</td>
        </tr>
        </table>
        <br><br>

        <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr>
                        <td width="100%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb" >
        <b>Port Dû Enc</b></td>
            </tr>

        </table>

        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
        <tr>
            <td width="50%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2"><b>Caissier / Livreur</b>
            </td>
            <td width="25%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Nbre Expéditions</b>
            </td>
            <td width="25%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Montant</b>
            </td>
        </tr>
        <tr>
            <td width="50%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" >' . $livreur . '
            </td>
            <td width="25%" style="height:18px !important; font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" >' . $pdeCount . '</td>
            <td width="25%" style="height:18px !important;  font-size: 9px !important;text-align:right !important;border:0,2px solid !important;"  >' . \App\Models\Util::moneyFormat($pdeTotal) . ' Dhs</td>
        </tr>
        </table>

        <br>
     <br>
        <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr>
                        <td width="100%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb" ><b>DEPENSES & VERSEMENTS
                        </b>
                        </td>                </tr>

                    </table>
                    <table style="width:100% !important;  height:100% !important; " cellpadding="2">
        <tr>
            <td width="15%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2"><b>Type</b>
            </td>
            <td width="15%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Date saisie</b>
            </td>
            <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Date opération</b>
            </td>
            <td width="10%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>N° Reçu</b>
            </td>
            <td width="30%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Observation</b>
            </td>
            <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Montant</b>
            </td>
        </tr>
       ' . $versementRows . '
        <tr>
            <td width="85%" style="height:18px !important; font-size: 9px !important;text-align:left !important;border:none !important;" >
            </td>

            <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:right !important;border:0,2px solid !important;"  bgcolor="#e2e2e2"><b>' . \App\Models\Util::moneyFormat($totalVersements) . ' Dhs</b>
            </td>

        </tr>
    </table>
    <br>
    <br>
    <br>
    <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr>
                        <td width="50%" style="text-align:center!important;height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb" ><b>VISA AGENCE
                        </b>
                        </td>
                        <td width="50%" style="text-align:center!important; height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb" ><b>VISA SERVICE CAISSE
                        </b>
                        </td>
                        </tr>

                    </table>
                    <table style="width:100% !important;  height:100% !important; " cellpadding="2">';


        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Facture.pdf', 'I');
    }

    public function genBy()
    {
        return $this->belongsTo(\App\User::class, 'id_utilisateur_gen');
    }

    public function getStatuts()
    {
        $statut = DB::table('statuts')->get()->where('code', 'CAISSE')->where('key', $this->statut)->first();
        return $statut->value ?? '';
    }

    public function agenceDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'id_agence');
    }


    public function getexpedition()
    {
        return $this->hasMany(\App\Models\CaissesExpeditions::class, 'id_caisse');
    }
}
