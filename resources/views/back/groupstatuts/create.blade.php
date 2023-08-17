@extends($layout)

@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des groupstatutss</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('groupstatuts_list')}}">Liste des groupstatutss</a>
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
                <form method="POST" action="{{route('groupstatuts_create')}}">
                    @csrf
                    <br>
                    <div class="card">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="row">

                                        <div class="col s5 input-field">
                                            <input id="code" name="code" value="{{old('code')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="code"> Code </label>
                                            @error('code')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s5 input-field">
                                            <input id="libelle" name="libelle" value="{{old('libelle')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="libelle"> Libelle </label>
                                            @error('libelle')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s2 input-field">
                                            <select class="icons" id="color" name="color">
                                                <option value="red" {{ $record->color == old('color') ? 'selected' : '' }}
                                                    data-icon="https://cdn-icons-png.flaticon.com/512/595/595005.png"
                                                    class="left circle">Rouge</option>
                                                <option value="blue" {{ $record->color == old('color') ? 'selected' : '' }}
                                                    data-icon="https://cdn-icons-png.flaticon.com/512/594/594846.png"
                                                    class="left circle">Blue</option>
                                                <option value="green" {{ $record->color == old('color') ? 'selected' : '' }}
                                                    data-icon="https://emojipedia-us.s3.amazonaws.com/source/skype/289/large-green-circle_1f7e2.png"
                                                    class="left circle">Vert</option>
                                                <option value="orange"
                                                    {{ $record->color == old('color') ? 'selected' : '' }}
                                                    data-icon="https://www.freeiconspng.com/uploads/orange-circle-png-3.png"
                                                    class="left circle">Orange</option>
                                                <option value="brown"
                                                    {{ $record->color == old('color') ? 'selected' : '' }}
                                                    data-icon="https://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Circle_Brown_Solid.svg/1024px-Circle_Brown_Solid.svg.png"
                                                    class="left circle">Brun</option>
                                                <option value="indigo"
                                                    {{ $record->color == old('color') ? 'selected' : '' }}
                                                    data-icon="https://cdn-icons-png.flaticon.com/512/6162/6162025.png"
                                                    class="left circle">Bleu Indigo</option>
                                                <option value="purple"
                                                    {{ $record->color == old('color') ? 'selected' : '' }}
                                                    data-icon="https://emojipedia-us.s3.amazonaws.com/source/skype/289/large-purple-circle_1f7e3.png"
                                                    class="left circle">Violet</option>


                                            </select>
                                            @error('color')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('groupstatuts_list')}}"><button type="button"
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
