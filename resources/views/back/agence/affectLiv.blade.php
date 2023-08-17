@extends($layout)

@section('content')

<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">

                <form method="POST" action="">
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

                                <div class="col s6 input-field">
                                    <select name='agence' id='agence' onchange='window.location.href = "/agence/affectlivaison/"+$("#agence").val();
' class="select2 browser-default">
                                        <option value=''></option>
                                        @foreach ($agences as $record)
                                        <option value="{{ $record->id }}"
                                            {{($record->id == $agence->id) ? 'selected' : ''}}>{{ $record->Libelle }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <label for="agence"> Agence</label>
                                </div>

                                <div class="col s12 input-field" id="agences">
                                    <select multiple="multiple" size="10" name="agences[]">
                                        @foreach ($agences as $record)
                                        <option value="{{ $record->id }}"
                                            {{ in_array($record->id, $agence->related()->allRelatedIds()->toArray()) ? "selected" : '' }}>
                                            {{ $record->Libelle }}</option>
                                        @endforeach
                                    </select>
                                    <label for="label" style="font-size: 1rem;"> Liste des agences :
                                    </label>
                                </div>


                            </div>


                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
                                        @if($agence->id)
                                        <button type="submit" class="btn indigo" style="margin-left: 1rem;">
                                            Enregistrer</button>
                                        @endif
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
</div>

@stop
@section('js')
<link rel="stylesheet" type="text/css" href="/assets/vendors/duallistbox/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/assets/vendors/duallistbox/bootstrap-duallistbox.css">
<script src="/assets/vendors/duallistbox/jquery.bootstrap-duallistbox.js"></script>

<style>
#agences .select-dropdown {
    display: none !important;
}

.info {
    display: none !important;
}
</style>
<script>
$(document).ready(function() {

    $('select[name="agences[]"]').bootstrapDualListbox();

});
</script>
@stop