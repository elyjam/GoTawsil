<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
class Client extends Model
{
    use Notifiable;
    protected $table = 'clients';
    protected $guarded = [];

    public function envoi_count()
    {
        $star = Carbon::now()->format('Y-m-01 00:00:00');
        $end =  carbon::now()->format('Y-m-d H:i:s');
        return Expedition::where('client', $this->id)->where('sens', "Envoi")
                                                    ->where('created_at', '>=', $star)
                                                    ->where('created_at', '<=', $end)
                                                    ->where('deleted', "0")->count();
    }


    public function ecom_count()
    {
        $star = Carbon::now()->format('Y-m-01 00:00:00');
        $end =  carbon::now()->format('Y-m-d H:i:s');
        return Expedition::where('client', $this->id)->where('created_at', '>=', $star)
                                                    ->where('created_at', '<=', $end)
                                                    ->where('type', "ECOM")
                                                    ->where('sens', "Envoi")
                                                    ->where('deleted', "0")->count();
    }

    public function cdp_count()
    {
        $star = Carbon::now()->format('Y-m-01 00:00:00');
        $end =  carbon::now()->format('Y-m-d H:i:s');
        return Expedition::where('client', $this->id)->where('type', "CDP")
                                                    ->where('created_at', '>=', $star)
                                                    ->where('created_at', '<=', $end)
                                                    ->where('sens', "Envoi")
                                                    ->where('deleted', "0")->count();
    }


    public function retour_count()
    {
        $star = Carbon::now()->format('Y-m-01 00:00:00');
        $end =  carbon::now()->format('Y-m-d H:i:s');
        return Expedition::where('client', $this->id)->where('sens', "Retour")->where('deleted', "0")->count();
    }

    public function exp_count()
    {
        $star = Carbon::now()->format('Y-m-01 00:00:00');
        $end =  carbon::now()->format('Y-m-d H:i:s');
        return Expedition::where('client', $this->id)->where('deleted', "0")->count();
    }




    public static function fetchAll()
    {
        return self::all()->where('deleted', "0");
    }

    public static function available_client(){
        $client_used = \App\User::all()->where('client','!=',null)->pluck('client');

        return self::all()->where('deleted', "0")->whereNotIn('id',$client_used);
    }


    public static function getNewClients()
    {
        return  \DB::table("clients")
            ->select(
                "*",
                \DB::raw('clients.id as id'),
                \DB::raw('clients.libelle as libelle'),
                \DB::raw('clients.code as code_client'),
                \DB::raw('villes.libelle as ville_label'),
                \DB::raw('users.id as user_id')
            )
            ->leftJoin('users', 'clients.id', '=', 'users.client')
            ->leftJoin('villes', 'villes.id', '=', 'clients.ville')
            ->where("clients.deleted", 0)
            ->where("users.validated", 0)->get();
    }

    public function expeditionDetail()
    {
        return $this->hasMany(\App\Models\Expedition::class, 'client');
    }

    public function getExpeditionDateOne($start)
    {
        return $this->expeditionDetail()->whereDate("created_at", $start);
    }
    public function getExpeditionDateTow($start, $end)
    {
        return $this->expeditionDetail()->whereBetween("created_at", [$start, $end]);
    }

    public function expeditionLivree()
    {
        return $this->expeditionDetail()->where('etape', '14')->where('deleted', '0');
    }
    public function expeditionTransfert()
    {
        return $this->expeditionDetail()->where('etape', '!=', '10');
    }
    public function expeditionBonNull()
    {
        return $this->expeditionDetail()->where('id_bon', Null)->where('etape', '=', '1')->where('deleted', '0');
    }

    public function villeDetail()
    {
        return $this->belongsTo(\App\Models\Agence::class, 'agence');
    }


    public function agenceDetail()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'agence');
    }


    public function typeclient()
    {
        if ($this->type_client == 1) {
            return 'Professionnel';
        } elseif ($this->type_client == 2) {
            return 'Personne physique';
        }
    }

    public function getstatut()
    {
        if ($this->statut == 1) {
            return 'Actif';
        } elseif ($this->statut == 2) {
            return 'Inactif';
        }
    }

    public function userDetail()
    {
        return $this->belongsTo(\App\User::class, 'user');
    }
    public function ComercialDetail()
    {
        return $this->belongsTo(\App\Models\Employe::class, 'commerciale');
    }
    public function categoriesclientDetail()
    {
        return $this->belongsTo(\App\Models\Categoriesclient::class, 'categorie');
    }
    public function banqueDetail()
    {
        return $this->belongsTo(\App\Models\Banque::class, 'banque');
    }

    public static function clientsRemboursement($date)
    {
        return  \DB::table("expeditions")
            ->select(
                \DB::raw('DISTINCT (clients.libelle) as libelle, clients.id')
            )
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
            ->leftJoin('caisses', 'caisses.id', '=', 'expeditions.caisse_id')
            ->where("expeditions.etape", 7)
            ->whereRaw("caisses.statut = 4 or ( caisses.statut = 3 and clients.remboursement_rapide = 'Oui')")
            ->whereDate("expeditions.created_at", '<=', $date)
            ->get();
    }


    public static function client_Remboursements($date,$id)
    {
        return  \DB::table("expeditions")
            ->select(
                \DB::raw('*')
            )
            ->where("expeditions.client", $id)
            ->where("expeditions.etape", 7)
            ->leftJoin('clients', 'clients.id', '=', 'expeditions.client')
            ->leftJoin('caisses', 'caisses.id', '=', 'expeditions.caisse_id')

            ->whereRaw("caisses.statut = 4 or ( caisses.statut = 3 and clients.remboursement_rapide = 'Oui')")
            ->whereDate("expeditions.created_at", '<=', $date)
            ->get();
    }





    public function client_total_remb($star, $end)
    {
        $Exps_remb =  Expedition::getExpeditionsByAllRemboursement();

        $Exps_remb = $Exps_remb->where("created_at", '>=', $star)
            ->where("created_at", '<=', $end)
            ->where('client_id', $this->id);
            $netsTotal = 0;


            foreach ($Exps_remb as $expedition) {
                $cheque = isset($cheques[$expedition->expedition_id]) ? $cheques[$expedition->expedition_id] : 0;
                $chequeVal = $cheque > 0 ? Util::moneyFormat($cheque) : '';
                $net = $expedition->fond - $expedition->ttc - $cheque;
                $netsTotal += $net;
            }
        return $netsTotal;
    }

    public function total_remb($date)
    {


        $Exps_remb =  $this::client_Remboursements($date,$this->id);

            $netsTotal = 0;

            foreach ($Exps_remb as $expedition) {

                $cheque = isset($cheques[$expedition->id]) ? $cheques[$expedition->id] : 0;
                $chequeVal = $cheque > 0 ? Util::moneyFormat($cheque) : '';
                if($expedition->port == 'PP' || $this->factureMois == 'Oui'){
                    $net = $expedition->fond - $cheque;
                }else{
                    $net = $expedition->fond - $expedition->ttc - $cheque;
                }


                $netsTotal += $net;
            }
        return $netsTotal;
    }

    public static function clientsFacture($date, $type)
    {
        $clients = \DB::table("remboursements_paiements")
            ->select(
                \DB::raw('DISTINCT (clients.libelle) as libelle, clients.id, factureMois')
            )
            ->leftJoin('clients', 'clients.id', '=', 'remboursements_paiements.client')
            ->whereNull('remboursements_paiements.facture')
            ->whereDate("remboursements_paiements.created_at", '<=', $date);
        if ($type == 1) {
            $clients->where('factureMois', 'Oui');
        } else {
            $clients->where(function ($clients) {
                $clients->where('factureMois', '!=', 'Oui');
                $clients->orWhereNull('factureMois');
            });
        }
        return $clients->get();
    }

    public function ClientUserDetail()
    {
        return $this->hasOne(\App\User::class, 'client');
    }
    public static function print($client)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) use ($client) {
            $pdf->writeHTML('
            <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                <tr >
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="25%">
                    <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                    </td>
                    <td style="height:25px !important; font-size: 18px !important;text-align:right !important;" width="75%">
                        <b>FICHE CLIENT</b>
                    </td>

                </tr>
            </table>
            <hr>', true, false, true, false, '');
            $pdf->writeHTML("", true, false, false, false, '');
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
        $montant = \App\Models\Util::ChiffreEnLettre("37500");

        $color = '';
        if ($client->statut == '1') {
            $color = '#249818';
        } elseif ($client->statut == '2') {
            $color = '#c81537';
        }
        // Set some content to print
        $html = ' <table style="width:100% !important;  height:100% !important; " cellpadding="5">
        <tr >
            <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="100%"><b>Etats du compte :<span color="' . $color . '"> ' . $client->getstatut() . '</span></b>
            </td>

        </tr>
    </table><br>
        <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                  <tr>
                  <td width="100%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>INFORMATIONS GÉNÉRAL
                  </b>
                  </td>                </tr>

              </table>
              <br>
          <table style="width:100% !important;  height:100% !important; " cellpadding="5">

                  <tr >

                      <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb" ><b>Client</b>
                      </td>
                      <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important;" >' . $client->libelle . '
                      </td>
                      <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb"><b>CIN</b>
                      </td>
                      <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important;" >' . $client->cin . '
                      </td>
                  </tr>


                  <tr style="" >

                      <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important; border-color:black !important;border-left-color:black !important;" bgcolor="#d3edfb"><b>Email</b>
                      </td>
                      <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important; " >' . $client->email . '
                      </td>
                      <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important; border-color:black !important;border-left-color:black !important;" bgcolor="#d3edfb"><b>Téléphone</b>
                      </td>
                      <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important; " >' . $client->telephone . '
                      </td>
                  </tr>
                  <tr>
                      <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important; border-color:black !important;border-left-color:black !important;" bgcolor="#d3edfb"><b>Ville</b>
                      </td>
                      <td width="45%" style="height:25px !important;  font-size: 10px !important; border:none !important; " >' . $client->agenceDetail->libelle . '
                      </td>
                  </tr>
                  <tr >

                  <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb"><b>Adresse</b>
                  </td>
                  <td width="85%" style="height:25px !important;  font-size: 10px !important; border:none !important;" >' . $client->adresse . '
                  </td>
              </tr>



              </table>
              <br><br>
              <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr>
                        <td width="100%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>INFORMATIONS COMPTE
                        </b>
                        </td>                </tr>

                    </table>
                    <br>
                <table style="width:100% !important;  height:100% !important; " cellpadding="5">

                        <tr >

                            <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb" ><b>Inscris le</b>
                            </td>
                            <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important;" >' . $client->created_at . '
                            </td>

                        </tr>


                        <tr style="" >

                        <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb"><b>Activation</b>
                        </td>
                        <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important;" >' . @$client->userDetail->activated_at . '
                        </td>
                            <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important; border-color:black !important;border-left-color:black !important;" bgcolor="#d3edfb"><b>Validation B.O </b>
                            </td>
                            <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important; " >' . @$client->userDetail->validated_at . '
                            </td>
                        </tr>
                        <tr>
                            <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important; border-color:black !important;border-left-color:black !important;" bgcolor="#d3edfb"><b>Type client</b>
                            </td>
                            <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important; " >' . $client->typeclient() . '
                            </td>
                            <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important; border-color:black !important;border-left-color:black !important;" bgcolor="#d3edfb"><b>Catégorie</b>
                            </td>
                            <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important; " >' . @$client->categoriesclientDetail->libelle . '
                            </td>
                        </tr>

                    </table>
                    <br><br>
                    <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr>
                        <td width="100%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>INFORMATIONS REMBOURSEMENT
                        </b>
                        </td>                </tr>

                    </table>
                    <br>

                    <table style="width:100% !important;  height:100% !important; " cellpadding="5">



                        <tr style="" >

                        <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb"><b>Type Remb.</b>
                        </td>
                        <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important;" >' . $client->type_remboursement . '
                        </td>
                            <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important; border-color:black !important;border-left-color:black !important;" bgcolor="#d3edfb"><b>RIB</b>
                            </td>
                            <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important; " >' . $client->rib . '
                            </td>
                        </tr>


                    </table>
                    <br><br>
                    <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                        <tr>
                        <td width="100%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>INFORMATIONS REMBOURSEMENT
                        </b>
                        </td>                </tr>

                    </table>
                    <br>

                    <table style="width:100% !important;  height:100% !important; " cellpadding="5">



                        <tr style="" >

                        <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important;" bgcolor="#d3edfb"><b>RC</b>
                        </td>
                        <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important;" >' . $client->rc_org . '
                        </td>
                            <td width="15%" style="height:25px !important;  font-size: 10px !important;border:0,2px solid !important; border-color:black !important;border-left-color:black !important;" bgcolor="#d3edfb"><b>ICE</b>
                            </td>
                            <td width="35%" style="height:25px !important;  font-size: 10px !important; border:none !important; " >' . $client->ice_org . '
                            </td>
                        </tr>


                    </table>

          ';
        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);


        $pdf::Output('Fiche client.pdf', 'I');
    }


    public static function print_forcer($client)
    {
        $pdf = new \PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $record = Expedition::all()->where('client', $client->id)->where('id_bon', Null)->where('etape', '=', '1')->where('deleted', '0');


        $exp_table = '';
        $total_colis = 0;
        $total_fond = 0;

        foreach ($record as $exp) {
            $total_colis = $total_colis + $exp->colis;
            $total_fond = $total_fond + $exp->fond;
        }

        foreach ($record as $exp) {
            $exp_table = $exp_table . ' <tr>
            <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >' . $exp->num_expedition . '
            </td>
            <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" >' . $exp->created_at . '
            </td>

            <td style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;"  >' . $exp->agenceDetail->libelle . '
            </td>
            <td style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;"  >' . $exp->destinataire  . '
            </td>

            <td style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;"  >' . $exp->agenceDesDetail->libelle  . '</td>
            <td style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;"  >' . $exp->telephone . '
            </td>
            <td style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;"  >' . $exp->colis  . '</td>
            <td  style="height:18px !important; text-align: right !important; font-size: 8px !important; border:0,2px solid !important;"  >
            ' . $exp->fond  . '
            </td>

        </tr>';
        }


        $pdf::setHeaderCallback(function ($pdf) use ($client) {
            $pdf->writeHTML('
            <table style="width:100% !important;  height:100% !important; " cellpadding="5">
                <tr >
                    <td style="height:25px !important; font-size: 10px !important;text-align:left !important;" width="20%">
                    <img src="/assets/front/logo-hori.png" height="30px"  width="140px" style="padding: 5px !important;" >
                    </td>
                    <td style="height:25px !important; font-size: 12px !important;text-align:center !important;" width="65%">
                        <b>INSTANCE DE RAMASSAGE</b>
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
                    ' . $client->libelle . '
                    </td>
                </tr>
            </table>', true, false, true, false, '');
            $pdf->writeHTML("", true, false, false, false, '');
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
        $montant = \App\Models\Util::ChiffreEnLettre("37500");

        // Set some content to print
        $html = '
        <table style="width:100% !important;  height:100% !important; " cellpadding="2">
        <tr>
        <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" width="9%" bgcolor="#e2e2e2"><b>N° Expéd.</b>
        </td>
        <td style="height:18px !important; font-size: 8px !important;text-align:left !important;border:0,2px solid !important;" width="9%" bgcolor="#e2e2e2">
        <b>Date</b>
        </td>
        <td width="15%" style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Origine</b></td>
        <td width="22%" style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destinataire</b></td>
        <td width="15%" style="height:18px !important;  font-size: 8px !important;border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Destination</b></td>
        <td width="17%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Telephone</b>
        </td>
        <td width="5%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" ><b>Colis</b>
        </td>
        <td width="8%" style="height:18px !important;  font-size: 8px !important; border:0,2px solid !important;" bgcolor="#e2e2e2" >
        <b>Montant</b>
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
                </tr>
                <tr>
                    <td width="20%" style="height:18px !important; font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" ><b>' . $record->count() . '</b>
                    </td>
                    <td width="15%" style="height:18px !important; font-size: 9px !important;text-align:center !important;border:0,2px solid !important;" ><b>' . $total_colis . '</b>
                    </td>
                    <td width="15%" style="height:18px !important;  font-size: 9px !important;text-align:center !important;border:0,2px solid !important;"  ><b>' . number_format($total_fond, 2) . ' Dhs</b>
                    </td>


                </tr>
            </table>

          ';
        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);


        $pdf::Output('Fiche client.pdf', 'I');
    }
}
