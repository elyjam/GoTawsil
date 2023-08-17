@extends("layouts/base")

@section('content')
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">
                <form method="POST" enctype="multipart/form-data">
                    @CSRF
                    <div class="row">
                        <h4 class="gradient-45deg-indigo-light-blue white-text"
                            style="margin:15px;border-radius:10px;padding: 10px;text-align:center">Versements &
                            Dépenses
                        </h4>
                        <div class="col s12 m12 ">
                            <ul id="projects-collection" class="collection z-depth-1">
                                <li class="collection-item avatar">
                                    <i class="material-icons circle" style="background-color: #c81537">move_to_inbox</i>
                                    <h6 class="collection-header ">Caisse :</h6>
                                    <span class="badge blue left" style="margin-left:0; ">N°
                                        {{$record->numero}}</span><span style="margin-left:0; "
                                        class="badge grey left">Agence de {{$record->agenceDetail->libelle}}</span>
                                    <div class="valign-wrapper right">
                                        @if ($record->statut == 1)
                                        <span class="statut-badge lime darken-2 valign-wrapper">
                                            {{ $record->getStatuts() }} <i class="material-icons"
                                                style="margin-left:4px;font-size: 24px;">lock_open</i>
                                        </span>
                                        @elseif($record->statut == 2)
                                        <span class="statut-badge orange valign-wrapper">
                                            {{ $record->getStatuts() }} <i class="material-icons"
                                                style="margin-left:4px;font-size: 24px;">lock_outline</i>
                                        </span>
                                        @elseif($record->statut == 3)
                                        <span class="statut-badge blue valign-wrapper">
                                            {{ $record->getStatuts() }} <i class="material-icons"
                                                style="margin-left:4px;font-size: 24px;">subdirectory_arrow_right</i>
                                        </span>
                                        @elseif($record->statut == 4)
                                        <span class="statut-badge green valign-wrapper">
                                            {{ $record->getStatuts() }} <i class="material-icons"
                                                style="margin-left:4px;font-size: 24px;">done</i>
                                        </span>
                                        @endif
                                    </div>
                                </li>
                                @if($record->statut == 1 || ($record->statut == 2 && \Auth::user()::hasRessource('Menu Caisse : Modification après fermeture')) || ($record->statut == 3 && \Auth::user()::hasRessource('Menu Caisse : Modification après réception')) || ($record->statut == 4 && \Auth::user()::hasRessource('Menu Caisse : Modification après validation')))
                                <li class="collection-item">
                                    <div class="row">
                                        <div class="input-field col m6 s12">
                                            <select id="type" name="type" placeholder=""
                                                class="select select2 browser-default"
                                                onchange="window.location.replace('/caisse/versements/{{$record->id}}/'+$(this).val())">
                                                <option value='DEPENSE' {{$type == 'DEPENSE' ?  "selected" : "" }}>
                                                    Dépense</option>
                                                <option value='VERSEMENT' {{$type == 'VERSEMENT' ?  "selected" : "" }}>
                                                    Versement
                                                </option>
                                                <option value='JUSTIFICATIF'
                                                    {{$type == 'JUSTIFICATIF' ?  "selected" : "" }}>
                                                    Justificatifs
                                                </option>
                                                <option value='CHEQUE' {{$type == 'CHEQUE' ?  "selected" : "" }}>
                                                    Chèque
                                                </option>
                                            </select>
                                            <label for=" type">Type</label>
                                        </div>

                                    </div>
                                    @if($type == 'VERSEMENT')
                                    <div class="row">
                                        <div class="input-field col m4 s12"><input id="montant" name="montant"
                                                value="{{ isset($versements['VERSEMENT']) ? $versements['VERSEMENT']->montant : '0' }}"
                                                type="number"><label for="montant" class="">Montant </label></div>

                                        <div class="input-field col m4 s12">
                                            <select id="rubrique" name="rubrique" placeholder=""
                                                class="select select2 browser-default"
                                                onchange="window.location.replace('/caisse/versements/{{$record->id}}/'+$('#type').val()+'/'+$(this).val())">
                                                @foreach($types as $row)
                                                <option value='{{$row->id}}' {{$rub == $row->id ?  "selected" : "" }}>
                                                    {{$row->libelle}}</option>
                                                @endforeach
                                            </select>
                                            <label for="rubrique">Rubrique</label>
                                        </div>

                                        <div class="input-field col m3 s12"><input id="reference" name="reference"
                                                type="text" disabled
                                                value="{{ isset($versements['VERSEMENT']) ? $versements['VERSEMENT']->versement_date : '' }}"><label
                                                for="reference" class="">
                                                Date de versement
                                            </label></div>

                                    </div>
                                    <div class="row">
                                        <div class="input-field col m3 s12"><input id="reference" name="reference"
                                                type="text"
                                                value="{{ isset($versements['VERSEMENT']) ? $versements['VERSEMENT']->reference : '' }}"><label
                                                for="reference" class="">Réference
                                                (N°
                                                Justif.)
                                            </label></div>

                                        <div class="input-field col m9 s12">
                                            <input id="observation" name="observation" type="text"
                                                value="{{ isset($versements['VERSEMENT']) ? $versements['VERSEMENT']->observation : '' }}">
                                            <label for="observation" class="">Observation </label>
                                        </div>
                                    </div>
                                    @elseif($type == 'DEPENSE')
                                    <div class="row">
                                        <div class="input-field col m3 s12"><input id="montant" name="montant"
                                                value="{{ isset($versements['DEPENSE_'.$rub]) ? $versements['DEPENSE_'.$rub]->montant : 0 }}"
                                                type="number"><label for="montant" class="">Montant </label></div>

                                        <div class="input-field col m6 s12">
                                            <select id="rubrique" name="rubrique" placeholder=""
                                                class="select select2 browser-default"
                                                onchange="window.location.replace('/caisse/versements/{{$record->id}}/'+$('#type').val()+'/'+$(this).val())">
                                                @foreach($types as $row)
                                                <option value='{{$row->id}}' {{$rub == $row->id ?  "selected" : "" }}>
                                                    {{$row->libelle}}</option>
                                                @endforeach
                                            </select>
                                            <label for="rubrique">Rubrique</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m3 s12"><input id="reference" name="reference"
                                                type="text"
                                                value="{{ isset($versements['DEPENSE_'.$rub]) ? $versements['DEPENSE_'.$rub]->reference : '' }}"><label
                                                for="reference" class="">Réference
                                                (N°
                                                Justif.)
                                            </label></div>

                                        <div class="input-field col m9 s12">
                                            <input id="observation" name="observation" type="text"
                                                value="{{ isset($versements['DEPENSE_'.$rub]) ? $versements['DEPENSE_'.$rub]->observation : '' }}">
                                            <label for="observation" class="">Observation </label>
                                        </div>
                                    </div>
                                    @elseif($type == 'JUSTIFICATIF')
                                    <div class="row">
                                        <div class="file-field input-field" style="width: 40%; float: left;">
                                            <div class="btn">
                                                <i class="material-icons left">insert_drive_file</i>
                                                <span>Image</span>
                                                <input name="file" type="file">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path" type="text">
                                            </div>
                                        </div>
                                        <div class="input-field col m6 s12"><input name="comment_justif"
                                                id="comment_justif" type="text"><label for="comment_justif"
                                                class="">Commentaire
                                            </label></div>
                                    </div>
                                    @elseif($type == 'CHEQUE')
                                    <div class="row">

                                        <div class="input-field col m3 s12">
                                            <select id="expedition" name="expedition" placeholder=""
                                                class="select select2 browser-default">
                                                @foreach($expeditions as $record)
                                                <option value='{{$record->id}}'>
                                                    {{$record->num_expedition." / ". $record->client}}</option>
                                                @endforeach
                                            </select>
                                            <label for="expedition">Expedition</label>
                                        </div>
                                        <div class="input-field col m3 s12"><input id="montant_cheque"
                                                name="montant_cheque" type="number"><label for="montant_cheque"
                                                class="">Montant chéque
                                            </label></div>
                                        <div class="input-field col m6 s12"><input name="numero_cheque"
                                                id="numero_cheque" type="text"><label for="numero_cheque"
                                                class="">Numéro
                                                chéque
                                            </label></div>
                                    </div>
                                    @endif

                                </li>

                            </ul>
                        </div>

                        <div class="right" style="margin-bottom:15px;padding-right:14px;">
                            <button class="btn modal-action  blue darken-2" type="submit" name="Enregistrer">Enregistrer
                                <i class="material-icons right">save</i>
                            </button>
                            <!--<a href="{{route('caisse_list')}}"><button
                                    class="btn modal-action  red darken-3 modal-close" type="button"
                                    name="cancel">Retour
                                    <i class="material-icons right">close</i>
                                </button></a>-->
                            {{-- <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Annuler</a> --}}
                        </div>

                        @endif
                        <div class="col s12 m12 ">

                            <ul id="projects-collection" class="collection z-depth-1">
                                <li class="collection-item avatar">
                                    <i class="material-icons blue circle">credit_card</i>
                                    <h6 class="collection-header ">Détail Caisse</h6>
                                </li>
                                <li class="collection-item">
                                    <div class="row" style="padding:10px;">
                                        <table class="Highlight">
                                            <thead>
                                                <tr>
                                                    <th>MT Total</th>
                                                    <th>MT Versé</th>
                                                    <th style="text-align:center ;"><i class="material-icons">remove</i>
                                                    </th>
                                                    <th style="text-align:center ;"><i class="material-icons">add</i>
                                                    </th>
                                                    <th>Nbr colis</th>
                                                    <th>COMMISSION</th>
                                                    <th>TAXATION SAT</th>
                                                    <th>TRANSPORT</th>
                                                    <th>RAMASSAGE</th>
                                                    <th>AUTRE</th>
                                                    <th>MANQUE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="text-align: center; padding-right: 20px;">
                                                        {{number_format( $montant_total, 0)}}
                                                    </td>
                                                    <td style="text-align: center; padding-right: 20px;">
                                                        {{number_format( $versementsMtn, 0)}}
                                                    </td>
                                                    <td style="text-align: center; padding-right: 20px;">
                                                        @if($montant_total > $versementsMtn)
                                                        {{ number_format( $montant_total - $versementsMtn , 0)}}
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center; padding-right: 20px;">
                                                        @if($montant_total < $versementsMtn)
                                                            {{ number_format( $versementsMtn - $montant_total , 0)}}
                                                            @endif </td>
                                                    <td style="text-align: center; padding-right: 20px;">
                                                        {{count($expeditions)}}</td>
                                                    <td style="text-align: center; padding-right: 20px;">
                                                        {{ isset($versements['DEPENSE_3']) ? $versements['DEPENSE_3']->montant : '' }}
                                                    </td>
                                                    <td style="text-align: center; padding-right: 20px;">
                                                        {{ isset($versements['DEPENSE_4']) ? $versements['DEPENSE_4']->montant : '' }}
                                                    </td>
                                                    <td style="text-align: center; padding-right: 20px;">
                                                        {{ isset($versements['DEPENSE_5']) ? $versements['DEPENSE_5']->montant : '' }}
                                                    </td>
                                                    <td style="text-align: center; padding-right: 20px;">
                                                        {{ isset($versements['DEPENSE_6']) ? $versements['DEPENSE_6']->montant : '' }}
                                                    </td>
                                                    <td style="text-align: center; padding-right: 20px;">
                                                        {{ isset($versements['DEPENSE_7']) ? $versements['DEPENSE_7']->montant : '' }}
                                                    </td>
                                                    <td
                                                        style="text-align: center; {{$manqueMtn != 0 ? 'background-color: red; color: #fff;' : '' }}">
                                                        {{\App\Models\Util::moneyFormat($manqueMtn)}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </li>

                            </ul>
                        </div>
                        <div class="col s12 m12 ">

                            <ul id="projects-collection" class="collection z-depth-1">
                                <li class="collection-item avatar">
                                    <i class="material-icons blue circle">content_copy</i>
                                    <h6 class="collection-header ">Chéques </h6>
                                </li>
                                <li class="collection-item">
                                    <div class="row" style="padding:10px;">
                                        <table class="Highlight">
                                            <thead>
                                                <tr>
                                                    <th>Expedition</th>
                                                    <th>Montant chéque</th>
                                                    <th>Numéro chéque</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach($cheques as $cheque)
                                                <tr>
                                                    <td>{{$cheque->num_expedition." / ".$cheque->client}}</td>
                                                    <td>{{number_format( $cheque->montant , 0)}}</td>
                                                    <td>{{$cheque->numero}}</td>
                                                </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="col s12 m12 ">

                            <ul id="projects-collection" class="collection z-depth-1">
                                <li class="collection-item avatar">
                                    <i class="material-icons blue circle">attachment</i>
                                    <h6 class="collection-header ">Justificatifs </h6>
                                </li>
                                <li class="collection-item">
                                    <div class="row" style="padding:10px;">
                                        <table class="Highlight">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30%;">Image</th>
                                                    <th>Commentaire</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($justifs as $justif)
                                                <tr>
                                                    <td><a target="_blank"
                                                            href="/uploads/caisses/{{$justif->image}}"><img
                                                                width="300px" src="/uploads/caisses/{{$justif->image}}"
                                                                alt=""></a></td>
                                                    <td>{{$justif->commentaire}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </li>
                            </ul>
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
function suppRecord() {
    window.location.replace("/caisse/delete/" + $("#delId").val());
}
$(document).ready(function() {
    $('.modal').modal();
});
</script>
@stop