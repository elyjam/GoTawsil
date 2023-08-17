@extends($layout)

@section('content')

<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                @if(Session::has('success_message'))
                <div class="alert alert-success">
                    {{ Session::get('success_message') }}
                </div>
                @endif

                <form method="POST" action="{{route('user_profil')}}" enctype="multipart/form-data">
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

                                <div class="media col s12 m1">
                                    <img src="{{$user->photo ? '/uploads/photos/'.Auth::user()->photo : '/uploads/photos/default.png' }}"
                                        class="border-radius-4" alt="profile image" height="64" width="64" />
                                </div>

                                <div class="file-field input-field col s12 m5">
                                    <div class="btn">
                                        <span>Image</span>
                                        <input name="file" type="file" value="" autocomplete="off" readonly
                                            onfocus="this.removeAttribute('readonly');">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path" type="text">
                                    </div>
                                </div>


                            </div>

                            <div class="row">

                                <div class="col s12 m12">
                                    <div class="row">
                                        {{-- <div class="col s6 input-field">
                                            <input disabled id="code" name="code" type="text"
                                                value="{{ Auth::user()->EmployeDetail->code }}">
                                            <label for="code"> code</label>
                                            @error('code')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div> --}}

                                        <div class="col s6 input-field">
                                            <input disabled id="login" name="login" value="{{ Auth::user()->login }}"
                                                type="text">
                                            <label for="login"> Identifiant</label>
                                            @error('login')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="col s12 m12">
                                    <div class="row">
                                        <div class="col s6 input-field">
                                            <input  id="name" value="{{ Auth::user()->EmployeDetail->libelle }}" name="name"
                                                type="text">
                                            <label for="name">Nom</label>
                                            @error('name')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s6 input-field">
                                            <input id="email" value="{{ Auth::user()->EmployeDetail->email }}" name="name"
                                                type="text">
                                            <label for="email">email</label>
                                            @error('email')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="col s12 m12">
                                    <div class="row">
                                        <div class="col s6 input-field">
                                            <input id="telephone" value="{{ Auth::user()->EmployeDetail->telephone }}" name="telephone"
                                                type="text">
                                            <label for="telephone">Téléphone</label>
                                            @error('telephone')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s6 input-field">
                                            <input id="adresse" value="{{ Auth::user()->EmployeDetail->adresse }}" name="adresse"
                                                type="text">
                                            <label for="adresse">Adresse</label>
                                            @error('adresse')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                                <div class="col s12 m12">
                                    <div class="row">
                                        <div class="col s6 input-field">
                                            <input disabled id="ville" value="{{ Auth::user()->EmployeDetail->agenceDetail->libelle }}" name="ville"
                                                type="text">
                                            <label for="ville">Ville</label>
                                            @error('ville')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col s6 input-field">
                                            <input id="rib" value="{{ Auth::user()->EmployeDetail->rib }}" name="rib"
                                                type="text">
                                            <label for="rib">RIB</label>
                                            @error('rib')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="col s12 m12">
                                    <div class="row">
                                        <div class="col s6 input-field">
                                            <input id="ice_org" value="{{ Auth::user()->EmployeDetail->ice_org }}" name="ice_org"
                                                type="text">
                                            <label for="ice_org">ICE</label>
                                            @error('ice_org')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s6 input-field">
                                            <input  id="rc_org" value="{{ Auth::user()->EmployeDetail->rc_org }}" name="rc_org"
                                                type="text">
                                            <label for="rc_org">RC</label>
                                            @error('rc_org')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>


                                <div class="col s12 m12">
                                    <div class="row">
                                        <div class="col s6 input-field">
                                            <input id="password" name="password" value="" autocomplete="off" readonly
                                                onfocus="this.removeAttribute('readonly');" type="password">
                                            <label for="password"> Mot de passe</label>
                                            @error('password')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s6 input-field">
                                            <input id="password_confirmation" name="password_confirmation"
                                                type="password" class="validate" value="" autocomplete="off" readonly
                                                onfocus="this.removeAttribute('readonly');">
                                            <label for="password_confirmation">Confirmation mot de passe</label>
                                            @error('password_confirmation')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <button type="submit" class="btn indigo">
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

@stop
