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
        padding: 18px 20px;
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

    .input_container ul li {
        display: block;
        position: relative;
        float: left;
        width: 45%;
        height: 100px;
    }

    .input_container ul li input[type=radio] {
        position: absolute;
        visibility: hidden;
    }

    .input_container ul li label {
        display: block;
        color: black;
        text-align: center;
        border: 1px solid #C8C8C8;
        position: relative;
        font-weight: 300;
        font-size: 1.35em;
        padding: 25px 25px 25px 25px;
        z-index: 9;
        cursor: pointer;
        -webkit-transition: all 0.25s linear;
    }

    .input_container input[type=radio]:checked~label {
        color: white;
        background: #1991ce;
        border: 1px solid #2D35BA;
    }

    .switch-field #Inactif:checked+label {
        background-color: #c81537;
        box-shadow: none;
        color: white;
    }

    .switch-field #Actif:checked+label {
        background-color: #15c854;
        box-shadow: none;
        color: white;
    }
</style>
@section('content')
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">

                    <form method="POST" action="{{ route('employe_update', ['employe' => $record->id]) }}">
                        @csrf
                        <br>
                        @if (Session::has('success'))
                            <div class="card-alert card green">
                                <div class="card-content white-text">
                                    <p>{{ Session::get('success') }} </p>
                                </div>
                                <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-panel">

                                <div class="row">

                                    <div class="col s6 input-field">
                                        <div class="switch-field">

                                            <input type="radio" id="Actif" name='statut' value="1"
                                                {{ $record->statut == '1' ? 'checked' : '' }}>
                                            <label for="Actif">Actif</label>

                                            <input type="radio" id="Inactif" name='statut' value="0"
                                                {{ $record->statut == '0' ? 'checked' : '' }}>
                                            <label for="Inactif">Inactif</label>

                                        </div>
                                        @error('type_client')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col s6 input-field">
                                        <input id="libelle" name="libelle" value="{{ old('libelle', $record->libelle) }}"
                                            autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                            type="text">
                                        <label for="libelle"> Nom & Prénom </label>
                                        @error('libelle')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col s6 input-field">
                                        <textarea d="adresse" name="adresse" class="materialize-textarea">{{ old('adresse', $record->adresse) }}</textarea>
                                        <label for="adresse"> Adresse </label>
                                        @error('adresse')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col s6 input-field">
                                        <input id="telephone" name="telephone"
                                            value="{{ old('telephone', $record->telephone) }}" autocomplete="off" readonly
                                            onfocus="this.removeAttribute('readonly');" type="text">
                                        <label for="telephone"> Téléphone </label>
                                        @error('telephone')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col s6 input-field">
                                        <input id="email" name="email" value="{{ old('email', $record->email) }}"
                                            autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                            type="text">
                                        <label for="email"> Email </label>
                                        @error('email')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col s6 input-field">
                                        <select name='agence' id='agence' class="select2 browser-default">
                                            <option value=''></option>
                                            @foreach ($agenceRecords as $row)
                                                <option class='option'
                                                    {{ $row->id == old('agence', $record->agence) ? 'selected' : '' }}
                                                    value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                            @endforeach
                                        </select>
                                        <label for="agence"> Agence</label>
                                        @error('agence')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if ($record->ClientUserDetail)
                                        <div class="col s6 input-field">

                                            <select name='statut' id='statut' class="select2 browser-default">
                                                <option {{ $record->userDetail->validated == 1 ? 'selected' : '' }}
                                                    value='1'>Actif</option>
                                                <option {{ $record->userDetail->validated == 0 ? 'selected' : '' }}
                                                    value='2'>Inactif</option>
                                            </select>
                                            <label for="statut"> Agence</label>
                                            @error('statut')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror

                                        </div>
                                    @endif
                                    <div class="col s6 input-field">
                                        <select name='type' id='type' class="select2 browser-default">
                                            <option value=''></option>
                                            @foreach ($typesemployeRecords as $row)
                                                <option class='option'
                                                    {{ $row->code == old('type', $record->type) ? 'selected' : '' }}
                                                    value='{{ $row->code }}'> {{ $row->label }}</option>
                                            @endforeach
                                        </select>
                                        <label for="type"> Type</label>
                                        @error('type')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="col s6 input-field">
                                                <select name='fonction' id='fonction' class="select2 browser-default">
                                                    <option value=''></option>
                                                    @foreach ($fonctionRecords as $row)
                                                        <option class='option'
                                                            {{ $row->id == old('fonction', $record->fonction) ? 'selected' : '' }}
                                                            value='{{ $row->id }}'> {{ $row->label }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="fonction"> Fonction</label>
                                                @error('fonction')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div> --}}




                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="col s12 display-flex justify-content-end mt-3">
                                            <a href="{{ route('employe_list') }}"><button type="button"
                                                    class="btn btn-light">Retour </button></a>
                                            <button type="submit" class="btn indigo" style="margin-left: 1rem;">
                                                Enregistrer</button>
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
