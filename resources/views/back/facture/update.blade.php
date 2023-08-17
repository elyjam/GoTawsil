@extends($layout)
@section('content')
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <form method="POST" action="{{route('facture_update', ['facture' => $record->id])}}">
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
                                            <label for="code"> Code </label>
                                            @error('code')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    <div class="col s6 input-field">
                                    <select name='client' id='client' class="select2 browser-default">
                                    <option value=''></option>
                                        @foreach ($clientRecords as $row)
                                            <option class='option' {{($row->id == old('client', $record->client)) ? 'selected' : ''}}
                                            value='{{$row->id}}'> {{$row->libelle}}</option>
                                        @endforeach
                                </select> 
                                    <label for="client"> Client</label>
                                    @error('client')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                                    
                                        <div class="col s6 input-field">
                                            <input id="datefacture" name="datefacture" value="{{old('datefacture', $record->datefacture)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text" class="datepicker">
                                            <label for="datefacture"> Date facture </label>
                                            @error('datefacture')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="dateecheance" name="dateecheance" value="{{old('dateecheance', $record->dateecheance)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text" class="datepicker">
                                            <label for="dateecheance"> Date echéance </label>
                                            @error('dateecheance')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="tauxtva" name="tauxtva" value="{{old('tauxtva', $record->tauxtva)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="tauxtva"> Taux TVA </label>
                                            @error('tauxtva')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="ht" name="ht" value="{{old('ht', $record->ht)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="ht"> H.T </label>
                                            @error('ht')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="tva" name="tva" value="{{old('tva', $record->tva)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="tva"> TVA </label>
                                            @error('tva')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="ttc" name="ttc" value="{{old('ttc', $record->ttc)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="ttc"> TTC </label>
                                            @error('ttc')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('facture_list')}}"><button type="button"
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