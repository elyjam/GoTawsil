@extends($layout)
@section('content')

    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des bons de ramassages </span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
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
                    @if (session()->has('validate'))
                        <div class="card-alert card green">
                            <div class="card-content white-text">
                                <p> {{ session()->get('validate') }}</p>
                            </div>
                            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    @endif
                    <br>
                    <form action="{{ route('insert_bon', $records->id) }}" method="post">
                        @csrf

                        {{ Form::submit('Modifier Frais', ['class' => 'btn btn-info', 'name' => 'submitbutton']) }}
                        {{ Form::submit('Réception colis', ['class' => 'btn btn-success', 'name' => 'submitbutton']) }}

                        {{-- <button type="submit" name="send"  class="btn btn-light">Modifier Frais</button>

                    <button type="submit" name="recept"  class="btn btn-light">Réception colis </button> --}}

                        {{-- <div class="list-table" id="app">
                    <div class="card">
                        <div class="card-content">
                            <!-- datatable start -->
                            <div class="responsive-table">
                                <table id="list-datatable" class="table table-bordered table-striped">
                                    <thead>

                                        <tr>
                                            <th> <label>
                                                <input type="checkbox" class="select-all" />
                                                <span></span>
                                            </label></th>
                                            <th>N° Expéd</th>
                                            <th>Saisie le</th>
                                            <th>Destinataire</th>
                                            <th>Destinatation</th>
                                            <th>N° Téléphone</th>
                                            <th>Adresse</th>
                                            <th>Type</th>
                                            <th>Colis</th>
                                            <th>Fond</th>
                                            <th>Frais Trs</th>



                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records->expeditionDetail as $record)
                                        <tr>
                                            <td>  <label>
                                                <input type="checkbox" />
                                                <span></span>
                                            </label></td>
                                            <td> {{ $record->num_expedition }} </td>
                                            <td> {{ $record->created_at }} </td>
                                            <td> {{ $record->destinataire }}</td>
                                            <td> {{ $record->agenceDetail->Libelle}}</td>
                                            <td> {{ $record->telephone }}</td>
                                            <td> {{ $record->adresse_destinataire }}</td>

                                            <td>  @if ($record->type == 'CDP')
                                                <i class="blue-text material-icons"
                                                   title="Document administratif">email</i>
                                            @elseif($record->type == 'ECOM')

                                                <i class="red-text material-icons" title="Colis e-commerce">inbox</i>
                                            @endif </td>
                                            <td> {{$record->colis}}</td>
                                            <td>{{$record->fond}}</td>
                                                <td>

                                             <input type="text" value="{{$record->ttc}}" name="updateFields[{{$record->id}}][ttc]"/> </td>




                                        </tr>
                                        @endforeach

                                </table>
                            </div>
                        </form>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div> --}}

                        <div class="card">
                            <div class="card-content">
                                <h4 class="card-title">Bon N° : {{ $records->code }}
                                </h4>
                                <div class="row">
                                    <div class="col s12">
                                        <table id="multi-select" class="display">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <label>
                                                            <input type="checkbox" class="select-all" name="selectall" />
                                                            <span></span>
                                                        </label>
                                                    </th>
                                                    <th>N° Expéd</th>
                                                    <th>Saisie le</th>
                                                    <th>Destinataire</th>
                                                    <th>Destinatation</th>
                                                    <th>N° Téléphone</th>
                                                    <th>Adresse</th>
                                                    <th>Type</th>
                                                    <th>Colis</th>
                                                    <th>Fond</th>
                                                    <th>Frais Trs</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($records->expeditionDetail as $record)
                                                @if(empty($record->processusDetail->where('code','RAMASSAGE')->first()->date_validation) )

                                                    <tr>
                                                        <td> <label>

                                                                <input type="checkbox"
                                                                    name="updateFields[{{ $record->id }}][check]" />

                                                                <span>
                                                                    @if ($record->etape == '1')
                                                                        <i class="blue-text material-icons"
                                                                            title="Document administratif"
                                                                            style="color: rgb(213 14 14) !important;">priority_high</i>
                                                                    @else
                                                                        <i class="blue-text material-icons"
                                                                            title="Document administratif"
                                                                            style="color: rgb(81 221 71) !important;">check_circle</i>
                                                                    @endif
                                                                </span>
                                                            </label></td>
                                                        <td> {{ $record->num_expedition }} </td>
                                                        <td> {{ $record->created_at }} </td>
                                                        <td> {{ $record->destinataire }}</td>
                                                        <td> {{ $record->agenceDetail->Libelle }}</td>
                                                        <td> {{ $record->telephone }}</td>
                                                        <td> {{ $record->adresse_destinataire }}</td>

                                                        <td>
                                                            @if ($record->type == 'CDP')
                                                                <i class="blue-text material-icons"
                                                                    title="Document administratif">email</i>
                                                            @elseif($record->type == 'ECOM')
                                                                <i class="red-text material-icons"
                                                                    title="Colis e-commerce">inbox</i>
                                                            @endif
                                                        </td>
                                                        <td> {{ $record->colis }}</td>
                                                        <td>{{ $record->fond }}</td>
                                                        <td>

                                                            <input type="text" value="{{ $record->ttc }}"
                                                                name="updateFields[{{ $record->id }}][ttc]" />
                                                        </td>




                                                    </tr>
                                                    @endif
                                                @endforeach

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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


    <script src="/assets/js/scripts/data-tables.js"></script>


    <script>
        function suppRecord() {
            window.location.replace("/bon/delete/" + $("#delId").val());
        }
        $(document).ready(function() {
            $('.modal').modal();
        });
    </script>


@stop
