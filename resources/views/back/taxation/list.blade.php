@extends($layout)
@section('content')
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des taxations</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('taxation_list') }}">Liste des taxations</a>
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
                                            <select id="Client" name="Client" placeholder=""
                                                class="select select2 browser-default">
                                                <option value='0'></option>
                                                @foreach ($ClientRecords as $row)
                                                    <option class='option'
                                                        {{ $row->id == old('Client') ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="agence">Client</label>
                                        </div>
                                        <div class="col s12 m2 input-field">
                                            <select id="agence_depard" name="agence_depard" placeholder=""
                                                class="select2 browser-default">
                                                <option value='0'></option>
                                                @foreach ($AgenceRecords as $row)
                                                    <option class='option'
                                                        {{ $row->id == 1 ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->Libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="agence_des">Départ</label>
                                        </div>
                                        <div class="col s12 m2 input-field">
                                            <select id="Destination" name="Destination" placeholder=""
                                                class="select2 browser-default">
                                                <option value='0'>Toutes les villes</option>
                                                @foreach ($AgenceRecords as $row)
                                                <option class='option' {{($row->id == old('Destination')) ? 'selected' : ''}} value='{{$row->id}}'> {{$row->Libelle}}</option>
                                                        value='{{ $row->id }}'> {{ $row->Libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="agence_des">Destination</label>
                                        </div>


                                    </div>

                                    <div class="row">

                                        <div class="col s12 m3 input-field">
                                            <button type="button"
                                                onclick="event.preventDefault();
                                                            $('#form').attr('action', '{{ route('taxation_list') }}'); document.getElementById('form').submit();"
                                                class="btn btn-light" style="margin-right: 1rem;">
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

                                                {{-- <th> code </th> --}}
                                                <th> Ville Expedition </th>
                                                <th> Ville Dest </th>
                                                {{-- <th> Sens </th> --}}
                                                {{-- <th> Statut </th> --}}
                                                <th> Montant Min </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($records as $record)
                                                <tr>

                                                    {{-- <td> {{ $record->code }} </td> --}}
                                                    <td> {{ $record->villeDetailExp->libelle }} </td>
                                                    <td> {{ $record->villeDetailDest->libelle }} </td>
                                                    {{-- <td> {{ $record->sens }} </td> --}}
                                                    {{-- <td> {{ $record->statut }} </td> --}}
                                                    <td> {{ $record->mnt_min }} Dhs</td>
                                                    <td>
                                                        <a
                                                            href="{{ route('taxation_update', ['taxation' => $record->id]) }}"><i
                                                                class="material-icons tooltipped" data-position="top"
                                                                data-tooltip="Modifier">edit</i></a>
                                                        <a href="#!" onclick="openSuppModal({{ $record->id }})"><i
                                                                class="material-icons tooltipped" style="color: #c10027;"
                                                                data-position="top" data-tooltip="Supprimer">delete</i></a>
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
            <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a
                    href="{{ route('taxation_create', 0) }}"
                    class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i
                        class="material-icons">add</i></a></div>
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
            window.location.replace("/taxation/delete/" + $("#delId").val());
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
    </script>
@stop
