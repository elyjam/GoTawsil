@extends($layout)
@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des bordereaus</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('bordereau_list')}}">Liste des bordereaus</a>
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
                                        <input id="start_date" value="{{old('start_date')}}" name="start_date"
                                               type="text" placeholder="" class="datepicker">
                                        <label for="start_date">Du </label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <input id="end_date" value="{{old('end_date')}}" name="end_date"
                                               type="text" placeholder="" class="datepicker">
                                        <label for="end_date">Au </label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <input id="code" value="{{old('code')}}" name="n_colis"
                                               type="text" placeholder="">
                                        <label for="code">N° Expedition </label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <select id="comment" name="comment" placeholder=""
                                                class="select2 browser-default">
                                            <option value='0'>Tous</option>
                                            <option value='1'>En cours</option>
                                            <option value='2'>Remis</option>
                                        </select>
                                        <label for="comment">Comment</label>
                                    </div>
                                    <div class="col s12 m3 input-field">
                                        <button type="button"
                                                onclick="event.preventDefault();
                                                    $('#form').attr('action', '{{route('bordereau_list')}}'); document.getElementById('form').submit();"
                                                class="btn btn-light" style="margin-right: 1rem;">
                                            <i class="material-icons">search</i></button>
                                        <button type="button"
                                                onclick="event.preventDefault();
                                                    $('#form').attr('action', '{{route('bordereau_list')}}'); document.getElementById('form').submit();"
                                                class="btn btn-download"><i
                                                class="material-icons">file_download</i></button>
                                    </div>

                                </div>

                                <div class="row">

                                    {{--                                        <div class="col s12 m2 input-field">--}}
                                    {{--                                            <select id="Statut" name="Statut" placeholder=""--}}
                                    {{--                                                    class="select2 browser-default">--}}
                                    {{--                                                <option value=''></option>--}}
                                    {{--                                            </select>--}}
                                    {{--                                            <label for="Statut">Statut</label>--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="col s12 m2 input-field">--}}
                                    {{--                                            <select name='Expediteur' id='Expediteur'--}}
                                    {{--                                                    class="select2 browser-default">--}}
                                    {{--                                                <option value='0'></option>--}}
                                    {{--                                                <option value='2'>dsfq</option>--}}
                                    {{--                                            </select>--}}
                                    {{--                                            <label for="Expediteur"> Expéditeur</label>--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="col s12 m3 input-field">--}}
                                    {{--                                            <select id="comment" name="comment" placeholder=""--}}
                                    {{--                                                    class="select2 browser-default">--}}
                                    {{--                                                <option value='1'></option>--}}
                                    {{--                                                <option value='test'></option>--}}
                                    {{--                                            </select>--}}
                                    {{--                                            <label for="comment">Comment</label>--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="col s12 m3 input-field">--}}
                                    {{--                                            <button type="button"--}}
                                    {{--                                                    onclick="event.preventDefault();--}}
                                    {{--                                                        $('#form').attr('action', '{{route('expedition_list')}}'); document.getElementById('form').submit();"--}}
                                    {{--                                                    class="btn btn-light" style="margin-right: 1rem;">--}}
                                    {{--                                                <i class="material-icons">search</i></button>--}}
                                    {{--                                            <button type="button"--}}
                                    {{--                                                    onclick="event.preventDefault();--}}
                                    {{--                                                        $('#form').attr('action', '{{route('expedition_export')}}'); document.getElementById('form').submit();"--}}
                                    {{--                                                    class="btn btn-download"><i--}}
                                    {{--                                                    class="material-icons">file_download</i></button>--}}
                                    {{--                                        </div>--}}
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

                                            <th> Code </th>
                                            <th> Crée le </th>
                                            <th> Client </th>
                                            <th> Employé </th>
                                            <th> Généré par </th>
                                            <th> Remis le </th>
                                            <th> Détail </th>
                                            <th> Remise </th>
                                            <th> Editer </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                        <tr>

                                            <td> {{ $record->code }} </td>
                                            <td> {{ $record->crea_at }} </td>
                                            <td> {{ $record->client }} </td>
                                            <td> {{ $record->employe }} </td>
                                            <td> {{ $record->gen_by }} </td>
                                            <td> {{ $record->remise_le }} </td>
                                            <td> {{ $record->detail }} </td>
                                            <td> {{ $record->remise }} </td>
                                            <td> {{ $record->editer }} </td>
                                            <td>
                                                <a href="{{route('bordereau_update', ['bordereau'=>$record->id])}}"><i
                                                        class="material-icons tooltipped" data-position="top"
                                                        data-tooltip="Modifier">edit</i></a>
                                                <a  href="#!" onclick="openSuppModal({{$record->id}})"><i class="material-icons tooltipped" style="color: #c10027;" data-position="top"
                                            data-tooltip="Supprimer">delete</i></a>
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
        <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a href="{{route('bordereau_create')}}" class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i class="material-icons">add</i></a></div>
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
        window.location.replace("/bordereau/delete/"+$("#delId").val());
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
