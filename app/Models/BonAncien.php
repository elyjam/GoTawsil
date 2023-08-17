<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class BonAncien extends Model
{
    protected $table = 'bons_ancien';
    protected $guarded = [];


    public function expeditionDetail()
    {
        return $this->hasMany(\App\Models\Expedition::class, 'id_bon');
    }

    public function expeditionValide()
    {
        return $this->expeditionDetail()->where('etape', '!=', '1');
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'id_client');
    }

    public function agenceDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'id_agence_exp');
    }

    public function transportDetail()
    {
        return $this->belongsTo(\App\Models\Transporteur::class, 'id_transporteur');
    }

    public function agenceDesDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'id_agence_dest');
    }


    public function prossesusDetail()
    {
        return $this->hasMany(\App\Models\Processus_expedition::class, 'id_feuille_charge');
    }
    public function prossesusChargement()
    {
        return $this->hasOne(\App\Models\Processus_expedition::class, 'id_feuille_charge');
    }
    public function processusArivage()
    {
        return $this->prossesusDetail()->where('code', 'CHARGEMENT');
    }

    public static function printDetail($caisse)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) use ($caisse){
            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>DEMANDE DE RAMASSAGE N° : '.$caisse->code.' </b>
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="30%">
                            ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages() . '
                        </td>
                    </tr>
                </table>
                <hr>
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="10%"><b>Client :</b>
                        </td>
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="40%">
                        '.$caisse->client->libelle.'
                        </td>

                        <td style="height:25px !important; font-size: 10px !important;text-align:right !important;" width="25%">
                            <b>Généré le :</b>
                        </td>
                        <td style="height:25px !important; font-size: 10px !important;text-align:right !important;" width="25%">
                        '.$caisse->created_at.'
                        </td>
                    </tr>
                </table>';
            $pdf->writeHTML($header, true, false, true, false, '');
        });
        $pdf::setFooterCallback(function ($pdf)  {
            $pdf->writeHTML('<hr> <table style="width:100% !important;  height:100% !important; " cellpadding="5"> <tr > <td style="height:25px !important; font-size: 10px !important;text-align:right !important;" width="100%"> ' . date('d/m/Y H:i') . ' </td></tr> </table>', true, false, false, false, '');
        });


        // set margins
        $pdf::SetMargins(7, PDF_MARGIN_TOP, 7);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(17);

        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf::AddPage();
        $record = ExpeditionAncien::where('id_bon', $caisse->id)->get();

        $exp_table = '';
        $total_colis = 0;
        $total_fond = 0;
        $total_frais = 0;
        foreach ($record as $exp) {
            $total_colis = $total_colis + $exp->colis;
            $total_fond = $total_fond + $exp->fond;
            $total_frais = $total_frais + $exp->ttc;
        }

        foreach ($record as $exp) {
            $exp_table = $exp_table . '<tr>
                    <td style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" >' . $exp->num_expedition . '</td>
                    <td style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" >' . $exp->created_at . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  >' .($exp->agenceDetail->libelle ?? ''). '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  >' . $exp->destinataire . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  >' . ($exp->agenceDesDetail->libelle ?? '') . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  >' . $exp->telephone . '
                    </td>
                    <td  style="height:18px !important; text-align: left !important; font-size: 7px !important; border:0,2px solid !important;"  >' . $exp->adresse_destinataire . '
                    </td>
                    <td style="height:18px !important;text-align: center !important;  font-size: 7px !important; border:0,2px solid !important;" >' . $exp->colis . '</td>
                    <td style="height:18px !important;text-align: right !important;  font-size: 7px !important; border:0,2px solid !important;" >' . $exp->retour_fond . '</td>
                    <td style="height:18px !important;text-align: right !important;  font-size: 7px !important; border:0,2px solid !important;" >' . $exp->fond . '</td>
                    <td style="height:18px !important;text-align: right !important;  font-size: 7px !important; border:0,2px solid !important;" >' . $exp->ttc . '</td>
                </tr>';
        }
        // Set some content to print
        //Téléphone Adresse
        $html = '
        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
                <tr>
                    <td width="8%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2">
                        <b>N° Expéd.</b>
                    </td>
                    <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Date</b>
                    </td>
                    <td width="8%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Origine</b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destinataire</b>
                    </td>
                    <td width="8%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destination</b>
                    </td>
                    <td width="9%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Téléphone</b>
                    </td>
                    <td width="20%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Adresse</b>
                    </td>
                    <td width="4%" style="height:18px !important; text-align:center !important; font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Colis</b>
                    </td>

                    <td width="6%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                    <b>Nature</b>
                    </td>
                    <td width="6%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                    <b>Fond</b>
                    </td>
                    <td width="6%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
                    <b>Frais</b>
                    </td>
                </tr>

' . $exp_table . '

            </table>
            <br><br><table style="width:100% !important;  height:100% !important; " cellpadding="2">
                <tr>
                    <td width="20%" style="height:18px !important; font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  bgcolor="#e2e2e2"><b>TOTAL EXPEDITIONS</b>
                    </td>
                    <td width="15%" style="height:18px !important; font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>TOTAL COLIS</b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>TOTAL FOND</b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>TOTAL PP</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>VISA CLIENT</b>
                    </td>
                    <td width="18%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>VISA RAMASSEUR</b>
                    </td>
                </tr>
                <tr>
                    <td width="20%" style="height:18px !important; font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" ><b>' . $record->count() . '</b>
                    </td>
                    <td width="15%" style="height:18px !important; font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" ><b>' . $total_colis . '</b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  ><b>' . number_format($total_fond, 2) . ' Dhs</b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  ><b>' . number_format($total_frais, 2) . ' Dhs</b>
                    </td>

                </tr>
            </table>
        ';

        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Facture.pdf', 'I');
    }
}
