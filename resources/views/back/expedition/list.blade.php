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

    #modal_details,
    #modal_slide {
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
                    <div>
                        <form id="form" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col s12 m3 input-field">
                                            <select id="agence" name="agence" placeholder=""
                                                class="select select2 browser-default">
                                                <option value='0'></option>
                                                @foreach ($agenceRecords as $row)
                                                    <option class='option' {{ $row->id == old('agence') ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="agence"> Origine</label>
                                        </div>
                                        <div class="col s12 m3 input-field">
                                            <select id="agence_des" name="agence_des" placeholder=""
                                                class="select2 browser-default">
                                                <option value='0'></option>
                                                @foreach ($agenceRecords as $row)
                                                    <option class='option'
                                                        {{ $row->id == old('agence_des') ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="agence_des"> Destination</label>
                                        </div>
                                        {{-- <div class="col s12 m2 input-field">
                                        <select id="typ" name="typ" placeholder="" class="select2 browser-default">
                                            <option value='1'></option>
                                            <option value='test'>Type 1</option>
                                        </select>
                                        <label for="typ"> Type</label>
                                    </div> --}}
                                        <div class="col s12 m3 input-field">
                                            <input id="start_date" value="{{ old('start_date') }}" name="start_date"
                                                type="text" placeholder="" class="datepicker">
                                            <label for="start_date">Du </label>
                                        </div>
                                        <div class="col s12 m3 input-field">
                                            <input id="end_date" value="{{ old('end_date') }}" name="end_date"
                                                type="text" placeholder="" class="datepicker">
                                            <label for="end_date">Au </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12 m2 input-field">
                                            <input id="n_colis" value="{{ old('n_colis') }}" name="n_colis"
                                                type="text" placeholder="">
                                            <label for="n_colis">N° Colis </label>
                                        </div>

                                        <div class="col s12 m2 input-field">
                                            <select name='expediteur' id='expediteur' class="select2 browser-default">
                                                <option value='0'></option>
                                                @foreach ($clientRecords as $row)
                                                    <option class='option'
                                                        {{ $row->id == old('expediteur') ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="expediteur"> Client</label>
                                        </div>

                                        <div class="col s12 m3 input-field">
                                            <select id="etapes" name="etapes[]" class="select2 browser-default"
                                                multiple="multiple">

                                                <option value='0'>Selectionner l'etape</option>
                                                @foreach ($commentRecords as $row)
                                                    <option value="{{ $row->key }}"
                                                        {{ collect(old('etapes'))->contains($row->key) ? 'selected' : '' }}>
                                                        {{ $row->value }}</option>
                                                @endforeach


                                            </select>
                                            <label for="etapes">Etapes</label>
                                        </div>
                                        <div class="col s12 m2 input-field">
                                            <select name='gEtapes' id='gEtapes' class="select2 browser-default">
                                                <option value='0'></option>
                                                @foreach ($GroupStatutsRecords as $row)
                                                    <option class='option'
                                                        {{ $row->id == old('gEtapes') ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="gEtapes">G.Etapes</label>
                                        </div>
                                        <div class="col s12 m2 input-field">
                                            <button type="button"
                                                onclick="event.preventDefault();
                                                                                    $('#form').attr('action', '{{ route('expedition_list') }}'); document.getElementById('form').submit();"
                                                class="btn btn-light" style="margin-right: 1rem;">
                                                <i class="material-icons">search</i></button>
                                            <button type="button"
                                                onclick="event.preventDefault();
                                                                                    $('#form').attr('action', '{{ route('expedition_export') }}'); document.getElementById('form').submit();"
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
        @if (auth()->user()->role == '1' || auth()->user()->role == '7')
            <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a
                    href="{{ route('expedition_create') }}"
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
            // var check_role = {!! auth()->user()->hasRessource('Expedition Details Avance') !!};
            console.log(check_frais_display);
            // if ({!! \Auth::user()->hasRessource('Expedition Details Avance') !!}) {
            //     check_client_display = true;
            //     check_frais_display = true;
            // }

            var url = new URL(url_string);
            var exp = url.searchParams.get("exp");
            if (!exp) exp = ""
            // console.log(exp);
            $("#table").DataTable({
                "aaSorting": [
                    [1, "desc"]
                ],
                "scrollX": true,
                // "oSearch": {
                //     "sSearch": exp,
                //     "bRegex": true
                // },
                processing: true,
                serverSide: true,
                "ajax": {
                    url: '/expedition/api',
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



            regExSearch = exp;
            $("#table").DataTable().column(2).search(regExSearch, true, false).draw();

            setTimeout(function() {
                $('.tooltipped').tooltip();
            }, 2000);

        });
    </script>
@stop
