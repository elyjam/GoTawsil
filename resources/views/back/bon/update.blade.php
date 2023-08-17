@extends($layout)
@section('content')
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <form method="POST" action="{{route('bon_update', ['bon' => $record->id])}}">
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
                                            <input id="code" name="code" value="{{old('code', $record->code)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="code"> code </label>
                                            @error('code')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="date_cloture" name="date_cloture" value="{{old('date_cloture', $record->date_cloture)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="date_cloture">  </label>
                                            @error('date_cloture')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="date_validation" name="date_validation" value="{{old('date_validation', $record->date_validation)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="date_validation">  </label>
                                            @error('date_validation')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="id_organisme" name="id_organisme" value="{{old('id_organisme', $record->id_organisme)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="id_organisme">  </label>
                                            @error('id_organisme')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="statut" name="statut" value="{{old('statut', $record->statut)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="statut">  </label>
                                            @error('statut')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="type" name="type" value="{{old('type', $record->type)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="type">  </label>
                                            @error('type')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="id_transporteur" name="id_transporteur" value="{{old('id_transporteur', $record->id_transporteur)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="id_transporteur">  </label>
                                            @error('id_transporteur')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="id_agence_dest" name="id_agence_dest" value="{{old('id_agence_dest', $record->id_agence_dest)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="id_agence_dest">  </label>
                                            @error('id_agence_dest')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="id_agence_exp" name="id_agence_exp" value="{{old('id_agence_exp', $record->id_agence_exp)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="id_agence_exp">  </label>
                                            @error('id_agence_exp')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="transaction_user" name="transaction_user" value="{{old('transaction_user', $record->transaction_user)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="transaction_user">  </label>
                                            @error('transaction_user')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="sens" name="sens" value="{{old('sens', $record->sens)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="sens">  </label>
                                            @error('sens')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="id_employe" name="id_employe" value="{{old('id_employe', $record->id_employe)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="id_employe">  </label>
                                            @error('id_employe')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('bon_list')}}"><button type="button"
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