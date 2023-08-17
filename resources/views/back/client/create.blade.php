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
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des clients</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('client_list') }}">Liste des clients</a>
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
                        <form method="POST" action="{{ route('client_create') }}">
                            @csrf
                        <br>
                        <div class="card">
                            <div class="card-panel">
                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="row">
                                            <div class="row">
                                                <div class="col s12 input-field input_container">
                                                    <p><label>
                                                            <h6>Type client :</h6>
                                                        </label></p>
                                                    <div class="switch-field">

                                                        <input type="radio" id="f-option" name='type_client'
                                                            value="Professionnel" checked>
                                                        <label for="f-option">Professionnel</label>

                                                        <input type="radio" id="s-option" name='type_client'
                                                            value="Physique">
                                                        <label for="s-option">Personne physique</label>

                                                    </div>
                                                    @error('type_client')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror


                                                </div>
                                            </div>


                                            {{-- <div class="col s12 input-field">
                                                <select name='type_client' id='type_client' class="select2 browser-default">
                                                    <option value=''></option>
                                                    <option value="Professionnel"> Professionnel </option>
                                                    <option value="Physique">  </option>
                                                </select> <label for="type_client"> </label>
                                                @error('type_client')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                            <div class="row">
                                                <div class="col m6 s12 input-field">
                                                    <input id="libelle" name="libelle" value="{{ old('libelle') }}"
                                                        autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="libelle"> Nom / R.S </label>
                                                    @error('libelle')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col m6 s12 input-field">
                                                    <input id="cin" name="cin" value="{{ old('cin') }}"
                                                        autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="cin"> CIN </label>
                                                    @error('cin')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col m6 s12 input-field">
                                                    <input id="telephone" name="telephone" value="{{ old('telephone') }}"
                                                        autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="telephone"> Téléphone </label>
                                                    @error('telephone')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col m6 s12 input-field">
                                                    <input id="email" name="email" value="{{ old('email') }}"
                                                        autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="email"> Email </label>
                                                    @error('email')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col m6 s12 input-field">
                                                    <select name='agence' id='agence' class="select2 browser-default">
                                                        <option value=''></option>
                                                        @foreach ($villeRecords as $row)
                                                            <option class='option'
                                                                {{ $row->id == old('agence') ? 'selected' : '' }}
                                                                value='{{ $row->id }}'> {{ $row->libelle }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="agence"> Ville</label>
                                                    @error('agence')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                {{-- <div class="col m6 s12 input-field">
                                            <select name='agence' id='agence' class="select2 browser-default">
                                                <option value=''></option>
                                                @foreach ($agenceRecords as $row)
                                                <option class='option' {{ $row->id == old('agence') ? 'selected' : '' }}
                                                    value='{{ $row->id }}'> {{ $row->Libelle }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <label for="agence"> Agence</label>
                                            @error('agence')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div> --}}
                                            </div>
                                            <hr>




                                            <div class="row">
                                                <div class="col m6 s12 input-field">
                                                    <select name='commerciale' id='commerciale'
                                                        class="select2 browser-default">
                                                        <option value=''></option>
                                                        @foreach ($CommercialRecords as $row)
                                                            <option class='option'
                                                                {{ $row->id == old('commerciale') ? 'selected' : '' }}
                                                                value='{{ $row->id }}'> {{ $row->libelle }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="commerciale"> Commerciale</label>
                                                    @error('commerciale')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col m6 s12 input-field">
                                                    <input value="5" type="number" id="seuil_colis"
                                                        name="seuil_colis" value="{{ old('seuil_colis') }}"
                                                        autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="seuil_colis"> Min colis / Ram </label>
                                                    @error('seuil_colis')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col m6 s12 input-field">
                                                    <input id="valeur_declaree" type="number" name="valeur_declaree"
                                                        value="{{ old('valeur_declaree') ?? 1 }}" autocomplete="off"
                                                        readonly onfocus="this.removeAttribute('readonly');"
                                                        type="text">
                                                    <label for="valeur_declaree">Valeur Déclarée</label>
                                                    @error('valeur_declaree')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col m6 s12 input-field">
                                                    <select name='type_remboursement' id='type_remboursement'
                                                        class="select2 browser-default">
                                                        <option value=''></option>
                                                        <option value="Virrement"> Par Virement </option>
                                                        <option value="Cheque"> Par Chèque </option>
                                                        <option value="Espece"> Par Espèce </option>
                                                    </select> <label for="type_remboursement"> Type remboursement</label>
                                                    @error('type_remboursement')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col m6 s12 input-field">
                                                    <input id="vplafond" type="text" name="vplafond"
                                                        value="{{ old('vplafond')}}" autocomplete="off"
                                                        readonly onfocus="this.removeAttribute('readonly');"
                                                        type="text">
                                                    <label for="vplafond">Valeur déclarée automatique</label>
                                                    @error('vplafond')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col m6 s12 input-field">
                                                    <select name='num_exp' id='num_exp'
                                                        class="select2 browser-default">
                                                        <option value="AUTO" selected="selected">Automatique</option>
                                                        <option value="CLIENT">Par le client</option>
                                                    </select>
                                                    <label> N° Expédition</label>
                                                </div>
                                                <div class="col m6 s12 input-field">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col m6 s12 input-field">
                                                    <input id="email_nolivre" name="email_nolivre" value="{{ old('email_nolivre') }}"
                                                        autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="email_nolivre"> Email non livré :</label>
                                                    @error('email_nolivre')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col m6 s12 input-field">
                                                    <input id="email_rembroursement" name="email_rembroursement" value="{{ old('email_rembroursement') }}"
                                                        autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="email_rembroursement"> Email remboursement :</label>
                                                    @error('email_rembroursement')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col m6 s12 input-field">
                                                    <input id="rib" name="rib" value="{{ old('rib') }}"
                                                        autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="rib"> RIB </label>
                                                    @error('rib')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col m6 s12 input-field">
                                                    <select name='banque' id='banque'
                                                        class="select2 browser-default">
                                                        <option value=''></option>
                                                        @foreach ($banqueRecords as $row)
                                                            <option class='option'
                                                                {{ $row->id == old('banque') ? 'selected' : '' }}
                                                                value='{{ $row->id }}'> {{ $row->libelle }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="banque"> Banque</label>
                                                    @error('banque')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col m6 s12 input-field">
                                                    <input id="rc_org" name="rc_org" value="{{ old('rc_org') }}"
                                                        autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="rc_org"> RC </label>
                                                    @error('rc_org')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col m6 s12 input-field">
                                                    <input id="ice_org" name="ice_org" value="{{ old('ice_org') }}"
                                                        autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="ice_org"> ICE </label>
                                                    @error('ice_org')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col m6 s12 input-field">
                                                    <input id="if_org" name="if_org" value="{{ old('if_org') }}"
                                                        autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="if_org"> IF </label>
                                                    @error('if_org')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col m6 s12 input-field">
                                                    <input id="cnss_org" name="cnss_org"
                                                        value="{{ old('cnss_org') }}" autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="cnss_org"> CNSS </label>
                                                    @error('cnss_org')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col m6 s12 input-field">
                                                    <input id="apatente_org" name="apatente_org"
                                                        value="{{ old('apatente_org') }}" autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="apatente_org"> Patente </label>
                                                    @error('apatente_org')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col m6 s12 input-field">
                                                    <input id="code_cmpt" name="code_cmpt"
                                                        value="{{ old('code_cmpt') }}" autocomplete="off" readonly
                                                        onfocus="this.removeAttribute('readonly');" type="text">
                                                    <label for="code_cmpt"> Compte Comptable </label>
                                                    @error('code_cmpt')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col s6 input-field">

                                                    <h6>Facture fin du mois :</h6>


                                                    <div class="switch-field">
                                                        <input type="radio" id="fac-oui" name="factureMois"
                                                            value="Oui" onchange="changebyfacture(this)" />
                                                        <label for="fac-oui">Oui</label>
                                                        <input type="radio" id="fac-non" checked name="factureMois"
                                                            value="Non" onchange="changebyfacture(this)" />
                                                        <label for="fac-non">Non</label>
                                                    </div>

                                                    @error('factureMois')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col s6 input-field">

                                                    <h6>Colis en échange :</h6>


                                                    <div class="switch-field">
                                                        <input type="radio" id="ech-oui" name="colisEchange"
                                                            value="Oui" />
                                                        <label for="ech-oui">Oui</label>
                                                        <input type="radio" id="ech-non" checked name="colisEchange"
                                                            value="Non" />
                                                        <label for="ech-non">Non</label>
                                                    </div>

                                                    @error('factureMois')
                                                        <span
                                                            class="helper-text materialize-red-text">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">

                                    <div class="col s6 input-field" id="colis_simple">
                                        <h6>Colis Simple :</h6>
                                        <div class="switch-field">
                                            <input type="radio" id="simple-oui" name="colisSimple" value="Oui"
                                                onchange="changetype(this)" />
                                            <label for="simple-oui">Oui</label>
                                            <input type="radio" checked id="simple-non" name="colisSimple"
                                                value="Non" onchange="changetype(this)" />
                                            <label for="simple-non">Non</label>
                                        </div>
                                        @error('colisSimple')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col s6 input-field">
                                        <h6>Remboursement rapide :</h6>
                                        <div class="switch-field">
                                            <input type="radio" id="remboursement_rapide-oui" name="remboursement_rapide" value="Oui"
                                                />
                                            <label for="remboursement_rapide-oui">Oui</label>
                                            <input type="radio" checked id="remboursement_rapide-non" name="remboursement_rapide"
                                                value="Non"  />
                                            <label for="remboursement_rapide-non">Non</label>
                                        </div>
                                        @error('remboursement_rapide')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col s6 input-field" id="pp_simple">

                                        <h6>Sélectionner le type :</h6>


                                        <div class="switch-field">
                                            <input type="radio" id="PP" name="ppSimple" value="PP" />
                                            <label for="PP">PP</label>
                                            <input type="radio" id="PPNE" name="ppSimple" value="PPNE" />
                                            <label for="PPNE">PPNE</label>
                                        </div>

                                        @error('ppSimple')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>


                            <div style="padding-right: 6%;" class=" display-flex justify-content-end mt-3">
                                <a href="{{ route('client_list') }}"><button type="button"
                                        class="btn btn-light">Retour
                                    </button></a>
                                <button type="submit" class="btn indigo" style="margin-left: 1rem;">
                                    Enregistrer</button>

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
        if (document.getElementById('fac-non').checked) {
            if (document.getElementById('simple-oui').checked) {
                document.getElementById('pp_simple').style.display = 'block';
            } else {
                document.getElementById('pp_simple').style.display = 'none';
            }
        } else if (document.getElementById('fac-oui').checked) {
            document.getElementById('pp_simple').style.display = 'none';
            document.getElementById('colis_simple').style.display = 'none';
        }

        function changetype(element) {
            if (element.id == 'simple-oui' && element.checked) {
                document.getElementById('pp_simple').style.display = 'block';

            } else if (element.id == 'simple-non' && element.checked) {
                document.getElementById('pp_simple').style.display = 'none';
            }
        }

        function changebyfacture(element) {
            if (element.id == 'fac-non' && element.checked) {
                document.getElementById('colis_simple').style.display = 'block';
                if (document.getElementById('simple-oui').checked) {
                    document.getElementById('pp_simple').style.display = 'block';
                } else {
                    document.getElementById('pp_simple').style.display = 'none';
                }

            } else if (element.id == 'fac-oui' && element.checked) {
                document.getElementById('colis_simple').style.display = 'none';
                document.getElementById('pp_simple').style.display = 'none';

            }
        }
    </script>
@stop
