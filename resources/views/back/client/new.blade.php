@extends($layout)
@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Nouveaux inscris</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Nouveaux inscris</a>
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
                <br>
                <div class="list-table" id="app">
                    <div class="card">
                        <div class="card-content">
                            <!-- datatable start -->
                            <div class="responsive-table">
                                <table id="list-datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>

                                            <th class="hide-on-small-only"> Code</th>
                                            <th class="hide-on-small-only"> Nom / R.S</th>
                                            <th class="hide-on-small-only"> Adresse </th>
                                            <th class="hide-on-small-only"> Ville</th>
                                            <th class="hide-on-small-only"> Téléphone</th>
                                            <th class="hide-on-small-only"> Email</th>
                                            <th class="hide-on-small-only"> Type</th>
                                            <th class="hide-on-small-only"> Activé le</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                        <tr>

                                            <td class="hide-on-small-only">{{$record->code_client}} </td>
                                            <td> {{$record->libelle}}</td>
                                            <td class="hide-on-small-only"> {{$record->adresse}} </td>
                                            <td class="hide-on-small-only"> {{$record->ville_label}} </td>
                                            <td class="hide-on-small-only"> {{$record->telephone}} </td>
                                            <td class="hide-on-small-only"> {{$record->email}} </td>
                                            <td class="hide-on-small-only"> ECOM </td>
                                            <td class="hide-on-small-only"> {{$record->activated_at}} </td>


                                            <td>
                                                @if(strlen($record->activated_at)>6)
                                                <a href="#!" onclick="openValModal({{$record->user_id}})"><i
                                                        class="material-icons tooltipped" data-position="top"
                                                        data-tooltip="Valider" style="color: green;">check</i></a>
                                                @endif


                                                <a href="#!" onclick="openActModal({{$record->user_id}})"><i
                                                        class="material-icons tooltipped" data-position="top"
                                                        data-tooltip="Forcer" style="color: red;">check</i></a>


                                                <br>
                                                <a href="{{route('client_print', ['client' => $record->id])}}"
                                                    target="_blank"><i class="material-icons tooltipped"
                                                        data-position="top"
                                                        data-tooltip="Fiche client">picture_as_pdf</i></a>
                                                <a href="#!" onclick="openSuppModal({{$record->id}})"><i
                                                        class="material-icons tooltipped" style="color: #c10027;"
                                                        data-position="top" data-tooltip="Annuler">delete</i></a>
                                            </td>
                                        </tr>
                                        @endforeach

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

<div id="activate_modal" class="modal">
    <div class="modal-content">
        <h4> Forcer la validation</h4>
        <div>
            Êtes-vous sûr de vouloir valider ?
        </div>
        <input type="hidden" name="actId" id="actId">
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
        <a href="#!" class="waves-effect waves-green btn green" onclick="actRecord()">Activer</a>
    </div>
</div>

<div id="validate_modal" class="modal">
    <div class="modal-content">
        <h4> Confirmation de validation</h4>
        <div>
            Êtes-vous sûr de vouloir valider ?
        </div>
        <input type="hidden" name="valId" id="valId">
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
        <a href="#!" class="waves-effect waves-green btn green" onclick="valRecord()">Valider</a>
    </div>
</div>
@stop
@section('js')
<script>
function openSuppModal(id) {
    $("#delId").val(id);
    $('#delete_modal').modal('open');
}

function suppRecord() {
    window.location.replace("/client/remove/" + $("#delId").val());
}


function openActModal(id) {
    $("#actId").val(id);
    $('#activate_modal').modal('open');
}

function actRecord() {
    window.location.replace("/client/activate/" + $("#actId").val());
}

function openValModal(id) {
    $("#valId").val(id);
    $('#validate_modal').modal('open');
}

function valRecord() {
    window.location.replace("/client/validate/" + $("#valId").val());
}

$(document).ready(function() {
    $('.modal').modal();
});
</script>
@stop
