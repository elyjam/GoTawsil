@extends($layout)
@section('content')

<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des factures</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('facture_list')}}">Liste des factures</a>
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
                <div>
                    <form id="form" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s12 m2 input-field">
                                        <input id="start_date" value="{{old('start_date',$star_date)}}" name="start_date"
                                            type="text" placeholder="" class="datepicker">
                                        <label for="start_date">Du </label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <input id="end_date" value="{{old('end_date',$end_date)}}" name="end_date" type="text"
                                            placeholder="" class="datepicker">
                                        <label for="end_date">Au </label>
                                    </div>
                                    <div class="col s12 m1 input-field">
                                        <input id="code" value="{{old('code')}}" name="code" type="text" placeholder="">
                                        <label for="code">code </label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <select id="statut" name="statut" placeholder=""
                                            class="select2 browser-default">
                                            <option value='0'>Tous</option>
                                            @foreach ($statutRecords as $row)
                                            <option class='option' {{($row->key == old('statut')) ? 'selected' : ''}}
                                                value='{{$row->key}}'> {{$row->value}}</option>
                                            @endforeach
                                        </select>
                                        <label for="Statut">Statut</label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <select name='client' id='client' class="select2 browser-default">
                                            <option value='0'></option>
                                            @foreach ($clientRecords as $row)
                                            <option class='option' {{($row->id == old('client')) ? 'selected' : ''}}
                                                value='{{$row->id}}'> {{$row->libelle}}</option>
                                            @endforeach
                                        </select>
                                        <label for="Client"> Client</label>
                                    </div>
                                    <div class="col s12 m3 input-field">
                                        <button type="submit" class="btn btn-light" style="margin-right: 1rem;">
                                            <i class="material-icons">search</i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <form method="POST" id="form_table">
                    @csrf
                    <div class="col s12 display-flex justify-content-end">
                        <button type="button" onclick="$('#form_table').submit();" class="btn green"> <i
                                style="line-height: normal;" class="material-icons">check</i>
                            Remise
                        </button>
                    </div>
                    <br><br>
                    <div class="list-table" id="app">
                        <div class="card">
                            <div class="card-content">
                                <!-- datatable start -->
                                <div class="responsive-table">
                                    <table id="list-datatable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="20px" style="width: 20px !important;">
                                                    <label>
                                                        <input type="checkbox" id='checkall'>
                                                        <span></span>
                                                    </label>
                                                </th>
                                                <th> Code </th>
                                                <th class="hide-on-small-only"> Client </th>
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
                                                <td style="padding-left: 18px;"> <label>
                                                        <input class="checkbox" value="{{$record->id}}" type="checkbox"
                                                            name='remis[]'>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    @if ($record->remise != 1)
                                                   <p style="
                                                   background: #4caf50;
                                                   color: white;
                                                   text-align: center;
                                               ">{{ $record->code }}</p>
                                                    @else
                                                    <p style="
                                                    text-align: center;
                                                ">{{ $record->code }}</p>
                                                    @endif


                                                    <p class="hide-on-med-and-up">
                                                        {{ $record->code }}
                                                    </p>
                                                    <p class="hide-on-med-and-up">
                                                        <span class="new badge gradient-45deg-light-blue-cyan"
                                                            data-badge-caption="TTC : {{ $record->ttc }} Dhs"></span>
                                                    </p>
                                                </td>
                                                <td class="hide-on-small-only">
                                                    {{ $record->client }}
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
                                                    <a href="{{route('facture_print', ['facture'=>$record->id, 'type'=>2])}}"
                                                        target="_blank">
                                                        <i class="material-icons tooltipped"
                                                            data-tooltip="Imprimer facture N° {{ $record->code }} (Non Signée)"
                                                            data-position="top">picture_as_pdf</i>
                                                    </a>
                                                    <a target="_blank"
                                                        href="{{route('facture_print', ['facture'=>$record->id, 'type'=>1])}}">
                                                        <i class="material-icons tooltipped"
                                                            data-tooltip="Imprimer facture N° {{ $record->code }} (Signée)"
                                                            data-position="top">picture_as_pdf</i>
                                                    </a>
                                                    <a target="_blank"
                                                        href="{{route('facture_print_detail', ['facture'=>$record->id])}}">
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
                </form>
            </div>
        </div>
        <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a
                href="{{route('facture_gen', [$type])}}"
                class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i
                    class="material-icons">add</i></a>
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
