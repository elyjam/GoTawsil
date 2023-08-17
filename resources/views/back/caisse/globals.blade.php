@extends($layout)
<style>
    .modal-content::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    }

    .modal-content::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5;
    }

    .modal-content::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #1991ce;
    }

    #modal_details {
        top: 5% !important;
        width: 90% !important;
        height: 100vh !important;
        max-height: 89% !important;
    }

    #table {
        font-size: 13px;
    }
</style>

@section('content')
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Caisses > Liste des caisses globals</span></h5>
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
                                                <option value=''>Tous</option>
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
                                            <input id="end_date" value="{{ old('end_date') }}" name="end_date"
                                                type="text" placeholder="" class="datepicker">
                                            <label for="end_date">Au </label>
                                        </div>
                                        <div class="col s12 m2 input-field">
                                            <select id="statut" name="statut" placeholder=""
                                                class="select2 browser-default">
                                                <option value=''>Tous</option>
                                                @foreach ($status as $s)
                                                    <option value='{{ $s->key }}'
                                                        {{ $s->key == old('statut') ? 'selected' : '' }}>
                                                        {{ $s->value }}</option>
                                                @endforeach
                                            </select>
                                            <label for="statut"> Statut</label>
                                        </div>
                                        <div class="col s12 m2 input-field">
                                            <button type="submit" class="btn btn-light tooltipped"
                                                data-tooltip="Lancer la recherche">
                                                <i class="material-icons">search</i></button>

                                            @if (\Auth::user()::hasRessource('Menu Caisse : Rapport'))
                                                <button type="button" class="btn tooltipped"
                                                    data-tooltip="Télécharger le rapport détail des caisses"
                                                    onclick="window.location ='/caisse/export?'+$('#form').serialize()"
                                                    class="btn btn-download"><i
                                                        class="material-icons">assignment</i></button>
                                            @endif

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
                                    <table id="table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr style="font-size: 13px!important;">

                                                <th style="width:20px ;"> Numéro </th>
                                                <th> Du </th>
                                                <th> Au </th>
                                                <th> Ville </th>
                                                <th> Générée par </th>
                                                <th style="width:30px ;"> Férmée Par </th>
                                                <th> Validée le </th>
                                                <th> Reçu le </th>
                                                <th> Statut </th>
                                                @if (\Auth::user()::hasRessource('Menu Caisse : Affichage montant dans la liste'))
                                                    <th> Montant </th>
                                                @endif
                                                <th style="width: 50px;"> Actions </th>
                                                <th style="width:20px ;">Imprimer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
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
            <a href="#!" id="mybtn1" class="waves-effect waves-green btn green" onclick="closeRecord()">Fermer</a>
        </div>
    </div>

    <div id="recu_modal" class="modal">
        <div class="modal-content">
            <h4> Confirmation de la réception</h4>
            <div>
                Êtes-vous sûr de vouloir valider la réception ?
            </div>
            <input type="hidden" name="caisseIdRecu" id="caisseIdRecu">
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
            <button id="mybtn2" class="waves-effect waves-green btn green" onclick="recuRecord()" autofocus>Valider la
                réception</button>
        </div>
    </div>

    <div id="valid_modal" class="modal">
        <div class="modal-content">
            <h4> Confirmation de la validation</h4>
            <div>
                Êtes-vous sûr de vouloir valider ?
            </div>
            <input type="hidden" name="caisseIdValid" id="caisseIdValid">
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
            <a href="#!" id="mybtn3" class="waves-effect waves-green btn green"
                onclick="validRecord()">Valider</a>
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

            let btn = document.getElementById('mybtn1');

            // when the btn is clicked print info in console
            btn.addEventListener('click', (ev) => {
                console.log("Btn clicked");
            });

            document.addEventListener('keypress', (event) => {

                // event.keyCode or event.which  property will have the code of the pressed key
                let keyCode = event.keyCode ? event.keyCode : event.which;

                // 13 points the enter key
                if (keyCode === 13) {
                    // call click function of the buttonn
                    btn.click();
                }

            });
            $('#close_modal').modal('open');
        }





        function closeRecord() {
            window.location.replace("/caisse/change-status/" + $("#caisseId").val() + "/2");
        }

        function openRecuModal(id) {
            $("#caisseIdRecu").val(id);

            let btn = document.getElementById('mybtn2');

            // when the btn is clicked print info in console
            btn.addEventListener('click', (ev) => {
                console.log("Btn clicked");
            });

            document.addEventListener('keypress', (event) => {

                // event.keyCode or event.which  property will have the code of the pressed key
                let keyCode = event.keyCode ? event.keyCode : event.which;

                // 13 points the enter key
                if (keyCode === 13) {
                    // call click function of the buttonn
                    btn.click();
                }

            });
            $('#recu_modal').modal('open');
        }

        function recuRecord() {
            window.location.replace("/caisse/change-status/" + $("#caisseIdRecu").val() + "/3");
        }

        function openValidModal(id) {
            $("#caisseIdValid").val(id);

            let btn = document.getElementById('mybtn3');

            // when the btn is clicked print info in console
            btn.addEventListener('click', (ev) => {
                console.log("Btn clicked");
            });

            document.addEventListener('keypress', (event) => {

                // event.keyCode or event.which  property will have the code of the pressed key
                let keyCode = event.keyCode ? event.keyCode : event.which;

                // 13 points the enter key
                if (keyCode === 13) {
                    // call click function of the buttonn
                    btn.click();
                }

            });
            $('#valid_modal').modal('open');
        }

        function validRecord() {
            window.location.replace("/caisse/change-status/" + $("#caisseIdValid").val() + "/4");
        }

        function Detailsmodal(url) {
            document.getElementById('inlineFrameExample').src = url;
            $('#modal_details').modal('open');
        }

        $(document).ready(function() {
            $('.modal').modal();
            var url_string = window.location.href;
            var url = new URL(url_string);
            var exp = url.searchParams.get("exp");
            if (!exp) exp = ""
            // console.log(exp);
            $("#table").DataTable({
                "aaSorting": [
                    [1, "desc"]
                ],
                "scrollX": false,
                "oSearch": {
                    "sSearch": exp
                },
                processing: true,
                serverSide: true,
                "ajax": {
                    url: '/caisse/api',
                    "type": "GET",
                    "data": function(d) {
                        d.form = $("#form").serialize();
                    }
                },
                columns: [{
                        data: 'code',
                        name: 'numero'
                    },
                    {
                        data: 'created_at_td',
                        name: 'caisses.created_at'
                    },
                    {
                        data: 'date_fin_td',
                        name: 'date_fin'
                    },
                    {
                        data: 'agence',
                        name: 'id_agence.libelle'
                    },
                    {
                        data: 'generee_par',
                        name: 'id_utilisateur_gen.libelle'
                    },
                    {
                        data: 'confirme_par',
                        name: 'confirme_par'
                    },
                    {
                        data: 'validate_at_td',
                        name: 'caisses.date_validation'
                    },
                    {
                        data: 'received_at_td',
                        name: 'caisses.date_reception'
                    },
                    {
                        data: 'statut_td',
                        name: 'statuts.value'
                    }
                    @if (\Auth::user()::hasRessource('Menu Caisse : Affichage montant dans la liste'))
                        , {
                            data: 'montant',
                            name: 'montant',
                            orderable: false,
                            searchable: false
                        }
                    @endif , {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'print',
                        name: 'print',
                        orderable: false,
                        searchable: false
                    }
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
