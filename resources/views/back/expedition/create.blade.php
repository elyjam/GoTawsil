@extends($layout)

<style>
    .switch-field {
        display: flex;
        margin-top: 13px;
        overflow: hidden;
    }

    .switch-field input {
        position: absolute !important;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        width: 1px;
        border: 0;
        overflow: hidden;
    }

    .switch-field label {
        background-color: #e4e4e4;
        color: rgba(0, 0, 0, 0.6);
        font-size: 14px;
        line-height: 1;
        text-align: center;
        padding: 13px 20px;
        margin-right: -1px;
        border: 1px solid rgba(0, 0, 0, 0.2);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
        transition: all 0.1s ease-in-out;
    }

    .switch-field label:hover {
        cursor: pointer;
    }

    .switch-field input:checked+label {
        background-color: #1991ce;
        box-shadow: none;
        color: white;
    }

    .switch-field label:first-of-type {
        border-radius: 4px 0 0 4px;
    }

    .switch-field label:last-of-type {
        border-radius: 0 4px 4px 0;
    }

    .card-panel {
        padding-top: 34px !important;
        padding-bottom: 34px !important;
    }

    input[type=text]:not(.browser-default):focus:not([readonly]) {
        border: 0 !important;
    }


    @media only screen and (max-width: 1100px) {
        .row hr {

            border: 0;
            height: 1px;
            margin: 30px 0px;
            background-image: linear-gradient(to right, rgba(25, 145, 206, 0), rgba(25, 145, 206, 0.75), rgba(25, 145, 206, 0));

        }
    }

    @media only screen and (min-width: 1100px) {

        .card {
            margin: 1rem 5rem 1rem 5rem !important;

        }

        .input-field.col label {
            left: 3.75rem !important;
        }

        .input-field {

            padding-inline: 50px !important;
        }

        .row hr {
            border: 0;
            height: 1px;
            margin: 40px 0px;
            background-image: linear-gradient(to right, rgba(25, 145, 206, 0), rgba(25, 145, 206, 0.75), rgba(25, 145, 206, 0));
        }


    }



    @import url('https://fonts.googleapis.com/css?family=Lato');

    body,
    html {
        height: 100%;
        background: white;
        font-family: 'Lato', sans-serif;
    }

    /* .input_container {
  width: 100%;
  padding: 0;
  margin: 0;
} */

    /* .container ul {
  list-style: none;
  height: 100%;
  width: 100%;
  margin: 0;
  padding: 0;
} */
</style>

@section('content')
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Saisies des colis</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('expedition_list') }}">Liste des expeditions</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <form method="POST" action="{{ route('expedition_create') }}">
                        @csrf
                        <br>

                        <div class="card">
                            <div class="card-panel">
                                <div class="row">
                                    <div class="col s12 m12">

                                        <div class="row">
                                            <div class="col s12 input-field">
                                                <select name='client' id='client' class="select2 browser-default">

                                                    @foreach ($clientRecords as $row)
                                                        <option class='option'
                                                            {{ $row->id == old('client') ? 'selected' : '' }}
                                                            value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="client"> Expéditeur</label>
                                                @error('client')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12 input-field">
                                                <h6>
                                                    <label for=""> Type de livraison :</label>
                                                </h6>
                                                <div class="switch-field">

                                                    <input type="radio" id='remboursement' name='type' value='ECOM'
                                                        {{ 'ECOM' == old('type') ? 'checked' : '' }} />
                                                    <label for="remboursement">Colis contre remboursement</label>

                                                    <input type="radio" id='payer' name='type' value='CDP'
                                                        {{ 'CDP' == old('type') ? 'checked' : '' }} />
                                                    <label for="payer">Colis déjà payer</label>


                                                    <input type="radio" id="echange" name="type" value="COLECH"
                                                        {{ 'COLECH' == old('type') ? 'checked' : '' }} />
                                                    <label for="echange">Colis en échange</label>

                                                </div>
                                                @error('type')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col col m12 s12 px-5 input-field" id="Expeditions">
                                            <select name="echange" id='mes_expedition' class="select2 browser-default">


                                            </select>

                                            <label for="mes_expedition"> Éxpedition á échangé</label>
                                            @error('mes_expedition')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col col m6 s12 px-5 input-field">

                                                <select name='agence' id='agence' class="select2 browser-default">
                                                    <option value=''></option>
                                                    @foreach ($getVille as $row)
                                                        <option class='option'
                                                            {{ $row->id == old('agence') ? 'selected' : '' }}
                                                            value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="agence">Origin</label>
                                                @error('agence')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col col m6 s12 input-field">
                                                <input id="destinataire" name="destinataire"
                                                    value="{{ old('destinataire') }}" autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly');" type="text">
                                                <label for="destinataire"> Destinataire </label>
                                                @error('destinataire')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col m6 s12 input-field">
                                                <input id="adresse_destinataire" name="adresse_destinataire"
                                                    value="{{ old('adresse_destinataire') }}" autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly');" type="text">
                                                <label for="adresse_destinataire"> Adresse </label>
                                                @error('adresse_destinataire')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col m6 s12 input-field">
                                                <input id="telephone" name="telephone" value="{{ old('telephone') }}"
                                                    autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                    type="text">
                                                <label for="telephone"> Téléphone </label>
                                                @error('telephone')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12 input-field">
                                                <select name='agence_des' id='agence_des'
                                                    class="select2 browser-default">
                                                    <option value=''></option>
                                                    @foreach ($agenceRecords as $row)
                                                        <option class='option'
                                                            {{ $row->id == old('agence_des') ? 'selected' : '' }}
                                                            value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="agence_des"> Destination</label>
                                                @error('agence_des')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="col m6 s12 input-field">
                                                <select name='port' id='port' class="select2 browser-default">
                                                    <option value=''></option>
                                                    <option value="PD" {{ 'PD' == old('port') ? 'selected' : '' }}> PD
                                                    </option>
                                                    <option value="PP" {{ 'PP' == old('port') ? 'selected' : '' }}>
                                                        PP</option>
                                                    <option value="PPE" {{ 'PPE' == old('port') ? 'selected' : '' }}>
                                                        PPE</option>
                                                    <option value="PPNE" {{ 'PPNE' == old('port') ? 'selected' : '' }}>
                                                        PPNE</option>
                                                </select> <label for="port"> Port</label>
                                                @error('port')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col m6 s12 input-field">
                                                <input id="ttc" name="ttc" value="{{ old('ttc') }}"
                                                    autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly');" type="text">
                                                <label for="ttc"> Prix colis </label>
                                                @error('ttc')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>


                                        </div>
                                        @if (Session::has('fail'))
                                            <div class="card-alert card red">
                                                <div class="card-content white-text">
                                                    <p>{{ Session::get('fail') }} </p>
                                                </div>
                                                <button type="button" class="close white-text" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                        @endif
                                        <div class="row">


                                            {{-- <div class="col m6 s12 input-field">
                                            <select name='retour_fond' id='retour_fond' class="select2 browser-default">
                                                <option value=''></option>
                                                <option value="CR"> Contre Espèce</option>
                                                <option value="S"> Simple</option>
                                            </select> <label for="retour_fond"> Nature</label>
                                            @error('retour_fond')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div> --}}

                                            <div class="col m6 s12 input-field">
                                                <input id="fond" name="fond" value="{{ old('fond') }}"
                                                    autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly');" type="text">
                                                <label for="fond"> Fond </label>
                                                @error('fond')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col col m6 s12 input-field">
                                                <input id="colis" name="colis" value="{{ old('colis', 1) }}"
                                                    autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly');" type="number"
                                                    min="1" max="20">
                                                <label for="colis" class="active"> Nb. Colis </label>


                                                @error('colis')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col col m6 s12 input-field">
                                                <input id="vDeclaree" name="vDeclaree" value="{{ old('vDeclaree') }}"
                                                    autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly');" type="text">
                                                <label for="vDeclaree"> V. Déclarée </label>
                                                @error('vDeclaree')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col m6 s12 input-field">
                                                <input id="Indication" name="Indication" value="{{ old('Indication') }}"
                                                    autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly');" type="text">
                                                <label for="Indication">Indication</label>
                                                @error('Indication')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col m6 s12 input-field">



                                                <h6>
                                                    <label for=""> Paiement / chèque :</label>
                                                </h6>
                                                <div class="switch-field">

                                                    <input type="radio" id='chequeOui' name='paiementCheque'
                                                        value="Oui">
                                                    <label for="chequeOui">Oui</label>

                                                    <input type="radio" id='chequeNon' name='paiementCheque'
                                                        value="Non" checked>
                                                    <label for="chequeNon">Non</label>

                                                </div>


                                                @error('paiementCheque')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col col m6 s12 input-field">

                                                <h6>
                                                    <label for=""> Ouverture Colis :</label>
                                                </h6>
                                                <div class="switch-field">

                                                    <input type="radio" id='ouvertureOui' name='ouvertureColis'
                                                        value="Oui">
                                                    <label for="ouvertureOui">Oui</label>

                                                    <input type="radio" id='ouvertureNon' name='ouvertureColis'
                                                        value="Non" checked>
                                                    <label for="ouvertureNon">Non</label>

                                                </div>


                                                @error('ouvertureColis')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <input type="hidden" name="etape" value="1">
                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="col s12 display-flex justify-content-end mt-3">
                                            <a href="{{ route('expedition_list') }}">
                                                <button type="button" class="btn btn-light">Retour
                                                </button>
                                            </a>
                                            <button type="submit" class="btn indigo" style="margin-left: 1rem;">
                                                Enregistrer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
@section('js')
    <script>
        $(document).ready(function() {
            $('#selectaff').change(function() {
                let cid = $(this).val();
                if (cid != '') {
                    $.ajax({
                        url: '/arrivage/bon/list',
                        type: 'post',
                        data: 'cid=' + cid + '&_token={{ csrf_token() }}',
                        success: function(result) {
                            $('#datax').html(result)
                        }
                    });
                }

            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#Expeditions').hide();



            if ($("#echange").is(':checked')) {
                brightEchange();
            }



            $('#echange').click(function() {
                brightEchange();
            });
            $('#payer').click(function() {
                $('#Expeditions').hide();
            });
            $('#remboursement').click(function() {
                $('#Expeditions').hide();
            });
            $('#agence_des').change(function() {
                let colis = $("#colis").val();
                let client = $("#client").val();
                let fond = $("#fond").val();
                let agence = $("#agence").val();
                let vDeclaree = $("#vDeclaree").val();
                let agence_des = $("#agence_des").val();
                $.ajax({
                    url: '/expedition/getprixcolis',
                    type: 'get',
                    data: {
                        'client': client,
                        'agence': agence,
                        'agence_des': agence_des,
                        'colis': colis,
                        'vDeclaree': vDeclaree,
                        'fond': fond
                    },
                    success: function(result) {
                        console.log(result)
                        $('#ttc').val(result).next().addClass("active");
                    }
                });


            });
            $('#agence').change(function() {
                let colis = $("#colis").val();
                let client = $("#client").val();
                let agence = $("#agence").val();
                let vDeclaree = $("#vDeclaree").val();
                let agence_des = $("#agence_des").val();
                let fond = $("#fond").val();
                $.ajax({
                    url: '/expedition/getprixcolis',
                    type: 'get',
                    data: {
                        'client': client,
                        'agence': agence,
                        'agence_des': agence_des,
                        'colis': colis,
                        'vDeclaree': vDeclaree,
                        'fond': fond
                    },
                    success: function(result) {
                        console.log(result)
                        $('#ttc').val(result).next().addClass("active");
                    }
                });


            });

            $('#colis').change(function() {
                let colis = $("#colis").val();
                let client = $("#client").val();
                let agence = $("#agence").val();
                let vDeclaree = $("#vDeclaree").val();
                let agence_des = $("#agence_des").val();
                let fond = $("#fond").val();
                $.ajax({
                    url: '/expedition/getprixcolis',
                    type: 'get',
                    data: {
                        'client': client,
                        'agence': agence,
                        'agence_des': agence_des,
                        'colis': colis,
                        'vDeclaree': vDeclaree,
                        'fond': fond
                    },
                    success: function(result) {
                        console.log(result)
                        $('#ttc').val(result).next().addClass("active");
                    }
                });


            });

            $('#client').change(function() {

                if ($("#echange").is(':checked')) {
                brightEchange();
            }



                let colis = $("#colis").val();
                let client = $("#client").val();
                let agence = $("#agence").val();
                let vDeclaree = $("#vDeclaree").val();
                let agence_des = $("#agence_des").val();
                let fond = $("#fond").val();
                $.ajax({
                    url: '/expedition/getprixcolis',
                    type: 'get',
                    data: {
                        'client': client,
                        'agence': agence,
                        'agence_des': agence_des,
                        'colis': colis,
                        'vDeclaree': vDeclaree,
                        'fond': fond
                    },
                    success: function(result) {

                        $('#ttc').val(result).next().addClass("active");
                    }
                });


            });

            $('#vDeclaree').keyup(function() {
                let colis = $("#colis").val();
                let client = $("#client").val();
                let agence = $("#agence").val();
                let vDeclaree = $("#vDeclaree").val();
                let agence_des = $("#agence_des").val();
                let fond = $("#fond").val();
                $.ajax({
                    url: '/expedition/getprixcolis',
                    type: 'get',
                    data: {
                        'client': client,
                        'agence': agence,
                        'agence_des': agence_des,
                        'colis': colis,
                        'vDeclaree': vDeclaree,
                        'fond': fond
                    },
                    success: function(result) {
                        console.log(result)
                        $('#ttc').val(result).next().addClass("active");
                    }
                });


            });
            $('#fond').keyup(function() {
                let colis = $("#colis").val();
                let client = $("#client").val();
                let agence = $("#agence").val();
                let vDeclaree = $("#vDeclaree").val();
                let agence_des = $("#agence_des").val();
                let fond = $("#fond").val();
                $.ajax({
                    url: '/expedition/getprixcolis',
                    type: 'get',
                    data: {
                        'client': client,
                        'agence': agence,
                        'agence_des': agence_des,
                        'colis': colis,
                        'vDeclaree': vDeclaree,
                        'fond': fond
                    },
                    success: function(result) {
                        console.log(result)
                        $('#ttc').val(result).next().addClass("active");
                    }
                });


            });
            function brightEchange(){
            let cid = $('#client').val();
            $.ajax({
                    url: '/expedition/enechange',
                    type: 'post',
                    data: 'cid=' + cid + '&_token={{ csrf_token() }}',
                    success: function(result) {
                        if(result == 0){
                            $('#mes_expedition').html(" <option value = '0'> Il y a pas d'éxpedition á échangé </option>")
                        }else{

                         $('#mes_expedition').html(result)
                        }
                        $('#Expeditions').show();

                    }
                });
        }
        });
    </script>
@stop
