@extends($layout)
@section('content')

<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des bons de ramassages </span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('bon_list')}}">Liste des bons</a>
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
                @if(session()->has('validate'))
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
                <form id="form" method="POST">
                    @csrf

                    <div class="card">

                        <div class="card-content">
                            <div class="row">
                                <div class="col s12 m2 input-field">
                                    <input id="start_date" value="{{ old('start_date') }}" name="start_date"
                                        type="text" placeholder="" class="datepicker">
                                    <label for="start_date">Du </label>
                                </div>
                                <div class="col s12 m2 input-field">

                                    <input id="end_date" value="{{ old('end_date') }}" name="end_date" type="text"
                                        placeholder="" class="datepicker">
                                    <label for="end_date">Au </label>
                                </div>

                                <div class="col s12 m2 input-field">
                                    <input id="code" value="{{ old('code') }}" name="code" type="text"
                                        placeholder="">
                                    <label for="code">N° Bon</label>
                                </div>
                                <div class="col s12 m2 input-field">
                                    <button type="button"
                                    onclick="event.preventDefault();
                                                                        $('#form').attr('action', '{{ route('bon_list') }}'); document.getElementById('form').submit();"
                                    class="btn btn-light" style="margin-right: 1rem;">
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
                                <table id="table" style="width: 100%"  class="table table-bordered table-striped">
                                    <thead>
                                        <tr>

                                            <th>code</th>
                                            <th>Crée le</th>
                                            <th>Client</th>
                                            <th>Validé le</th>
                                            <th>Etiquette</th>
                                            <th>Editer</th>
                                            <th>Partiel</th>
                                            <th>Valider</th>
                                            {{--
                                            <th>Validé le</th>
                                            <th>Etiquette</th>
                                            <th>Editer</th>
                                            <th>Partiel</th>
                                            <th>Valider</th>



                                            <th>Colis validé</th> --}}

                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    {{-- <tbody>
                                        @foreach ($records as $record)

                                        <tr>
                                            <td><span class=" badge grey">{{ $record->code }}</span></td>
                                            <td> {{ $record->created_at }}</td>

                                            <td>{{ $record->client->libelle ?? ''}}</td>
                                            <td> {{ $record->date_validation }}</td>
                                            <td style="text-align: center"> <a target="_blank" href="{{route('pdf_bon',$record->id)}}"><i
                                                        class="material-icons tooltipped" style="color: #2196f3;"
                                                        data-position="top"
                                                        data-tooltip="Imprimer etiquette">print</i></a> </td>
                                            <td style="text-align: center"> <a href="{{route('bon_print_detail',$record->id)}}" target="_blank"><i
                                                        class="material-icons tooltipped" style="color: #2196f3;"
                                                        data-position="top"
                                                        data-tooltip="Imprimer bon N° {{ $record->code }} ">print</i></a>
                                            </td>
                                            <td style="text-align: center"> <a href="{{route('modif_bon',$record->id)}}"><i
                                                        class="material-icons tooltipped" style="color: #2196f3;"
                                                        data-position="top"
                                                        data-tooltip="Détail du bon ">library_books</i></a> </td>
                                            @if ($record->date_validation)
                                            <td style="text-align: center"> <i class="material-icons green-text tooltipped"
                                                         data-position="top"
                                                        data-tooltip="Caisse déjà validé ">verified_user</i></td>
                                            @else
                                            <td style="text-align: center"> <a href="#!" onclick="openValideModal({{$record->id}})"><i
                                                        class="material-icons tooltipped" style="color: #2196f3;"
                                                        data-position="top"
                                                        data-tooltip="Valider la caisse ">verified_user</i></a> </td>

                                            @endif


                                            <td> {{ $record->expeditionValide->count()}} {{'/'}}
                                                {{$record->expeditionDetail->count()}}</td>


                                        </tr>


                                        @endforeach --}}

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

<div id="valideRamassage" class="modal">
    <div class="modal-content">
        <h4> Confirmation de ramassage</h4>
        <div>
            Êtes-vous sûr de valider le ramassage ?
        </div>
        <input type="hidden" name="validId" id="validId">
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
        <a href="#!" class="waves-effect waves-green btn red" onclick="validRecord()">valider</a>
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
    window.location.replace("/bon/delete/" + $("#delId").val());
}

function openValideModal(id) {
    $("#validId").val(id);
    $('#valideRamassage').modal('open');
}

function validRecord() {
    window.location.replace("/bon/validate/" + $("#validId").val());
}


$(document).ready(function() {
    $('.modal').modal();
});

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
                        [1, "desc"]
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


$(document).ready(function() {
    $('.modal').modal();
            var url_string = window.location.href;
            var check_client_display = true;
            var check_frais_display = true;



            var url = new URL(url_string);
            var exp = url.searchParams.get("exp");
            if (!exp) exp = ""
            // console.log(exp);
            $("#table").DataTable({
                "aaSorting": [
                    [1, "desc"]
                ],
                "scrollX": true,
                "pageLength": 25,
                "oSearch": {
                    "sSearch": exp
                },
                processing: true,
                serverSide: true,
                orderable: true,
              searchable: true,

                "ajax": {
                    url: '/bon/api',
                    "type": "GET",
                    "data": function(d) {
                        d.form = $("#form").serialize();
                    }
                },
                columns: [
                    {
                        data: 'code',
                        name: 'bons.code',

                    },
                    {
                        data: 'created_at',
                        name: 'bons.created_at'
                    }, {
                        data: 'libelle',
                        name: 'clients.libelle'
                    }, {
                        data: 'date_validation',
                        name: 'bons.date_validation'
                    },{
                        data: 'etiquette',
                        name: 'etiquette',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'editer',
                        name: 'editer',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'partiel',
                        name: 'partiel',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'valider',
                        name: 'valider',
                        orderable: false,
                        searchable: false
                    },


                ],
                "language": {
                    url: '/assets/vendors/data-tables/i18n/fr_fr.json'
                }
            });
            setTimeout(function() {
                $('.tooltipped').tooltip();
            }, 2000);

        });
</script>
@stop
