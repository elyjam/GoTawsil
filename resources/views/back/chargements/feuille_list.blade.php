@extends($layout)
<style>
@media only screen and (max-width: 600px) {
    .dataTable {
        font-size: 10px !important;
    }
}

.btn-download {
    background-color: #c81537 !important;
}
</style>
@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Feuilles de chargements </span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('expedition_list') }}">Liste des expeditions</a>
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

                {{-- <button type="button" onclick="advancedsearch()" class="btn btn-light">Recherche Avancée</button> --}}

                <form method="POST">
                    @csrf

                    <div class="card">

                        <div class="card-content">
                            <div class="row">
                                <div class="col s12 m2 input-field">
                                    <input id="start_date" value="{{ old('start_date',$star_date) }}" name="start_date"
                                        type="text" placeholder="" class="datepicker">
                                    <label for="start_date">Du </label>
                                </div>
                                <div class="col s12 m2 input-field">
                                    <input id="end_date" value="{{ old('end_date',$end_date) }}" name="end_date" type="text"
                                        placeholder="" class="datepicker">
                                    <label for="end_date">Au </label>
                                </div>

                                <div class="col s12 m2 input-field">
                                    <input id="code" value="{{ old('code') }}" name="code" type="text"
                                        placeholder="">
                                    <label for="code">Code</label>
                                </div>



                                <div class="col s12 m2 input-field">
                                    <select id="Transporteur" name="Transporteur" placeholder=""
                                        class="select select2 browser-default">
                                        <option value=''></option>
                                        @foreach ($transporteurs as $row)
                                        <option class='option'
                                            {{ $row->id == old('Transporteur') ? 'selected' : '' }}
                                            value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                        @endforeach
                                    </select>
                                    <label for="Transporteur"> Transporteur</label>
                                </div>
                                <div class="col s12 m2 input-field">
                                    <select id="Destination" name="Destination" placeholder=""
                                        class="select2 browser-default">
                                        <option value=''></option>
                                        @foreach ($agences as $row)
                                        <option class='option' {{ $row->id == old('Destination') ? 'selected' : '' }}
                                            value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                        @endforeach
                                    </select>
                                    <label for="Destination"> Destination</label>
                                </div>
                                <div class="col s12 m2 input-field">
                                    <button  type="submit" class="btn btn-light" style="margin-right: 1rem;">
                                        <i class="material-icons">search</i></button>
                                </div>

                            </div>



                        </div>

                    </div>
                </form>
                <div class="list-table" id="app">
                    <div class="card">
                        <div class="card-content">
                            <!-- datatable start -->
                            <div class="responsive-table">
                                <table id="list-datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>

                                            <th>Code</th>
                                            <th class="hide-on-small-only">Validé le </th>
                                            <th> Transporteur</th>
                                            <th>Origine</th>

                                            <th class="hide-on-small-only"> Destination</th>
                                            <th class="hide-on-small-only"> Reçu le</th>
                                            <th class="hide-on-small-only"> Editer</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($records->unique('id_feuille_charge') as $record)

                                        @if ($record->id_feuille_charge)
                                        <tr>

                                            <td><span class=" badge grey"> {{ (!empty($record->BonChargementDetail->code)) ? $record->BonChargementDetail->code : '' }} </span></td>
                                            <td class="hide-on-small-only"> {{ $record->BonChargementDetail->created_at }} </td>
                                            <td>{{ $record->transporteurDetail->libelle }}
                                                <p class="hide-on-med-and-up"><span
                                                        class="new badge gradient-45deg-light-blue-cyan"
                                                        data-badge-caption="{{ $record->transporteurDetail->libelle }}"></span>
                                                </p>

                                            </td>
                                            <td>{{ $record->agenceDetail->libelle }} </td>
                                            <td class="hide-on-small-only">
                                                {{ $record->agenceDesDetail->libelle }} </td>

                                            <td class="hide-on-small-only"> {{ $record->date_reception }}
                                            </td>
                                            <td class="hide-on-small-only"> <a
                                                    href="{{ route('chargement_print_detail', $record->BonChargementDetail->id) }}"
                                                    target="_blank"><i class="material-icons tooltipped"
                                                        style="color: #01579b;" data-position="top"
                                                        data-tooltip="Imprimer">print</i></a></td>


                                        </tr>
                                        @endif
                                        @endforeach --}}

                                        @foreach ($records as $record)

                                        @if($record->count_exps() != 0)
                                        <tr>

                                            <td><span class=" badge grey">  {{ (!empty($record->code)) ? $record->code : '' }} </span></td>
                                            <td class="hide-on-small-only"> {{ $record->created_at }} </td>
                                            <td>{{ $record->transportDetail->libelle }}
                                                <p class="hide-on-med-and-up"><span
                                                        class="new badge gradient-45deg-light-blue-cyan"
                                                        data-badge-caption="{{ $record->transportDetail->libelle }}"></span>
                                                </p>

                                            </td>
                                            <td>{{ $record->agenceDetail->libelle }} </td>
                                            <td class="hide-on-small-only">
                                                {{ $record->agenceDesDetail->libelle }} </td>

                                            <td class="hide-on-small-only"> {{ (!empty($record->prossesusChargement->date_reception)) ? $record->prossesusChargement->date_receptione : '' }}
                                            </td>
                                            <td class="hide-on-small-only"> <a
                                                    href="{{ route('chargement_print_detail', $record->id) }}"
                                                    target="_blank"><i class="material-icons tooltipped"
                                                        style="color: #01579b;" data-position="top"
                                                        data-tooltip="Imprimer">print</i></a></td>


                                        </tr>
                                        @endif
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
    window.location.replace("/expedition/delete/" + $("#delId").val());
}

$(document).ready(function() {
    $('.modal').modal();
});
document.getElementById("form").style.display = "none";

// function advancedsearch() {
//     var x = document.getElementById("form");
//     if (x.style.display === "none") {
//         x.style.display = "block";
//     } else {
//         x.style.display = "none";
//     }
// }


$(document).ready(function() {

    const App = {
        mounted() {
            this.loadData();
            $('.tooltipped').tooltip();
        },
        methods: {
            loadData() {
                if ($("#list-datatable").length > 0) {
                    $("#list-datatable").DataTable({
                        "aaSorting": [
                            [2, "desc"]
                        ],
                        "language": {
                            url: '/assets/vendors/data-tables/i18n/fr_fr.json'
                        }
                    });
                };

            },
        },
    }

    Vue.createApp(App).mount('#app');

});
</script>
@stop
