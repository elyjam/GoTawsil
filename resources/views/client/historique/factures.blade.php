@extends($layout)
@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg-client.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des factures</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('Dashboard_Client')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('facture_list_client')}}">Liste des factures</a>
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
                <!--<br>
                <button type="button" onclick="advancedsearch()" class="btn btn-light">Recherche Avancée</button>-->
                <br>


                <div class="list-table" id="app">
                    <div class="card">
                        <div class="card-content">
                            <!-- datatable start -->
                            <div class="responsive-table">
                                <table id="list-datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>

                                            <th> Code </th>

                                            <th class="hide-on-small-only"> Date facture </th>
                                            <th class="hide-on-small-only"> Date remise </th>
                                            <th class="hide-on-small-only"> Taux TVA </th>
                                            <th class="hide-on-small-only"> H.T </th>
                                            <th class="hide-on-small-only"> TVA </th>
                                            <th class="hide-on-small-only"> TTC </th>
                                            <th>Imprimer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                        <tr>


                                            <td class="hide-on-small-only">
                                                {{ $record->code }}
                                            </td>
                                            <td> {{ date('d/m/Y H:i', strtotime($record->created_at)) }} </td>
                                            <td>@if(strlen($record->date_remise)>2)
                                                {{ date('d/m/Y H:i', strtotime($record->date_remise)) }}
                                                @endif
                                            </td>
                                            <td class="hide-on-small-only"> {{ $record->tauxtva }} % </td>
                                            <td class="hide-on-small-only">
                                                {{ \App\Models\Util::moneyFormat($record->ht, 2) }} Dhs</td>
                                            <td class="hide-on-small-only">
                                                {{ \App\Models\Util::moneyFormat($record->tva, 2) }} Dhs</td>
                                            <td class="hide-on-small-only">
                                                {{ \App\Models\Util::moneyFormat($record->ttc, 2) }} Dhs</td>
                                            <td>
                                                <a href="{{route('ancien_facture_print', ['facture'=>$record->id, 'type'=>2])}}"
                                                    target="_blank">
                                                    <i class="material-icons tooltipped"
                                                        data-tooltip="Imprimer facture N° {{ $record->code }} (Non Signée)"
                                                        data-position="top">picture_as_pdf</i>
                                                </a>
                                                <a target="_blank"
                                                    href="{{route('ancien_facture_print', ['facture'=>$record->id, 'type'=>1])}}">
                                                    <i class="material-icons tooltipped"
                                                        data-tooltip="Imprimer facture N° {{ $record->code }} (Signée)"
                                                        data-position="top">picture_as_pdf</i>
                                                </a>
                                                <a target="_blank"
                                                    href="{{route('printDetail_ancien_facture', ['facture'=>$record->id])}}">
                                                    <i class="material-icons tooltipped"
                                                        data-tooltip="Imprimer détails facture N° {{ $record->code }}"
                                                        data-position="top">picture_as_pdf</i>
                                                </a>
                                                <!--<a href="#"
                                                        onclick="javascript:window.open('/facture/detail/{{$record->id}}','Facture Detail','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=1200,height=1200');">
                                                        <i class="material-icons tooltipped"
                                                            data-tooltip="Détails facture N° {{ $record->code }}"
                                                            data-position="top">insert_drive_file</i>
                                                    </a>-->

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
document.getElementById("historique_factures").classList.add("activate");

function openSuppModal(id) {
    $("#delId").val(id);
    $('#delete_modal').modal('open');
}

function suppRecord() {
    window.location.replace("/facture/delete/" + $("#delId").val());
}
$(document).ready(function() {
    $('.modal').modal();
    $("#checkall").change(function(e) {
        $('.checkbox').prop("checked", $('#checkall').prop("checked"));
    });
});

//document.getElementById("form").style.display = "none";

function advancedsearch() {
    var x = document.getElementById("form");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
</script>
@stop
