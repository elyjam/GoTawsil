@extends($layout)

@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des types_commentaires</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('types_commentaire_list')}}">Liste des types_commentaires</a>
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
                <form method="POST" action="{{route('types_commentaire_create')}}">
                    @csrf
                    <br>
                    <div class="card">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="row">
                                                        
                                        <div class="col s6 input-field">
                                            <input id="libelle" name="libelle" value="{{old('libelle')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="libelle"> Commentaire </label>
                                            @error('libelle')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div> 
                                    <div class="col s6 input-field">
                                    <p><label><h6>statut :</h6></label></p><p>
                                <label><input class='with-gap' name='statut' value='1' type='radio'><span>Actif</span></label> 
<label><input class='with-gap' name='statut' value='0' type='radio'><span>Inactif</span></label> 
</p>
                                    @error('statut')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                    
                                    <div class="col s6 input-field">
                                    <select name='type' id='type' class="select2 browser-default">
                                    <option value=''></option>
                                <option value="CLIENT">  Commentaires annulation inscription client </option> 
<option value="LIVREUR"> Commentaire arrivage / Encaissement </option> 
</select> <label for="type"> Type</label>
                                    @error('type')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                    
                                    <div class="col s6 input-field">
                                    <select name='force_stock' id='force_stock' class="select2 browser-default">
                                    <option value=''></option>
                                <option value="oui"> Force l'expédition vers le stock </option> 
<option value="non"> Commentaire normal  </option> 
</select> <label for="force_stock"> Impact</label>
                                    @error('force_stock')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('types_commentaire_list')}}"><button type="button"
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