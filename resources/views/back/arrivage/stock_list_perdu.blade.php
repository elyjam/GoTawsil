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
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Stock Perdu</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('stock_list') }}">Liste des stocks</a>
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
                                <div class="responsive-table">
                                    <table id="list-datatable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Expédition</th>
                                                {{-- <th>Saisie le</th> --}}
                                                {{-- <th>Date Ram</th> --}}
                                                <th>Expéditeur</th>
                                                {{-- <th>Origine</th> --}}
                                                <th>Destinataire</th>
                                                {{-- <th>Destination</th> --}}
                                                {{-- <th>Téléphone</th> --}}
                                                <th>Sens</th>
                                                <th>Nature</th>
                                                <th>Fond</th>
                                                <th>Port</th>
                                                <th>TTC</th>
                                                {{-- <th>Retour</th> --}}
                                                {{-- <th>Transfert</th> --}}
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($records as $record)
                                                <tr>
                                                    <td>
                                                        <span class="badge  gradient-45deg-blue-grey-blue">
                                                            {{ $record->num_expedition }}
                                                        </span>
                                                        <p>
                                                            <strong> Saisie le :</strong>
                                                            {{ $record->created_at }}
                                                        </p>
                                                        <p>
                                                            <strong>Ramasser le :</strong>
                                                            {{ empty($record->bonRamassageDetail->date_validation) ? '' : $record->bonRamassageDetail->date_validation }}
                                                        </p>

                                                    </td>
                                                    {{-- <td>{{$record->created_at}}</td> --}}
                                                    {{-- <td>{{$record->bonRamassageDetail->date_validation}}</td> --}}
                                                    <td>{{ $record->clientDetail->libelle ?? '' }}

                                                        <p>
                                                            <span
                                                                class="badge grey">{{ $record->agenceDetail->libelle ?? '' }}</span>
                                                        </p>
                                                    </td>
                                                    {{-- <td>{{$record->agenceDetail->Libelle ?? ""}}</td> --}}
                                                    <td>{{ $record->destinataire }}
                                                        <p>
                                                            <span
                                                                class="badge grey">{{ $record->agenceDesDetail->libelle ?? '' }}</span>
                                                        </p>
                                                        <p>
                                                            {{ $record->telephone }}
                                                        </p>
                                                    </td>
                                                    {{-- <td>{{$record->agenceDesDetail->Libelle ?? ""}}</td> --}}
                                                    {{-- <td>{{$record->telephone}}</td> --}}
                                                    <td>{{ $record->sens }}</td>
                                                    <td>{{ $record->retour_fond == 'CR' ? 'C. espèce' : 'Simple' }}</td>
                                                    <td>{{ $record->fond }}</td>
                                                    <td>{{ $record->port }}</td>
                                                    <td>{{ $record->ttc }}</td>
                                                    {{-- <td> <a  href="#!" onclick="openRetourModal({{ $record->id }})"><i
                                                class="material-icons" title="Retour">assignment_return</i></a> </td> --}}
                                                    {{-- <td><i class="material-icons" title="Transfert">sync</i></td> --}}
                                                    <td>
                                                        <a href="#!" onclick="openRetrouverModal({{ $record->id }})"><i
                                                                class="material-icons green-text"
                                                                title="Marquer comme trouvé">done</i></a>
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


    <div id="transfert_modal" class="modal">
        <div class="modal-content">
            <h4> Confirmation retrouvement</h4>
            <div>
                Êtes-vous sûr de vouloir retrouver?
            </div>
            <input type="hidden" name="retrouverid" id="retrouverid">
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
            <a href="#!" class="waves-effect waves-green btn red" onclick="retrouver()">Retrouver l'expédition</a>
        </div>
    </div>


@stop
@section('js')
    <script>
        function openRetrouverModal(id) {
            $("#retrouverid").val(id);
            $('#transfert_modal').modal('open');
        }


        function retrouver() {
            window.location.replace("/stock/retrouver/" + $("#retrouverid").val());
        }
        $(document).ready(function() {
            $('.modal').modal();
        });
    </script>
@stop
