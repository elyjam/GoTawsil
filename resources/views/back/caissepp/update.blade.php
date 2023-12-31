@extends($layout)
@section('content')
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <form method="POST" action="{{route('caissepp_update', ['caissepp' => $record->id])}}">
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
                                            <input id="reception_date" name="reception_date" value="{{old('reception_date', $record->reception_date)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="reception_date"> Date de réception </label>
                                            @error('reception_date')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="emp" name="emp" value="{{old('emp', $record->emp)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="emp"> Employé </label>
                                            @error('emp')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="montant" name="montant" value="{{old('montant', $record->montant)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="montant"> Montant </label>
                                            @error('montant')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="statut" name="statut" value="{{old('statut', $record->statut)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="statut"> Statut </label>
                                            @error('statut')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="action" name="action" value="{{old('action', $record->action)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="action"> Action(s) </label>
                                            @error('action')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('caissepp_list')}}"><button type="button"
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