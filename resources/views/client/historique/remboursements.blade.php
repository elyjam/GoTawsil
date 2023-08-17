@extends($layout)
@section('content')

<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg-client.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des remboursements</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('Dashboard_Client')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('remboursement_list')}}">Liste des remboursements</a>
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
                                            <th> Groupe </th>
                                            <th> Date Création </th>
                                            <th> Créé par </th>
                                            <th> Type </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                        <tr>

                                            <td> {{ $record->code}} </td>
                                            <td> {{ date('d/m/Y H:i', strtotime($record->created_at)) }} </td>
                                            <td> {{ $record->user->name.' '.$record->user->first_name }} </td>
                                            <td>
                                                {{$record->typeDetail()}}
                                            </td>
                                            <td>
                                                <a target="_blank"
                                                    href="{{route('print_renboursement_ancien', ['remboursement'=>$record->rembDetail->id, 'paiement'=>$record->id])}}">
                                                    <i class="material-icons tooltipped"
                                                        data-tooltip="Détail remboursement groupe"
                                                        data-position="top">picture_as_pdf</i>
                                                </a>
                                                {{-- <a target="_blank"
                                                    href="{{route('remboursement_ordre_virement', ['remboursement'=>$record->rembDetail->id])}}">
                                                    <i class="material-icons tooltipped"
                                                        data-tooltip="Ordre de virement"
                                                        data-position="top">picture_as_pdf</i>
                                                </a> --}}
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
@stop
@section('js')
<script>

document.getElementById("historique").classList.add("activate");
document.getElementById("historique_remboursements").classList.add("activate");

function openSuppModal(id) {
    $("#delId").val(id);
    $('#delete_modal').modal('open');
}

function suppRecord() {
    window.location.replace("/remboursement/delete/" + $("#delId").val());
}
$(document).ready(function() {
    $('.modal').modal();
});
</script>
@stop
