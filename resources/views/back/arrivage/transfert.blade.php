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
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span> Transfert & Réeacheminement</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('stock_list') }}">Liste Stock </a>
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
                    <form method="POST" action="{{ route('transfert_Create', $expedition->id) }}">
                        @csrf
                        <br>
                        @if (session()->has('error'))
                        <div class="card-alert card red">
                            <div class="card-content white-text">
                                <p> {{ session()->get('error') }}</p>
                            </div>
                            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        @endif
                        <div class="card">
                            <div class="card-panel">



                                <div class="row">


                                    <div class="row padding-2">
                                        <div class="col s12 m4">
                                            <div
                                                class="card gradient-shadow border border-radius-3">
                                                <div class="card-content center">

                                                    <h5 class="black-text lighten-4">Expédition N°</h5>
                                                    <p class="blue-text lighten-4">{{ $expedition->num_expedition }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s12 m4">
                                            <div class="card gradient-shadow border border-radius-3">
                                                <div class="card-content center">
                                                    <h5 class="black-text lighten-4">Origine</h5>
                                                    <p class="blue-text lighten-4">{{ $expedition->agenceDetail->libelle }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s12 m4">
                                            <div class="card gradient-shadow border border-radius-3">
                                                <div class="card-content center">

                                                    <h5 class="black-text lighten-4">Expéditeur</h5>
                                                    <p class="blue-text lighten-4">{{ $expedition->clientDetail->libelle }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- <div class="col s12 input-field">

                                        <h6>Expédition N° : {{ $expedition->num_expedition }}</h6>
                                        <h6>Origine : {{ $expedition->agenceDetail->Libelle }}</h6>
                                        <h6>Expéditeur : {{ $expedition->clientDetail->libelle }}</h6>


                                    </div> --}}
                                </div>

                                <div class="row">

                                    <div class="col s12 input-field">



                                        <h6>
                                            <label for=""> Type de livraison :</label>
                                        </h6>
                                        <div class="switch-field">

                                            <input type="radio" id='Oui' name='type' value='ECOM'
                                                 {{ old('type',$expedition->type) == 'ECOM' ? 'checked' : '' }}>
                                            <label for="Oui">Colis contre remboursement</label>

                                            <input type="radio" id='Non' name='type' value='CDP'
                                                {{ old('type',$expedition->type) == 'CDP' ? 'checked' : '' }}>
                                            <label for="Non">Colis déjà payer</label>


                                            {{-- <input type="radio" id="radio-three" name="type" value="COLECH"
                                                        {{ $expedition->type == 'COLECH' ? 'checked' : '' }} />
                                                    <label for="radio-three">Colis en échange</label> --}}

                                        </div>
                                        @error('type')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col m6 s12 input-field">
                                        <input id="destinataire" name="destinataire"
                                            value="{{ old('destinataire',$expedition->destinataire)}}" autocomplete="off" readonly
                                            onfocus="this.removeAttribute('readonly');" type="text">
                                        <label for="destinataire"> Destinataire </label>
                                        @error('destinataire')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col m6 s12 input-field">
                                        <select name='agence_des' id='agence_des' class="select2 browser-default">
                                            <option value=''></option>
                                            @foreach ($agenceRecords as $row)
                                                <option class='option'
                                                    {{ $row->id == old('agence_des') ? 'selected' : '' }}
                                                    {{ $row->id == $expedition->agence_des ? 'selected' : '' }}

                                                    value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                            @endforeach
                                        </select>
                                        <label for="agence_des"> Destination</label>
                                        @error('agence_des')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col m6 s12 input-field">
                                        <input id="adresse_destinataire" name="adresse_destinataire"
                                            value="{{ old('adresse_destinataire',$expedition->adresse_destinataire) }}" autocomplete="off" readonly
                                            onfocus="this.removeAttribute('readonly');" type="text">
                                        <label for="adresse_destinataire"> Adresse </label>
                                        @error('adresse_destinataire')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col m6 s12 input-field">
                                        <input id="telephone" name="telephone"
                                            value="{{ old('telephone',$expedition->telephone) }}" autocomplete="off" readonly
                                            onfocus="this.removeAttribute('readonly');" type="text">
                                        <label for="telephone"> Téléphone </label>
                                        @error('telephone')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <hr>
                                <div class="row">


                                    {{-- <div class="col m6 s12 input-field">
                                        <select name='retour_fond' id='retour_fond' class="select2 browser-default">

                                            <option {{ $expedition->port == 'CR' ? 'selected' : '' }} value="CR">
                                                Contre Espèce</option>
                                            <option {{ $expedition->port == 'S' ? 'selected' : '' }} value="S">
                                                Simple</option>
                                        </select> <label for="retour_fond"> Nature</label>
                                        @error('retour_fond')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                    <div class="col m6 s12 input-field">
                                        <input id="fond" name="fond" value="{{ old('fond',$expedition->fond )}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                        <label for="fond"> Fond </label>
                                        @error('fond')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col m6 s12 input-field">
                                        <select name='port' id='port' class="select2 browser-default">
                                            <option value=''></option>
                                            <option {{old('port',$expedition->port) == 'PD' ? 'selected' : '' }} value="PD">
                                                Port Dû</option>
                                            <option {{old('port',$expedition->port)  == 'PP' ? 'selected' : '' }} value="PP">
                                                Port Payé</option>
                                                <option {{old('port',$expedition->port)  == 'PPE' ? 'selected' : '' }} value="PPE">
                                                    Port Payé Enc</option>
                                            <option {{ old('port',$expedition->port)  == 'PPNE' ? 'selected' : '' }} value="PPNE">PPNE</option>

                                        </select> <label for="port"> Port</label>
                                        @error('port')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">


                                    <div class="col m6 s12 input-field">
                                        <input id="ttc" name="ttc" value="{{ old('ttc',$expedition->ttc )}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                        <label for="ttc"> Prix colis </label>
                                        @error('ttc')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col col m6 s12 input-field">
                                        <input id="colis" name="colis" value="{{ old('colis',$expedition->colis) }}"
                                            autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                            type="number">
                                        <label for="colis"> Nb. Colis </label>
                                        @error('colis')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col col m6 s12 input-field">
                                       <p id="prixcolis"></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col col m12 s12 input-field">
                                        {{-- <input id="commentaire" name="commentaire" value="" autocomplete="off"
                                                    readonly onfocus="this.removeAttribute('readonly');" type="text">
                                                <label for="commentaire">Commentaire</label> --}}



                                        <i class="material-icons prefix">comment</i>
                                        <input id="commentaire" name="commentaire" required autocomplete="off" readonly
                                            onfocus="this.removeAttribute('readonly');" type="text" />
                                        <label for="commentaire">Ajouter un commentaire</label>

                                        @error('commentaire')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>


                                </div>


                                <input type="hidden" name="etape" value="1">
                                <input value="{{$expedition->agence}}" name="agence" id="agence" type="text" hidden>
                                <input value="{{$expedition->ClientDetail->id}}" name="client" id="client" type="text" hidden>

                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="col s12 display-flex justify-content-end mt-3">
                                            <a href="{{ route('exp_transfert', $expedition->id) }}">
                                                <button type="button" class="btn btn-light"> Réinitialiser
                                                </button>
                                            </a>
                                            <button type="submit" class="btn indigo" style="margin-left: 1rem;">
                                                Transferer
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

        $('#agence_des').change(function() {
            let client = $("#client").val();
            let agence = $("#agence").val();
            let agence_des = $("#agence_des").val();
            let agence_d = $("#agence_des option[value="+$("#agence_des").val()+"] ").attr('selected','selected').html();
            $.ajax({
                url: '/expedition/getprixcolis',
                type: 'get',
                data: {
                    'client': client,
                    'agence': agence,
                    'agence_des': agence_des,
                },
                success: function(result) {
                    $oldPrice = parseInt($('#ttc').val());
                    $resultat = parseInt(result);

                    if( $oldPrice < $resultat){
                        $('#ttc').val(result).next().addClass("active");
                        $('#prixcolis').val("Le prix d'expédition vers ");
                    }
                    $('#prixcolis').html("Le prix d'expédition vers"+agence_d + " = "+result+" Dh");
                }
            });


        });

    });
</script>
@stop
