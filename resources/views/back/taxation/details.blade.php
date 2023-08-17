@extends($layout)
@section('content')
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <form method="POST" action="{{route('taxation_update', ['taxation' => $record->id])}}">
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

                                        {{-- <div class="col s6 input-field">
                                            <input id="code" name="code" value="{{old('code', $record->code)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="code"> code </label>
                                            @error('code')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div> --}}
                                    <div class="col s6 input-field">
                                    <select name='id_ville_exp' id='id_ville_exp' class="select2 browser-default">
                                    <option value=''></option>
                                        @foreach ($villeRecordsExp as $row)
                                            <option class='option' {{($row->id == old('id_ville_exp', $record->id_ville_exp)) ? 'selected' : ''}}
                                            value='{{$row->id}}'> {{$row->libelle}}</option>
                                        @endforeach
                                </select>
                                    <label for="id_ville_exp"> Ville Expedition </label>
                                    @error('id_ville_exp')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                    <div class="col s6 input-field">
                                    <select name='id_ville_dest' id='id_ville_dest' class="select2 browser-default">
                                    <option value=''></option>
                                        @foreach ($villeRecordsDest as $row)
                                            <option class='option' {{($row->id == old('id_ville_dest', $record->id_ville_dest)) ? 'selected' : ''}}
                                            value='{{$row->id}}'> {{$row->libelle}}</option>
                                        @endforeach
                                </select>
                                    <label for="id_ville_dest"> Ville Dest</label>
                                    @error('id_ville_dest')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                        {{-- <div class="col s6 input-field">
                                            <input id="sens" name="sens" value="{{old('sens', $record->sens)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="sens"> Sens </label>
                                            @error('sens')
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
                                        </div>                 --}}
                                        <div class="col s6 input-field">
                                            <input id="mnt_min" name="mnt_min" value="{{old('mnt_min', $record->mnt_min)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="mnt_min"> Montant Min </label>
                                            @error('mnt_min')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('taxation_list')}}"><button type="button"
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
