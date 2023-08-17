@extends($layout)
@section('content')
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">
                <form method="POST" action="{{route('promotion_update', ['promotion' => $record->id])}}">
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
                                        <div class="col s12 m6 input-field">
                                            <input id="libelle" name="libelle"
                                                value="{{old('libelle', $record->libelle)}}" autocomplete="off" readonly
                                                onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="libelle"> Titre </label>
                                            @error('libelle')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s12 m6 input-field">
                                            <select name='client' id='client' class="select2 browser-default">
                                                <option value='0'>Tous les clients</option>
                                                @foreach ($clientRecords as $row)
                                                    <option class='option'
                                                        {{ $row->id == $record->client ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="client"> Client</label>
                                            @error('libelle')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s12 input-field">
                                            <textarea d="description" style="  hieght: 80px;" name="description"
                                                class="materialize-textarea">{{old('description', $record->description)}}</textarea>
                                            <label for="description"> Description </label>
                                            @error('description')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s12 input-field">
                                            <textarea d="imgUrl" style="  hieght: 80px;" name="imgUrl"
                                                class="materialize-textarea">{{old('description', $record->imgUrl)}}</textarea>
                                            <label for="imgUrl"> Lien de l'image </label>
                                            @error('imgUrl')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s12 m6 input-field">
                                            <input id="date_debut" name="date_debut"
                                                value="{{old('date_debut', $record->date_debut)}}" autocomplete="off"
                                                readonly onfocus="this.removeAttribute('readonly');" type="text"
                                                class="datepicker">
                                            <label for="date_debut"> Date de début </label>
                                            @error('date_debut')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s12 m6 input-field">
                                            <input id="date_fin" name="date_fin"
                                                value="{{old('date_fin', $record->date_fin)}}" autocomplete="off"
                                                readonly onfocus="this.removeAttribute('readonly');" type="text"
                                                class="datepicker">
                                            <label for="date_fin"> Date de fin </label>
                                            @error('date_fin')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <!--<div class="col s6 input-field">
                                            <input id="date_debut_visibilite" name="date_debut_visibilite" value="{{old('date_debut_visibilite', $record->date_debut_visibilite)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text" class="datepicker">
                                            <label for="date_debut_visibilite"> Date Début visibilité </label>
                                            @error('date_debut_visibilite')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s6 input-field">
                                            <input id="date_fin_visibilite" name="date_fin_visibilite" value="{{old('date_fin_visibilite', $record->date_fin_visibilite)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text" class="datepicker">
                                            <label for="date_fin_visibilite"> Date fin visibilité </label>
                                            @error('date_fin_visibilite')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    <div class="col s6 input-field">
                                    <p><label><h6>type :</h6></label></p><p>
                                <label><input class='with-gap' {{($record->type == '1') ? 'checked' : ''}} name='type' value='1' type='radio'><span>CATEGORIE A (DEFAULT)</span></label>
<label><input class='with-gap' {{($record->type == '2') ? 'checked' : ''}} name='type' value='2' type='radio'><span>CATEGORIE B (C001)</span></label>
</p>
                                    @error('type')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>-->

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('promotion_list')}}"><button type="button"
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
