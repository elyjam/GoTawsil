@extends($layout)
@section('content')

<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des instances de ramassages</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('bon_list') }}">Liste des bons</a>
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
                @if (session()->has('validate'))
                <div class="card-alert card green">
                    <div class="card-content white-text">
                        <p> {{ session()->get('validate') }}</p>
                    </div>
                    <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                @endif
                <br>
                <div class="list-table" id="app">
                    <div class="card">
                        <div class="card-content">
                            <!-- datatable start -->
                            <div class="responsive-table">
                                <table id="list-datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>NB. Expédition</th>
                                            <th>Colis</th>
                                            <th>Editer</th>
                                            <th>Partiel</th>
                                            <th>Valider</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($records as $record)

                                        {{-- @if ($record->expeditionBonNull->count() != 0)

                                        <tr>
                                            <td> {{ $record->libelle }} </td>
                                            <td> {{ $record->expeditionBonNull->count() }}</td>
                                            <td> {{ $record->expeditionBonNull->sum('colis') }}</td>
                                            <td> <a href="{{ route('print_forcer', $record->id) }}" target=_blank><i
                                                        class="material-icons tooltipped" style="color: #2196f3;"
                                                        data-position="top"
                                                        data-tooltip="Imprimer etiquette">print</i></a> </td>
                                            <td> <a href="{{ route('modif_force', $record->id) }}"><i
                                                        class="material-icons tooltipped" style="color: #2196f3;"
                                                        data-position="top"
                                                        data-tooltip="Détail du bon ">library_books</i></a>
                                            </td>
                                            <td> <a href="#!" onclick="openForceModal({{ $record->id }})"><i
                                                        class="material-icons tooltipped" style="color: #2196f3;"
                                                        data-position="top"
                                                        data-tooltip="Valider le ramassage">verified_user</i></a>
                                            </td>

                                        </tr>
                                        @endif --}}
                                        <tr>
                                            <td> {{ $record->libelle }} </td>
                                            <td>{{$record->expcount}}</td>
                                            <td> {{ $record->colissum }}</td>
                                            <td> <a href="{{ route('print_forcer', $record->client) }}" target=_blank><i
                                                        class="material-icons tooltipped" style="color: #2196f3;"
                                                        data-position="top"
                                                        data-tooltip="Imprimer etiquette">print</i></a> </td>
                                            <td> <a href="{{ route('modif_force', $record->client) }}"><i
                                                        class="material-icons tooltipped" style="color: #2196f3;"
                                                        data-position="top"
                                                        data-tooltip="Détail du bon ">library_books</i></a>
                                            </td>
                                            <td> <a href="#!" onclick="openForceModal({{ $record->client }})"><i
                                                        class="material-icons tooltipped" style="color: #2196f3;"
                                                        data-position="top"
                                                        data-tooltip="Valider le ramassage">verified_user</i></a>
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
        {{-- <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a href="{{route('bon_create')}}"
        class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i
            class="material-icons">add</i></a>
    </div> --}}
</div>
</div>

<div id="delete_modal" class="modal">
    <div class="modal-content">
        <h4> Confirmation de suppression</h4>
        <div>
            Êtes-vous sûr de vouloir supprimer ?
        </div>
        <input type="hidden" name="delId" id="delId">
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
        <a href="#!" class="waves-effect waves-green btn red" onclick="suppRecord()">Supprimer</a>
    </div>
</div>

<div id="force_modal" class="modal">
    <div class="modal-content">
        <h4> Confirmation</h4>
        <div>
            Êtes-vous sûr de vouloir forcer le ramassage?
        </div>
        <input type="hidden" name="forceId" id="forceId">
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
        <a href="#!" class="waves-effect waves-green btn red" onclick="forceRm()">Valider le ramassage</a>
    </div>
</div>
@stop
@section('js')
<script>
function openForceModal(id) {
    $("#forceId").val(id);
    $('#force_modal').modal('open');
}

function forceRm() {
    window.location.replace("/bon/forcevalidate/" + $("#forceId").val());
}

function openSuppModal(id) {
    $("#delId").val(id);
    $('#delete_modal').modal('open');
}

function suppRecord() {
    window.location.replace("/bon/delete/" + $("#delId").val());
}
$(document).ready(function() {
    $('.modal').modal();
});
</script>
@stop
