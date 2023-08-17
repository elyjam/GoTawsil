@extends($layout)

@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des agences</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('agence_list')}}">Liste des agences</a>
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
                <form method="POST" action="{{route('agence_create')}}">
                    @csrf
                    <br>
                    <div class="card">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="row">
                                                        
                                        <div class="col s6 input-field">
                                            <input id="code" name="code" value="{{old('code')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="code"> code </label>
                                            @error('code')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    <div class="col s6 input-field">
                                    <select name='ville' id='ville' class="select2 browser-default">
                                    <option value=''></option>
                                    @foreach ($villeRecords as $row)
                                        <option class='option' {{($row->id == old('ville')) ? 'selected' : ''}}
                                        value='{{$row->id}}'> {{$row->libelle}}</option>
                                    @endforeach
                                </select> 
                                    <label for="ville"> ville</label>
                                    @error('ville')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                                    
                                        <div class="col s6 input-field">
                                            <input id="adresse" name="adresse" value="{{old('adresse')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="adresse"> Adresse </label>
                                            @error('adresse')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="telephone" name="telephone" value="{{old('telephone')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="telephone"> Téléphone </label>
                                            @error('telephone')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="email" name="email" value="{{old('email')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="email"> Email </label>
                                            @error('email')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div> 
                                    <div class="col s6 input-field">
                                    <p><label><h6>Visible Site :</h6></label></p><p>
                                <label><input class='with-gap' name='visible_site' value='oui' type='radio'><span>oui</span></label> 
<label><input class='with-gap' name='visible_site' value='non' type='radio'><span>non</span></label> 
</p>
                                    @error('visible_site')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                     
                                    <div class="col s6 input-field">
                                    <p><label><h6>Statut  :</h6></label></p><p>
                                <label><input class='with-gap' name='statut' value='1' type='radio'><span>Actif</span></label> 
<label><input class='with-gap' name='statut' value='0' type='radio'><span>Inactif</span></label> 
</p>
                                    @error('statut')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                                    
                                        <div class="col s6 input-field">
                                            <input id="Libelle" name="Libelle" value="{{old('Libelle')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="Libelle"> Libellé </label>
                                            @error('Libelle')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('agence_list')}}"><button type="button"
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