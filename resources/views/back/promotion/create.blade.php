@extends($layout)

@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des promotions</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('promotion_list')}}">Liste des promotions</a>
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
                <form method="POST" action="{{route('promotion_create')}}" enctype="multipart/form-data">
                    @csrf
                    <br>
                    <div class="card">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="row">


                                        <div class="col s12 m6 input-field">
                                            <input id="libelle" name="libelle" value="{{old('libelle')}}"
                                                autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                type="text">
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
                                                        {{ $row->id == old('client') ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="client"> Client</label>
                                            @error('libelle')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s12 input-field">
                                            <textarea d="description" name="description" class="materialize-textarea"
                                                style="height: 80px;"></textarea>
                                            <label for="description"> Description </label>
                                            @error('description')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- <div class="col s12 input-field">
                                            <textarea d="imgUrl" name="imgUrl" class="materialize-textarea"
                                                style="height: 80px;"></textarea>
                                            <label for="imgUrl"> Lien de l'image </label>
                                            @error('imgUrl')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div> --}}



                                        <div class="col s12 m6 input-field">
                                            <input id="date_debut" name="date_debut" value="{{old('date_debut')}}"
                                                autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                type="text" class="datepicker">
                                            <label for="date_debut"> Date début </label>
                                            @error('date_debut')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s12 m6 input-field">
                                            <input id="date_fin" name="date_fin" value="{{old('date_fin')}}"
                                                autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                type="text" class="datepicker">
                                            <label for="date_fin"> Date fin </label>
                                            @error('date_fin')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="file-field input-field col s12 m5">
                                            <div class="btn">
                                                <span>Image</span>
                                                <input name="file" type="file" value="" autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly');">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path" type="text">
                                            </div>
                                        </div>
                                        <!--<div class="col s6 input-field">
                                            <input id="date_debut_visibilite" name="date_debut_visibilite"
                                                value="{{old('date_debut_visibilite')}}" autocomplete="off" readonly
                                                onfocus="this.removeAttribute('readonly');" type="text"
                                                class="datepicker">
                                            <label for="date_debut_visibilite"> Date Début visibilité </label>
                                            @error('date_debut_visibilite')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s6 input-field">
                                            <input id="date_fin_visibilite" name="date_fin_visibilite"
                                                value="{{old('date_fin_visibilite')}}" autocomplete="off" readonly
                                                onfocus="this.removeAttribute('readonly');" type="text"
                                                class="datepicker">
                                            <label for="date_fin_visibilite"> Date fin visibilité </label>
                                            @error('date_fin_visibilite')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col s6 input-field">
                                            <p><label>
                                                    <h6>type :</h6>
                                                </label></p>
                                            <p>
                                                <label><input class='with-gap' name='type' value='1'
                                                        type='radio'><span>CATEGORIE A (DEFAULT)</span></label>
                                                <label><input class='with-gap' name='type' value='2'
                                                        type='radio'><span>CATEGORIE B (C001)</span></label>
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
