<style>
    @import url(https://fonts.googleapis.com/css?family=Roboto:100,300,400,900,700,500,300,100);

    * {
        margin: 0;
        box-sizing: border-box;

    }

    body {

        background: #bbe3f7 !important;
        font-family: 'Roboto', sans-serif;
        background-image: url('');
        background-repeat: repeat-y;
        background-size: 100%;
    }

    ::selection {
        background: #f31544;
        color: #FFF;
    }

    ::moz-selection {
        background: #f31544;
        color: #FFF;
    }

    h1 {
        font-size: 1.5em;
        color: #222;
    }

    h2 {
        font-size: .9em;
    }

    h3 {
        font-size: 1.2em;
        font-weight: 300;
        line-height: 2em;
    }

    p {
        font-size: .7em;
        color: #666;
        line-height: 1.2em;
    }

    #invoiceholder {
        width: 100%;
        hieght: 100%;
        padding-top: 50px;
    }



    #invoice {
        position: relative;

        margin: 0 auto;
        width: 100%;
        background: #FFF;
    }

    [id*='invoice-'] {
        /* Targets all id with 'col-' */
        border-bottom: 1px solid #EEE;
        padding: 30px;
    }

    #invoice-top {
        min-height: 120px;
    }

    #invoice-mid {
        min-height: 120px;
    }

    #invoice-bot {
        min-height: 250px;
    }

    .logo {
        float: left;
        margin-right: 10px;
        height: 60px;
        width: 60px;
        background: url(/assets/images/gallery/fast-delivery.png) no-repeat;
        background-size: 60px 60px;
    }

    .info {
        display: block;
        float: left;
        margin-left: 20px;
    }

    .title {
        text-align: right;
    }


    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 5px 0 5px 15px;
        border: 1px solid #EEE
    }


    form {
        float: right;
        margin-top: 30px;
        text-align: right;
    }


    .effect2 {
        position: relative;
    }

    .effect2:before,
    .effect2:after {
        z-index: -1;
        position: absolute;
        content: "";
        bottom: 15px;
        left: 10px;
        width: 50%;
        top: 80%;
        max-width: 300px;
        background: #777;
        -webkit-box-shadow: 0 15px 10px #777;
        -moz-box-shadow: 0 15px 10px #777;
        box-shadow: 0 15px 10px #777;
        -webkit-transform: rotate(-3deg);
        -moz-transform: rotate(-3deg);
        -o-transform: rotate(-3deg);
        -ms-transform: rotate(-3deg);
        transform: rotate(-3deg);
    }

    .effect2:after {
        -webkit-transform: rotate(3deg);
        -moz-transform: rotate(3deg);
        -o-transform: rotate(3deg);
        -ms-transform: rotate(3deg);
        transform: rotate(3deg);
        right: 10px;
        left: auto;
    }


    .legal {
        width: 70%;
    }


    .expedition_info .title {
        color: #fff;
        font-size: 25px;
        font-weight: 400;
        text-transform: capitalize;
        margin: 0;
        text-align: center;
    }

    .exp_header {
        background: #c81537;
        padding: 14px 0 14px;
        margin: 0 0 13px;
        border-radius: 100px 0;
        position: relative;
    }

    .prix_header {
        background: #1991ce;
        padding: 14px 0 14px;
        margin: 0 0 13px;
        border-radius: 100px 0;
        position: relative;
    }

    .des_header {
        background: #8d9498;
        padding: 14px 0 14px;
        margin: 0 0 13px;
        border-radius: 100px 0;
        position: relative;
    }

    .panel-heading {
        background: #4b5154;
        padding: 1px;
        border-radius: 20px 20px 0 0;
        margin: 0;

    }

    .panel-heading .title {
        color: #fff;
        font-size: 24px;
        font-weight: 600;
        line-height: 39px;
        text-transform: capitalize;
        margin: 0;
        text-align: center;
    }

    .panel {
        margin-bottom: 30px;
    }

    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #D62929;
    }
</style>
<link rel="stylesheet" href="/assets/vendors/select2/select2.min.css" type="text/css">
<link rel="stylesheet" href="/assets/vendors/select2/select2-materialize.css" type="text/css">
<link rel="stylesheet" type="text/css" href="/assets/css/custom/custom.css" />
<!-- BEGIN: VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="/assets/vendors/vendors.min.css" />
<!-- END: VENDOR CSS-->
<!-- BEGIN: Page Level CSS-->
<link rel="stylesheet" type="text/css" href="/assets/css/themes/vertical-dark-menu-template/materialize.css" />
<link rel="stylesheet" type="text/css" href="/assets/css/themes/vertical-dark-menu-template/style.css" />
<link rel="apple-touch-icon" href="/assets/images/favicon/apple-touch-icon-152x152.png" />
<link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon/favicon-32x32.png" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<!-- BEGIN: VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="/assets/vendors/vendors.min.css" />
<!-- END: VENDOR CSS-->
<!-- BEGIN: Page Level CSS-->
<link rel="stylesheet" type="text/css" href="/assets/css/themes/vertical-dark-menu-template/materialize.css" />
<link rel="stylesheet" type="text/css" href="/assets/css/themes/vertical-dark-menu-template/style.css" />

<!-- END: Page Level CSS-->
<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="/assets/css/custom/custom.css" />

<link rel="stylesheet" type="text/css" href="/assets/vendors/hover-effects/media-hover-effects.css">

<link rel="stylesheet" type="text/css" href="/assets/vendors/data-tables/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css"
    href="/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="/assets/vendors/data-tables/css/dataTables.checkboxes.css">
<link rel="stylesheet" type="text/css" href="/assets/vendors/fullcalendar/css/fullcalendar.min.css">
<link rel="stylesheet" type="text/css" href="/assets/vendors/fullcalendar/daygrid/daygrid.min.css">
<link rel="stylesheet" type="text/css" href="/assets/vendors/fullcalendar/timegrid/timegrid.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/pages/app-calendar.min.css">


{{-- <div id="headerimage"></div> --}}
<div style="padding:30px;">
    <div id="invoice" style="padding-bottom:60px!important;" class="effect2">

        <div id="invoice-top">

            <div class="info">
                <div class="logo"></div>
                <h5 style="margin: 0;">Traitement de l'expédition</h5>
            </div>
            <!--End Info-->
            <div class="title">
                <h4 style="margin: 0;">N° #{{ $record->num_expedition }}</h4>
                <p>Date: {{ date_format($record->created_at, 'W M Y - H:i:s') }}
                </p>
            </div>
            <!--End Title-->
        </div>
        <!--End InvoiceTop-->

        <div id="invoice-mid">
            @if (Session::has('success'))
                <div class="card-alert card green">
                    <div class="card-content white-text">
                        <p style="color: white;
                    font-size: 16px;">{{ Session::get('success') }}
                        </p>
                    </div>
                    <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            @endif
            <div class="row">
                <form class="col s12" method="POST" action="{{ route('expedition_detail', $record->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="input-field col s12">
                        @if (\Auth::user()::hasRessource('SMenu Expedition Details - Button Commentaire'))
                            <i class="material-icons prefix" style="padding-right: 10px">textsms</i>
                            <textarea id="textarea1" name="insert_comment" class="materialize-textarea" data-length="120" required></textarea>
                            <label for="textarea1">Motif</label>
                        @endif
                        <div class="file-field input-field" style="width: 40%; float: right;">
                            @if (\Auth::user()::hasRessource('SMenu Expedition Details - Button Piece Jointe'))
                                <div class="btn">
                                    <i class="material-icons left">insert_drive_file</i>
                                    <span>Fichier</span>
                                    <input name="file" type="file" value="" autocomplete="off" readonly
                                        onfocus="this.removeAttribute('readonly');">
                                </div>
                            @endif
                            <div class="file-path-wrapper">
                                <input class="file-path" type="text">
                            </div>
                            <p>Ajouter un pièce jointe</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            @if (\Auth::user()::hasRessource('SMenu Expedition Details - Button Commentaire'))
                                <button class="btn gradient-45deg-light-blue-cyan waves-effect waves-light right"
                                    style="margin: 10px" type="submit" name="submit" value="submit">+ Commentaire
                                    <i class="material-icons right">insert_comment</i>
                                </button>
                            @endif
                            @if ($record->WhereNotIn('etape', ['14', '7', '5', '8']))
                                @if (\Auth::user()::hasRessource('SMenu Expedition Details - Button Annuler Etape'))
                                    <button class="btn gradient-45deg-light-blue-cyan waves-effect waves-light right"
                                        style="margin: 10px" type="submit" name="annuler_etape"
                                        value="annuler_etape">+
                                        Annuler
                                        l'etape
                                        <i class="material-icons right">cancel</i>
                                    </button>
                                @endif
                                @if (\Auth::user()::hasRessource('SMenu Expedition Details - Button Annuler Expedition'))
                                    <button
                                        class="btn gradient-45deg-purple-deep-orange waves-effect waves-light right"
                                        style="margin: 10px" type="submit" name="annuler" value="annuler">Annuler
                                        l'expédition
                                        <i class="material-icons right">delete</i>
                                    </button>
                                @endif
                            @elseif (
                                $record->WhereIn('etape', ['14', '7']) &&
                                    \Auth::user()::hasRessource('SMenu Expedition Details - Button Annuler Encaissement'))
                                @if (\Auth::user()::hasRessource('SMenu Expedition Details - Button Annuler Etape'))
                                    <button class="btn gradient-45deg-light-blue-cyan waves-effect waves-light right"
                                        style="margin: 10px" type="submit" name="annuler_etape"
                                        value="annuler_etape">+
                                        Annuler
                                        l'etape
                                        <i class="material-icons right">cancel</i>
                                    </button>
                                @endif
                                @if (\Auth::user()::hasRessource('SMenu Expedition Details - Button Annuler Expedition'))
                                    <button
                                        class="btn gradient-45deg-purple-deep-orange waves-effect waves-light right"
                                        style="margin: 10px" type="submit" name="annuler" value="annuler">Annuler
                                        l'expédition
                                        <i class="material-icons right">delete</i>
                                    </button>
                                @endif
                            @elseif ($record->etape == '8' && \Auth::user()::hasRessource('SMenu Expedition Details - Button Annuler Etape Rembourser'))
                                @if (\Auth::user()::hasRessource('SMenu Expedition Details - Button Annuler Etape'))
                                    <button class="btn gradient-45deg-light-blue-cyan waves-effect waves-light right"
                                        style="margin: 10px" type="submit" name="annuler_etape"
                                        value="annuler_etape">+
                                        Annuler
                                        l'etape
                                        <i class="material-icons right">cancel</i>
                                    </button>
                                @endif
                                @if (\Auth::user()::hasRessource('SMenu Expedition Details - Button Annuler Expedition'))
                                    <button
                                        class="btn gradient-45deg-purple-deep-orange waves-effect waves-light right"
                                        style="margin: 10px" type="submit" name="annuler" value="annuler">Annuler
                                        l'expédition
                                        <i class="material-icons right">delete</i>
                                    </button>
                                @endif

                            @endif

                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="invoice-mid">

            <div class="row expedition_info">
                @if (\Auth::user()::hasRessource('Expedition Details Avance'))
                    <div class="col s12 m4 input-field text-center">
                        <div class="exp_header">
                            <h4 class="title">Expéditeur</h4>
                        </div>

                        <ul class="text-center">
                            <li>
                                {{ $record->clientDetail->libelle }}
                            </li>
                            <li>
                                {{ $record->clientDetail->adresse }}
                            </li>
                            <li>
                                {{ $record->clientDetail->telephone }}
                            </li>
                            <li>
                                {{ $record->agenceDetail->libelle }}
                            </li>
                            <li>
                                Ouverture Colis : {{ $record->ouvertureColis }}
                            </li>
                            <li>
                                Paiement / Chèque : {{ $record->paiementCheque }}
                            </li>
                        </ul>
                    </div>
                @endif
                @if (\Auth::user()::hasRessource('Expedition Details Avance'))
                    <div class="col s12 m4 input-field text-center">
                    @else
                        <div class="col s12 m6 input-field text-center">
                @endif
                <div class="des_header">
                    <h4 class="title">Destinataire</h4>
                </div>
                <ul>
                    <li>
                        {{ $record->destinataire }}
                    </li>
                    <li>
                        {{ $record->adresse_destinataire }}
                    </li>
                    <li>
                        {{ $record->telephone }}
                    </li>
                    <li>
                        {{ $record->DestinationDetail->libelle }}
                    </li>
                </ul>
            </div>
            @if (\Auth::user()::hasRessource('Expedition Details Avance'))
                <div class="col s12 m4 input-field text-center">
                @else
                    <div class="col s12 m6 input-field text-center">
            @endif
            <div class="prix_header">
                <h4 class="title">Prix / Fonds</h4>
            </div>
            <ul>
                <li>
                    Crée le : {{ $record->created_at }}
                </li>
                <li>
                    Livré le : En cours ...
                </li>
                <li>
                    C. espèce : {{ $record->fond }} Dhs
                </li>
                @if (\Auth::user()::hasRessource('Expedition Details Avance'))
                    @if ($record->vDeclaree != null)
                        <li>
                            Val. Déclarée : {{ $record->vDeclaree }}
                        </li>
                    @endif
                @endif
                <li>
                    Prix Colis : {{ $record->ttc }} Dhs
                </li>
                <li>
                    EN COURS
                </li>
            </ul>
        </div>

    </div>

</div>
<!--End Invoice Mid-->

<div id="invoice-bot">
    <div class="panel">
        <div class="panel-heading">
            <h4 class="title">Informations encaissement</h4>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Caisse</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Agence</th>
                    <th>Caissier</th>
                    <th>Livreur</th>
                    <th>Banque</th>
                    <th>N° Pièce</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($record->Caisse->Caisse))
                    <tr>
                        <td></td>
                        <td><a target="_blank"
                                href="{{ route('caisse_print_detail', ['caisse' => $record->Caisse->Caisse->id]) }}">{{ $record->Caisse->Caisse->numero }}</a>
                        </td>
                        <td></td>
                        <td>{{ $record->Caisse->Caisse->date_creation }}</td>
                        <td>{{ $record->Caisse->agenceDetail->libelle }}</td>
                        <td>{{ $record->Caisse->Caissier->EmployeDetail->libelle }}</td>
                        <td>{{ $record->Caisse->livreur->libelle }}</td>
                        <td></td>
                        <td></td>
                        <td>{{ $record->Caisse->montant }}</td>

                    </tr>
                @endif
            </tbody>
        </table>

    </div>

    {{-- <div class="panel">
                <div class="panel-heading">
                    <h4 class="title">Information affectations</h4>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>N° Bon/ F.Charge</th>
                            <th>Fait à</th>
                            <th>Date</th>
                            <th>Reçu par</th>
                            <th>Fait par</th>
                            <th>Annuler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @if (isset($ramassageRecords))
                                    @if ($ramassageRecords->id_bon_ramassage == null)
                                        <i class="material-icons green-text" title="En cours">check_box</i>
                                    @elseif($ramassageRecords->id_bon_ramassage != null)
                                        <i class="material-icons blue-text" title="Fait">check_box</i>
                                    @endif
                                @endif
                            </td>
                            <td>RAMASSAGE</td>
                            <td>{{ @$ramassageRecords->BonRamassageDetail->code }}</td>
                            <td>{{ @$ramassageRecords->ExpeditionDetail->agenceDetail->Libelle }}</td>
                            <td>{{ @$ramassageRecords->BonRamassageDetail->date_validation }}</td>
                            <td>{{ @$ramassageRecords->RecuPar->libelle }}</td>
                            <td></td>
                            <td></td>

                        </tr>
                        @foreach ($chargementRecords as $chargementRecord)
                            <tr>

                                <td>
                                    @if (isset($chargementRecords) && isset($ramassageRecords))
                                        @if ($chargementRecord->id_feuille_charge == null && $ramassageRecords->id_bon_ramassage == null)
                                            <i class="material-icons orange-text" title="Planifié">check_box</i>
                                        @elseif($chargementRecord->id_feuille_charge == null && $ramassageRecords->id_bon_ramassage != null)
                                            <i class="material-icons green-text" title="En cours">check_box</i>
                                        @elseif($chargementRecord->id_feuille_charge != null && $ramassageRecords->id_bon_ramassage != null)
                                            <i class="material-icons blue-text" title="Fait">check_box</i>
                                        @endif
                                    @endif
                                </td>


                                <td>CHARGEMENT</td>
                                <td>
                                    <a target="_blank"
                                        href="{{ route('chargement_print_detail', $chargementRecord->BonChargementDetail->id) }}">
                                        {{ @$chargementRecord->BonChargementDetail->code }}
                                    </a>

                                </td>
                                <td>{{ @$chargementRecord->ExpeditionDetail->agenceDetail->Libelle }}</td>
                                <td>{{ @$chargementRecord->BonChargementDetail->date_validation }}</td>
                                <td>{{ @$chargementRecord->RecuPar->libelle }}</td>
                                <td></td>
                                <td></td>

                            </tr>
                        @endforeach
                        <tr>
                            <td>
                                @if (isset($livraisonRecords) && isset($chargementRecords))
                                    @if ($chargementRecords->last()->id_feuille_charge == null && $livraisonRecords->id_bon_livraison == null)
                                        <i class="material-icons orange-text" title="Planifié">check_box</i>
                                    @elseif($chargementRecords->last()->id_feuille_charge != null && $livraisonRecords->id_bon_livraison == null)
                                        <i class="material-icons green-text" title="En cours">check_box</i>
                                    @elseif($chargementRecords->last()->id_feuille_charge != null && $livraisonRecords->id_bon_livraison != null)
                                        <i class="material-icons blue-text" title="Fait">check_box</i>
                                    @endif
                                @endif

                            </td>
                            <td>LIVRAISON</td>
                            <td>{{ @$livraisonRecords->BonLivraisonDetail->code }}</td>
                            <td>{{ @$livraisonRecords->agenceDesDetail->Libelle }}</td>
                            <td>{{ @$livraisonRecords->BonLivraisonDetail->date_validation }}</td>
                            <td>{{ @$livraisonRecords->RecuPar->libelle }}</td>
                            <td></td>
                            <td></td>

                        </tr>
                    </tbody>
                </table>

            </div> --}}

    <div class="panel">
        <div class="panel-heading">
            <h4 class="title">Commentaires & Motifs</h4>
        </div>
        <div class="dataTables_wrapper" id="app">

            <div class="card-content">
                <!-- datatable start -->
                <div class="responsive-table">
                    <table id="list-datatable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Utilisateur</th>
                                <th>Commentaire</th>
                                <th>Source</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commentRecords as $comment)
                                <tr>
                                    <td></td>
                                    <td>
                                        {{-- @if (strlen($comment->lat) > 4)
                                        <a href="{{route('expedition_map', [$comment->id])}}" target="_blank"><i class="material-icons">map</i></a>
                                    @endif --}}
                                        @if ($comment->code == 'COMMENTAIRE_NORMAL')
                                            <span class="badge blue">
                                            @else
                                                <span class="badge grey">
                                        @endif

                                        {{ $comment->code }}

                                        </span>
                                        @if ($comment->code == 'AFFECTATION')
                                            @if ($comment->bon != null)
                                                <a target="_blank" class="right"
                                                    href="{{ route('bonliv_download', $comment->bon) }}">
                                                    {{ $comment->bon_code }}
                                                    <i class="material-icons right">file_download</i>
                                            @endif
                                        @elseif($comment->code == 'CHARGEMENT')
                                            @if ($comment->bon != null)
                                                <a target="_blank" class="right green-text"
                                                    href="{{ route('chargement_print_detail', $comment->bon) }}">
                                                    {{ $comment->fc_code }}
                                                    <i class="material-icons right">file_download</i>
                                            @endif
                                        @elseif($comment->code == 'RAMASSAGE')
                                            @if ($comment->bon != null)
                                                <a target="_blank" class="right orange-text"
                                                    href="{{ route('bon_print_detail', $comment->bon) }}">
                                                    {{ $comment->fc_code }}
                                                    <i class="material-icons right">file_download</i>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $comment->created_at }}</td>
                                    <td>

                                        {{ $comment->name }}

                                    </td>
                                    <td>
                                        @if ($comment->code == 'COMMENTAIRE_NORMAL')
                                            <b>
                                                {{ $comment->commentaires }}
                                            </b>
                                        @else
                                            {{ $comment->commentaires }}
                                        @endif



                                        @if (isset($comment->justif_path))
                                            <a target="_blank"
                                                href="/uploads/commentaire/{{ $comment->justif_path }}"><i
                                                    class="material-icons">attach_file</i></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($comment->source == 2)
                                        <i class="material-icons blue-text" title="Saisie par smartphone">phone_android</i>
                                        @else
                                        <i class="material-icons orange-text " title="Saisie par pc">laptop</i>

                                        @endif

                                        {{-- @if ($comment->code == 'CHARGEMENT')
                                        @foreach ($comment->expedition_chargement() as $bon)
                                            <a target="_blank"
                                                href="{{ route('chargement_print_detail', $bon->id_feuille_charge) }}">
                                                <p>{{ $bon->BonChargementDetail->code }}</p>
                                            </a>
                                        @endforeach
                                    @endif --}}

                                    </td>
                                </tr>
                            @endforeach
                            {{-- @if (isset($ramassageRecords->id_bon_ramassage))
                                <tr>
                                    <td>
                                        @if (isset($ramassageRecords))
                                            @if ($ramassageRecords->id_bon_ramassage == null)
                                                <i class="material-icons green-text" title="En cours">check_box</i>
                                            @elseif($ramassageRecords->id_bon_ramassage != null)
                                                <i class="material-icons blue-text" title="Fait">check_box</i>
                                            @endif
                                        @endif
                                    </td>
                                    <td><span class="badge green">RAMASSAGE</span>

                                        <a target="_blank" class="right"
                                            href="{{ route('bon_print_detail', @$ramassageRecords->bon_id) }}">{{ @$ramassageRecords->bon_code }}
                                            <i class="material-icons right">file_download</i></a>

                                    </td>
                                    <td>{{ @$ramassageRecords->date_validation }}{{ $ramassageRecords->date_validation }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                            @foreach ($chargementRecords as $chargementRecord)
                                @if (isset($chargementRecord->BonChargementDetail->id))
                                    <tr>

                                        <td>
                                            @if (isset($chargementRecords) && isset($ramassageRecords))
                                                @if ($chargementRecord->id_feuille_charge == null && $ramassageRecords->id_bon_ramassage == null)
                                                    <i class="material-icons orange-text"
                                                        title="Planifié">check_box</i>
                                                @elseif($chargementRecord->id_feuille_charge == null && $ramassageRecords->id_bon_ramassage != null)
                                                    <i class="material-icons green-text"
                                                        title="En cours">check_box</i>
                                                @elseif($chargementRecord->id_feuille_charge != null && $ramassageRecords->id_bon_ramassage != null)
                                                    <i class="material-icons blue-text" title="Fait">check_box</i>
                                                @endif
                                            @endif
                                        </td>


                                        <td><span class="badge green">CHARGEMENT</span><a target="_blank"
                                                class="right"
                                                href="{{ route('chargement_print_detail', $chargementRecord->BonChargementDetail->id) }}">
                                                {{ @$chargementRecord->BonChargementDetail->code }}
                                                <i class="material-icons right">file_download</i>
                                            </a></td>
                                        <td>
                                            {{ @$chargementRecord->date_validation }}

                                        </td>
                                        <td>
                                        </td>
                                        <td>{{ @$chargementRecord->date_validation }}</td>
                                        <td>{{ @$chargementRecord->RecuPar->libelle }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            @if (isset($livraisonRecords->id_bon_livraison))
                                <tr>
                                    <td>
                                        @if (isset($livraisonRecords) && isset($chargementRecords))
                                            @if ($chargementRecords->last()->id_feuille_charge == null && $livraisonRecords->id_bon_livraison == null)
                                                <i class="material-icons orange-text" title="Planifié">check_box</i>
                                            @elseif($chargementRecords->last()->id_feuille_charge != null && $livraisonRecords->id_bon_livraison == null)
                                                <i class="material-icons green-text" title="En cours">check_box</i>
                                            @elseif($chargementRecords->last()->id_feuille_charge != null && $livraisonRecords->id_bon_livraison != null)
                                                <i class="material-icons blue-text" title="Fait">check_box</i>
                                            @endif
                                        @endif

                                    </td>
                                    <td><span class="badge green">LIVRAISON</span>
                                        <a target="_blank" class="right"
                                            href="{{ route('bonliv_download', $livraisonRecords->id_bon_livraison) }}">
                                            {{ @$livraisonRecords->BonLivraisonDetail->code }}
                                            <i class="material-icons right">file_download</i>
                                    </td>
                                    <td>{{ @$livraisonRecords->BonLivraisonDetail->date_validation }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif --}}
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>


    {{-- <div id="legalcopy"> --}}
    {{-- <p class="legal"><strong>Thank you for your business!</strong>  Payment is expected within 31 days; --}}
    {{-- please process this invoice within that time. There will be a 5% interest charge per month on late --}}
    {{-- invoices. --}}
    {{-- </p> --}}
    {{-- </div> --}}

</div>
<!--End InvoiceBot-->
</div>
</div>
<!--End Invoice-->
<!-- End Invoice Holder-->
<!-- BEGIN VENDOR JS-->
<script src="/assets/js/vendors.min.js"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<script src="/assets/vendors/chartjs/chart.min.js"></script>
<!-- END PAGE VENDOR JS-->
<!-- BEGIN THEME  JS-->
<script src="/assets/js/plugins.js"></script>
<script src="/assets/js/search.js"></script>
<script src="/assets/js/custom/custom-script.js"></script>

<script src="/assets/vendors/data-tables/js/jquery.dataTables.min.js"></script>
<script src="/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/vendors/data-tables/js/datatables.checkboxes.min.js"></script>

<script src="/assets/vendors/select2/select2.full.min.js"></script>

<script src="/assets/js/vue3.prod.js"></script>

<script>
    $(document).ready(function() {



        $('.timepicker').timepicker({
            twelveHour: false,
            showClearBtn: true,
            autoClose: true,
            showView: 'hours',
            i18n: {
                months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                    'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ],

                monthsShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jui', 'Jui', 'Aoû', 'Sep', 'Oct',
                    'Nov', 'Déc'
                ],
                weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                today: 'Aujourd\'hui',
                cancel: 'Annuler',
                done: 'OK',
                clear: 'Effacer'
            }
        });
        $('.datepicker').datepicker({
            firstDay: true,
            format: 'yyyy-mm-dd',
            clear: 'effacer',
            formatSubmit: 'yyyy/mm/dd',
            showClearBtn: true,
            autoClose: true,
            i18n: {
                months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                    'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ],
                monthsShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jui', 'Jui', 'Aoû', 'Sep', 'Oct',
                    'Nov', 'Déc'
                ],
                weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                today: 'Aujourd\'hui',
                cancel: 'Annuler',
                done: 'OK',
                clear: 'Effacer'
            }
        });
        $(".select2").select2({
            dropdownAutoWidth: true,
            width: '100%'
        });

        const App = {
            mounted() {
                this.loadData();
                $('.tooltipped').tooltip();
            },
            methods: {
                loadData() {
                    if ($("#list-datatable").length > 0) {
                        $("#list-datatable").DataTable({

                            "bPaginate": false,

                            "bFilter": false,
                            "bInfo": false,
                            "aaSorting": [
                                [2, "desc"]
                            ],
                            "language": {
                                url: '/assets/vendors/data-tables/i18n/fr_fr.json'
                            }
                        });
                    };

                },
            },
        }

        Vue.createApp(App).mount('#app');

    });
</script>
