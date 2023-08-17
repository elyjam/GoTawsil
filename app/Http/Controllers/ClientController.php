<?php

namespace App\Http\Controllers;

use App\User;
use DataTables;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\BonAncien;
use App\Models\Expedition;
use Illuminate\Http\Request;
use App\Models\FactureAncien;
use Illuminate\Support\Str;
use App\Models\CaissesCheques;
use App\Models\ExpeditionAncien;
use Illuminate\Support\Facades\DB;
use App\Models\RemboursementAncien;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\RemboursementPaiementsAncien;



class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }




    protected function getRules()
    {
        return [];
    }

    public function list(Request $request)
    {
        // dd(Client::all()->count());
        $request->flash();


        if($request->isMethod('post')){
            $records =  DB::table('clients')
            ->select(
                "*",
                \DB::raw('clients.email as email'),
                \DB::raw('clients.id as clientId'),
                \DB::raw('villes.libelle as ville'),
                \DB::raw('clients.libelle as libelle'),
                )
            ->leftJoin('users', 'users.client', '=', 'clients.id')
            ->leftJoin('villes', 'villes.id', '=', 'clients.ville')
            ->get();
            $spreadsheet = IOFactory::load(storage_path('export/clients.xlsx'));
            $i = 2;

            foreach ($records as $record) {
                if($record->validated == 1){
                    $statut = 'Actif';
                }else{
                    $statut = 'Inactif';
                }
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("A" . $i, $record->libelle);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("B" . $i, $record->adresse);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("C" . $i, $record->telephone);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("D" . $i, $statut);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("E" . $i, $record->email);

                $i++;
            }

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="clients.xlsx"');

            $writer->save('php://output');
            exit();
        }

        $request->flash();
        // dd(\App\Models\Client::all()->where('deleted', '0')->last());
        $viewsData = [];
        $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
        $viewsData['agenceRecords'] = \App\Models\Ville::all()->where('deleted', '0')->where('id', "!=",2);
        $viewsData['statutRecords'] = \App\Models\Statut::all()->where('deleted', '0')->where('code', 'EXPEDITION');
        $viewsData['commentRecords'] = \App\Models\Statut::all()->where('deleted', '0')
            ->where('code', 'ETAPE_EXPEDITION');
        // ->whereIn('key', ['1', '2', '3', '4', '5', '7', '8', '10']);

        return view('back/client/list', $viewsData);

    }

    public function apiClient(Request $request){

        $formData = array();
        parse_str($request->all()['form'], $formData);

        $query =  DB::table('clients')
        ->select(
            "*",
            \DB::raw('clients.email as email'),
            \DB::raw('clients.id as clientId'),
            \DB::raw('clients.code as code_client'),
            \DB::raw('villes.libelle as ville'),
            \DB::raw('clients.libelle as libelle'),
            \DB::raw('users.validated as validated'),
            )
        ->leftJoin('users', 'users.client', '=', 'clients.id')
        ->leftJoin('villes', 'villes.id', '=', 'clients.ville');

        return Datatables::of($query)->addIndexColumn()
           ->addColumn('action', function ($record) {

                    return '
                    <a href="'.route('client_print', ['client' => $record->clientId]).'"
                    target="_blank"><i class="material-icons tooltipped"
                        data-position="top"
                        data-tooltip="Fiche client">picture_as_pdf</i></a>

                        <a href="'.route('client_update', ['client'=>$record->clientId]).'"><i
                        class="material-icons tooltipped" data-position="top"
                        data-tooltip="Modifier">edit</i></a>

                        <a href="#!" onclick="openSuppModal('.$record->clientId.')"><i
                        class="material-icons tooltipped" style="color: #c10027;"
                        data-position="top" data-tooltip="Supprimer">delete</i></a>


                                        ';

            })
            ->addColumn('statut', function ($record) {
                if($record->validated == 1){
                    return '     <td class="hide-on-small-only"> Actif </td> ' ;
                }else{
                    return '     <td class="hide-on-small-only"> Inactif </td> ' ;
                }

        })
            ->rawColumns(['action','statut'])
            ->make(true);
    }

    public function new(Request $request)
    {


        $request->flash();
        return view(
            'back/client/new',
            [
                'records' => Client::getNewClients()
            ]
        );
    }

    public function create(Request $request)
    {

        if ($request->isMethod('post')) {
            $rules = [
                'valeur_declaree' => 'required|numeric|between:0,100',
                'libelle' => 'required',
                'cin' => 'required',
                'telephone' => 'required|digits:10|numeric',
                'rib' => 'required|digits:24|numeric',
                'email' => 'required|email',
                'agence' => 'required',
                // 'commerciale' => 'required',

            ];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }else {

                $code =  'C' . sprintf("%06d", Client::all()->count() + 1);
                Client::create(array_merge($request->all(), [
                    'code' => $code
                ]));
                Redirect::to(route('client_list'))->send();
            }
        }
        $viewsData = [];

        $viewsData['villeRecords'] = \App\Models\Ville::all()->where('deleted', '0')->where('id', "!=", 2);
        $viewsData['agenceRecords'] = \App\Models\Agence::all()->where('deleted', '0');
        $viewsData['CommercialRecords'] = \App\Models\Employe::all()->where('deleted', '0')->where('statut',1);
        $viewsData['categoriesclientRecords'] = \App\Models\Categoriesclient::all()->where('deleted', '0');
        $viewsData['banqueRecords'] = \App\Models\Banque::all()->where('deleted', '0');

        return view('back/client/create', $viewsData);
    }

    public function update(Client $client, Request $request)
    {

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [
                'valeur_declaree' => 'required|numeric|between:0,100',
                'cin' => 'required',
                'telephone' => 'required|digits:10|numeric',
                'rib' => 'required|digits:24|numeric',
                'email' => 'required|email',
                'agence' => 'required',
                // 'commerciale' => 'required',

            ];
            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {

                $port = 'PD';
                if ($request->factureMois == 'Oui') {
                    $port = 'PPE';
                } elseif ($request->colisSimple == 'Oui') {
                    if ($request->ppSimple == 'PP') {
                        $port = 'PP';
                    } elseif ($request->ppSimple == 'PPNE') {
                        $port = 'PPNE';
                    }
                } elseif ($request->colisSimple == 'Non') {
                    $port = 'PD';
                }

                $client->update(array_merge($request->all(), ['port' => $port]));
                if($request->statut == 1){
                    $user = \App\user::where('client',$client->id)->first()->update(['validated' => 1]);
                }elseif($request->statut == 0){
                    $user = \App\user::where('client',$client->id)->first()->update(['validated' => 0]);
                }
                Redirect::to(route('client_list'))->send();


            }
        }
        $viewsData['record'] = $client;
        $viewsData['villeRecords'] = \App\Models\Ville::all()->where('deleted', '0')->where('id', "!=",2);
        $viewsData['agenceRecords'] = \App\Models\Agence::all()->where('deleted', '0');
        $viewsData['CommercialRecords'] = \App\Models\Employe::all()->where('deleted', '0')->where('statut',1);
        $viewsData['categoriesclientRecords'] = \App\Models\Categoriesclient::all()->where('deleted', '0');
        $viewsData['banqueRecords'] = \App\Models\Banque::all()->where('deleted', '0');

        return view('back/client/update', $viewsData);
    }

    public function delete(Client $client)
    {
        $client->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }
    public function remove(Client $client)
    {
        $client->delete();
        $user = User::where('client', $client->id)->first();
        $user->delete();
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

    public function activate(User $user)
    {
        $user->update(
            [
                'confirmation_token' => null, 'activated_at' => date("Y-m-d H:i:s"), 'activated_by' => \Auth::user()->id, 'validated' => 1, 'validated_at' => date("Y-m-d H:i:s"), 'validated_by' => \Auth::user()->id
            ]
        );
        return redirect()->back()->with('success', "L'enregistrement a été activé avec succès");
    }

    public function validat(User $user)
    {
        $user->update(
            [
                'validated' => 1, 'validated_at' => date("Y-m-d H:i:s"), 'validated_by' => \Auth::user()->id
            ]
        );
        return redirect()->back()->with('success', "L'enregistrement a été activé avec succès");
    }

    public function print(Client $client)
    {
        Client::print($client);
    }

    public function print_forcer(Client $client)
    {
        Client::print_forcer($client);
    }


    public function ancien_exps(Request $request){
        if ($request->isMethod('post')) {
            $request->flash();
            $star = Carbon::parse($request->start_date)->format("Y-m-d 00:00:00");
            $end = Carbon::parse($request->end_date)->format("Y-m-d 23:59:59");

            $records = ExpeditionAncien::where('deleted', "0")
                ->where('client', auth()->user()->ClientDetail->id)
                ->get();
            if ($request->start_date != null) {
                $records = $records->where("created_at", '>=', $star);
            }
            if ($request->end_date != null) {
                $records = $records->where("created_at", '<=', $end);
            }
            if ($request->Destination != null) {
                $records = $records->where("agence_des", $request->Destination);
            }
            if ($request->code != null) {
                $records = $records->where("num_expedition", $request->code);
            };
            if ($request->statuts != null) {
                $records = $records->where("etape", $request->statuts);
            };
            $agences =  \App\Models\Agence::where('deleted', '0')->where('id', "!=",152)->get();
            $statuts = \App\Models\Statut::where('code', 'ETAPE_EXPEDITION')->whereNotIn('key', [13, 15, 17, 18])->get();
            return view('client/historique/expeditions', [
                'records' => $records,
                'agences' => $agences,
                'statuts' => $statuts
            ]);
        }
        $request->flash();

        $records = ExpeditionAncien::where('deleted', "0")->where('client', auth()->user()->ClientDetail->id)->get();

        $agences = \App\Models\Agence::where('deleted', '0')->where('id', "!=",152)->get();
        $statuts = \App\Models\Statut::where('code', 'ETAPE_EXPEDITION')->whereNotIn('key', [13, 15, 17, 18])->get();
        return view('client/historique/expeditions', [
            'records' => $records,
            'agences' => $agences,
            'statuts' => $statuts
        ]);
    }

    public function ancien_bons()
    {
        $bons = \App\Models\BonAncien::where('type', 'RAMASSAGE')->where('id_client', auth()->user()->ClientDetail->id)->where('deleted', 0)->get();

        return view('client/historique/bons', [
            'records' => $bons
        ]);
    }

    public function ancien_factures(){

        $viewsData['clientRecords'] = \App\Models\Client::all()->where('deleted', '0');
        $viewsData['statutRecords'] = \App\Models\Statut::all()->where('deleted', '0')->where('code', 'FACTURE');
        $viewsData['records'] = \App\Models\FactureAncien::all()->where('client',Auth()->user()->ClientDetail->id);
        return view('client/historique/factures' ,$viewsData);
    }

    public static function ancien_exp_pdf(ExpeditionAncien $expedition)
    {
        $pdf = new \PDF('P', 'mm', 'A4');
        $pdf::SetTitle('');
        // set margins
        $pdf::SetMargins(2, 2, 2, true);
        $pdf::SetPrintHeader(false);
        $pdf::SetPrintFooter(false);
        $pdf::SetAutoPageBreak(TRUE, 0);


        $qrcode = '<img  src="@' . base64_encode(QrCode::format('png')->size(100)->generate($expedition->num_expedition)) . '" width="77px">';

        // $qrcode = '';

        $pdf::AddPage('L', 'A6');


        $header = '<br><br>
        <table style="width:100% !important;border:1px solid   !important; height:100% !important; " cellpadding="5">
            <tr >
                <td style="height:50px !important; font-size: 10px !important;" >
                 <img src="/assets/front/logo-hori.png" height="40px"  width="180px" style="padding: 5px !important;" >
                </td>

                <td style="height:50px !important; padding=10px !important; text-align:center !important; font-size: 11px !important;" width="50%">
                <br><br> <b> ' . $expedition->agenceDesDetail->libelle . ' </b> <br>
                </td>
            </tr>
        </table>

        <table style="width:100% !important;border-left:1px solid !important; border-right:1px solid !important; height:100% !important; " cellpadding="5">
            <tr >
                <td style="height:20px !important; font-size: 10px !important;text-align:left !important;" width="20%"><b>Expéditeur </b>
                </td>

                <td width="30%" style="height:20px !important;  font-size: 10px !important;" >' . $expedition->clientDetail->libelle . '
                </td>

                <td style="height:20px !important; font-size: 10px !important;text-align:left !important;" width="20%"><b>Téléphone </b>
                </td>

                <td width="30%" style="height:20px !important;  font-size: 10px !important;" >' . $expedition->telephone . '
                </td>
            </tr>


            <tr >
            <td style=" border-bottom-color:#d8d8d8; height:20px !important; font-size: 10px !important; border-bottom: 0.2px red  !important;border-collapse: collapse !important;" width="20%"><b>Destinataire </b>
        </td>

                <td width="80%" style=" border-bottom-color:#d8d8d8; height:20px !important; font-size: 10px !important; border-bottom: 0.2px red  !important;border-collapse: collapse !important;" >' . $expedition->destinataire . ' - ' . $expedition->adresse_destinataire . '
                </td>
            </tr>
        </table>

        <table style="width:100% !important;border-left:1px solid !important; border-right:1px solid !important;  border-top-color:#d8d8d8;border-top: 0.2px red  !important;" cellpadding="5">
            <tr >
                <td style="height:20px !important; font-size: 10px !important;" width="50%" >

    <table style="width:100% !important; border-color:#d8d8d8; border:0.1px solid !important; height:100% !important; " cellpadding="5">
    <tr >
        <td style="height:25px !important; font-size: 10px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important;" width="50%"><b>N° Expédition </b>
        </td>

        <td width="50%" style="height:25px !important;  font-size: 10px !important;" >' . $expedition->num_expedition . '
        </td>
    </tr>

    <tr >
        <td style="height:25px !important; font-size: 10px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" width="50%"><b>Montant </b>
        </td>

        <td width="50%" style="height:25px !important;  font-size: 10px !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" >' . number_format($expedition->fond, 2) . ' DH
        </td>
    </tr>

    <tr >
        <td style="height:25px !important; font-size: 8px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" width="50%"><b>Ouverture de colis</b>
        </td>

        <td width="50%" style="height:25px !important;  font-size: 10px !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" >' . $expedition->ouvertureColis . '
        </td>
    </tr>

    <tr >
    <td style="height:25px !important; font-size: 8px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" width="50%"><b>Paiement par chèque</b>
    </td>

    <td width="50%" style="height:25px !important;  font-size: 10px !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" >' . $expedition->paiementCheque . '
    </td>
</tr>

    </table>
                </td>
                <td style="height:20px!important; text-align:center !important; border-bottom-color:#d8d8d8; border-left:0.2px solid !important;"  width="25%">
                <br><br>' . $qrcode . '<br>
                </td>
                <td style="height:20px !important; border-bottom-color:#d8d8d8; border-left:0.2px solid !important;"  width="25%">




                <table style="width:100% !important; border-color:#d8d8d8; border:0.1px solid !important; height:100% !important; " cellpadding="5">
                <tr >
                    <td style="height:25px !important; font-size: 10px !important;text-align:center !important; border-right-color:#d8d8d8; border-right:0.1px solid !important;" width="100%"><b>Indication</b>
                    </td>
                </tr>
                </table> <h5>
                ' . Str::limit($expedition->Indication, 40) . '</h5>
                </td>

            </tr>
        </table>

        <table style="width:100% !important;border:1px solid   !important; height:100% !important; " cellpadding="5">
            <tr >
            <td style="height:20px !important; font-size: 10px !important;" width="80%"><b>' . $expedition->agenceDetail->libelle . ' le : ' . $expedition->created_at . '</b>
            </td>
                <td style="height:100% !important; padding=10px !important; text-align:right !important; font-size: 10px !important;" width="20%"> <b>Colis : ' . $expedition->colis . '/1 </b>
                </td>
            </tr>
        </table>
    ';
        $pdf::WriteHTML($header, true, 0, true, 0);

        $pdf::Output("Etiquette.pdf");
    }

    public function ancien_bon_pdf(BonAncien $bon)
    {
        BonAncien::printDetail($bon);
    }
    public static function ancien_bon_exp(BonAncien $bon)
    {
        $pdf = new \PDF('P', 'mm', 'A4');
        $pdf::SetTitle('');
        // set margins
        $pdf::SetMargins(2, 2, 2, true);
        $pdf::SetPrintHeader(false);
        $pdf::SetPrintFooter(false);
        $pdf::SetAutoPageBreak(TRUE, 0);

        $record = ExpeditionAncien::where('id_bon', $bon->id)->get();
        foreach ($record as $expedition) {
            $qrcode = '<img  src="@' . base64_encode(QrCode::format('png')->size(100)->generate($expedition->num_expedition)) . '" width="77px">';
            // $qrcode = '';
            $header = '<br><br>
            <table style="width:100% !important;border:1px solid   !important; height:100% !important; " cellpadding="5">
                <tr >
                    <td style="height:50px !important; font-size: 10px !important;" >
                     <img src="/assets/front/logo-hori.png" height="40px"  width="180px" style="padding: 5px !important;" >
                    </td>

                    <td style="height:50px !important; padding=10px !important; text-align:center !important; font-size: 11px !important;" width="50%">
                    <br><br> <b> ' . $expedition->agenceDesDetail->libelle . ' </b> <br>
                    </td>
                </tr>
            </table>

            <table style="width:100% !important;border-left:1px solid !important; border-right:1px solid !important; height:100% !important; " cellpadding="5">
                <tr >
                    <td style="height:20px !important; font-size: 10px !important;text-align:left !important;" width="20%"><b>Expéditeur </b>
                    </td>

                    <td width="30%" style="height:20px !important;  font-size: 10px !important;" >' . $expedition->clientDetail->libelle . '
                    </td>

                    <td style="height:20px !important; font-size: 10px !important;text-align:left !important;" width="20%"><b>Téléphone </b>
                    </td>

                    <td width="30%" style="height:20px !important;  font-size: 10px !important;" >' . $expedition->telephone . '
                    </td>
                </tr>


                <tr >
                <td style=" border-bottom-color:#d8d8d8; height:20px !important; font-size: 10px !important; border-bottom: 0.2px red  !important;border-collapse: collapse !important;" width="20%"><b>Destinataire </b>
            </td>

                    <td width="80%" style=" border-bottom-color:#d8d8d8; height:20px !important; font-size: 10px !important; border-bottom: 0.2px red  !important;border-collapse: collapse !important;" >' . $expedition->destinataire . ' - ' . $expedition->adresse_destinataire . '
                    </td>
                </tr>
            </table>

            <table style="width:100% !important;border-left:1px solid !important; border-right:1px solid !important;  border-top-color:#d8d8d8;border-top: 0.2px red  !important;" cellpadding="5">
                <tr >
                    <td style="height:20px !important; font-size: 10px !important;" width="50%" >

        <table style="width:100% !important; border-color:#d8d8d8; border:0.1px solid !important; height:100% !important; " cellpadding="5">
        <tr >
            <td style="height:25px !important; font-size: 10px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important;" width="50%"><b>N° Expédition </b>
            </td>

            <td width="50%" style="height:25px !important;  font-size: 10px !important;" >' . $expedition->num_expedition . '
            </td>
        </tr>

        <tr >
            <td style="height:25px !important; font-size: 10px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" width="50%"><b>Montant </b>
            </td>

            <td width="50%" style="height:25px !important;  font-size: 10px !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" >' . number_format($expedition->fond, 2) . ' DH
            </td>
        </tr>

        <tr >
            <td style="height:25px !important; font-size: 8px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" width="50%"><b>Ouverture de colis</b>
            </td>

            <td width="50%" style="height:25px !important;  font-size: 10px !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" >' . $expedition->ouvertureColis . '
            </td>
        </tr>

        <tr >
        <td style="height:25px !important; font-size: 8px !important;text-align:left !important; border-right-color:#d8d8d8; border-right:0.1px solid !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" width="50%"><b>Paiement par chèque</b>
        </td>

        <td width="50%" style="height:25px !important;  font-size: 10px !important; border-top-color:#d8d8d8; border-top:0.1px solid !important;" >' . $expedition->paiementCheque . '
        </td>
    </tr>

        </table>
                    </td>
                    <td style="height:20px!important; text-align:center !important; border-bottom-color:#d8d8d8; border-left:0.2px solid !important;"  width="25%">
                    <br><br>' . $qrcode . '<br>
                    </td>
                    <td style="height:20px !important; border-bottom-color:#d8d8d8; border-left:0.2px solid !important;"  width="25%">




                    <table style="width:100% !important; border-color:#d8d8d8; border:0.1px solid !important; height:100% !important; " cellpadding="5">
                    <tr >
                        <td style="height:25px !important; font-size: 10px !important;text-align:center !important; border-right-color:#d8d8d8; border-right:0.1px solid !important;" width="100%"><b>Indication</b>
                        </td>
                    </tr>
                    </table> <h5>
                    ' . Str::limit($expedition->Indication, 40) . '</h5>
                    </td>

                </tr>
            </table>

            <table style="width:100% !important;border:1px solid   !important; height:100% !important; " cellpadding="5">
                <tr >
                <td style="height:20px !important; font-size: 10px !important;" width="80%"><b>' . $expedition->agenceDetail->libelle . ' le : ' . $expedition->created_at . '</b>
                </td>
                    <td style="height:100% !important; padding=10px !important; text-align:right !important; font-size: 10px !important;" width="20%"> <b>Colis : ' . $expedition->colis . '/1 </b>
                    </td>
                </tr>
            </table>
        ';

            $pdf::AddPage('L', 'A6');
            $pdf::WriteHTML($header, true, 0, true, 0);
        }
        $pdf::Output("Etiquettes_bon.pdf");
    }
    public function ancien_facture_print(FactureAncien $facture, $type){

        FactureAncien::print($facture, $type);
      }

      public function printDetail_ancien_facture(FactureAncien $facture){

        $facture->printDetail($facture);
    }
    public function ancien_remboursements(Request $request)
    {
        $request->flash();

        if (auth()->user()->role == '1') {
            return view(
                'back/remboursement/list',
                [
                    'records' => RemboursementAncien::where('deleted', "0")->get()
                ]
            );
        } elseif (auth()->user()->role == '3') {

            return view(
                'client/historique/remboursements',
                [
                    'records' => RemboursementPaiementsAncien::where('client', auth()->user()->ClientDetail->id)->get()
                ]
            );
        }
    }

    public function print_renboursement_ancien(RemboursementAncien $remboursement, RemboursementPaiementsAncien $paiement)
    {

        $expeditionsIds = $remboursement->expeditions()->allRelatedIds()->toArray();
        $expeditions = ExpeditionAncien::getExpeditionsByRemboursement($remboursement->id);
        $cheques = CaissesCheques::getMntArray(null, $expeditionsIds);
        $clients = ExpeditionAncien::getClientsByRemboursement($expeditions);

        //dd($clients, $expeditions, $cheques, $remboursement);
        $record = new RemboursementAncien();

        $record->printDetail($clients, $remboursement, $expeditions, $cheques, $paiement);
    }

}
