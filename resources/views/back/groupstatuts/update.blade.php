@extends($layout)
@section('content')
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">

                    <form method="POST" action="{{ route('groupstatuts_update', ['groupstatuts' => $record->id]) }}">
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

                                            <div class="col s5 input-field">
                                                <input id="code" name="code"
                                                    value="{{ old('code', $record->code) }}" autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly');" type="text">
                                                <label for="code"> Code </label>
                                                @error('code')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col s5 input-field">
                                                <input id="libelle" name="libelle"
                                                    value="{{ old('libelle', $record->libelle) }}" autocomplete="off"
                                                    readonly onfocus="this.removeAttribute('readonly');" type="text">
                                                <label for="libelle"> Libelle </label>
                                                @error('libelle')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col s2 input-field">
                                                <select class="icons" id="color" name="color">
                                                    <option value="red" {{ $record->color == 'red' ? 'selected' : '' }}
                                                        data-icon="https://cdn-icons-png.flaticon.com/512/595/595005.png"
                                                        class="left circle">Rouge</option>
                                                    <option value="blue" {{ $record->color == 'blue' ? 'selected' : '' }}
                                                        data-icon="https://cdn-icons-png.flaticon.com/512/594/594846.png"
                                                        class="left circle">Blue</option>
                                                    <option value="green" {{ $record->color == 'green' ? 'selected' : '' }}
                                                        data-icon="https://emojipedia-us.s3.amazonaws.com/source/skype/289/large-green-circle_1f7e2.png"
                                                        class="left circle">Vert</option>
                                                    <option value="orange"
                                                        {{ $record->color == 'orange' ? 'selected' : '' }}
                                                        data-icon="https://www.freeiconspng.com/uploads/orange-circle-png-3.png"
                                                        class="left circle">Orange</option>
                                                    <option value="brown"
                                                        {{ $record->color == 'brown' ? 'selected' : '' }}
                                                        data-icon="https://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Circle_Brown_Solid.svg/1024px-Circle_Brown_Solid.svg.png"
                                                        class="left circle">Brun</option>
                                                    <option value="indigo"
                                                        {{ $record->color == 'indigo' ? 'selected' : '' }}
                                                        data-icon="https://cdn-icons-png.flaticon.com/512/6162/6162025.png"
                                                        class="left circle">Bleu Indigo</option>
                                                    <option value="purple"
                                                        {{ $record->color == 'purple' ? 'selected' : '' }}
                                                        data-icon="https://emojipedia-us.s3.amazonaws.com/source/skype/289/large-purple-circle_1f7e3.png"
                                                        class="left circle">Violet</option>


                                                </select>
                                                @error('color')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12 input-field" id="statuts">
                                                <select multiple="multiple" size="10" name="statuts[]">
                                                    @foreach ($statuts as $statut)
                                                        <option value="{{ $statut->id }}"
                                                            {{ in_array($statut->id,$record->relatedStatuts()->allRelatedIds()->toArray())? 'selected': '' }}>
                                                            {{ $statut->value }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="label" style="font-size: 1rem;"> Liste des statuts :
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="col s12 display-flex justify-content-end mt-3">
                                            <a href="{{ route('groupstatuts_list') }}"><button type="button"
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
@section('js')
    <link rel="stylesheet" type="text/css" href="/assets/vendors/duallistbox/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendors/duallistbox/bootstrap-duallistbox.css">
    <script src="/assets/vendors/duallistbox/jquery.bootstrap-duallistbox.js"></script>

    <style>
        #statuts .select-dropdown {
            display: none !important;
        }

        .info {
            display: none !important;
        }
    </style>
    <script>
        $(document).ready(function() {

            $('select[name="statuts[]"]').bootstrapDualListbox();

        });
    </script>
@stop
