@extends($layout)
<style>

    .switch-field {
        display: flex;
        margin-top: 13px;
        overflow: hidden;
    }

    .switch-field input {
        position: absolute !important;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        width: 1px;
        border: 0;
        overflow: hidden;
    }

    .switch-field label {
        background-color: #e4e4e4;
        color: rgba(0, 0, 0, 0.6);
        font-size: 14px;
        line-height: 1;
        text-align: center;
        padding: 13px 20px;
        margin-right: -1px;
        border: 1px solid rgba(0, 0, 0, 0.2);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
        transition: all 0.1s ease-in-out;
    }

    .switch-field label:hover {
        cursor: pointer;
    }

    .switch-field input:checked+label {
        background-color: #1991ce;
        box-shadow: none;
        color: white;
    }

    .switch-field label:first-of-type {
        border-radius: 4px 0 0 4px;
    }

    .switch-field label:last-of-type {
        border-radius: 0 4px 4px 0;
    }
</style>
@section('content')
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <form method="POST" action="{{route('region_update', ['region' => $record->id])}}">
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
                                            <input id="Libelle" name="Libelle" value="{{old('Libelle', $record->libelle)}}" autocomplete="off"
                                            readonly onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="Libelle"> Libelle </label>
                                            @error('Libelle')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s6 input-field">

                                            <h6>
                                                <label > Statut </label>
                                            </h6>

                                            <div class="switch-field">

                                                <input type="radio" id='active' name='statut' value="1"  {{ old('Statut', $record->statut) == 1 ? 'checked' : '' }}>
                                                <label for="active">Active</label>

                                                <input type="radio" id='inactive' name='statut' value="0"  {{ old('Statut', $record->statut) == 0 ? 'checked' : '' }}>
                                                <label for="inactive">Inactive</label>

                                            </div>

                                            @error('Statut')
                                                <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col s12 input-field" id="villes">
                                            <select multiple="multiple" size="10" name="villes[]">
                                                @foreach ($villes as $ville)
                                                <option value="{{ $ville->id }}"
                                                    {{ in_array($ville->id, $record->relatedVilles()->allRelatedIds()->toArray()) ? "selected" : '' }}
                                                    >
                                                    {{ $ville->libelle }}</option>



                                                @endforeach
                                            </select>
                                            <label for="label" style="font-size: 1rem;"> Liste des villes :
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('region_list')}}"><button type="button"
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
#villes .select-dropdown {
    display: none !important;
}

.info {
    display: none !important;
}
</style>
<script>
$(document).ready(function() {

    $('select[name="villes[]"]').bootstrapDualListbox();

});
</script>
@stop
