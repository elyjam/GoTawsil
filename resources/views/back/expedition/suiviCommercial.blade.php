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
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image" style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Liste des expeditions / Commercial</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('expedition_list')}}">Liste des expeditions</a>
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

                <button type="button" onclick="advancedsearch()" class="btn btn-light">Recherche Avancée</button>

                <div>

                    <form id="form" method="POST">
                        @csrf

                        <div class="card">

                            <div class="card-content">
                                <div class="row">

                                    <div class="col s12 m2 input-field">
                                        <select id="agence" name="agence" placeholder="" class="select select2 browser-default">
                                            <option value='0'></option>
                                            @foreach ($agenceRecords as $row)
                                            <option class='option' {{($row->id == old('agence')) ? 'selected' : ''}} value='{{$row->id}}'> {{$row->Libelle}}</option>
                                            @endforeach
                                        </select>
                                        <label for="agence"> Origine</label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <select id="agence_des" name="agence_des" placeholder="" class="select2 browser-default">
                                            <option value='0'></option>
                                            @foreach ($agenceRecords as $row)
                                            <option class='option' {{($row->id == old('agence_des')) ? 'selected' : ''}} value='{{$row->id}}'> {{$row->Libelle}}</option>
                                            @endforeach
                                        </select>
                                        <label for="agence_des"> Destination</label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <select id="typ" name="typ" placeholder="" class="select2 browser-default">
                                            <option value='1'></option>
                                            <option value='test'>Type 1</option>
                                        </select>
                                        <label for="typ"> Type</label>
                                    </div>

                                    <div class="col s12 m3 input-field">
                                        <input id="start_date" value="{{old('start_date')}}" name="start_date" type="text" placeholder="" class="datepicker">
                                        <label for="start_date">Du </label>
                                    </div>
                                    <div class="col s12 m3 input-field">
                                        <input id="end_date" value="{{old('start_date')}}" name="end_date" type="text" placeholder="" class="datepicker">
                                        <label for="end_date">Au </label>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col s12 m2 input-field">
                                        <input id="n_colis" value="{{old('start_date')}}" name="n_colis" type="text" placeholder="">
                                        <label for="n_colis">N° Colis </label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <select id="Statut" name="Statut" placeholder="" class="select2 browser-default">
                                            <option value='0'>Tous</option>
                                            @foreach ($statutRecords as $row)
                                            <option class='option' {{($row->id == old('Statut')) ? 'selected' : ''}} value='{{$row->key}}'> {{$row->value}}</option>
                                            @endforeach
                                        </select>
                                        <label for="Statut">Statut</label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <select name='Expediteur' id='Expediteur' class="select2 browser-default">
                                            <option value='0'></option>
                                            @foreach ($clientRecords as $row)
                                            <option class='option' {{($row->id == old('Expediteur')) ? 'selected' : ''}} value='{{$row->id}}'> {{$row->libelle}}</option>
                                            @endforeach
                                        </select>
                                        <label for="Expediteur"> Expéditeur</label>
                                    </div>
                                    <div class="col s12 m3 input-field">
                                        <select id="comment" name="comment" placeholder="" class="select2 browser-default">
                                            <option value='1'></option>
                                            <option value='test'></option>
                                        </select>
                                        <label for="comment">Comment</label>
                                    </div>
                                    <div class="col s12 m3 input-field">
                                        <button type="button" onclick="event.preventDefault();
                                                        $('#form').attr('action', '{{route('expedition_list')}}'); document.getElementById('form').submit();" class="btn btn-light" style="margin-right: 1rem;">
                                            <i class="material-icons">search</i></button>
                                        <button type="button" onclick="event.preventDefault();
                                                        $('#form').attr('action', '{{route('expedition_export')}}'); document.getElementById('form').submit();" class="btn btn-download"><i class="material-icons">file_download</i></button>
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
                                            <th></th>
                                            <th hidden></th>
                                            <th>N° Exp</th>
                                            {{-- <th>Saisie le</th>
                                            <th>Date Ram</th> --}}
                                            <th> Expéditeur</th>
                                            {{-- <th class="hide-on-small-only"> Destination</th> --}}
                                            <th> Destinataire</th>
                                            {{-- <th> Adresse </th>--}}
                                            {{-- <th class="hide-on-small-only"> Téléphone</th> --}}
                                            <th class="hide-on-small-only"> Etape</th>
                                            <th class="hide-on-small-only"> Nature</th>
                                            <th class="hide-on-small-only"> Fond</th>
                                            <th class="hide-on-small-only"> Port</th>
                                            <th class="hide-on-small-only"> Prix colis</th>
                                            <th class="hide-on-small-only"> Nb. Colis</th>
                                            {{-- <th> V. Déclarée </th>--}}
                                            {{-- <th> Paiement / chèque </th>--}}
                                            {{-- <th> Ouverture Colis </th>--}}
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a href="{{route('expedition_create')}}" class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i class="material-icons">add</i></a></div>
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

    function advancedsearch() {
        var x = document.getElementById("form");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }


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

</script>
@stop

