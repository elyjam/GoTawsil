@extends($layout)

@section('content')
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des employes</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('employe_list') }}">Liste des employes</a>
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
                    <form method="POST" action="{{ route('employe_create') }}">
                        @csrf
                        <br>
                        <div class="card">
                            <div class="card-panel">
                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="row">

                                            <div class="col s6 input-field">
                                                <input id="libelle" name="libelle" value="{{ old('libelle') }}"
                                                    autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                    type="text">
                                                <label for="libelle"> Nom & Prénom </label>
                                                @error('libelle')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col s6 input-field">
                                                <textarea d="adresse" name="adresse"  value="{{ old('adresse') }}" class="materialize-textarea"></textarea>
                                                <label for="adresse"> Adresse </label>
                                                @error('adresse')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col s6 input-field">
                                                <input id="telephone" name="telephone" value="{{ old('telephone') }}"
                                                    autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                    type="text">
                                                <label for="telephone"> Téléphone </label>
                                                @error('telephone')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col s6 input-field">
                                                <input id="email" name="email" value="{{ old('email') }}"
                                                    autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                    type="text">
                                                <label for="email"> Email </label>
                                                @error('email')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col s6 input-field">
                                                <select name='agence' id='agence' class="select2 browser-default">
                                                    <option value=''></option>
                                                    @foreach ($agenceRecords as $row)
                                                        <option class='option'
                                                            {{ $row->id == old('agence') ? 'selected' : '' }}
                                                            value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="agence"> Agence</label>
                                                @error('agence')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            {{-- <div class="col s6 input-field">
                                                <select name='fonction' id='fonction' class="select2 browser-default">
                                                    <option value=''></option>
                                                    @foreach ($fonctionRecords as $row)
                                                        <option class='option'
                                                            {{ $row->id == old('fonction') ? 'selected' : '' }}
                                                            value='{{ $row->id }}'> {{ $row->label }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="fonction"> Fonction</label>
                                                @error('fonction')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div> --}}

                                            <div class="col s6 input-field">
                                                <select name='type' id='type' class="select2 browser-default">
                                                    <option value=''></option>
                                                    @foreach ($typesemployeRecords as $row)
                                                        <option class='option'
                                                            {{ $row->code == old('type') ? 'selected' : '' }}
                                                            value='{{ $row->code }}'> {{ $row->label }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="type"> Type</label>
                                                @error('type')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="col s12 display-flex justify-content-end mt-3">
                                            <a href="{{ route('employe_list') }}"><button type="button"
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
@stop
