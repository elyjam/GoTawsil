@extends($layout)

@section('content')
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Gestion des taxations</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('taxation_list') }}">Liste des taxations</a>
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
                    <form method="POST" action="{{ route('taxation_create', $idville) }}">
                        @csrf
                        <br>
                        <div class="card">
                            <div class="card-panel">
                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="row">

                                            {{-- <div class="col s6 input-field">
                                            <input id="code" name="code" value="{{old('code')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="code"> code </label>
                                            @error('code')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div> --}}
                                            <div class="col s6 input-field">
                                                <select name='id_ville_exp' id='id_ville_exp' onchange="window.location.href=this.options[this.selectedIndex].id;"
                                                    class="select2 browser-default" title="Select la ville d'éxpedition">
                                                    <option class='option'
                                                        value='0' id='{{route('taxation_create', 0)}}' ></option>
                                                    @foreach ($villeRecordsExp as $row)
                                                        <option class='option'
                                                        {{ $row->id == $idville ? 'selected' : '' }}
                                                            value='{{ $row->id }}' id='{{route('taxation_create', $row->id)}}'> {{ $row->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="id_ville_exp"> Ville Expedition </label>
                                                @error('id_ville_exp')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col s6 input-field">
                                                <select name='id_ville_dest' id='id_ville_dest'
                                                    class="select2 browser-default">

                                                    @foreach ($villeRecordsDest as $row)
                                                        <?php
                                                        $check = 0;
                                                        ?>;
                                                        @foreach ($record as $tax)

                                                            @if ($row->id == $tax->id_ville_dest)
                                                                <?php
                                                                $check = 1;
                                                                ?>;
                                                            @endif
                                                        @endforeach

                                                        @if ($check != 1)
                                                            <option class='option'
                                                                {{ $row->id == old('id_ville_dest') ? 'selected' : '' }}
                                                                value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <label for="id_ville_dest"> Ville Destination</label>
                                                @error('id_ville_dest')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            {{-- <div class="col s6 input-field">
                                            <input id="sens" name="sens" value="{{old('sens')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="sens"> Sens </label>
                                            @error('sens')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s6 input-field">
                                            <input id="statut" name="statut" value="{{old('statut')}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="statut"> Statut </label>
                                            @error('statut')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div> --}}
                                            <div class="col s6 input-field">
                                                <input id="mnt_min" name="mnt_min" value="{{ old('mnt_min') }}"
                                                    autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                    type="text">
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
                                            <a href="{{ route('taxation_list') }}"><button type="button"
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
