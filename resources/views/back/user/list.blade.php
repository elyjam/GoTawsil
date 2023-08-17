@extends($layout)
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/pages/page-users.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@stop
@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h2 class="flow-text white-text mt-0 mb-0 valign-wrapper"><i
                        class="material-icons mr-3">people_outline</i><span>Gestion des utilisateurs</span></h2>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('user_list')}}">Liste des utilisateurs</a>
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
                @if (\Session::has('success'))
                <div class="card-alert card gradient-45deg-green-teal">
                    <div class="card-content white-text">
                        <p>
                            <i class="material-icons">check</i> {!! \Session::get('success') !!}
                        </p>
                    </div>
                    <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                @endif
                <form id="form" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col s12 m4 input-field">
                            <select id="role" name="role" class="select select2 browser-default">
                                <option value='0'>Toutes les role</option>
                                <option {{ old('role') == '00' ? 'selected' : '' }} value='00'>Tous les employes</option>
                                @foreach ($roles as $row)
                                    <option class='option'
                                    {{ $row->id == old('role') ? 'selected' : '' }}
                                    value='{{ $row->id }}'> {{ $row->label }}</option>
                                @endforeach
                            </select>
                            <label for="role">Filtrer par Role</label>
                        </div>

                        <div class="col s12 m4 input-field">
                            <button type="button"
                            onclick="event.preventDefault();
                                                                $('#form').attr('action', '{{ route('user_list') }}'); document.getElementById('form').submit();"
                            class="btn btn-light" style="margin-right: 1rem;">
                            <i class="material-icons">search</i></button>
                        </div>
                    </div>

                    <!-- Dropdown Structure -->


                </form>
                <div class="list-table" id="app">

                    <div class="card">
                        <div class="card-content">
                            <!-- datatable start -->
                            <div class="responsive-table" id="responsive-table">
                                <table id="table" style="width: 100%" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Photo</th>
                                            <th>Nom</th>
                                            <th>name</th>
                                            <th>first_name</th>
                                            <th class="hide-on-small-only">Identifiant</th>
                                            <th class="hide-on-small-only">Role</th>
                                            <th class="hide-on-small-only">Date de création</th>
                                            <td></td>
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
            <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a
                    href="{{route('user_create')}}"
                    class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i
                        class="material-icons">add</i></a>
            </div>
        </div>
        <div class="content-overlay"></div>
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
<script src="/assets/js/scripts/page-users.js"></script>
<script>
function openSuppModal(id) {
    $("#delId").val(id);
    $('#delete_modal').modal('open');
}

function suppRecord() {
    window.location.replace("/user/delete/" + $("#delId").val());
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
                    [6, "desc"]
                ],
                "scrollX": true,
                "pageLength": 25,
                "oSearch": {
                    "sSearch": exp
                },
                processing: true,
                serverSide: true,

                "ajax": {
                    url: '/user/api',
                    "type": "GET",
                    "data": function(d) {
                        d.form = $("#form").serialize();
                    }
                },
                columns: [
                    {

                        data: 'photo',
                        name: 'users.photo'

                    },
                    {
                        data: 'nom',
                        name: 'nom'
                    },
                    {
                        data: 'name',
                        name: 'users.name',
                        'visible': false
                    },
                    {
                        data: 'first_name',
                        name: 'users.first_name',
                        'visible': false
                    },
                    {
                        data: 'login',
                        name: 'users.login'
                    },
                    {
                        data: 'role_label',
                        name: 'role_label'
                    },
                    {
                        data: 'created_at',
                        name: 'users.created_at'
                    },
                    {
                        data: 'action',
                        name: 'users.action',
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
