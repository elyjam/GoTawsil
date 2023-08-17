@extends($layout)
<style>
    h4.header {
        line-height: 1.9rem!important;
    margin:  0px!important;


}
</style>
@section('content')

    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des utilisateurs</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('user_list') }}">Liste des utilisateurs</a>
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

                    <form method="POST" action="{{ route('user_create') }}">
                        @csrf

                        <br>

                        <div class="card">
                            <div class="card-panel">
                                <div class="row" style="padding-bottom:20px;">
                                    <div class="col s12 m12">
                                        <h4 class="header">
                                            <span style="color: #3f51b5 ;  font-weight: 900;">
                                            Username :
                                            </span>

                                        <input hidden type="text" id="username" name="username" value="{{$username}}">
                                            {{$username}}

                                        </h4>
                                        <h4 class="header">
                                            <span style="color: #3f51b5 ;  font-weight: 900;">
                                                Password :
                                            </span>
                                            <input hidden type="text" id="password" name="password"  value="{{$password}}">
                                            {{$password}}
                                            </h4>
                                     </div>
                                </div>
                                <div class="row">

                                    <div class="col s12 m12">
                                        <div class="row">
                                            <div class="col s6 input-field">
                                                <select style="width: 100%;" name="type" id="type"
                                                    class="select2 browser-default">
                                                    <option class="option" value="1"
                                                        {{ '1' == old('type') ? 'selected' : '' }}>Employé
                                                    </option>
                                                    <option class="option" value="2"
                                                        {{ '2' == old('type') ? 'selected' : '' }}>Client </option>
                                                </select>
                                                <label>Type</label>
                                                @error('type')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col s6 input-field" id="employe_row"
                                                {{ '2' === old('type') ? 'style=display:none' : '' }}
                                                style="display: block;">
                                                <select style="width: 100%;" name="employe" id="employe"
                                                    class="select2 browser-default">
                                                    <option value=""></option>
                                                    @foreach ($employes as $employe)
                                                        <option class="option"
                                                            {{ $employe->id == old('employe') ? 'selected' : '' }}
                                                            value="{{ $employe->id }}"> {{ $employe->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                <label>Employé</label>
                                                @error('employe')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col s6 input-field" id="client_row"
                                                {{ '1' === old('type', '1') ? 'style=display:none' : '' }}>
                                                <select style="width: 100%;" name="client" id="client"
                                                    class="select2 browser-default">
                                                    <option value=""></option>
                                                    @foreach ($clients as $client)
                                                        <option class="option"
                                                            {{ $client->id == old('client') ? 'selected' : '' }}
                                                            value="{{ $client->id }}"> {{ $client->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                <label>Client</label>
                                                @error('client')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col s12 m12">
                                        <div class="row">
                                            <div class="col s6 input-field">
                                                <input id="first_name" name="first_name" type="text"
                                                    value="{{ old('first_name') }}">

                                                <label for="first_name">Prénom</label>
                                                @error('first_name')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col s6 input-field">
                                                <input id="name" value="{{ old('name') }}" name="name" type="text">
                                                <label for="name">Nom </label>
                                                @error('name')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="col s12 m12">
                                        <div class="row">
                                            <div class="col s6 input-field">
                                                <select style="width: 100%;" name="role" id="role"
                                                    class="select2 browser-default">
                                                    @foreach ($roles as $role)
                                                        <option class="option"
                                                            {{ $role->id == old('role') ? 'selected' : '' }}
                                                            value="{{ $role->id }}"> {{ $role->label }}</option>
                                                    @endforeach
                                                </select>
                                                <label>Role</label>
                                                @error('role')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>






                                </div>
                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="col s12 display-flex justify-content-end mt-3">


                                            <a href="{{ route('user_list') }}"><button type="button"
                                                    class="btn btn-light">Retour </button></a>
                                            <button type="submit" class="btn indigo" style="margin-left: 1rem;">
                                                Enregistrer</button>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    </div>
@stop

@section('js')
    <link rel="stylesheet" type="text/css" href="/assets/vendors/duallistbox/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendors/duallistbox/bootstrap-duallistbox.css">
    <script src="/assets/vendors/duallistbox/jquery.bootstrap-duallistbox.js"></script>

    <style>
        #autorisations .select-dropdown {
            display: none !important;
        }

        .info {
            display: none !important;
        }
    </style>
    <script>

        $(document).ready(function() {
            $("#type").change(function(e) {
                if ($(this).val() === '1') {
                    $('#client_row').css('display', 'none');
                    $('#employe_row').css('display', 'block');
                    $('#role').prop('disabled', false);
                    $("#role").select2("val", "1");

                } else {
                    $('#client_row').css('display', 'block');
                    $('#employe_row').css('display', 'none');
                    $("#role").select2("val", "3");
                    $('#role').prop('disabled', 'disabled');

                }
            });


        });
    </script>
@stop
