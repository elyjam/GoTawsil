@extends($layout)
@section('content')
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <form method="POST" action="{{route('reclamation_update', ['reclamation' => $record->id])}}">
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
                                            <input id="date_cloture" name="date_cloture" value="{{old('date_cloture', $record->date_cloture)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text" class="datepicker">
                                            <label for="date_cloture"> Date Cloture </label>
                                            @error('date_cloture')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s6 input-field">
                                            <textarea d="description" name="description" class="materialize-textarea">{{old('description', $record->description)}}</textarea>
                                            <label for="description"> Description </label>
                                            @error('description')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    <div class="col s6 input-field">
                                    <select name='typereclamation' id='typereclamation' class="select2 browser-default">
                                    <option value=''></option>
                                        @foreach ($typereclamationRecords as $row)
                                            <option class='option' {{($row->id == old('typereclamation', $record->typereclamation)) ? 'selected' : ''}}
                                            value='{{$row->id}}'> {{$row->libelle}}</option>
                                        @endforeach
                                </select>
                                    <label for="typereclamation"> Type reclamation</label>
                                    @error('typereclamation')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                    <div class="col s6 input-field">
                                    <select name='user' id='user' class="select2 browser-default">
                                    <option value=''></option>
                                        @foreach ($userRecords as $row)
                                            <option class='option' {{($row->id == old('user', $record->user)) ? 'selected' : ''}}
                                            value='{{$row->id}}'> {{$row->libelle}}</option>
                                        @endforeach
                                </select>
                                    <label for="user"> User</label>
                                    @error('user')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                    <div class="col s6 input-field">
                                    <select name='cloture_par' id='cloture_par' class="select2 browser-default">
                                    <option value=''></option>
                                        @foreach ($employeRecords as $row)
                                            <option class='option' {{($row->id == old('cloture_par', $record->cloture_par)) ? 'selected' : ''}}
                                            value='{{$row->id}}'> {{$row->libelle}}</option>
                                        @endforeach
                                </select>
                                    <label for="cloture_par"> Cloture par</label>
                                    @error('cloture_par')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                    <div class="col s6 input-field">
                                    <select name='statut' id='statut' class="select2 browser-default">
                                    <option value=''></option>
                                <option value="1" {{($record->statut == '1') ? 'selected' : ''}}> Résolu </option>
<option value="2" {{($record->statut == '2') ? 'selected' : ''}}> Annuler </option>
</select> <label for="statut"> Statut</label>
                                    @error('statut')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('reclamation_list')}}"><button type="button"
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
<script>
    document.getElementById("reclamation").classList.add("activate");
</script>
@stop
