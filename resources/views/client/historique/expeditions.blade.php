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

    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg-client.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Mes Ancien Expeditions</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('Dashboard_Client') }}">Accueil</a>
                        </li>
                        {{-- <li class="breadcrumb-item"><a href="{{ route('expedition_list') }}">Gestion des expeditions</a>
                        </li> --}}
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

                    <form method="POST">
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
                                        <label for="code">
                                            N° Expedition</label>
                                    </div>

                                    <div class="col s12 m2 input-field">
                                        <select id="statuts" name="statuts" placeholder=""
                                            class="select2 browser-default">
                                            <option value=''></option>
                                            @foreach ($statuts as $row)
                                            <option class='option' {{ $row->key == old('statuts') ? 'selected' : '' }}
                                                value='{{ $row->key }}'> {{ $row->value }}</option>
                                            @endforeach
                                        </select>
                                        <label for="statuts"> Statuts</label>
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
                                                {{-- <th> Expéditeur</th> --}}
                                                <th>Numéro</th>
                                                <th>Date Création</th>
                                                <th>Date Livraison</th>
                                                <th> Destinataire</th>
                                                <th> Destination</th>
                                                {{-- <th> Adresse </th> --}}
                                                <th> Téléphone</th>
                                                <th> Statut</th>
                                                <th> Nature</th>
                                                <th> Fond</th>
                                                <th> frais</th>
                                                {{-- <th class="hide-on-small-only"> Port</th> --}}
                                                {{-- <th class="hide-on-small-only"> Prix colis</th> --}}
                                                <th> Nb. Colis</th>
                                                {{-- <th> V. Déclarée </th> --}}
                                                {{-- <th> Paiement / chèque </th> --}}
                                                {{-- <th> Ouverture Colis </th> --}}
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($records as $record)
                                                <tr>
                                                    <td>
                                                        @if ($record->type == 'CDP')
                                                            <i class="blue-text material-icons"
                                                                title="Document administratif">email</i>
                                                        @elseif($record->type == 'ECOM')
                                                            <i class="red-text material-icons"
                                                                title="Colis e-commerce">inbox</i>
                                                        @elseif($record->type == 'COLECH')
                                                            <i class=" material-icons" title="Colis en échange"
                                                                style="color: #d8a71d ">autorenew</i>
                                                        @endif


                                                    </td>
                                                    {{-- <td> {{ $record->clientDetail->libelle ?? '' }} </td> --}}
                                                    <td>{{ $record->num_expedition }}</td>
                                                    <td> {{ $record->created_at }} </td>
                                                    <td> </td>
                                                    <td> {{ $record->destinataire }}</td>

                                                    <td>
                                                        {{ $record->agenceDesDetail->libelle ?? '' }}
                                                    </td>
                                                    {{-- <td>
                                                        <p class="hide-on-med-and-up"><span
                                                                class="new badge gradient-45deg-light-blue-cyan"
                                                                data-badge-caption="{{ $record->agenceDesDetail->Libelle ?? '' }}"></span>
                                                        </p>

                                                    </td> --}}
                                                    {{-- <td> {{ $record->adresse_destinataire }} </td> --}}
                                                    <td> {{ $record->telephone }} </td>

                                                    <td> <a href="#!" onclick="historyOpen({{ $record->id }})">
                                                            @if ($record->etape == 13)
                                                                {{ \App\Models\Expedition::getEtapeCommentaire($record->beforeLastEtape($record->id)) }}
                                                            @else
                                                                {{ $record->getEtape() }}
                                                            @endif

                                                        </a></td>
                                                    <td> {{ $record->retour_fond }} </td>
                                                    <td> {{ $record->fond }} </td>
                                                    <td> {{ $record->ttc }} </td>
                                                    {{-- <td class="hide-on-small-only"> {{ $record->port }} </td> --}}
                                                    {{-- <td class="hide-on-small-only"> {{ $record->ttc }} </td> --}}
                                                    <td> {{ $record->colis }} </td>
                                                    {{-- <td> {{ $record->vDeclaree }} </td> --}}
                                                    {{-- <td> {{ $record->paiementCheque }} </td> --}}
                                                    {{-- <td> {{ $record->ouvertureColis }} </td> --}}
                                                    <td>
                                                        {{-- @if ($record->etape == '1')
                                                            <a
                                                                href="{{ route('expedition_update_client', ['expedition' => $record->id]) }}"><i
                                                                    class="material-icons tooltipped" data-position="top"
                                                                    data-tooltip="Modifier">edit</i></a>
                                                            <a href="#!"
                                                                onclick="openSuppModal({{ $record->id }})"><i
                                                                    class="material-icons tooltipped"
                                                                    style="color: #c10027;" data-position="top"
                                                                    data-tooltip="Annuler l'expédition">close</i></a>
                                                            <a href="{{ route('expedition_pdf', $record->id) }}"
                                                                target="_blank"><i class="material-icons tooltipped"
                                                                    style="color: rgb(255 173 0);" data-position="top"
                                                                    data-tooltip="Imprimer">print</i></a>
                                                        @else --}}
                                                            {{-- <a href="#"><i class="material-icons tooltipped"
                                                                    data-position="top"
                                                                    data-tooltip="Vous avez pas le droit de faire une modification"
                                                                    style="color: #666666;">edit</i></a>
                                                            <a href="#"><i class="material-icons tooltipped"
                                                                    style="color: #666666;" data-position="top"
                                                                    data-tooltip="Vous avez pas le droit de faire une suppression">close</i></a>
                                                             --}}
                                                                    <a href="{{ route('ancien_exp_pdf', $record->id) }}"
                                                                target="_blank"><i class="material-icons tooltipped"
                                                                    style="color:  rgb(255 173 0);" data-position="top"
                                                                    data-tooltip="Imprimer">print</i></a>
                                                        {{-- @endif --}}

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
        {{-- <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a --}}
        {{-- href="{{route('expedition_create')}}" --}}
        {{-- class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i --}}
        {{-- class="material-icons">add</i></a></div> --}}
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
   {{-- @foreach ($records as $record)
        <div id="history_modal{{ $record->id }}" class="modal">
            <div class="modal-content">
                <h4> Historique du statut</h4>
                <div>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($record->etapeHistory as $history)
                                @if ($history->etape != '13' && $history->etape != null)
                                    <tr>
                                        @if ($history->etape == 20)
                                            <td>{{ $history->created_at }}</td>
                                            <td>{{ $history->libelle }}</td>
                                        @else
                                            <td>{{ $history->created_at }}</td>
                                            <td>{{ $history->getEtape() }}</td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>



                </div>
                <input type="hidden" name="delId" id="delId">
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-green btn red">Ferme</a>

            </div>
        </div>
    @endforeach --}}




    @foreach ($records as $record)
        <div id="history_modal{{ $record->id }}" class="modal">
        </div>
    @endforeach
@stop
@section('js')
<script>
         $(document).ready(function() {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var exp = url.searchParams.get("exp");
            if (!exp) exp = ""
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
                                "oSearch": {
                                    "sSearch": exp
                                },
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
    <script>
        document.getElementById("historique").classList.add("activate");
        document.getElementById("historique_exps").classList.add("activate");
        function openSuppModal(id) {
            $("#delId").val(id);
            $('#delete_modal').modal('open');
        }

        function historyOpen(id) {
            let cid = id;
                if (cid != '') {
                    $.ajax({
                        url: '/expedition/historyExpeditionShow',
                        type: 'post',
                        data: 'cid=' + cid + '&_token={{ csrf_token() }}',
                        success: function(result) {


                            $('#history_modal'+cid).html(result);
                            $('#history_modal'+cid).modal('open');

                        }

                    });
                }



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
            var url_string = window.location.href;
            var url = new URL(url_string);
            var exp = url.searchParams.get("exp");
            if (!exp) exp = ""
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
                                "oSearch": {
                                    "sSearch": exp
                                },
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
