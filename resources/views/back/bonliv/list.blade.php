@extends($layout)
@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Bons de livraisons > Liste des bons
                    </span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
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
                <div>

                    <form id="form" method="POST">
                        @csrf

                        <div class="card">

                            <div class="card-content">
                                <div class="row">
                                    <div class="col s12 m2 input-field">
                                        <input id="from" value="{{old('from',$star_date)}}" name="from" type="text" placeholder=""
                                            class="datepicker">
                                        <label for="start_date">Crée Du </label>
                                    </div>

                                    <div class="col s12 m2 input-field">
                                        <input id="to" value="{{old('to',$end_date)}}" name="to" type="text" placeholder=""
                                            class="datepicker">
                                        <label for="start_date">Au </label>
                                    </div>

                                    <div class="col s12 m2 input-field">
                                        <input id="code" value="{{old('code')}}" name="code" type="text" placeholder="">
                                        <label for="numero">Code </label>
                                    </div>

                                    <div class="col s12 m2 input-field">
                                        <select id="statut" name="statut" placeholder=""
                                            class="select2 browser-default">
                                            <option value=''>Tous</option>
                                            @foreach ($status as $s)
                                            <option value='{{$s->key}}'
                                                {{($s->key == old('statut')) ? 'selected' : ''}}>{{$s->value}}</option>
                                            @endforeach
                                        </select>
                                        <label for="statut"> Statut</label>
                                    </div>
                                    <div class="col s2 input-field">
                                        <select name='employe' id='employe' class="select2 browser-default">
                                            <option value=''>Tous</option>
                                            @foreach ($employes as $record)
                                            <option value="{{ $record->id }}"
                                                {{($record->id == old('employe')) ? 'selected' : ''}}>
                                                {{ $record->libelle }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <label for="employe"> Livreur</label>
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
                                            <th> Code </th>
                                            <th> Crée le </th>
                                            <th> Livreur </th>
                                            <th> Type </th>
                                            <th> Férmé le </th>
                                            <th> Statut </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                        <tr>
                                            <td><span class=" badge grey">  {{ $record->code }} </span></td>
                                            <td> {{ $record->created_at }} </td>
                                            <td> {{ $record->livreur_name }} </td>
                                            <td>

                                                @if($record->type_label == "Livraison")
                                                <p class="statut-badge blue valign-wrapper" style="max-width:88px;">
                                                    {{ $record->type_label }}<i class="material-icons"
                                                        style="margin-left:4px;font-size: 24px;">call_made</i>
                                                </p>
                                                @elseif($record->type_label == "Retour")
                                                <p class="statut-badge green valign-wrapper" style="max-width:75px;">
                                                    {{ $record->type_label }} <i class="material-icons"
                                                        style="margin-left:4px;font-size: 24px;">replay</i>
                                                </p>
                                                @endif

                                                 </td>
                                            <td> {{ $record->closed_at }} </td>
                                            <td>
                                                @if($record->statut == 2)
                                                <span class=" badge orange"> {{ $record->statut_label }}</span>
                                                @elseif($record->statut == 1)
                                                <span class=" badge green"> {{ $record->statut_label }} </span>
                                                @endif

                                            </td>
                                            <td>
                                                <a href="{{route('bonliv_download',['bl'=> $record->id])}}"
                                                    target="_blank"> <i class="material-icons tooltipped"
                                                        data-position="top"
                                                        data-tooltip="Modifier">picture_as_pdf</i></a>
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
function openSuppModal(id) {
    $("#delId").val(id);
    $('#delete_modal').modal('open');
}

function suppRecord() {
    window.location.replace("/bonliv/delete/" + $("#delId").val());
}
$(document).ready(function() {
    $('.modal').modal();
});
</script>
@stop
