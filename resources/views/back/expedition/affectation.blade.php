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
                                    <select name='employe' id='employe' class="select2 browser-default"
                                        onchange='window.location.href = "{{route($type == 1 ? "expedition_affec_liv" : "expedition_affec_retour",["type"=>$type])}}"+"/"+$(this).val();'>
                                        <option value=''></option>
                                        @foreach ($employes as $record)
                                        <option value="{{ $record->EmployeDetail->id }}"
                                            {{ $livreur == $record->EmployeDetail->id ? 'selected=selected' : ''}}>
                                            {{ $record->EmployeDetail->libelle }} </option>
                                        @endforeach
                                    </select>
                                    <label for="employe"> Livreur</label>
                                    @error('employe')
                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if(is_numeric($livreur))
                                <!--<div class="col s6 input-field">
                                    <select name='bl' id='bl' class="select2 browser-default">
                                        <option value=''></option>
                                        @foreach ($bls as $record)
                                        <option value="{{ $record->id }}"
                                            {{ $livreur == $record->id ? 'selected=selected' : ''}}>
                                            {{ $record->code . " Crée le : ". $record->created_at }} </option>
                                        @endforeach
                                    </select>
                                    <label for="bl"> Bon de livraison</label>
                                    @error('bl')
                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>-->
                                @endif

                                <div class="col s12 input-field" id="colis">
                                    <select multiple="multiple" size="10" name="colis[]">
                                        @foreach ($colis as $record)

                                        <option value="{{ $record->id }}">

                                            {{$record->num_expedition . ' - '. (!empty($record->clientDetail->libelle) ? $record->clientDetail->libelle : ''  )  ." │ ". $record->agenceDetail->libelle  ." → ". $record->DestinationDetail->libelle ." │ ". $record->destinataire  ?? '' }}
                                            ( {{ $type == 1 ? 'Envoi' : 'Retour'}} )
                                        </option>

                                        @endforeach
                                    </select>
                                    <label for="label" style="font-size: 1rem;"> Liste des colis :
                                    </label>
                                    @error('colis')
                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="col s12 display-flex justify-content-end mt-3">
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
</div>
@stop
@section('js')
<link rel="stylesheet" type="text/css" href="/assets/vendors/duallistbox/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/assets/vendors/duallistbox/bootstrap-duallistbox.css">
<script src="/assets/vendors/duallistbox/jquery.bootstrap-duallistbox.js"></script>
<style>
#colis .select-dropdown {
    display: none !important;
}

.info {
    display: none !important;
}
</style>
<script>
$(document).ready(function() {
    $('select[name="colis[]"]').bootstrapDualListbox();
});
</script>
@stop
