@extends($layout)

@section('content')
<style>
#modal_details {
    top: 5% !important;
    width: 90% !important;
    height: 100vh !important;
    max-height: 89% !important;
}
</style>
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Caisses > Liste des caisses</span></h5>
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <br>

                <div>

                    <form id="form" method="POST">
                        @csrf

                        <div class="card">

                            <div class="card-content">
                                <div class="row">

                                    <div class="col s12 m2 input-field">
                                        <select id="ville" name="ville" placeholder=""
                                            class="select select2 browser-default">
                                            <option value=''></option>
                                            @foreach ($villes as $row)
                                            <option class='option' {{ $row->id == old('ville') ? 'selected' : '' }}
                                                value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                            @endforeach
                                        </select>
                                        <label for="ville">Ville</label>
                                    </div>

                                    <div class="col s12 m2 input-field">
                                        <input id="numero" value="{{ old('numero') }}" name="numero" type="text"
                                            placeholder="">
                                        <label for="numero">Numéro </label>
                                    </div>

                                    <div class="col s12 m2 input-field">
                                        <input id="start_date" value="{{ old('start_date') }}" name="start_date"
                                            type="text" placeholder="" class="datepicker">
                                        <label for="start_date">Date caisse du </label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <input id="end_date" value="{{ old('end_date') }}" name="end_date" type="text"
                                            placeholder="" class="datepicker">
                                        <label for="end_date">Au </label>
                                    </div>


                                    <div class="col s12 m2 input-field">
                                        <select id="statut" name="statut" placeholder=""
                                            class="select2 browser-default">
                                            <option value=''></option>
                                            @foreach ($status as $s)
                                            <option value='{{ $s->key }}'
                                                {{ $s->key == old('statut') ? 'selected' : '' }}>
                                                {{ $s->value }}</option>
                                            @endforeach


                                        </select>
                                        <label for="statut"> Statut</label>
                                    </div>

                                    <div class="col s12 m2 input-field">
                                        <button type="submit" class="btn btn-light" style="margin-right: 1rem;">
                                            <i class="material-icons">search</i></button>
                                    </div>


                                </div>



                            </div>

                        </div>
                    </form>

                </div>


                <div class="list-table" id="app">
                    <div class="card">
                        <div class="card-content">
                            <!-- datatable start -->
                            <div class="responsive-table">
                                <table id="list-datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th> Numéro </th>
                                            <th> Du </th>
                                            <th> Au </th>
                                            <th> Ville </th>
                                            <th> Générée par </th>
                                            <th> Férmée Par </th>
                                            <th> Validée le </th>
                                            <th> Validée Par </th>
                                            <th> Statut </th>
                                            <th> Versements & Dépenses </th>
                                            <th style="width:60px ;"> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                        <tr>
                                            <td> <span class=" badge grey">{{ $record->numero }} </span></td>
                                            <td style="font-size: 14px;">
                                                {{ date('Y/m/d H:i:s', strtotime($record->created_at)) }}
                                            </td>
                                            <td style="font-size: 14px;">
                                                @if (strlen($record->date_fin) > 6)
                                                {{ date('Y/m/d H:i:s', strtotime($record->date_fin)) }}
                                                @endif
                                            </td>
                                            <td>{{$record->agenceDetail->libelle}}</td>
                                            <td>{{$record->id_utilisateur_gen}} {{ $record->genBy->name }} {{ $record->genBy->first_name }} </td>
                                            <td> {{ $record->confirme_par }} </td>

                                            <td style="font-size: 14px;">
                                                @if (strlen($record->date_validation) > 6)
                                                {{ date('Y/m/d H:i:s', strtotime($record->date_validation)) }}
                                                @endif
                                            </td>
                                            <td> {{ $record->valide_par }} </td>
                                            <td class="valign-wrapper">
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
                                            </td>
                                            <td style="text-align: center">

                                                <a href="#!"
                                                    onclick="Detailsmodal('{{route('caisse_versements', ['caisse' => $record->id])}}')"
                                                    style="text-align: center">
                                                    <i class="material-icons green-text tooltipped"
                                                        data-tooltip="Versements & Dépenses"
                                                        data-position="top">payment</i>
                                                </a>
                                                @if($record->statut == 1)
                                                <a href="#!" onclick="openCloseModal('{{$record->id}}')"
                                                    style="text-align: center">

                                                    <i class="material-icons green-text tooltipped"
                                                        data-tooltip="Fermér la caisse" data-position="top">check</i>
                                                </a>
                                                @endif
                                            </td>
                                            <td style="text-align: center"> <a target="_blank"
                                                    href="{{ route('caisse_print', ['caisse' => $record->id]) }}">
                                                    <i class="material-icons tooltipped" data-tooltip="Imprimer caisse"
                                                        data-position="top">picture_as_pdf</i>
                                                </a>
                                                <a target="_blank"
                                                    href="{{ route('caisse_print_detail', ['caisse' => $record->id]) }}">
                                                    <i class="material-icons tooltipped"
                                                        data-tooltip="Imprimer détail caisse"
                                                        data-position="top">picture_as_pdf</i>
                                                </a>
                                            </td>

                                        </tr>
                                        @endforeach

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="close_modal" class="modal">
    <div class="modal-content">
        <h4> Confirmation de fermeture</h4>
        <div>
            Êtes-vous sûr de vouloir fermer ?
        </div>
        <input type="hidden" name="caisseId" id="caisseId">
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
        <a href="#!" class="waves-effect waves-green btn green" onclick="closeRecord()">Fermer</a>
    </div>
</div>

<div id="modal_details" class="modal">
    <div class="modal-content" style="height:100%;">
        <iframe id="inlineFrameExample" title="Inline Frame Example" src="" width="100%" height="100%">
        </iframe>
    </div>
    {{-- <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
        </div> --}}
</div>

@stop
@section('js')
<script>
function openCloseModal(id) {
    $("#caisseId").val(id);
    $('#close_modal').modal('open');
}

function Detailsmodal(url) {
    document.getElementById('inlineFrameExample').src = url;
    $('#modal_details').modal('open');
}

function closeRecord() {
    window.location.replace("/caisse/change-status/" + $("#caisseId").val() + "/2");
}
$(document).ready(function() {
    $('.modal').modal();
});
</script>
@stop
