@extends($layout)

@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des bonlivs</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('bonliv_list')}}">Liste des bonlivs</a>
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
                <form method="POST" action="{{route('bonliv_create')}}">
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
                                            <label for="code"> Code </label>
                                            @error('code')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="created" name="created" value="{{old('created')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="created"> Crée le </label>
                                            @error('created')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    <div class="col s6 input-field">
                                    <select name='livreur' id='livreur' class="select2 browser-default">
                                    <option value=''></option>
                                    @foreach ($employeRecords as $row)
                                        <option class='option' {{($row->id == old('livreur')) ? 'selected' : ''}}
                                        value='{{$row->id}}'> {{$row->libelle}}</option>
                                    @endforeach
                                </select>
                                    <label for="livreur"> Livreur</label>
                                    @error('livreur')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                    
                                    <div class="col s6 input-field">
                                    <select name='Type' id='Type' class="select2 browser-default">
                                    <option value=''></option>
                                    @foreach ($typeblRecords as $row)
                                        <option class='option' {{($row->id == old('Type')) ? 'selected' : ''}}
                                        value='{{$row->id}}'> {{$row->label}}</option>
                                    @endforeach
                                </select>
                                    <label for="Type"> Type</label>
                                    @error('Type')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                                    
                                        <div class="col s6 input-field">
                                            <input id="closed_at" name="closed_at" value="{{old('closed_at')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="closed_at"> Férmé le	 </label>
                                            @error('closed_at')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('bonliv_list')}}"><button type="button"
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