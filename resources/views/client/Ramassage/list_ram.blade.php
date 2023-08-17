@extends($layout)
@section('content')
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg-client.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->

        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion de ramassage</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('Dashboard_Client') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('bon_list') }}">Liste des bons</a>
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
                        @if (session()->has('success'))
                            <div class="card-alert card green">
                                <div class="card-content white-text">
                                    <p> {{ session()->get('success') }}</p>
                                </div>
                                <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div>
                        @if (session()->has('unsuccess'))
                            <div class="card-alert card red">
                                <div class="card-content white-text">
                                    <p> {{ session()->get('unsuccess') }}</p>
                                </div>
                                <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <form action="{{ route('demande_ram__client') }}" method="POST">
                        @csrf

                        <button type="submit" name="action" value="dem_ram" class="btn btn-light">Envoyer demande de
                            ramasage</button>
                        <button type="submit" name="action" value="print"
                            class="btn waves-effect waves-light cyan right">Imprimer</button>

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
                                                            <input name="checkall" type="checkbox" id='checkall'>
                                                            <span></span>
                                                        </label>
                                                    </th>
                                                    <th></th>
                                                    {{-- <th> Expéditeur</th> --}}
                                                    <td>Numéros</td>
                                                    <td>Date Création</td>
                                                    <td>Date Livraison</td>
                                                    <th> Destinataire</th>
                                                    <th> Destination</th>
                                                    {{-- <th> Adresse </th> --}}
                                                    <th> Téléphone</th>
                                                    <th> Statut</th>
                                                    <th> Nature</th>
                                                    <th> Fond</th>
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
                                                            <label>
                                                                <input class="checkbox" value="{{ $record->id }}"
                                                                    type="checkbox" name='expeditions[]'>
                                                                <span></span>
                                                            </label>
                                                        </td>
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
                                                        <td></td>
                                                        <td> {{ $record->destinataire }} </td>
                                                        <td>
                                                            {{ $record->agenceDesDetail->libelle ?? '' }}
                                                        </td>



                                                        {{-- <td> {{ $record->adresse_destinataire }} </td> --}}
                                                        <td> {{ $record->telephone }} </td>
                                                        <td> <a href="#!"
                                                                onclick="historyOpen({{ $record->id }})">{{ $record->getEtape() }}
                                                            </a></td>
                                                        <td> {{ $record->retour_fond }} </td>
                                                        <td> {{ $record->fond }} </td>
                                                        {{-- <td class="hide-on-small-only"> {{ $record->port }} </td> --}}
                                                        {{-- <td class="hide-on-small-only"> {{ $record->ttc }} </td> --}}
                                                        <td> {{ $record->colis }} </td>
                                                        {{-- <td> {{ $record->vDeclaree }} </td> --}}
                                                        {{-- <td> {{ $record->paiementCheque }} </td> --}}
                                                        {{-- <td> {{ $record->ouvertureColis }} </td> --}}
                                                        <td>
                                                            @if (empty($record->id_bon))
                                                                <a
                                                                    href="{{ route('expedition_update', ['expedition' => $record->id]) }}"><i
                                                                        class="material-icons tooltipped"
                                                                        data-position="top"
                                                                        data-tooltip="Modifier">edit</i></a>
                                                                <a href="#!"
                                                                    onclick="openSuppModal({{ $record->id }})"><i
                                                                        class="material-icons tooltipped"
                                                                        style="color: #c10027;" data-position="top"
                                                                        data-tooltip="Annuler l'expédition">close</i></a>
                                                                <a href="{{ route('expedition_pdf', $record->id) }}"
                                                                    target="_blank"><i class="material-icons tooltipped"
                                                                        style="color: #1fc43a;" data-position="top"
                                                                        data-tooltip="Imprimer">print</i></a>
                                                            @else
                                                                <a href="#"><i class="material-icons tooltipped"
                                                                        data-position="top"
                                                                        data-tooltip="Vous avez pas le droit de faire une modification">edit</i></a>
                                                                <a href="#"><i class="material-icons tooltipped"
                                                                        style="color: #666666;" data-position="top"
                                                                        data-tooltip="Vous avez pas le droit de faire une suppression">close</i></a>
                                                                <a href="{{ route('expedition_pdf', $record->id) }}"
                                                                    target="_blank"><i class="material-icons tooltipped"
                                                                        style="color: #1fc43a;" data-position="top"
                                                                        data-tooltip="Imprimer">print</i></a>
                                                            @endif

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
            {{-- <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a href="{{route('bon_create')}}" class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i class="material-icons">add</i></a></div> --}}
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
                                    [2, "desc"]
                                ],
                                "pageLength": 50,
                                "language": {
                                    url: '/assets/vendors/data-tables/i18n/fr_fr.json'
                                }
                            });
                        };

                    },
                },
            }

            Vue.createApp(App).mount('#app');

            $("#checkall").change(function(e) {
                    $('.checkbox').prop("checked", $('#checkall').prop("checked"));
                });
        });
    </script>
@stop
