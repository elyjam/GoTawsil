@extends($layout)

@section('content')
<style>
label {
    font-size: 14px !important;
}

input {
    font-size: 12px !important;
}

textarea {
    font-size: 12px !important;
}

.invalid {
    font-size: 10px !important;
}
</style>
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg-client.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Chargement en masse</span></h5>
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                @if (Session::has('success'))
                <div class="card-alert card green">
                    <div class="card-content white-text">
                        <p>{{ Session::get('success') }} </p>
                    </div>
                    <button type="button" onclick="$(this).parent().remove();" class="close white-text"
                        data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                @endif

                @if (Session::has('error'))
                <div class="card-alert card red">
                    <div class="card-content white-text">
                        <p>{{ Session::get('error') }} </p>
                    </div>
                    <button type="button" class="close white-text" onclick="$(this).parent().remove();"
                        data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                @endif

                <div class="row">
                    <div class="col s12">

                        @if($expeditions === null || ( is_array($expeditions) && count($expeditions)===0 ) )
                        <div class="card ">
                            <div class="card-content" style="height: 160px;">
                                <a href="/assets/templates/modéle d'import des expédition.xlsx"><i
                                        class="material-icons">insert_drive_file</i> Télécharger le modèle
                                    d'import </a><br><br>
                                <div>
                                    <form method="POST" enctype="multipart/form-data">
                                        @CSRF

                                        <div class="file-field input-field" style="width: 50%; float: left;">
                                            <div class="btn">
                                                <i class="material-icons left">insert_drive_file</i>
                                                <span>Fichier</span>
                                                <input name="file" type="file">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path" type="text">
                                            </div>
                                        </div>

                                        <div class="file-field">
                                            <button type="submit" class="btn" style="margin: 1rem;"><i
                                                    class="material-icons left">file_upload</i>
                                                Charger
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>

                        <div class="list-table" id="app">
                            <div class="card">
                                <div class="card-content">
                                    <!-- datatable start -->
                                    <div class="responsive-table">
                                        <table id="list-datatable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>

                                                    <td>Code</td>
                                                    <td>Date Création</td>
                                                    <th>Etiquettes</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($chargementMass as $record)
                                                <tr>
                                                    <td>{{ $record->code }}</td>
                                                    <td> {{ $record->created_at }} </td>

                                            <td>   <a
                                                href="{{ route('chargement_mass_print_detail', $record->id) }} "target="_blank"><i
                                                    class="material-icons tooltipped" style="color: #c10027;"
                                                    data-position="top" data-tooltip="Imprimer">print</i></a></td>

                                                </tr>
                                                @endforeach

                                            </tbody>

                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                        </div>
                        @else
                        <form method="POST" enctype="multipart/form-data">
                            @CSRF
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3"
                                        style="margin-top: 0px !important;">
                                        <a href="{{route('expedition_import')}}">
                                            <button type="button" class="btn"><i class="material-icons left ">arrow_back</i>
                                                Retour
                                            </button>
                                        </a>
                                        <button type="submit" class="btn waves-effect waves-light"
                                            style="margin-left: 1rem;background-color: #089e0e;">
                                            <i class="material-icons left ">save</i> Enregistrer
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @foreach ($expeditions as $expedition)

                            <div class="card">
                                <div class="card-panel" style="padding: 5px;">
                                    <div class="row">
                                        <div class="col s12 m12">
                                            <div class="row">
                                                <div class="col s12 input-field"
                                                    style="padding: 0px;margin: 0px;text-align: right;">
                                                    <i onclick="$(this).closest('.card').remove();"
                                                        class="material-icons"
                                                        style="color: red;padding-right: 7px;cursor: pointer;">cancel</i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" col s12 m12">
                                            <div class="row">
                                                <div class="col s2 input-field">
                                                    <input name="destinataire[{{$loop->index}}]"
                                                        value="{{$expedition['destinataire']}}" placeholder=""
                                                        type="text">
                                                    <label for="code"> Destinataire </label>
                                                    @if(isset($errors[$loop->index.'_dest']))
                                                    <span
                                                        class="helper-text materialize-red-text invalid">{{$errors[$loop->index.'_dest']}}</span>
                                                    @endif
                                                </div>
                                                <div class="col s1 input-field">
                                                    <select name='ville[{{$loop->index}}]'
                                                        class="select2 browser-default">
                                                        <option value=''></option>
                                                        @foreach($villes as $ville => $id)
                                                        <option value='{{$ville}}'
                                                            {{ $ville == $expedition['ville'] ? 'selected' : '' }}>
                                                            {{$ville}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label style="font-size: 10px !important;"> Ville Destinataion
                                                    </label>
                                                    @if(isset($errors[$loop->index.'_ville']))
                                                    <span
                                                        class="helper-text materialize-red-text invalid">{{$errors[$loop->index.'_ville']}}</span>
                                                    @endif
                                                </div>
                                                <div class="col s1 input-field">
                                                    <input name="tel[{{$loop->index}}]" value="{{$expedition['tel']}}"
                                                        placeholder="" type="text">
                                                    <label> Téléphone </label>
                                                    @if(isset($errors[$loop->index.'_tel']))
                                                    <span
                                                        class="helper-text materialize-red-text invalid">{{$errors[$loop->index.'_tel']}}</span>
                                                    @endif
                                                </div>
                                                <div class="col s2 input-field">
                                                    <textarea name="adresse[{{$loop->index}}]"
                                                        class="materialize-textarea"
                                                        placeholder="">{{$expedition['adresse']}}</textarea>
                                                    <label> Adresse </label>
                                                    @if(isset($errors[$loop->index.'_adresse']))
                                                    <span
                                                        class="helper-text materialize-red-text invalid">{{$errors[$loop->index.'_adresse']}}</span>
                                                    @endif
                                                </div>
                                                <div class="col s1 input-field">
                                                    <input name="nbr_colis[{{$loop->index}}]"
                                                        value="{{$expedition['nbr_colis']}}" placeholder=""
                                                        type="number">
                                                    <label> Nombre Colis </label>
                                                    @if(isset($errors[$loop->index.'_nbr_colis']))
                                                    <span
                                                        class="helper-text materialize-red-text invalid">{{$errors[$loop->index.'_nbr_colis']}}</span>
                                                    @endif
                                                </div>
                                                <div class="col s1 input-field">
                                                    <input name="montant_fond[{{$loop->index}}]"
                                                        value="{{$expedition['montant_fond']}}" placeholder=""
                                                        type="number">
                                                    <label> Montant Fond </label>
                                                    @if(isset($errors[$loop->index.'_montant_fond']))
                                                    <span
                                                        class="helper-text materialize-red-text invalid">{{$errors[$loop->index.'_montant_fond']}}</span>
                                                    @endif
                                                </div>
                                                <div class="col s1 input-field">
                                                    <input name="num_commande[{{$loop->index}}]"
                                                        value="{{$expedition['num_commande']}}" placeholder=""
                                                        type="text">
                                                    <label> Num Commande </label>
                                                    @if(isset($errors[$loop->index.'_num_commande']))
                                                    <span
                                                        class="helper-text materialize-red-text invalid">{{$errors[$loop->index.'_num_commande']}}</span>
                                                    @endif
                                                </div>
                                                <div class="col s1 input-field">
                                                    <input name="valeur_declaree[{{$loop->index}}]"
                                                        value="{{$expedition['valeur_declaree']}}" placeholder=""
                                                        type="number">
                                                    <label> Valeur déclarée </label>
                                                    @if(isset($errors[$loop->index.'_valeur_declaree']))
                                                    <span
                                                        class="helper-text materialize-red-text invalid">{{$errors[$loop->index.'_valeur_declaree']}}</span>
                                                    @endif
                                                </div>
                                                <div class="col s1 input-field">
                                                    <select name="ouverture_colis[{{$loop->index}}]"
                                                        class="select2 browser-default">
                                                        <option value='NON'
                                                            {{ strtoupper($expedition['ouverture_colis']) == 'NON' ? 'selected' : '' }}>
                                                            Non</option>
                                                        <option value='OUI'
                                                            {{ strtoupper($expedition['ouverture_colis']) == 'OUI' ? 'selected' : '' }}>
                                                            Oui</option>
                                                    </select>
                                                    <label style="font-size: 10px !important;"> Ouverture colis </label>

                                                </div>
                                                <div class="col s1 input-field">
                                                    <select name="paiement_cheque[{{$loop->index}}]"
                                                        class="select2 browser-default">
                                                        <option value='NON'
                                                            {{ strtoupper($expedition['paiement_cheque']) == 'NON' ? 'selected' : '' }}>
                                                            Non</option>
                                                        <option value='OUI'
                                                            {{ strtoupper($expedition['paiement_cheque']) == 'OUI' ? 'selected' : '' }}>
                                                            Oui</option>
                                                    </select>
                                                    <label style="font-size: 10px !important;"> Paiement par chèque
                                                    </label>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </form>
                        @endif




                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')
@stop
