<?php

namespace App\Models;

use Carbon\Carbon;
use \App\Models\RemboursementPaiements;
use Illuminate\Database\Eloquent\Model;

class FactureAncien extends Model
{
    protected $table = 'factures_ancien';
    protected $facture;
    protected $guarded = [];

    public function clientDetail(){
        return $this->belongsTo(\App\Models\Client::class, 'client');
    }

    public static function getFactures($type){
        $formData = request()->all();
        $now = Carbon::now();
        $query = \DB::table("factures")->select('*',
                         \DB::raw('factures.id as id'),
                         \DB::raw('factures.created_at as created_at'),
                         \DB::raw('clients.libelle as client'),
                         \DB::raw('factures.code as code')
                         )
                        ->leftJoin('clients', 'clients.id', '=', 'factures.client')
                        ->where('factures.type',$type)
                        ->where('factures.deleted',"0");

        if (isset($formData['start_date']) && strlen(trim(($formData['start_date']))) > 0) {
            $query->whereDate("factures.created_at", '>=', $formData['start_date']);
        }else{
            $query->whereDate("factures.created_at", '>=', $now->subWeek());
        }
        if (isset($formData['end_date'])  && strlen(trim(($formData['end_date']))) > 0) {
            $query->whereDate("factures.created_at", '<=', $formData['end_date']);
        }
        if (isset($formData['code']) && strlen(trim(($formData['code']))) > 0) {
            $query->where("factures.code", '=', $formData['code']);
        }
        if (isset($formData['client']) && is_numeric($formData['client']) && $formData['client']>0) {
            $query->where("factures.client", '=', $formData['client']);
        }
        if (isset($formData['statut']) && is_numeric($formData['statut']) && $formData['statut']>0) {
            if($formData['statut'] == 1){
                $query->whereNull("factures.remise");
            }
            else{
                $query->whereNotNull("factures.remise");
            }

        }

        return $query->get();
    }

    public static function getCode($type = 1){


        return ($type == 1) ? "FE" . sprintf("%04d", Facture::whereYear('created_at', '=', date('Y'))->where('type', 1)->count() + 1).'/'.date('y') : "FR" . sprintf("%04d", Facture::whereYear('created_at', '=', date('Y'))->where('type', 2)->count() + 1).'/'.date('y');
    }

    public static function getPaiementExpeditions($paiement){

        $paiements = \DB::table("remboursements_expeditions_ancien")
                    ->select('*', \DB::raw('agences_des.Libelle as destination'), \DB::raw('expeditions_ancien.created_at as created_at'), \DB::raw('expeditions_ancien.telephone as telephone'))
                    ->leftJoin('expeditions_ancien', 'remboursements_expeditions_ancien.expedition_id', '=', 'expeditions_ancien.id')
                    ->leftJoin('agences as agences_des', 'agences_des.id', '=', 'expeditions_ancien.agence_des')
                    ->where("remboursements_expeditions_ancien.remboursement_id", $paiement->remboursement)
                    ->where("expeditions_ancien.client", $paiement->client)
                    ->get();

        return $paiements;
    }

    public static function getPaiementsFacture($client, $date){
        return RemboursementPaiements::select(
                        '*'
                    )->where("client", $client)
                    ->whereNull('facture')
                    ->whereDate("remboursements_paiements.created_at", '<=', $date)->get();
    }

    public static function getFactureMtnTTC($paiements){
        $mtnTTC = 0;
        foreach($paiements as $paiement){
            $expeditions = self::getPaiementExpeditions($paiement);
            foreach($expeditions as $expedition){
                $mtnTTC += $expedition->ttc;
            }
        }
        return $mtnTTC;
    }

    public static function generate($clients, $type, $date){

        foreach($clients as $client){
            $paiements = self::getPaiementsFacture($client, $date);
            //dd($paiements);
            $ttc = self::getFactureMtnTTC($paiements);
            $facture = new Facture();
                $facture->code = self::getCode($type);
                $facture->type = $type;
                $facture->tauxtva = session('global_parameters')->tauxtva;
                $facture->ht = ($ttc/6) * 5;
                $facture->tva = $ttc/6;
                $facture->ttc = $ttc;
                $facture->client = $client;
                $facture->created_by = \Auth::user()->id;
            $facture->save();
            foreach($paiements as $paiement){
                $paiement->facture = $facture->id;
                $paiement->save();
            }
        }
    }

    public static function print($facture, $type){

        $client = Client::find($facture->client);
        $ville = Ville::find($client->ville);
        $villeName = $ville->libelle ?? '';
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function($pdf){
            $pdf->writeHTML('<img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >', true, false, true, false, '');
            $pdf->writeHTML("<hr>", true, false, false, false, '');
        });
        $pdf::setFooterCallback(function($pdf){
            $pdf->writeHTML('<hr> <span style="font-size: 9px !important; text-align: center !important;"> <span style="margin:20px !important">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SARL CAPITAL : 50000 dhs Email: contact@gotawsil.ma Siège : 54, Route Ouled Ziane, 20320 Casablanca </span><br><span> Site: www.gotawsil.ma RC: 452277 ICE: 002385062000074 CNSS: 1856623 Numéro de tel: 0522444471 If: 39471647 </span></span><br><br>s', true, false, false, false, '');
        });

        if($type === '2'){
            $pdf::SetPrintHeader(false);
            $pdf::SetPrintFooter(false);
        }

        // set margins
        $pdf::SetMargins(7, PDF_MARGIN_TOP, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage();
        // Set some content to print
        $html = ' <br><br><br><br>
        <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                <tr >
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="40%">
                    </td>
                    <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" ><b>Client</b>
                    </td>
                    <td width="45%" style="height:25px !important;  font-size: 10px !important; border:0,2px solid !important;" >'.$client->libelle.'
                    </td>
                </tr>
                <tr >
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="40%">
                    </td>
                    <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" ><b>Adresse</b>
                    </td>
                    <td width="45%" style="height:25px !important;  font-size: 10px !important; border:0,2px solid !important;" >'.$client->adresse.'
                    </td>
                </tr>

                <tr style="" >
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="40%">
                    </td>
                    <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important; border-color:black !important;border-left-color:black !important;" ><b>Téléphone</b>
                    </td>
                    <td width="45%" style="height:25px !important;  font-size: 10px !important; border:0,2px solid !important; " >'.$client->telephone.'
                    </td>
                </tr>

                <tr>
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;border:0,2px solid !important;" width="40%" bgcolor="#e2e2e2"><b>N° FACTURE : '.$facture->code.'</b>
                    </td>
                    <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important; border-color:black !important;border-left-color:black !important;" ><b>Ville</b>
                    </td>
                    <td width="45%" style="height:25px !important;  font-size: 10px !important; border:0,2px solid !important; " >'.$villeName.'
                    </td>
                </tr>
                <tr>
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;border:0,2px solid !important;" width="25%"><b>CASABLANCA LE :</b></td>
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;border:0,2px solid !important;" width="15%">'.date('d/m/Y', strtotime($facture->created_at)).'</td>
                    <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important; border-color:black !important;border-left-color:black !important;" ><b>I.C.E</b>
                    </td>
                    <td width="45%" style="height:25px !important;  font-size: 10px !important; border:0,2px solid !important; " >'.$client->ice_org.'
                    </td>
                </tr>
                <tr>
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;border:0,2px solid !important;" width="40%" bgcolor="#e2e2e2"><b>Designation</b></td>
                    <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Date facture</b>
                    </td>
                    <td width="12%" style="height:25px !important;  font-size: 10px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>H.T</b>
                    </td>
                    <td width="8%" style="height:25px !important;  font-size: 10px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Taxes</b>
                    </td>
                    <td width="10%" style="height:25px !important;  font-size: 10px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>T.V.A</b>
                    </td>
                    <td width="15%" style="height:25px !important;  font-size: 10px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>T.T.C</b>
                    </td>
                </tr>

                <tr>
                    <td style="height:250px !important; font-size: 10px !important;text-align:left !important;border:0,2px solid !important;" width="40%" ><b>Préstation de livraison</b></td>
                    <td width="15%" style="  font-size: 10px !important;border:0,2px solid !important;"  ><b>'.date('d/m/Y', strtotime($facture->created_at)).'</b>
                    </td>
                    <td width="12%" style="  font-size: 10px !important; border:0,2px solid !important;"><b>'.Util::moneyFormat($facture->ht, 2).'</b>
                    </td>
                    <td width="8%" style="  font-size: 10px !important; border:0,2px solid !important;" ><b>0.20</b>
                    </td>
                    <td width="10%" style="  font-size: 10px !important; border:0,2px solid !important;" ><b>'.Util::moneyFormat($facture->tva, 2).'</b>
                    </td>
                    <td width="15%" style="  font-size: 10px !important; border:0,2px solid !important;" ><b>'.Util::moneyFormat($facture->ttc, 2).'</b>
                    </td>
                </tr>

            </table>

            <br><br>
        <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                <tr>
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="40%" ><b></b></td>
                    <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>TOTAL H.T</b>
                    </td>
                    <td width="15%" style="height:25px !important;  font-size: 10px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>TAUX T.V.A</b>
                    </td>
                    <td width="15%" style="height:25px !important;  font-size: 10px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>TOTAL T.V.A</b>
                    </td>
                    <td width="15%" style="height:25px !important;  font-size: 10px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>TOTAL T.T.C</b>
                    </td>

                </tr>

                <tr>
                    <td style=" font-size: 10px !important;text-align:left !important;" width="40%" ><b></b></td>
                    <td width="15%" style="  font-size: 10px !important;border:0,2px solid !important;"  ><b>'.Util::moneyFormat($facture->ht, 2).'</b>
                    </td>
                    <td width="15%" style="  font-size: 10px !important; border:0,2px solid !important;"><b>'.Util::moneyFormat($facture->tva, 2).'</b>
                    </td>
                    <td width="15%" style="  font-size: 10px !important; border:0,2px solid !important;" ><b>0.20</b>
                    </td>
                    <td width="15%" style="  font-size: 10px !important; border:0,2px solid !important;" ><b>'.Util::moneyFormat($facture->ttc, 2).'</b>
                    </td>

                </tr>

            </table>

            <br><br>
        <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                <tr>
                <td width="100%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>LA PRESENTE FACTURE EST ARRETEE A LA SOMME DE :
                </b>
                </td>                </tr>

                <tr>
                <td width="100%" style="  font-size: 10px !important; border:0,2px solid !important;" ><b>'.strtoupper(Util::ChiffreEnLettre($facture->ttc)).' DIRHAMS (S).</b>
                </td>
                </tr>

            </table>
        ';
        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        if($type === '1'){
            $pdf::writeHTML('<br><br><span style="width: 100% !important; text-align: center !important;"><img src="/assets/front/signature.png" height="100px"  width="200px" style="padding: 5px !important;" >', true, false, true, false, '');
        }

        $pdf::Output('Facture.pdf', 'I');
    }



    public function printDetail($facture){

        $this->facture = $facture;
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function($pdf){
            $header='
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 14px !important;text-align:center !important;" width="60%">
                            <b>DETAIL FACTURE N° : FR0630/22 </b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:right !important;" width="20%">
                            '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages().'
                        </td>
                    </tr>
                </table>
                <hr>
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="10%">
                        <b>Client : </b>
                        </td>
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="60%">
                        '.$this->clientDetail->libelle.'
                        </td>

                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="15%">
                            <b>Date Facture :</b>
                        </td>
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="15%">
                        '.date('d/m/Y', strtotime($this->facture->created_at)).'
                        </td>
                    </tr>
                </table>

            ';
            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function($pdf){
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
        $paiements = RemboursementPaiementsAncien::where('facture', $facture->id)->get();


        $html = '';
        foreach($paiements as $paiement){
            $html = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 9px !important;text-align:left !important;" width="15%">
                        <b>N° Virement : </b>
                        </td>
                        <td style="height:25px !important; font-size: 9px !important;text-align:left !important;" width="15%">
                        '.$paiement->code.'
                        </td>
                        <td style="height:25px !important; font-size: 9px !important;text-align:left !important;" width="15%">
                        '.date('d/m/Y', strtotime($paiement->created_at)).'
                        </td>
                    </tr>
                </table>';

            $expeditions = self::getPaiementExpeditions($paiement);
            $i=0;
            foreach($expeditions as $expedition){

                if($i==0){

                    $html .= '
                    <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                        <tr>
                            <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" width="10%" bgcolor="#e2e2e2">
                                <b>N° Expéd.</b>
                            </td>
                            <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" width="10%" bgcolor="#e2e2e2">
                                <b>Date</b>
                            </td>
                            <td width="26%" style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Destinataire</b>
                            </td>
                            <td width="20%" style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Destination</b>
                            </td>
                            <td width="9%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Téléphone</b>
                            </td>
                            <td width="8%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Colis</b>
                            </td>
                            <td width="10%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Fond</b>
                            </td>
                            <td width="7%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Frais</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >
                            '.$expedition->num_expedition.'
                            </td>
                            <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >
                            '.date('d/m/Y', strtotime($expedition->created_at)).'
                            </td>
                            <td style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;"  >
                            '.$expedition->destinataire.'
                            </td>
                            <td style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;"  >
                            '.$expedition->destination.'
                            </td>
                            <td style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;"  >'.$expedition->telephone.'
                            </td>
                            <td style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;"  >
                            '.$expedition->colis.'
                            </td>
                            <td  style="height:18px !important; text-align: right !important; font-size: 8px !important; border:0,2px solid !important;"  >
                            '.Util::moneyFormat($expedition->fond).'
                            </td>
                            <td style="height:18px !important;text-align: right !important;  font-size: 8px !important; border:0,2px solid !important;" >
                            '.Util::moneyFormat($expedition->ttc).'
                            </td>
                        </tr>';
                }
                else{
                    $html.= '
                    <tr>
                            <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >
                            '.$expedition->num_expedition.'
                            </td>
                            <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >
                            '.date('d/m/Y', strtotime($expedition->created_at)).'
                            </td>
                            <td style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;"  >
                            '.$expedition->destinataire.'
                            </td>
                            <td style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;"  >
                            '.$expedition->destination.'
                            </td>
                            <td style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;"  >'.$expedition->telephone.'
                            </td>
                            <td style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;"  >
                            '.$expedition->colis.'
                            </td>
                            <td  style="height:18px !important; text-align: right !important; font-size: 8px !important; border:0,2px solid !important;"  >
                            '.Util::moneyFormat($expedition->fond).'
                            </td>
                            <td style="height:18px !important;text-align: right !important;  font-size: 8px !important; border:0,2px solid !important;" >
                            '.Util::moneyFormat($expedition->ttc).'
                            </td>
                        </tr>';
                }
                $i++;
            }
            $html.='</table>';
            $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        }



        $pdf::Output('Facture.pdf', 'I');
    }

}
