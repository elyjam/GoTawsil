@extends($layout)
@section('content')
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des reclamations</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('reclamation_list') }}">Liste des reclamations</a>
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
                                        <div class="col s12 m2 input-field">
                                            <select name='client' id='client' class="select2 browser-default">
                                                <option value='0'>Tous</option>s
                                                @foreach ($clientRecords as $row)

                                                    <option class='option'
                                                        {{ $row->id == old('client') ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="client">Client</label>
                                            @error('client')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s12 m2 input-field">
                                            <select id="Statut" name="Statut" placeholder=""
                                                class="select2 browser-default">
                                                <option value='0'>Tous</option>
                                                @foreach ($statuts as $statut)
                                                <option  {{ $statut->key == old('Statut') ? 'selected' : '' }} value='{{$statut->key}}'>{{$statut->value}}</option>
                                                @endforeach

                                            </select>
                                            <label for="Statut">Statut</label>
                                        </div>

                                        <div class="col s12 m2 input-field">
                                            <select id="type" name="type" placeholder=""
                                                class="select2 browser-default">
                                                <option value='0'>Tous</option>
                                               @foreach ($typeReclamation as $type )
                                               <option     {{ $type->id == old('type') ? 'selected' : '' }} value='{{$type->id}}'>{{$type->libelle}}</option>
                                               @endforeach
                                            </select>
                                            <label for="type">Type</label>
                                        </div>
                                        {{-- <div class="col s12 m2 input-field"> --}}
                                        {{-- <input id="NumReclamation" value="{{old('NumReclamation')}}" name="NumReclamation" --}}
                                        {{-- type="text"> --}}
                                        {{-- <label for="NumReclamation"> N° Réclamation</label> --}}
                                        {{-- </div> --}}

                                        <div class="col s12 m2 input-field">
                                            <input id="start_date" value="{{ old('start_date',$star_date) }}" name="start_date"
                                                type="text" placeholder="" class="datepicker">
                                            <label for="start_date">Du </label>
                                        </div>
                                        <div class="col s12 m2 input-field">
                                            <input id="end_date" value="{{ old('end_date',$end_date) }}" name="end_date" type="text"
                                                placeholder="" class="datepicker">
                                            <label for="end_date">Au </label>
                                        </div>
                                        <div class="col s12 m4 input-field">
                                            <button type="button"
                                                onclick="event.preventDefault();
                                                            $('#form').attr('action', '{{ route('reclamation_list') }}'); document.getElementById('form').submit();"
                                                class="btn btn-light" style="margin-right: 1rem;">
                                                <i class="material-icons">search</i></button>
                                            <button type="button"
                                                onclick="event.preventDefault();
                                                            $('#form').attr('action', '{{ route('reclamation_export') }}'); document.getElementById('form').submit();"
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
                                <div class="responsive-table">
                                    <table id="list-datatable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>N° Réclamation</th>
                                                <th> Type reclamation</th>
                                                <th> Client</th>
                                                <th> Description</th>
                                                <th> Statut</th>
                                                <th> Cloture par</th>
                                                <th> Date Cloture</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($records as $record)
                                                <tr>
                                                    <td> {{ $record->code }} </td>
                                                    <td> {{ $record->typereclamationDetail->libelle }} </td>
                                                    <td> {{ $record->userDetail->clientDetail->libelle }} </td>

                                                    <td width="400px"> {{ $record->description }} </td>

                                                    <td>
                                                        @if ($record->statut == 1)
                                                            <span class="badge green">
                                                            @elseif($record->statut == 2)
                                                                <span class="badge orange">
                                                                @elseif($record->statut == 3)
                                                                    <span class="badge red">
                                                        @endif


                                                        {{ $record->getStatut() }}</span>
                                                    </td>

                                                    <td> {{ @$record->ClotureeParDetail->libelle }} </td>
                                                    <td> {{ $record->cloture_at }} </td>
                                                    <td>
                                                        <a
                                                            href="{{ route('reclamation_detail', ['reclamation' => $record->id]) }}"><i
                                                                class="material-icons tooltipped" data-position="top"
                                                                data-tooltip="Details">forum</i></a>
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
            {{-- <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a href="{{route('reclamation_create')}}" class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i class="material-icons">add</i></a></div> --}}

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
            window.location.replace("/reclamation/delete/" + $("#delId").val());
        }

        $(document).ready(function() {
            $('.modal').modal();
        });
        // document.getElementById("form").style.display = "none";

        // function advancedsearch() {
        //     var x = document.getElementById("form");
        //     if (x.style.display === "none") {
        //         x.style.display = "block";
        //     } else {
        //         x.style.display = "none";
        //     }
        // }

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
                                    [2, "ASC"]
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
