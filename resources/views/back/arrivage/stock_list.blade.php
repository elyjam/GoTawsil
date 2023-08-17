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

    #modal_details, #modal_slide{
        top: 5% !important;
        width: 90% !important;
        height: 100vh !important;
        max-height: 89% !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
    height: 45px;
    }
</style>
@section('content')

    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion de Stock</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
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
                    <div>
                        <form id="form" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col s12 m3 input-field">
                                            <select id="ville" name="ville" placeholder=""
                                                class="select select2 browser-default">
                                                <option value='0'></option>
                                                @foreach ($agenceRecords as $row)
                                                    <option class='option'
                                                        {{ $row->id == old('ville') ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="ville"> Filtrer par ville</label>
                                        </div>
                                        <div class="col s12 m3 input-field">
                                            <button type="button"
                                                onclick="event.preventDefault();
                                                                                    $('#form').attr('action', '{{ route('stock_list') }}'); document.getElementById('form').submit();"
                                                class="btn btn-light" style="margin-right: 1rem;">
                                                <i class="material-icons">search</i></button>
                                            <button type="button"
                                                onclick="event.preventDefault();
                                                                                    $('#form').attr('action', '{{ route('export_stock') }}'); document.getElementById('form').submit();"
                                                class="btn btn-download"><i
                                                    class="material-icons">file_download</i></button>

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
                                <div class="responsive-table" id="responsive-table">
                                    <table id="table" style="width: 100%" class="display dataTable dtr-inline">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Created at</th>
                                                <th>N° Exp</th>

                                                <th> Expéditeur</th>

                                                <th> Destinataire</th>
                                                <th> Sens</th>
                                                <th class="hide-on-small-only"> Etape</th>
                                                <th class="hide-on-small-only"> Nature</th>
                                                <th class="hide-on-small-only"> Fond</th>
                                                <th class="hide-on-small-only"> Port</th>
                                                <th class="hide-on-small-only"> Prix colis</th>
                                                <th class="hide-on-small-only"> Nb. Colis</th>
                                                <th style="width: 100px!important;"></th>
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

    <div id="retour_modal" class="modal">
        <div class="modal-content">
            <h4> Confirmation Retour</h4>
            <div>
                Êtes-vous sûr de vouloir valider le retour au client?
            </div>
            <input type="hidden" name="retourId" id="retourId">
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
            <a href="#!" class="waves-effect waves-green btn red" onclick="retourExp()">Valider Retour</a>
        </div>
    </div>

    <div id="transfert_modal" class="modal">
        <div class="modal-content">
            <h4> Confirmation Transfert</h4>
            <div>
                Êtes-vous sûr de vouloirtransferer?
            </div>
            <input type="hidden" name="transfertId" id="transfertId">
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
            <a href="#!" class="waves-effect waves-green btn red" onclick="transfertExp()">Transfert expédition</a>
        </div>
    </div>
@stop
@section('js')
    <script>
      function openRetourModal(id) {
            $("#retourId").val(id);
            $('#retour_modal').modal('open');
        }

        function openSuppModal(id) {
            $("#delId").val(id);
            $('#delete_modal').modal('open');
        }

        function openTransfertModal(id) {
            $("#transfertId").val(id);
            $('#transfert_modal').modal('open');
        }


        function suppRecord() {
            window.location.replace("/expedition/delete/" + $("#delId").val());
        }

        function retourExp() {
            window.location.replace("/stock/retour/" + $("#retourId").val());
        }

        function transfertExp() {
            window.location.replace("/stock/transfert/" + $("#transfertId").val());
        }
        $(document).ready(function() {
            $('.modal').modal();
            var url_string = window.location.href;
            var check_client_display = true;
            var check_frais_display = true;

            if ({!! auth()->user()->role !!} == 2 || {!! auth()->user()->role !!} == 5 || {!! auth()->user()->role !!} == 7 ||
                {!! auth()->user()->role !!} == 6 || {!! auth()->user()->role !!} == 8) {
                check_client_display = false;
                check_frais_display = false;
            }

            var url = new URL(url_string);
            var exp = url.searchParams.get("exp");
            if (!exp) exp = ""
            // console.log(exp);
            $("#table").DataTable({
                "aaSorting": [
                    [1, "desc"]
                ],
                "scrollX": true,
                "oSearch": {
                    "sSearch": exp
                },
                processing: true,
                serverSide: true,
                "ajax": {
                    url: '/stock/api',
                    "type": "GET",
                    "data": function(d) {
                        d.form = $("#form").serialize();
                    }
                },
                columns: [{
                        data: 'typeicon',
                        name: 'expeditions.type'
                    },
                    {
                        data: 'created_at',
                        name: 'expeditions.created_at',
                        'visible': false
                    },
                    {
                        data: 'num_expedition',
                        name: 'expeditions.num_expedition'
                    },
                    {
                        data: 'client',
                        name: 'expeditions.client',
                        'visible': check_client_display
                    },
                    {
                        data: 'destinataire',
                        name: 'expeditions.destinataire'
                    },
                    {
                        data: 'sens',
                        name: 'expeditions.sens'
                    },
                    {
                        data: 'statut_label',
                        name: 'statut_label'
                    },
                    {
                        data: 'nature',
                        name: 'expeditions.retour_fond'
                    },
                    {
                        data: 'fond',
                        name: 'expeditions.fond'
                    },
                    {
                        data: 'port',
                        name: 'expeditions.port'
                    },
                    {
                        data: 'ttc',
                        name: 'expeditions.ttc',
                        'visible': check_frais_display
                    },
                    {
                        data: 'colis',
                        name: 'expeditions.colis'
                    },
                    {
                        data: 'action',
                        name: 'expeditions.action',
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
