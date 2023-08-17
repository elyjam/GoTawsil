@extends($layout)
@section('content')
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <form method="POST" action="{{route('sfacture_update', ['sfacture' => $record->id])}}">
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
                                            <textarea d="designation" name="designation" class="materialize-textarea">{{old('designation', $record->designation)}}</textarea>
                                            <label for="designation"> Designation </label>
                                            @error('designation')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="date" name="date" value="{{old('date', $record->date)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text" class="datepicker">
                                            <label for="date"> Date de la facture </label>
                                            @error('date')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="total_ht" name="total_ht" value="{{old('total_ht', $record->total_ht)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="total_ht"> TOTAL H.T </label>
                                            @error('total_ht')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="taux_tva" name="taux_tva" value="{{old('taux_tva', $record->taux_tva)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="taux_tva"> TAUX T.V.A </label>
                                            @error('taux_tva')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="total_tva" name="total_tva" value="{{old('total_tva', $record->total_tva)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="total_tva"> TOTAL T.V.A </label>
                                            @error('total_tva')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="total_ttc" name="total_ttc" value="{{old('total_ttc', $record->total_ttc)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="total_ttc"> TOTAL T.T.C </label>
                                            @error('total_ttc')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('sfacture_list')}}"><button type="button"
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