<?php

namespace App\Models;

use Carbon\Carbon;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Database\Eloquent\Model;

class Bonliv extends Model
{
    protected $table = 'bonlivs';
    protected $guarded = [];

    public function fetchAll()
    {

        return self::all()->where('deleted', '0');
    }

    public static function getRecords($livreur = null, $type = null, $statut = null)
    {
        $now = Carbon::now();

        $records =  \DB::table("bonlivs")
            ->select(
                "*",
                \DB::raw('employes.libelle as livreur_name'),
                \DB::raw('typebls.label as type_label'),
                \DB::raw('bonlivs.id as id'),
                \DB::raw('bonlivs.statut as statut'),
                \DB::raw('bonlivs.code as code'),
                \DB::raw('statuts.value as statut_label'),
                \DB::raw('bonlivs.created_at as created_at')
            )
            ->leftJoin('typebls', 'typebls.id', '=', 'bonlivs.type')
            ->leftJoin('statuts', function ($join) {
                $join->on('statuts.key', '=', 'bonlivs.statut');
                $join->where('statuts.code', '=', 'BLS');
            })
            ->leftJoin('employes', 'employes.id', '=', 'bonlivs.livreur')
            ->where('bonlivs.deleted', "0");

        if (is_numeric($livreur)) {
            $records->where("bonlivs.livreur", '=', $livreur);
        }
        if (is_numeric($type)) {
            $records->where("bonlivs.type", '=', $type);
        }
        if (is_numeric($statut)) {
            $records->where("bonlivs.statut", '=', $statut);
        }

        if (request()->input('code') !== null && strlen(trim((request()->input('code')))) > 0) {
            $records->where("bonlivs.code", '=', request()->input('code'));
        }
        if (request()->input('employe') !== null && is_numeric(request()->input('employe'))) {
            $records->where("bonlivs.livreur", '=', request()->input('employe'));
        }

        if (request()->input('statut') !== null && is_numeric(request()->input('statut'))) {
            $records->where("bonlivs.statut", '=', request()->input('statut'));
        }

        if (request()->input('from') !== null && strlen(trim((request()->input('from')))) > 0) {
            $records->whereDate("bonlivs.created_at", '>=', request()->input('from'));
        } else {
            $records->whereDate("bonlivs.created_at", '>=', $now->subWeek());
        }

        if (request()->input('to') !== null && strlen(trim((request()->input('to')))) > 0) {
            $records->whereDate("bonlivs.created_at", '<=', request()->input('to'));
        }

        if (auth()->user()->role == '2') {
            $records->where('livreur',  auth()->user()->EmployeDetail->id);
        } elseif (auth()->user()->role == '5' || auth()->user()->role == '7' || auth()->user()->role == '8') {

            $records->whereIn('employes.agence', \Auth::user()::getUserVilles());
        }

        return  $records->get();
    }

    public function employeDetail()
    {
        return $this->belongsTo(\App\Models\Employe::class, 'livreur');
    }
    public function typeblDetail()
    {
        return $this->belongsTo(\App\Models\Typebl::class, 'type');
    }
    public function relatedColis()
    {
        return $this->belongsToMany(Expedition::class, 'bls_colis', 'bl_id', 'colis_id');
    }

    public function agenceDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'id_agence');
    }


    public function relatedColis_history()
    {
        return $this->hasMany(etapeHistory::class, 'bl_id');
    }


    public function ExpeditionDetail()
    {
        return $this->hasMany(Expedition::class, 'bl');
    }



    public static function print($bon)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        if ($bon->type == 1) {
            $titre = 'BON DE LIVRAISON';
        } else {
            $titre = 'BON DE RETOUR';
        }

        $pdf::setHeaderCallback(function ($pdf) use ($titre) {

            $header = '
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                        <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                        </td>
                        <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                            <b>' . $titre . '</b>
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

        $total_colis = 0;
        $total_fond = 0;
        $total_fond_stk = 0;
        $total_fond_lvr = 0;
        foreach ($bon->relatedColis_history->sortByDesc('created_at')->unique('expedition') as $exp) {

            if ($exp->etape == '14') {
                $total_fond_lvr = $total_fond_lvr + $exp->fond;
            } else {
                $total_fond_stk =  $total_fond_stk + $exp->fond;
            }
            $total_colis = $total_colis + $exp->expeditionDetail->colis;
        }

        $total_fond = $total_fond_lvr + $total_fond_stk;

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

                    <td width="10%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>N° Bon </b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Livreur</b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Crée le</b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Fermé le</b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Agence</b>
                    </td>
                </tr>
                <tr>
                    <td style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  ><b>' . $bon->code . '</b>
                    </td>
                    <td style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  ><b>' . $bon->employeDetail->libelle . '</b>
                    </td>
                    <td style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  ><b>' . $bon->created_at . '</b>
                    </td>
                    <td style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  ><b>' . $bon->closed_at . '</b>
                    </td>
                    <td style="height:18px !important;  font-size: 9px !important;text-align:left !important;border:0,2px solid !important;"  ><b>' . $bon->relatedColis->first()->agenceDetail->libelle . '</b>
                    </td>
                </tr>
            </table>
            <br><br><table style="width:100% !important;  height:100% !important; " cellpadding="2">
                <tr>
                    <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;"  bgcolor="#e2e2e2">
                        <b>N° Expéd.</b>
                    </td>
                    <td width="10%" style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" bgcolor="#e2e2e2"><b>Date Exp</b>
                    </td>
                    <td width="10%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Expéditeur</b></td>

                    <td width="8%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Origine</b>
                    </td>
                    <td width="9%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destination</b>
                    </td>
                    <td width="9%" style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destinataire</b>
                    </td>

                    <td width="7%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Téléphone</b>
                    </td>
                    <td width="17%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Adresse</b>
                    </td>
                    <td width="3%" style="height:18px !important; text-align:center !important; font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Colis</b>
                    </td>
                    <td width="4%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Fond</b></td>
                    <td width="9%" style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Sign. Client</b></td>
                </tr>';

        foreach ($bon->relatedColis_history->sortByDesc('created_at')->unique('expedition') as $exp) {

            $type_exp = '';
            if ($exp->etape == '14') {
                $type_exp = ' LVR ';
                $color = '#68f461';
            } elseif ($exp->etape == '20') {
                $type_exp = 'N.LVR';
                $color = '#e4c704';
            } else {
                $type_exp = '';
                $color = '#fff';
            }

            $html .= '<tr>
                    <td style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" >' . $exp->expeditionDetail->num_expedition . ' <b bgcolor="' . $color . '"> ' . $type_exp . ' </b></td>
                    <td style="height:18px !important; font-size: 7px !important;text-align:left !important;border:0,2px solid !important;" >' . $exp->expeditionDetail->created_at . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  >' . $exp->expeditionDetail->clientDetail->libelle . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important;border:0,2px solid !important;"  >' . $exp->agenceDetail->libelle . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  >' . $exp->agenceDesDetail->libelle . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  >' . $exp->expeditionDetail->destinataire . '
                    </td>
                    <td style="height:18px !important;  font-size: 7px !important; border:0,2px solid !important;"  >' . $exp->expeditionDetail->telephone . '
                    </td>
                    <td  style="height:18px !important; text-align: left !important; font-size: 7px !important; border:0,2px solid !important;"  >' . $exp->expeditionDetail->adresse_destinataire . '
                    </td>
                    <td style="height:18px !important;text-align: center !important;  font-size: 7px !important; border:0,2px solid !important;" >' . $exp->expeditionDetail->colis . '</td>

                    <td style="height:18px !important;text-align: right !important;  font-size: 7px !important; border:0,2px solid !important;" >' . $exp->fond . '</td>
                    <td style="height:18px !important;text-align: center !important;  font-size: 7px !important; border:0,2px solid !important;" ></td>
                </tr>';
        }
        $html .= '</table>
            <br><br><table style="width:100% !important;  height:100% !important; " cellpadding="2">
                <tr>


                    <td width="11%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Total expéditions</b>
                    </td>
                    <td width="11%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Total colis</b>
                    </td>
                    <td width="11%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Total Fonds</b>
                    </td>
                    <td width="11%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Tot. Non Livré</b>
                    </td>
                    <td width="11%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Net Livré</b>
                    </td>
                    <td width="15%" style="height:18px !important; font-size: 9px !important;text-align:center !important;">
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>VISA AGENCE </b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>VISA LIVREUR</b>
                    </td>
                </tr>
                <tr>

                    <td  style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  ><b>' . $bon->relatedColis_history->sortByDesc('created_at')->unique('expedition')->count() . '</b>
                    </td>
                    <td  style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  ><b>' . $total_colis . '</b>
                    </td>
                    <td  style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  ><b>' . number_format($total_fond, 2) . ' Dhs</b>
                    </td>
                    <td  style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  ><b>' . number_format($total_fond_stk, 2) . ' Dhs</b>
                    </td>
                    <td  style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  ><b>' . number_format($total_fond_lvr, 2) . ' Dhs</b>
                    </td>
                    <td  style="height:18px !important;  font-size: 9px !important;text-align:center !important;"  >
                    </td>
                    <td  style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  >
                    </td>
                    <td  style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  >
                    </td>
                </tr>
            </table>
        ';

        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output('Situation du stock.pdf', 'I');
    }
}
