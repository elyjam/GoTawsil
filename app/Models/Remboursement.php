<?php

namespace App\Models;

use Notification;
use Carbon\Carbon;
use App\Models\Expedition;
use App\Models\notificationWhatsapp;
use App\Models\RemboursementPaiements;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\remboursementEmail;

class Remboursement extends Model
{
    protected $table = 'remboursements';
    protected $remboursement = null;
    protected $paiement = null;

    protected $guarded = [];

    public function typeDetail()
    {
        if ($this->type == 1) {
            $type = 'Chèque';
        } elseif ($this->type == 2) {
            $type = 'Espèce';
        }
        return $type;
    }

    public static function generate($clients, $type, $date)
    {

        $remboursement = new Remboursement();
        $remboursement->code = "CE" . sprintf("%07d", Remboursement::all()->count() + 1);
        $remboursement->type = $type;
        $remboursement->created_by = \Auth::user()->id;
        $remboursement->save();

        $expeditions = Remboursement::getExpeditionsByClients($clients, $date)->whereIn('client', $clients);

        $remboursement->expeditions()->attach($expeditions);

        foreach ($expeditions as $expedition) {
            $expedition->etape = 8;
            $expedition->save();
            $commentaire = new Commentaire();
            $commentaire->code = "Remboursée";
            $commentaire->commentaires = "Remboursée";
            $commentaire->id_expedition = $expedition->id;
            $commentaire->id_utilisateur = auth()->user()->id;
            $commentaire->save();
        }

        foreach ($clients as $client) {
            $paiement = new RemboursementPaiements();
            $paiement->code = "OV" . sprintf("%07d", RemboursementPaiements::all()->count() + 1);
            $paiement->created_by = auth()->user()->id;
            $paiement->remboursement = $remboursement->id;
            $paiement->client = $client;
            $paiement->type = $type;
            $paiement->save();
            if (isset($paiement->clientDetail->email_rembroursement)) {
                $email = $paiement->clientDetail->email_rembroursement;
            } else {
                $email = $paiement->clientDetail->email;
            }


            Notification::route('mail', $email)->notify(new remboursementEmail());
            $message = "Bonjour M/Mme, Nous avons de plaisir de vous annoncer que de nouvelles expéditions ont été remboursées. ";
            notificationWhatsapp::whatsappMessage($paiement->clientDetail->telephone, $message);
        }
    }

    public function expeditions()
    {
        return $this->belongsToMany(Expedition::class, 'remboursements_expeditions', 'remboursement_id', 'expedition_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }

    public static function getExpeditionsByClients($clients, $date)
    {
        return  Expedition::select('expeditions.*')
            ->leftJoin('caisses', 'caisses.id', '=', 'expeditions.caisse_id')
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
            ->where("expeditions.etape", 7)
            ->whereRaw("caisses.statut = 4 or ( caisses.statut = 3 and clients.remboursement_rapide = 'Oui')")
            ->whereIn("expeditions.client", $clients)
            ->whereDate("expeditions.created_at", '<=', $date)
            ->get();
    }

    public function printDetail($clients, $remboursement, $expeditions, $cheques, $paiement)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->remboursement = $remboursement;
        $this->paiement = $paiement;

        if ($this->paiement->id) {
            $clients = [$this->paiement->client => $clients[$this->paiement->client]];
        }

        $pdf::setHeaderCallback(function ($pdf) {
            if ($this->paiement->id) {
                $header = '
                    <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr >
                            <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                            <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                            </td>
                            <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                                <b>DETAIL PAIEMENT N° : ' . $this->paiement->code . ' </b>
                            </td>
                            <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                                ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr >
                            <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="10%">
                            </td>
                            <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="60%">
                            </td>

                            <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="15%">
                                <b>Généré le :</b>
                            </td>
                            <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="15%">
                                ' . date('d/m/Y', strtotime($this->paiement->created_at)) . '
                            </td>
                        </tr>
                    </table>

                ';
            } else {
                $header = '
                    <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr >
                            <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                            <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                            </td>
                            <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                                <b>DETAIL REMBOURSEMENT GROUPE : ' . $this->remboursement->code . ' </b>
                            </td>
                            <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                                ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr >
                            <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="10%">
                            </td>
                            <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="60%">
                            </td>

                            <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="15%">
                                <b>Généré le :</b>
                            </td>
                            <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="15%">
                                ' . date('d/m/Y', strtotime($this->remboursement->created_at)) . '
                            </td>
                        </tr>
                    </table>

                ';
            }

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

        foreach ($clients as $client) {

            $head = '<br><br>
                    <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                        <tr >
                            <td style="height:10px !important; font-size: 9px !important;text-align:left !important;" width="15%"><b>Virement : </b>
                            </td>

                        </tr>
                        <tr >
                            <td style="height:15px !important; font-size: 9px !important;text-align:left !important;" width="30%"><b>' . $client['name'] . ' : </b>
                            </td>
                            <td style="height:25px !important; font-size: 9px !important;text-align:left !important;" width="70%">
                            ' . $client['rib'] . '
                            </td>

                        </tr>
                    </table>
            ';
            $pdf::writeHTMLCell(0, 0, '', '', $head, 0, 1, 0, true, '', true);

            $body = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="2">

                        <tr>
                            <td  width="10%" style="height:auto !important;line-height:14px!important;text-align: center !important; font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>N° Expéd.</b>
                            </td>
                            <td   width="9%" style="height:auto !important;line-height:14px!important;text-align: center !important; font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>Date</b>
                            </td>
                            <td width="12%" style="height:auto !important;line-height:14px!important;text-align: center !important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Destinataire</b>
                            </td>
                            <td width="13%" style="height:auto !important;line-height:14px!important;text-align: center !important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Destination</b>
                            </td>
                            <td width="10%" style="height:auto !important;line-height:14px!important;text-align: center !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Téléphone</b>
                            </td>
                            <td width="5%" style="height:auto !important;line-height:14px!important;text-align: center !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Colis</b>
                            </td>
                            <td width="7%" style="height:auto !important;line-height:14px!important; text-align: center !important; font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Nature</b>
                            </td>
                            <td width="5%" style="height:auto !important;line-height:14px!important;text-align: center !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Port</b>
                            </td>
                            <td width="9%" style="height:auto !important;line-height:14px!important; text-align: center !important; font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Fond</b>
                            </td>
                            <td width="7%" style="height:auto !important;line-height:14px!important; text-align: center !important; font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Chéque</b>
                            </td>
                            <td width="6%" style="height:auto !important;line-height:14px!important; text-align: center !important; font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Frais</b>
                            </td>
                            <td width="7%" style="height:auto !important;line-height:14px!important;text-align: center !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Net</b>
                            </td>
                        </tr>';
            $expeditionsTotal = $colisTotal = $fondsTotal = $chequesTotal = $fraisTotal = $netsTotal = 0;
            foreach ($expeditions as $expedition) {
                if ($expedition->client_id == $client['client_id']) {
                    $cheque = isset($cheques[$expedition->expedition_id]) ? $cheques[$expedition->expedition_id] : 0;
                    $chequeVal = $cheque > 0 ? Util::moneyFormat($cheque) : '_';

                    if ($expedition->port == 'PP' || $client['factureMois'] == 'Oui') {
                        $net = $expedition->fond - $cheque;
                    } else {
                        $net = $expedition->fond - $expedition->ttc - $cheque;
                    }



                    $expeditionsTotal++;
                    $colisTotal += $expedition->colis;
                    $fondsTotal += $expedition->fond;
                    $chequesTotal += $cheque;
                    $fraisTotal += $expedition->ttc;
                    $netsTotal += $net;

                    $body .= '
                        <tr>
                            <td style="height:auto !important;line-height:14px!important;text-align: center !important; font-size: 8px !important;border:0,2px solid !important;" >
                            ' . $expedition->num_expedition . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important; text-align: center !important;font-size: 8px !important;border:0,2px solid !important;" >' . date('Y/m/d', strtotime($expedition->created_at)) . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important; text-align: center !important; font-size: 8px !important;border:0,2px solid !important;"  >
                            ' . $expedition->destinataire . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;text-align: center !important;  font-size: 8px !important;border:0,2px solid !important;"  >
                            ' . $expedition->destination . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;text-align: center !important;  font-size: 8px !important; border:0,2px solid !important;"  >' . $expedition->telephone . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important; text-align: center !important; font-size: 8px !important; border:0,2px solid !important;"  >
                            ' . $expedition->colis . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;text-align: center !important;  font-size: 8px !important; border:0,2px solid !important;"  >
                            ' . $expedition->retour_fond . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;text-align: center !important;  font-size: 8px !important; border:0,2px solid !important;"  >
                            ' . $expedition->port . '
                            </td>
                            <td  style="height:auto !important;line-height:14px!important; text-align: center !important; font-size: 8px !important; border:0,2px solid !important;"  >
                            ' . Util::moneyFormat($expedition->fond) . '
                            </td>
                            <td  style="height:auto !important;line-height:14px!important; text-align: center !important; font-size: 8px !important; border:0,2px solid !important;"  >
                            ' . $chequeVal . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;text-align: center !important;  font-size: 8px !important; border:0,2px solid !important;" >
                            ' . Util::moneyFormat($expedition->ttc) . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;text-align: center !important;  font-size: 8px !important; border:0,2px solid !important;" >
                            ' . Util::moneyFormat($net) . '
                            </td>
                        </tr>
                    ';
                }
            }
            $body .= '</table>';
            $pdf::writeHTMLCell(0, 0, '', '', $body, 0, 1, 0, true, '', true);

            $total = '<br><br><table style="width:100% !important;  height:100% !important; " cellpadding="2">
                        <tr>
                            <td  width="17%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>TOTAL EXPEDITIONS</b>
                            </td>
                            <td  width="17%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>TOTAL COLIS</b>
                            </td>
                            <td  width="17%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>TOTAL FOND</b>
                            </td>
                            <td  width="17%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>TOTAL CHEQUE</b>
                            </td>
                            <td  width="16%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>TOTAL FRAIS</b>
                            </td>
                            <td  width="16%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>TOTAL NET</b>
                            </td>
                        </tr>
                        <tr>
                            <td width="17%" style="font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" >
                            ' . $expeditionsTotal . '
                            </td>
                            <td width="17%" style="font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" >
                            ' . $colisTotal . '
                            </td>
                            <td width="17%" style="font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" >
                            ' . Util::moneyFormat($fondsTotal) . '
                            </td>
                            <td width="17%" style="font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" >
                            ' . Util::moneyFormat($chequesTotal) . '
                            </td>
                            <td width="16%" style="font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" >
                            ' . Util::moneyFormat($fraisTotal) . '
                            </td>
                            <td width="16%" style="font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" >
                            ' . Util::moneyFormat($netsTotal) . '
                            </td>
                        </tr>
                        </table>';
            $pdf::writeHTMLCell(0, 0, '', '', $total, 0, 1, 0, true, '', true);
        }


        $pdf::Output('Remboursement.pdf', 'I');
    }


    public function printDetailAll($remboursements, $star, $end)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


        $pdf::setHeaderCallback(function ($pdf) use ($star, $end) {

            $header = '
                    <table style="width:100% !important;  height:100% !important;" cellpadding="5">
                        <tr>
                            <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                            <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                            </td>
                            <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                                <b>REMBOURSEMENTS EFFECTUES</b>
                            </td>
                            <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                                ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                            </td>
                        </tr>
                    </table>

                    <h5 style="text-align:center;padding:10px;border-top: 0,2px solid !important ;"> Du ' . $star . ' Au ' . $end . ' </h5>

                ';


            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function ($pdf) {
            $pdf->writeHTML('<hr> <span style="font-size: 9px !important; text-align: center !important;"> <span style="margin:20px !important">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SARL CAPITAL : 50000 dhs Email: contact@gotawsil.ma Siège : 54, Route Ouled Ziane, 20320 Casablanca </span><br><span> Site: www.gotawsil.ma RC: 452277 ICE: 002385062000074 CNSS: 1856623 Numéro de tel: 0522444471 If: 39471647 </span></span><br><br>s', true, false, false, false, '');
        });

        // set margins
        $pdf::SetMargins(7, 30, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage();


        foreach ($remboursements as $remboursement) {
            $expeditionsIds = $remboursement->expeditions()->allRelatedIds()->toArray();
            $expeditions = Expedition::getExpeditionsByRemboursement($remboursement->id);
            $cheques = CaissesCheques::getMntArray(null, $expeditionsIds);
            $clients = Expedition::getClientsByRemboursement($expeditions);
            $header = '<br><h4 bgcolor="#e2e2e2"> Remb. : ' . $remboursement->code . '</h4>';
            $pdf::writeHTMLCell(0, 0, '', '', $header, 0, 1, 0, true, '', true);
            foreach ($clients as $client) {

                $head = '
                    <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                        <tr >
                            <td style="height:10px !important; font-size: 9px !important;text-align:left !important;" width="15%"><b>Virement : </b>
                            </td>

                        </tr>
                        <tr >
                            <td style="height:15px !important; font-size: 9px !important;text-align:left !important;" width="30%"><b>' . $client['name'] . ' : </b>
                            </td>
                            <td style="height:25px !important; font-size: 9px !important;text-align:left !important;" width="70%">
                            ' . $client['rib'] . '
                            </td>

                        </tr>
                    </table>
            ';
                $pdf::writeHTMLCell(0, 0, '', '', $head, 0, 1, 0, true, '', true);

                $body = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="2">

                        <tr>
                            <td  width="9%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>N° Expéd.</b>
                            </td>
                            <td   width="9%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>Date</b>
                            </td>
                            <td width="17%" style="height:auto !important;line-height:14px!important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Destinataire</b>
                            </td>
                            <td width="13%" style="height:auto !important;line-height:14px!important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Destination</b>
                            </td>
                            <td width="9%" style="height:auto !important;line-height:14px!important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Téléphone</b>
                            </td>
                            <td width="5%" style="height:auto !important;line-height:14px!important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Colis</b>
                            </td>
                            <td width="5%" style="height:auto !important;line-height:14px!important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Port</b>
                            </td>
                            <td width="9%" style="height:auto !important;line-height:14px!important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Fond</b>
                            </td>
                            <td width="9%" style="height:auto !important;line-height:14px!important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Chéque</b>
                            </td>
                            <td width="6%" style="height:auto !important;line-height:14px!important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Frais</b>
                            </td>
                            <td width="9%" style="height:auto !important;line-height:14px!important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                            <b>Net</b>
                            </td>
                        </tr>';
                $expeditionsTotal = $colisTotal = $fondsTotal = $chequesTotal = $fraisTotal = $netsTotal = 0;

                foreach ($expeditions as $expedition) {
                    if ($expedition->client_id == $client['client_id']) {
                        $cheque = isset($cheques[$expedition->expedition_id]) ? $cheques[$expedition->expedition_id] : 0;
                        $chequeVal = $cheque > 0 ? Util::moneyFormat($cheque) : '';
                        if ($expedition->port == 'PP' || $client['factureMois'] == 'Oui') {
                            $net = $expedition->fond - $cheque;
                        } else {
                            $net = $expedition->fond - $expedition->ttc - $cheque;
                        }

                        $expeditionsTotal++;
                        $colisTotal += $expedition->colis;
                        $fondsTotal += $expedition->fond;
                        $chequesTotal += $cheque;
                        $fraisTotal += $expedition->ttc;
                        $netsTotal += $net;

                        $body .= '
                        <tr>
                            <td style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >
                            ' . $expedition->num_expedition . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >' . date('Y/m/d', strtotime($expedition->created_at)) . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;  font-size: 8px !important;border:0,2px solid !important;"  >
                            ' . $expedition->destinataire . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;  font-size: 8px !important;border:0,2px solid !important;"  >
                            ' . $expedition->destination . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;  font-size: 8px !important; border:0,2px solid !important;"  >' . $expedition->telephone . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;  font-size: 8px !important; border:0,2px solid !important;"  >
                            ' . $expedition->colis . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;  font-size: 8px !important; border:0,2px solid !important;"  >
                            ' . $expedition->retour_fond . '
                            </td>
                            <td  style="height:auto !important;line-height:14px!important; text-align: right !important; font-size: 8px !important; border:0,2px solid !important;"  >
                            ' . Util::moneyFormat($expedition->fond) . '
                            </td>
                            <td  style="height:auto !important;line-height:14px!important; text-align: right !important; font-size: 8px !important; border:0,2px solid !important;"  >
                            ' . $chequeVal . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;text-align: right !important;  font-size: 8px !important; border:0,2px solid !important;" >
                            ' . Util::moneyFormat($expedition->ttc) . '
                            </td>
                            <td style="height:auto !important;line-height:14px!important;text-align: right !important;  font-size: 8px !important; border:0,2px solid !important;" >
                            ' . Util::moneyFormat($net) . '
                            </td>
                        </tr>
                    ';
                    }
                }
                $body .= '</table>';
                $pdf::writeHTMLCell(0, 0, '', '', $body, 0, 1, 0, true, '', true);

                $total = '<br><br><table style="width:100% !important;  height:100% !important; " cellpadding="2">
                        <tr>
                            <td  width="17%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>TOTAL EXPEDITIONS</b>
                            </td>
                            <td  width="17%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>TOTAL COLIS</b>
                            </td>
                            <td  width="17%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>TOTAL FOND</b>
                            </td>
                            <td  width="17%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>TOTAL CHEQUE</b>
                            </td>
                            <td  width="16%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>TOTAL FRAIS</b>
                            </td>
                            <td  width="16%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2">
                                <b>TOTAL NET</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" >
                            ' . $expeditionsTotal . '
                            </td>
                            <td style="font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" >
                            ' . $colisTotal . '
                            </td>
                            <td style="font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" >
                            ' . Util::moneyFormat($fondsTotal) . '
                            </td>
                            <td style="font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" >
                            ' . Util::moneyFormat($chequesTotal) . '
                            </td>
                            <td style="font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" >
                            ' . Util::moneyFormat($fraisTotal) . '
                            </td>
                            <td style="font-size: 8px !important;text-align:center !important;border:0,2px solid !important;" >
                            ' . Util::moneyFormat($netsTotal) . '
                            </td>
                        </tr>
                        </table>';
                $pdf::writeHTMLCell(0, 0, '', '', $total, 0, 1, 0, true, '', true);
            }
        }
        $pdf::Output('Remboursement.pdf', 'I');
    }



    public function ordreVirement($clients, $remboursement, $expeditions, $cheques)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->remboursement = $remboursement;
        $pdf::setHeaderCallback(function ($pdf) {
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>ORDRE DE VIREMENT N° : ' . $this->remboursement->code . ' </b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                            ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                        </td>
                    </tr>
                </table>
                <hr>
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="10%">
                        </td>
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="60%">
                        </td>

                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="15%">
                            <b>Généré le :</b>
                        </td>
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="15%">
                        ' . date('d/m/Y', strtotime($this->remboursement->created_at)) . '
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
        $html = '
            <table style="width:100% !important;  height:100% !important; " cellpadding="2">';
        $total = 0;
        foreach ($clients as $client) {
            $expeditionsTotal = $colisTotal = $fondsTotal = $chequesTotal = $fraisTotal = $netsTotal = 0;
            foreach ($expeditions as $expedition) {
                if ($expedition->client_id == $client['client_id']) {
                    $cheque = isset($cheques[$expedition->expedition_id]) ? $cheques[$expedition->expedition_id] : 0;
                    if ($expedition->port == 'PP' || $client['factureMois'] == 'Oui') {
                        $net = $expedition->fond - $cheque;
                    } else {
                        $net = $expedition->fond - $expedition->ttc - $cheque;
                    }

                    $expeditionsTotal++;
                    $colisTotal += $expedition->colis;
                    $fondsTotal += $expedition->fond;
                    $chequesTotal += $cheque;
                    $fraisTotal += $expedition->ttc;
                    $netsTotal += $net;
                }
            }

            $html .= '
                <tr>
                    <td width="40%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >
                    <b>' . $client['name'] . '</b>
                    </td>
                    <td width="40%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >
                        ' . $client['rib'] . '
                    </td>

                    <td width="20%" style="height:auto !important;line-height:14px!important;text-align: right !important;  font-size: 8px !important; border:0,2px solid !important;" >
                        ' . Util::moneyFormat($netsTotal) . '
                    </td>
                </tr>
                ';

            $total += $netsTotal;
        }


        $html .= '
                <tr>
                    <td width="40%" style="height:auto !important;line-height:14px!important; font-size: 8px !important;text-align:left !important;" >
                    </td>
                    <td width="40%" bgcolor="#e2e2e2" style="height:25px !important; font-size: 12px !important;text-align:left !important;border:0,2px solid !important;" >
                        <b>Total </b>
                    </td>

                    <td  bgcolor="#e2e2e2" width="20%" style="height:25px !important;text-align: right !important;  font-size: 12px !important; border:0,2px solid !important;" >
                        <b>' . Util::moneyFormat($total) . '</b> Dh
                    </td>
                </tr>
            ';

        $html .= '
                    </table>';

        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Facture.pdf', 'I');
    }


    public function CheckTotalNet($clients, $remboursement, $expeditions, $cheques)
    {
        $this->remboursement = $remboursement;
        $total = 0;
        foreach ($clients as $client) {
            $expeditionsTotal = $colisTotal = $fondsTotal = $chequesTotal = $fraisTotal = $netsTotal = 0;
            foreach ($expeditions as $expedition) {
                if ($expedition->client_id == $client['client_id']) {
                    $cheque = isset($cheques[$expedition->expedition_id]) ? $cheques[$expedition->expedition_id] : 0;
                    if ($expedition->port == 'PP' || $client['factureMois'] == 'Oui') {
                        $net = $expedition->fond - $cheque;
                    } else {
                        $net = $expedition->fond - $expedition->ttc - $cheque;
                    }
                    $expeditionsTotal++;
                    $colisTotal += $expedition->colis;
                    $fondsTotal += $expedition->fond;
                    $chequesTotal += $cheque;
                    $fraisTotal += $expedition->ttc;
                    $netsTotal += $net;
                }
            }



            $total += $netsTotal;
        }
    }
}
