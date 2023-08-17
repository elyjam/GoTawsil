@extends($layout)

@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des sfactures</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('sfacture_list')}}">Liste des sfactures</a>
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
                <form method="POST" action="{{route('sfacture_create')}}">
                    @csrf
                    <br>
                    <div class="card">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="row">
                                        
                                    <div class="col s6 input-field">
                                    <select name='client' id='client' class="select2 browser-default">
                                    <option value=''></option>
                                    @foreach ($clientRecords as $row)
                                        <option class='option' {{($row->id == old('client')) ? 'selected' : ''}}
                                        value='{{$row->id}}'> {{$row->libelle}}</option>
                                    @endforeach
                                </select>
                                    <label for="client"> Client</label>
                                    @error('client')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                                            
                                        <div class="col s6 input-field">
                                            <textarea d="designation" name="designation" class="materialize-textarea"></textarea>
                                            <label for="designation"> Designation </label>
                                            @error('designation')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="date" name="date" value="{{old('date')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text" class="datepicker">
                                            <label for="date"> Date de la facture </label>
                                            @error('date')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="total_ht" name="total_ht" value="{{old('total_ht')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="total_ht"> TOTAL H.T </label>
                                            @error('total_ht')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="taux_tva" name="taux_tva" value="{{old('taux_tva')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="taux_tva"> TAUX T.V.A </label>
                                            @error('taux_tva')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="total_tva" name="total_tva" value="{{old('total_tva')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="total_tva"> TOTAL T.V.A </label>
                                            @error('total_tva')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>                
                                        <div class="col s6 input-field">
                                            <input id="total_ttc" name="total_ttc" value="{{old('total_ttc')}}" autocomplete="off"
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