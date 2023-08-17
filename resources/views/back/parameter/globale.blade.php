@extends($layout)
@section('content')
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <form method="POST">
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
                                            <select multiple="multiple" name="villes_depart[]" placeholder=""
                                                class="select2 browser-default">
                                                @php
                                                $villesDepart = explode(',', $record->villes_depart);
                                                @endphp
                                                @foreach ($villes as $row)
                                                <option class='option'
                                                    {{  in_array($row->id, $villesDepart)  ? 'selected' : '' }}
                                                    value='{{ $row->id }}'> {{ $row->libelle }} </option>
                                                @endforeach
                                            </select>
                                            <label for="label"> Villes de départ autorisées :
                                            </label>
                                        </div>
                                        <div class="col s6 input-field">
                                            <input id="tauxtva" name="tauxtva"
                                                value="{{old('tauxtva', $record->tauxtva)}}" autocomplete="off" readonly
                                                onfocus="this.removeAttribute('readonly');" type="text">
                                            <label for="tauxtva"> Tx.TVA </label>
                                            @error('tauxtva')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
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
</style>
<script>
$(document).ready(function() {
    $('select[name="autorisations[]"]').bootstrapDualListbox();
});
</script>
@stop