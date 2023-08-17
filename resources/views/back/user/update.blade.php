@extends($layout)

@section('content')

    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">

                    <form method="POST" action="{{ route('user_update', ['user' => $user->id]) }}">
                        @csrf
                        <br>

                        @if (Session::has('success'))
                            <div class="card-alert card green">
                                <div class="card-content white-text">
                                    <p>{{ Session::get('success') }} </p>
                                </div>
                                <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-panel">

                                <div class="row">


                                    <div class="col s12 m12">
                                        <div class="col s6 input-field">
                                            <select style="width: 100%;" name="type" id="type"
                                                class="select2 browser-default">
                                                {{-- <option class="option" value="1"
                                                    {{ '1' == old('type', $user->type) ? 'selected' : '' }}>Employé
                                                </option>
                                                <option class="option" value="2"
                                                    {{ '2' == old('type', $user->type) ? 'selected' : '' }}>Client
                                                </option> --}}
                                                @if ($user->role=="3")
                                                <option class="option" value="2" selected>Client</option>
                                                <option class="option" value="1">Employé</option>
                                                @else
                                                <option class="option" value="1" selected>Employé</option>
                                                <option class="option" value="2">Client</option>
                                                @endif
                                            </select>
                                            <label>Type</label>
                                            @error('type')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @if($user->role != '3')
                                        <div class="col s6 input-field" id="employe_row"
                                            style="display: block;">
                                            <select style="width: 100%;" name="employe" id="employe"
                                                class="select2 browser-default">
                                                <option value="{{$user->employe}}">{{$user->EmployeDetail->libelle}}</option>
                                                @foreach ($employes as $employe)
                                                    <option class="option"
                                                        value="{{ $employe->id }}"> {{ $employe->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label>Employé</label>
                                            @error('employe')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @else
                                        <div class="col s6 input-field" id="client_row">
                                            <select style="width: 100%;" name="client" id="client"
                                                class="select2 browser-default">
                                                <option value=""></option>
                                                @foreach ($clients as $client)
                                                    <option class="option"
                                                        {{ $client->id == old('client', $user->client) ? 'selected' : '' }}
                                                        value="{{ $client->id }}"> {{ $client->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label>Client</label>
                                            @error('client')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @endif
                                    </div>


                                    {{-- <div class="col s12 m12">

                                        <div class="col s6 input-field">
                                            <input id="first_name" name="first_name" type="text"
                                                value="{{ old('first_name', $user->first_name) }}">

                                            <label for="first_name">Prénom</label>
                                            @error('first_name')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col s6 input-field">
                                            <input id="name" value="{{ old('name', $user->name) }}" name="name"
                                                type="text">
                                            <label for="name">Nom </label>
                                            @error('name')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div> --}}


                                    <div class="col s12 m12">

                                        <div class="col s6 input-field">
                                            <input id="login" name="login" value="{{ old('login', $user->login) }}"
                                                autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                type="text">
                                            <label for="login">Identifiant </label>
                                            @error('login')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col s6 input-field" id='div_role'>
                                            <select style="width: 100%;" name="role" id='role'
                                                class="select2 browser-default">
                                                @foreach ($roles as $role)
                                                    <option class="option"
                                                        {{ $role->id == old('role', $user->role) ? 'selected' : '' }}
                                                        value="{{ $role->id }}">{{ $role->label }}</option>
                                                @endforeach
                                            </select>
                                            <label>Role</label>
                                            @error('role')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>



                                    <div class="col s12 m12">

                                        <div class="col s6 input-field">
                                            <input id="password" name="password" readonly
                                                onfocus="this.removeAttribute('readonly');" autocomplete="off"
                                                type="password">
                                            <label for="password"> Mot de passe</label>
                                            @error('password')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col s6 input-field">
                                            <input id="password_confirmation" readonly
                                                onfocus="this.removeAttribute('readonly');" name="password_confirmation"
                                                type="password" class="validate">
                                            <label for="password_confirmation"> Confirmation mot de passe</label>
                                            @error('password_confirmation')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>












                                    @if ($record->id != '3')
                                    <div class="row">
                                        <div class="col s12">
                                            <ul class="tabs">

                                                <li class="tab col s3"><a class="active" href="#test1">Ajouter des fonctionalites</a></li>
                                                <li class="tab col s3"><a href="#test2">Villes affecter</a></li>
                                                <li class="tab col s3"><a href="#test3">Regions affecter</a></li>
                                                @if ($user->role == '8')
                                                <li class="tab col s3"><a href="#test4">Villes affecter *Pour charger
                                                    de compte:</a></li>
                                                @else
                                                <li class="tab col s3 disabled"><a href="#test4">Villes affecter *Pour charger
                                                    de compte:</a></li>
                                                @endif

                                            </ul>
                                        </div>

                                        <div id="test1" class="col s12">
                                            <div class="col s12 input-field">
                                                <select multiple="multiple" size="10" name="fonctionnalites[]">
                                                    @foreach ($fonctionnalites as $fonctionnalite)
                                                        <option {{ in_array($fonctionnalite->id, $mes_fonc) ? 'selected' : '' }}
                                                            value="{{ $fonctionnalite->id }}">
                                                            {{ $fonctionnalite->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="label" style="font-size: 1rem;"> Ajouter des
                                                    fonctionnalités :
                                                </label>
                                            </div>
                                        </div>

                                        <div id="test2" class="col s12">
                                            <div class="col s12 input-field" id="villes">
                                                <select multiple="multiple" size="10" name="villes[]">
                                                    @foreach ($villes as $record)
                                                        <option value="{{ $record->id }}"
                                                            {{ in_array($record->id,$user->relatedVilles()->allRelatedIds()->toArray())? 'selected': '' }}>
                                                            {{ $record->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="label" style="font-size: 1rem;"> Liste des villes :
                                                </label>
                                            </div>
                                        </div>
                                        <div id="test3" class="col s12">
                                            <div class="col s12 input-field" id="regions">
                                                <select multiple="multiple" size="10" name="regions[]">
                                                    @foreach ($regions as $record)
                                                        <option value="{{ $record->id }}"
                                                            {{ in_array($record->id,$user->relatedRegions()->allRelatedIds()->toArray())? 'selected': '' }}>
                                                            {{ $record->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="regions" style="font-size: 1rem;"> Liste des regions :
                                                </label>
                                            </div>
                                        </div>
                                        @if ($user->role == '8')
                                        <div id="test4" class="col s12">
                                            <div class="col s12 input-field" id="villes">
                                                <select multiple="multiple" size="10" name="villes_charger[]">
                                                    @foreach ($villes as $record)
                                                        <option value="{{ $record->id }}"
                                                            {{ in_array($record->id,$user->relatedVillesCharger()->allRelatedIds()->toArray())? 'selected': '' }}>
                                                            {{ $record->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="label" style="font-size: 1rem;"> Liste des villes *Pour charger
                                                    de compte:
                                                </label>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endif

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
                    </form>

                </div>
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


        .tabs .tab a:hover, .tabs .tab a.active {
    color: #29b6f6;
    background-color: rgba(114, 207, 249, .2)!important;
}
        .tabs{
            margin-block: 30px
        }
        #villes .select-dropdown,
        #regions .select-dropdown {
            display: none !important;
        }

        .info {
            display: none !important;
        }

        .select-dropdown {
            display: none !important;
        }

        .info {
            display: none !important;
        }

        .info-container {
            display: none !important;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('tabs').tabs();
        })
        $(document).ready(function() {
            $('select[name="fonctionnalites[]"]').bootstrapDualListbox();
        });
        if ($('#type').val() === '1') {
            $('#client_row').css('display', 'none');
            $('#employe_row').css('display', 'block');
            $('#villes').show();
            $('#role').prop('disabled', false);
        } else {
            $('#client_row').css('display', 'block');
            $('#employe_row').css('display', 'none');
            $('#villes').hide()
            $("#role").select2("val", "3");
            $('#role').prop('disabled', 'disabled');
        }
        $(document).ready(function() {
            $('select[name="villes[]"]').bootstrapDualListbox();
            $('select[name="regions[]"]').bootstrapDualListbox();
            $('select[name="villes_charger[]"]').bootstrapDualListbox();

            $("#type").change(function(e) {
                if ($(this).val() === '1') {
                    $('#client_row').css('display', 'none');
                    $('#employe_row').css('display', 'block');
                    $('#autorisations').show();
                    $('#role').prop('disabled', false);
                    $("#role").select2("val", "1");
                } else {
                    $('#client_row').css('display', 'block');
                    $('#employe_row').css('display', 'none');
                    $('#autorisations').hide();
                    $("#role").select2("val", "3");
                    $('#role').prop('disabled', 'disabled');

                }
            });


        });
    </script>
@stop
