@extends($layout)
@section('content')
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <form method="POST" action="{{route('types_commentaire_update', ['types_commentaire' => $record->id])}}">
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
                                    <div class="row">
                                                        
                                        <div class="col s6 input-field">
                                            <input id="libelle" name="libelle" value="{{old('libelle', $record->libelle)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="libelle"> Commentaire </label>
                                            @error('libelle')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div> 
                                    <div class="col s6 input-field">
                                    <p><label><h6>statut :</h6></label></p><p>
                                <label><input class='with-gap' {{($record->statut == '1') ? 'checked' : ''}} name='statut' value='1' type='radio'><span>Actif</span></label> 
<label><input class='with-gap' {{($record->statut == '0') ? 'checked' : ''}} name='statut' value='0' type='radio'><span>Inactif</span></label> 
</p>
                                    @error('statut')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                    
                                    <div class="col s6 input-field">
                                    <select name='type' id='type' class="select2 browser-default">
                                    <option value=''></option>
                                <option value="CLIENT" {{($record->type == 'CLIENT') ? 'selected' : ''}}>  Commentaires annulation inscription client </option> 
<option value="LIVREUR" {{($record->type == 'LIVREUR') ? 'selected' : ''}}> Commentaire arrivage / Encaissement </option> 
</select> <label for="type"> Type</label>
                                    @error('type')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                    
                                    <div class="col s6 input-field">
                                    <select name='force_stock' id='force_stock' class="select2 browser-default">
                                    <option value=''></option>
                                <option value="oui" {{($record->force_stock == 'oui') ? 'selected' : ''}}> Force l'expédition vers le stock </option> 
<option value="non" {{($record->force_stock == 'non') ? 'selected' : ''}}> Commentaire normal  </option> 
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