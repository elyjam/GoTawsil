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
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des expeditions</span></h5>
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

                    <div class="list-table" id="app">
                        <div class="card">
                            <div class="card-content">
                                <form id="form" method="POST">
                                    @csrf



                                    <div class="row">
                                        <form id="form" method="POST">
                                            @csrf
                                            <button class="waves-effect waves-light  btn" type="submit"><i class="material-icons right">local_printshop</i> Imprimer</button>

                                        </form>

                                    </div>

                                    <!-- Dropdown Structure -->


                                </form>
                                <!-- datatable start -->
                                <div class="responsive-table" id="responsive-table">
                                    <table id="table" style="width: 100%" class="display dataTable dtr-inline">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th> Nom / R.S</th>
                                                <th>Téléphone</th>
                                                <th>Email</th>
                                                <th>Ville</th>
                                                <th>statut</th>
                                                <th></th>
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
        @if (auth()->user()->role == '1' || auth()->user()->role == '7')
            <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a
                    href="{{ route('client_create') }}"
                    class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i
                        class="material-icons">add</i></a>
            </div>
       @endif
    </div>

    <div id="delete_modal" class="modal ">
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

    <div id="modal_details" class="modal">
        <div class="modal-content" style="padding:0;">
            <iframe id="inlineFrameExample" title="Inline Frame Example" src="" width="100%" height="100%">
            </iframe>
        </div>
        {{-- <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
        </div> --}}
    </div>
    <div id="modal_slide" class="modal">
        <div class="modal-content" style="padding:0;">
            <iframe id="inlineFrameSlide" title="Inline Frame Example" src="" width="100%" height="100%">
            </iframe>
        </div>
        {{-- <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
        </div> --}}
    </div>
@stop
@section('js')
    <script>
        function openSuppModal(id) {
            $("#delId").val(id);
            $('#delete_modal').modal('open');
        }

        function Detailsmodal(id) {
            document.getElementById('inlineFrameExample').src = "/expedition/detail/" + id;
            $('#modal_details').modal('open');
        }

        function Slidemodal(id) {
            document.getElementById('inlineFrameSlide').src = "/expedition/slider/" + id;
            $('#modal_slide').modal('open');
        }

        function suppRecord() {
            window.location.replace("/expedition/delete/" + $("#delId").val());
        }
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
                "ajax": {
                    url: '/client/api',
                    "type": "GET",
                    "data": function(d) {
                        d.form = $("#form").serialize();
                    }
                },
                columns: [{
                        data: 'code_client',
                        name: 'clients.code'
                    },
                    {
                        data: 'libelle',
                        name: 'clients.libelle'
                    },
                    {
                        data: 'telephone',
                        name: 'clients.telephone'
                    },
                    {
                        data: 'email',
                        name: 'clients.email'
                    },
                    {
                        data: 'ville',
                        name: 'villes.libelle'
                    },
                    {
                        data: 'statut',
                        name: 'expeditions.statut',
                        orderable: false,
                        searchable: false
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
