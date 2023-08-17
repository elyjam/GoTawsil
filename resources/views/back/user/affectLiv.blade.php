@extends($layout)

@section('content')

<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <form method="POST" action="{{route('user_update', ['user' => $user->id])}}">
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
                                            <option class="option" value="1"
                                                {{('1' == old('type',$user->type)) ? 'selected' : ''}}>Employé
                                            </option>
                                            <option class="option" value="2"
                                                {{('2' == old('type',$user->type)) ? 'selected' : ''}}>Client
                                            </option>
                                        </select>
                                        <label>Type</label>
                                        @error('type')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col s6 input-field" id="employe_row"
                                        {{ ( '2' == old('type',$user->type) ) ? 'style=display:none' :  '' }}
                                        style="display: block;">
                                        <select style="width: 100%;" name="employe" id="employe"
                                            class="select2 browser-default">
                                            <option value=""></option>
                                            @foreach ($employes as $employe)
                                            <option class="option"
                                                {{($employe->id == old('employe',$user->employe)) ? 'selected' : ''}}
                                                value="{{$employe->id}}"> {{$employe->libelle}}</option>
                                            @endforeach
                                        </select>
                                        <label>Employé</label>
                                        @error('employe')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col s6 input-field" id="client_row"
                                        {{ ( '1' == old('type',$user->type) ) ? 'style=display:none' :  '' }}>
                                        <select style="width: 100%;" name="client" id="client"
                                            class="select2 browser-default">
                                            <option value=""></option>
                                            @foreach ($clients as $client)
                                            <option class="option"
                                                {{($client->id == old('client' ,$user->client)) ? 'selected' : ''}}
                                                value="{{$client->id}}"> {{$client->libelle}}</option>
                                            @endforeach
                                        </select>
                                        <label>Client</label>
                                        @error('client')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>


                                <div class="col s12 m12">
                                    <div class="col s12 m12s">
                                        <div class="col s6 input-field">
                                            <input id="first_name" name="first_name" type="text"
                                                value="{{old('first_name',$user->first_name)}}">

                                            <label for="first_name"> Prénom</label>
                                            @error('first_name')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col s6 input-field">
                                            <input id="name" value="{{old('name', $user->name)}}" name="name"
                                                type="text">
                                            <label for="name">Nom </label>
                                            @error('last_name')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col s12 m12">
                                        <div class="row">
                                            <div class="col s6 input-field">
                                                <input id="login" name="login" value="{{old('login',$user->login)}}"
                                                    autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly');" type="text">
                                                <label for="login">Identifiant </label>
                                                @error('login')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col s6 input-field">
                                                <select style="width: 100%;" name="role" id='role'
                                                    class="select2 browser-default">
                                                    @foreach ($roles as $role)
                                                    <option class="option"
                                                        {{($role->id == old('role', $user->role)) ? 'selected' : ''}}
                                                        value="{{$role->id}}">{{$role->label}}</option>
                                                    @endforeach
                                                </select>
                                                <label>Role</label>
                                                @error('role')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col s12 m12">
                                        <div class="row">
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
                                                    onfocus="this.removeAttribute('readonly');"
                                                    name="password_confirmation" type="password" class="validate">
                                                <label for="password_confirmation"> Confirmation mot de passe</label>
                                                @error('password_confirmation')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col s12 input-field" id="autorisations">
                                        <select multiple="multiple" size="10" name="autorisations[]">
                                            @foreach ($droits as $droit)
                                            <option
                                                {{ in_array($droit->id, $user->droits()->allRelatedIds()->toArray()) ? "selected" : '' }}
                                                value="{{ $droit->id }}">{{ $droit->label }}</option>
                                            @endforeach
                                        </select>
                                        <label for="label" style="font-size: 1rem;"> Liste des autorisations :
                                        </label>
                                    </div>


                                </div>


                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="col s12 display-flex justify-content-end mt-3">
                                            <a href="{{route('user_list')}}"><button type="button"
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
#autorisations .select-dropdown {
    display: none !important;
}

.info {
    display: none !important;
}
</style>
<script>
$(document).ready(function() {
    $('select[name="autorisations[]"]').bootstrapDualListbox();

    $("#type").change(function(e) {
        if ($(this).val() === '1') {
            $('#client_row').css('display', 'none');
            $('#employe_row').css('display', 'block');
        } else {
            $('#client_row').css('display', 'block');
            $('#employe_row').css('display', 'none');
        }
    });

});
</script>
@stop