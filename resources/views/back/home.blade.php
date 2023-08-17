@extends($layout)
<style>
    .tabs .indicator {
        display: none;
    }

    .tabs .tab a.active {
        border-bottom: 2px solid #1991ce;
        font-weight: 900;
        background: #63beec1a!important;
    }


</style>
@section('content')
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <!--card stats start-->
                    <div id="card-stats" class="pt-0">
                        <div class="row">
                            {{-- <div class="col s12 m6 l6 xl2">
                                <div class="card animate fadeLeft">
                                    <div class="card-content cyan white-text">
                                        <p class="card-stats-title">
                                            <i class="material-icons">local_shipping</i>
                                        </p>
                                        <h4 style="font-size:1.2vw;" class="card-stats-number white-text">{{number_format($sum_Remb, 2)}} Dhs</h4>
                                        <p class="card-stats-compare">
                                            <span class="cyan text text-lighten-5">Rembo. en attente</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col s12 m6 l6 xl2">
                                <div class="card animate fadeLeft">
                                    <div class="card-content red accent-2 white-text">
                                        <p class="card-stats-title">
                                            <i class="material-icons">local_shipping</i>
                                        </p>
                                        <h4 style="font-size:1.2vw;"  class="card-stats-number white-text">{{number_format($total_a_facture, 2)}} Dhs</h4>
                                        <p class="card-stats-compare">
                                            <span class="red-text text-lighten-5">Total a facturer</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6 l6 xl2">
                                <div class="card animate fadeRight">
                                    <div class="card-content orange lighten-1 white-text">
                                        <p class="card-stats-title">
                                            <i class="material-icons">local_post_office</i> Colis
                                        </p>
                                        <h4 style="font-size:1.2vw;" class="card-stats-number white-text">{{$delai_livraison}}</h4>
                                        <p class="card-stats-compare">
                                            <span class="orange-text text-lighten-5">Délai de livraison</span>
                                        </p>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col s12 m6 l6 xl3">
                                <div class="card animate fadeRight">
                                    <div class="card-content green lighten-1 white-text">

                                        <h4 class="card-stats-number white-text" style="font-size: 1.6rem;">
                                            {{ $taux_retour }} %</h4>
                                        <p class="card-stats-compare">
                                            <span class="green-text text-lighten-5">Taux de retour</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col s12 m6 l6 xl3">
                                <div class="card animate fadeRight">
                                    <div class="card-content blue lighten-1 white-text">

                                        <a href="#taux_modal" class="modal-trigger">
                                            <h4 class="card-stats-number white-text">
                                                {{ $taux_livraison }} %</h4>
                                        </a>
                                        <p class="card-stats-compare">
                                            <span class="green-text text-lighten-5">Taux de livraison</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col s12 m6 l6 xl3">
                                <div class="card  animate fadeRight">
                                    <div class="card-content red lighten-1 white-text">
                                        <a href="#prix_modal" class="modal-trigger">
                                            <h4 class="card-stats-number white-text">
                                                {{ number_format($prix, 2) }} Dhs</h4>
                                        </a>
                                        <p class="card-stats-compare">
                                            <span class="green-text text-lighten-5">Prix</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col s12 m6 l6 xl3">
                                <div class="card animate fadeRight">
                                    <div class="card-content deep-purple darken-1 white-text">

                                        <a href="#modal1" class="modal-trigger">
                                            <h4 class="card-stats-number white-text">
                                                {{ number_format($moyenne_commissions, 2) }}
                                                Dhs</h4>
                                        </a>
                                        <p class="card-stats-compare">
                                            <span class="green-text text-lighten-5">Commission moyenne</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6 l4 card-width">
                                <div class="card border-radius-6 blue lighten-5">
                                    <div class="card-content center-align">
                                        <h5 class=""><b>{{ $delai_livraison }}</b></h5>
                                        <p>Délai de livraison</p>

                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6 l4 card-width">
                                <div class="card border-radius-6 blue lighten-5">
                                    <div class="card-content center-align">
                                        <h5 class=""><b>{{ number_format($sum_Remb, 2) }} Dhs</b></h5>
                                        <p>Rembo. en attente</p>

                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6 l4 card-width">
                                <div class="card border-radius-6 blue lighten-5">
                                    <div class="card-content center-align">
                                        <h5 class=""><b>{{ number_format($total_a_facture, 2) }} Dhs</b></h5>
                                        <p>Total a facturer</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--card stats end-->
                <!--yearly & weekly revenue chart start-->
                <div id="sales-chart">
                    <div class="row">
                        <div class="col s12 l6">
                            <h4 class="header mt-0">
                                Réalisations mensuel </h4>

                            <canvas id="Rmensuel"></canvas>

                        </div>
                        <div class="col s12 l6">
                            <div class="card-content">
                                <h4 class="header m-0">
                                    Evolution Chiffre d'affaires
                                </h4>
                                <canvas id="EvolutionChiffre"></canvas>
                            </div>

                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col s12 m6">

                        <div id="bordered-table" class="card card card-default scrollspy">
                            <div class="card-content">
                                <h4 class="card-title"> Alertes</h4>

                                <div class="row">

                                    <div class="col s12">
                                        <table class="bordered">
                                            <thead>
                                                <tr>
                                                    <th data-field="id">Titre</th>
                                                    <th data-field="name">Nombre</th>
                                                    <th data-field="price">Rapport</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> Souffrances ramassage (+24H)</td>
                                                    <td style="text-align: center;color: red; ">{{ $count_ramassage }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <a href="{{ route('Souffrance_ramassage') }}" target="_blank">
                                                            <span class="material-icons">local_printshop</span>
                                                    </td>
                                                    </a>
                                                </tr>
                                                <tr>
                                                    <td>Souffrance chargement (24h)</td>
                                                    <td style="text-align: center;color: red; ">
                                                        {{ $count_chargement }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <a href="{{ route('Souffrance_chargement') }}"
                                                            target="_blank">
                                                            <span class="material-icons">local_printshop</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Souffrance arrivage (24h)</td>
                                                    <td style="text-align: center;color: red; ">{{ $count_arrivage }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <a href="{{ route('Souffrance_arrivage') }}" target="_blank">
                                                            <span class="material-icons">local_printshop</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Souffrance en cours de livraison (5h)</td>
                                                    <td style="text-align: center;color: red; ">
                                                        {{ $count_livraison }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <a href="{{ route('Souffrance_livraison') }}"
                                                            target="_blank">
                                                            <span class="material-icons">local_printshop</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> Caisses non validées (+12H)</td>
                                                    <td style="text-align: center;color: red; ">
                                                        {{ $count_caisse_nonvalide }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <a href="{{ route('pdf_caisses_nonvalide') }}"
                                                            target="_blank">
                                                            <span class="material-icons">local_printshop</span>
                                                    </td>
                                                    </a>
                                                </tr>
                                                <tr>
                                                    <td>Expéditions livrées non remboursées (+48H)</td>
                                                    <td style="text-align: center;color: red; ">
                                                        {{ $count_non_remboursées }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <a href="{{ route('pdf_exp_nonremb') }}" target="_blank">
                                                            <span class="material-icons">local_printshop</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Suivi chargement par ville d'envoi (24H)</td>
                                                    <td style="text-align: center;color: red; ">
                                                        {{ $count_exp_24h }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <a href="{{ route('suvi_parville') }}" target="_blank">
                                                            <span class="material-icons">local_printshop</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col s12 m6">
                        <div class="card">
                            <ul class="collection with-header">
                                <li class="collection-header">
                                    <h4>Aujourd'hui</h4>
                                </li>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($arr as $exp)
                                    <li class="collection-item"><i
                                            class="material-icons blue-text left">location_city</i>{{ $exp['ville'] }}<span
                                            class="badge grey">{{ $exp['nbr_exp'] }}</span></li>

                                    @php
                                        $total = $total + $exp['nbr_exp'];
                                    @endphp
                                @endforeach
                                <li class="collection-item" style="font-size:20px!important;">
                                    <i class="material-icons red-text left">equalizer</i> <strong>Total</strong> <span
                                        class="badge blue">{{ $total }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    {{-- <div class="col s12 m12 l4" style="margin-top: 30px;margin-bottom: 30px;">
                        <h4 class="header m-0">
                            Taux de retour
                        </h4>
                        <canvas id="Tauxretour"></canvas>
                    </div> --}}
                </div>

                <!-- Member online, Currunt Server load & Today's Revenue Chart start -->
                <!-- ecommerce product start-->

                <!--end container-->

            </div>

        </div>
        <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a href="#modal2"
                class="btn-floating modal-trigger btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i
                    class="material-icons" style="font-size:2.6rem!important;">update</i></a>
        </div>
        <div id="modal1" class="modal modal-fixed-footer" style="height: 60%;">
            <div class="modal-content">
                <h5 style="text-align: center;padding-block:10px;border-radius:10px;"
                    class="gradient-45deg-indigo-light-blue white-text">Taux de commissions</h5>
                <br>

                <div class="row">
                    <div class="col s12">
                        <ul class="tabs">
                            <li class="tab col m6"><a class="active" href="#parcommission">Filtre par commission</a></li>
                            <li class="tab col m6"><a href="#parville">Filtre par ville</a></li>

                        </ul>
                    </div>
                    <div id="parcommission" class="col s12">
                        <br>
                        <table class="Highlight centered">
                            <thead>
                                <tr>
                                    <th>Commission</th>
                                    <th>Taux</th>
                                    <th>Total Fond</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($moyenne_commission as $moy)
                                    <tr>
                                        <td>{{ number_format($moy['commission'], 2) }} Dhs</td>
                                        <td>{{ $moy['taux'] }} %</td>
                                        <td>{{ number_format($moy['fond'], 2) }} Dhs</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="parville" class="col s12">
                        <br>

                        <div class=" input-field">
                            <select id="ville_dep_comm" name="ville_dep_comm" placeholder=""
                                class="select select2 browser-default">
                                <option value='0' name='0'>Toutes les villes</option>
                                @foreach ($commission_grouped_ville_exp as $ville => $comm)
                                    <option class='option'
                                        {{ $comm->first()->id_ville_exp == old('ville_dep_comm') ? 'selected' : '' }}
                                        value='{{ $ville }}' name='id{{ $ville }}'>
                                        {{ $comm->first()->agence_exp }}</option>
                                @endforeach
                            </select>
                            <label for="ville_dep_comm">Choisir la ville de Départ</label>
                        </div>
                        <div class="all_ville_comm">
                            @foreach ($commission_grouped_ville_exp as $ville => $commission_ville)
                                <div id="id{{ $ville }}" class="villes_depard_commission">
                                    <h5 style="font-weight: 900;background-color:#1991ce;color:white;padding:20px;">
                                        <b>Ville de depard {{ $commission_ville->first()->agence_exp  }}
                                        </b>
                                    </h5>
                                    <table class="Highlight centered">
                                        <thead>
                                            <tr>

                                                <th>Ville de destination</th>
                                                <th>Moyenne Commission</th>
                                                <th>Total Fond</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($commission_ville->where('id_ville_dest', '!=', $ville)->groupby('id_ville_dest') as $ville_dest => $commissions)
                                                <tr>
                                                    <td>{{ $commissions->first()->destination }}</td>
                                                    <td>{{ $commissions->avg('commission') }} Dhs</td>
                                                    <td>{{ $commissions->sum('commission') }} Dhs</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Fermer</a>
            </div>
        </div>

        <div id="prix_modal" class="modal modal-fixed-footer" style="height: 80%;">
            <div class="modal-content">
                <h5 style="text-align: center;padding-block:10px;border-radius:10px;" class="gradient-45deg-indigo-light-blue white-text">Moyenne des prix par ville
                    destination et ville
                    départ</h5>
                <br>
                <div class=" input-field">
                    <select id="ville_dep" name="ville_dep" placeholder="" class="select select2 browser-default">
                        <option value='0' name='0'>Toutes les villes</option>
                        @foreach ($prix_villes as $ville => $exps)
                            <option class='option' {{ $ville == old('ville_dep') ? 'selected' : '' }}
                                value='{{ $ville }}' name='{{ $ville }}'>
                                {{ $exps->first()->agence_dep }}</option>
                        @endforeach
                    </select>
                    <label for="ville_dep">Choisir la ville de Départ</label>
                </div>
                <div name="all_villes">
                    @foreach ($prix_villes as $ville => $exps)
                        <div id="{{ $ville }}" class="villes_depard">


                            <h5 style="font-weight: 900;background-color:#1991ce;color:white;padding:20px;">
                                <b>Ville de depard {{ $exps->first()->libelle }}
                                    <span
                                        class="badge grey">{{ number_format($exps->where('agence_des', '!=', $ville)->avg('ttc'), 2) }}
                                        Dhs</span>
                                </b>
                            </h5>

                            <table class="Highlight bordered centered">
                                <thead>
                                    <tr>
                                        <th>Ville Destination</th>
                                        <th>Prix Moyenne</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                    </tr>

                                    @foreach ($exps->where('agence_des', '!=', $ville)->groupby('agence_des') as $ville_des => $exps_des)
                                        <tr>
                                            <th style="text-align: center;">
                                                {{ $exps_des->first()->destination }} </th>
                                            <th style="text-align: center;">
                                                {{ number_format($exps_des->avg('ttc'), 2) }}
                                                Dhs</th>
                                        </tr>
                                    @endforeach



                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Fermer</a>
            </div>
        </div>

        <div id="taux_modal" class="modal modal-fixed-footer" style="height: 80%;">
            <div class="modal-content">
                <h5 style="text-align: center;padding-block:10px;border-radius:10px;" class="gradient-45deg-indigo-light-blue white-text">Filtrer taux de livraison</h5>
                <br>
                <form action="{{ route('indicateurs') }}" method="post">
                    @csrf
                    <div class="list-table centered" id="app">
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s12 m6 input-field">
                                        <input id="start_date" value="{{ $date_start_taux_livraison }}"
                                            name="start_date" type="date">
                                        <label for="start_date">Du </label>
                                    </div>
                                    <div class="col s12 m6 input-field">
                                        <input id="end_date" value="{{ $date_end_taux_livraison }}" name="end_date"
                                            type="date">
                                        <label for="end_date">Au </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- <div class="col s12 m5 input-field">
                        <select id="ville_desti" name="ville_desti" placeholder=""
                            class="select select2 browser-default">
                            <option value='0' name='0'>Toutes les villes</option>
                            @foreach ($villes_taux_livraison as $ville)
                                <option class='option' {{ $ville->id == old('ville_desti') ? 'selected' : '' }}
                                    value='{{ $ville->id }}' name='{{ $ville->id }}'>
                                    {{ $ville->libelle }}</option>
                            @endforeach
                        </select>
                        <label for="ville_desti">Choisir la ville de Départ</label>
                    </div>
                    <div class="col s12 m5 input-field">
                        <select id="livreur" name="livreur" placeholder="" class="select select2 browser-default">
                            <option value='0' name='0'>Tous les livreurs</option>
                            @foreach ($list_livreur as $livreur)
                                <option class='option' {{ $livreur->id == old('livreur') ? 'selected' : '' }}
                                    value='{{ $livreur->id }}' name='{{ $livreur->id }}'>
                                    {{ $livreur->libelle }}</option>
                            @endforeach
                        </select>
                        <label for="livreur">Choisir le livreur</label>
                    </div>
                    <div class="col m2 input-field">
                        <button id='taux_search' class="mb-6 btn-floating waves-effect waves-light gradient-45deg-light-blue-cyan">
                            <i class="material-icons">search</i>
                        </button>
                    </div>
                    <div id='datax'>

                    </div> --}}

                        <div class="col s12 m6 l6 xl4">
                            <div class="card animate fadeRight">
                                <div class="card-content blue lighten-5 black-text" style="text-align: center">
                                    <button type="submit" name="tauxLivraisonDestination"
                                        style="background: none;border:none">
                                        <h4 class="card-stats-number blue-text">
                                            <i class="material-icons" style="font-size: 30px">file_download</i>
                                        </h4>
                                    </button>
                                    <p class="card-stats-compare">
                                        Filtre par Destination
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col s12 m6 l6 xl4">
                            <div class="card animate fadeRight">
                                <div class="card-content blue lighten-5 black-text" style="text-align: center">
                                    <button type="submit" name="tauxLivraisonClient"
                                        style="background: none;border:none">
                                        <h4 class="card-stats-number blue-text">
                                            <i class="material-icons" style="font-size: 30px">file_download</i>
                                        </h4>
                                    </button>
                                    <p class="card-stats-compare">
                                        Filtre par Client
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col s12 m6 l6 xl4">
                            <div class="card  animate fadeRight">
                                <div class="card-content blue lighten-5 black-text" style="text-align: center">
                                    <button type="submit" name="tauxLivraisonLivreur"
                                        style="background: none;border:none">
                                        <h4 class="card-stats-number blue-text">
                                            <i class="material-icons" style="font-size: 30px">file_download</i>
                                        </h4>
                                    </button>
                                    <p class="card-stats-compare">
                                        Filtre par Livreur
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Fermer</a>
            </div>
        </div>

        <div id="modal2" class="modal modal-fixed-footer" style="height: 40%;">
            <form method="POST" action="{{ route('admin') }}">
                @csrf
                <div class="modal-content">
                    <h4 style="text-align: center;padding-block:10px;border-radius:10px;"
                        class="gradient-45deg-indigo-light-blue white-text">Changer les date de statistique</h4>
                    <br>
                    <div class="col s12 m6 input-field">
                        <input id="start_date_dashboard" value="{{ old('start_date_dashboard') }}" required
                            name="start_date_dashboard" placeholder="" type="date">
                        <label for="start_date_dashboard">Du </label>
                    </div>
                    <div class="col s12 m6 input-field">
                        <input id="end_date_dashboard" value="{{ old('end_date_dashboard') }}" required
                            name="end_date_dashboard" placeholder="" type="date">
                        <label for="end_date_dashboard">Au </label>
                    </div>
                </div>
                <div class="modal-footer">

                    <a class="btn red waves-effect waves-light modal-action modal-close"
                        style="margin-inline: 5px;">Fermer
                        <i class="material-icons left">close</i>
                    </a>
                    <button class="btn waves-effect modal-action waves-light" type="submit">Actualiser
                        <i class="material-icons right">update</i>
                    </button>
                </div>
            </form>
        </div>
        <div class="content-overlay"></div>


    </div>
@stop

@section('js')
    <script src="/assets/js/scripts/dashboard-ecommerce.js"></script>
    <script>

        $(document).ready(function() {
            $('#taux_search').click(function() {

                $.ajax({
                    url: '/taux_livraison_filre',
                    type: 'post',
                    data: '&_token={{ csrf_token() }}',
                    success: function(result) {
                        $('#datax').html(result)
                    }
                });


            });
        });


        $(function() {
            $('#ville_dep').change(function() {

                if ($(this).find('option:selected').attr('name') == '0') {
                    $('.villes_depard').show();

                } else {
                    $('.villes_depard').hide();
                    $('#' + $(this).find('option:selected').attr('name')).show();
                }


            });
        });


        $(function() {
            $('#ville_dep_comm').change(function() {

                if ($(this).find('option:selected').attr('name') == '0') {
                    $('.villes_depard_commission').show();

                } else {
                    $('.villes_depard_commission').hide();
                    $('#' + $(this).find('option:selected').attr('name')).show();
                }


            });
        });


        $(document).ready(function() {
            $('tabs').tabs();
        })

        //realisation mensuels chart
        const labels = {!! $data_date !!};

        const data = {
            labels: labels,
            datasets: [{
                label: 'Colis contre remboursement',
                backgroundColor: '#66bb6a',
                borderColor: 'rgb(255, 99, 132)',
                data: {!! $data_ECOM !!},
            }, {
                label: 'Colis déjà payer',
                backgroundColor: '#ffa726',
                borderColor: 'rgb(255, 99, 132)',
                data: {!! $data_CDP !!},
            }, {
                label: 'Retour',
                backgroundColor: '#ff5252',
                borderColor: 'rgb(255, 99, 132)',
                data: {!! $data_retour !!},
            }]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {}
        };


        //Evolution Chiffrechart
        const labelsEvolutionChiffre = {!! $data_date !!};

        const dataEvolutionChiffre = {
            labels: labelsEvolutionChiffre,
            datasets: [{
                label: "Chiffre d'affaire encaissé",
                //   backgroundColor: '#ff5252 ',
                borderColor: '#ff5252',
                data: {!! $chiffre_encaisse !!},
            }, {
                label: "Chiffre d'affaire réalisé",
                //   backgroundColor: '#42a5f5 ',
                borderColor: '#00bcd4',
                data: {!! $chiffre_realise !!},
            }]
        };

        const configEvolutionChiffre = {
            type: 'line',
            data: dataEvolutionChiffre,
            options: {

            }
        };

        //Taux de retour
        const labelsTauxretour = [
            'Taux de retour',
        ];

        const dataTauxretour = {
            labels: labelsTauxretour,
            datasets: [{

                backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.2)'],
                borderColor: ['rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)'],
                data: [19.5, 80],
            }]
        };

        const configTauxretour = {
            type: 'pie',
            data: dataTauxretour,
            options: {

            }
        };
    </script>
    <script>
        const Rmensuel = new Chart(
            document.getElementById('Rmensuel'),
            config
        );

        const EvolutionChiffre = new Chart(
            document.getElementById('EvolutionChiffre'),
            configEvolutionChiffre
        );
        const Tauxretour = new Chart(
            document.getElementById('Tauxretour'),
            configTauxretour
        );
    </script>
@stop
