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
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span> Retrouvement de l'expédition</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('stock_perdu_list') }}">Liste Stock Perdu </a>
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
                    <form method="POST" action="{{ route('retrouver_create', $expedition->id) }}">
                        @csrf
                        <br>
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
                                    <div class="col m6 s12 input-field">
                                        <select name='agence' id='agence' class="select2 browser-default">
                                            <option value=''></option>
                                            @foreach ($agenceRecords as $row)
                                                <option class='option'
                                                    {{ $row->id == $expedition->agence_des ? 'selected' : '' }}
                                                    value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                            @endforeach
                                        </select>
                                        <label for="agence"> Il a été retrouvé à</label>
                                        @error('agence')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
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
                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="col s12 display-flex justify-content-end mt-3">
                                            <a href="{{ route('exp_retrouver', $expedition->id) }}">
                                                <button type="button" class="btn btn-light"> Réinitialiser
                                                </button>
                                            </a>
                                            <button type="submit" class="btn indigo" style="margin-left: 1rem;">
                                                Retrouver
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
