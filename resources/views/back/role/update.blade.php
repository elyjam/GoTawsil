@extends($layout)
@section('content')
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <form method="POST" action="{{route('role_update', ['role' => $record->id])}}">
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
                                            <input id="label" name="label" value="{{old('label', $record->label)}}"
                                                autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                type="text">
                                            <label for="label"> Libellé </label>
                                            @error('label')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s6 input-field">
                                            <textarea d="desc" name="desc"
                                                class="materialize-textarea">{{old('desc', $record->desc)}}</textarea>
                                            <label for="desc"> Description </label>
                                            @error('desc')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col s12 input-field">
                                            <select multiple="multiple" size="10" name="fonctionnalites[]">
                                                @foreach ($fonctionnalites as $fonctionnalite)
                                                <option
                                                    {{ in_array($fonctionnalite->id, $record->fonctionnalites()->allRelatedIds()->toArray()) ? "selected" : '' }}
                                                    value="{{ $fonctionnalite->id }}">{{ $fonctionnalite->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <label for="label" style="font-size: 1rem;"> Liste des fonctionnalités :
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        <a href="{{route('role_list')}}"><button type="button"
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
.select-dropdown {
    display: none !important;
}

.info {
    display: none !important;
}

.info-container {
    display: none !important;
}
</style>
<script>
$(document).ready(function() {
    $('select[name="fonctionnalites[]"]').bootstrapDualListbox();
});
</script>
@stop