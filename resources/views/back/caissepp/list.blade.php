@extends($layout)
@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Caisse PP</span></h5>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">
                <br>
                <div>
                        <form id="form" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col s12 m3 input-field">
                                            <select id="agence" name="agence" placeholder=""
                                                class="select select2 browser-default">
                                                <option value='0'></option>
                                                @foreach ($agenceRecords as $row)
                                                    <option class='option'
                                                        {{ $row->id == old('agence') ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="agence"> Origine</label>
                                        </div>
                                        <div class="col s12 m3 input-field">
                                            <select id="agence_des" name="agence_des" placeholder=""
                                                class="select2 browser-default">
                                                <option value='0'></option>
                                                @foreach ($agenceRecords as $row)
                                                    <option class='option'
                                                        {{ $row->id == old('agence_des') ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="agence_des"> Destination</label>
                                        </div>
                                        {{-- <div class="col s12 m2 input-field">
                                        <select id="typ" name="typ" placeholder="" class="select2 browser-default">
                                            <option value='1'></option>
                                            <option value='test'>Type 1</option>
                                        </select>
                                        <label for="typ"> Type</label>
                                    </div> --}}
                                        <div class="col s12 m3 input-field">
                                            <input id="start_date" value="{{ old('start_date') }}" name="start_date"
                                                type="text" placeholder="" class="datepicker">
                                            <label for="start_date">Du </label>
                                        </div>
                                        <div class="col s12 m3 input-field">
                                            <input id="end_date" value="{{ old('end_date') }}" name="end_date"
                                                type="text" placeholder="" class="datepicker">
                                            <label for="end_date">Au </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12 m3 input-field">
                                            <input id="n_colis" value="{{ old('n_colis') }}" name="n_colis"
                                                type="text" placeholder="">
                                            <label for="n_colis">N° Colis </label>
                                        </div>

                                        <div class="col s12 m3 input-field">
                                            <select name='expediteur' id='expediteur' class="select2 browser-default">
                                                <option value='0'></option>
                                                @foreach ($clientRecords as $row)
                                                    <option class='option'
                                                        {{ $row->id == old('expediteur') ? 'selected' : '' }}
                                                        value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                @endforeach
                                            </select>
                                            <label for="expediteur"> Client</label>
                                        </div>

                                        <div class="col s12 m3 input-field">
                                            <select  id="etapes" name="etapes[]" class="select2 browser-default" multiple="multiple">

                                                <option value='0'>Selectionner l'etape</option>
                                                @foreach ($commentRecords as $row)
                                                <option value="{{ $row->key }}" {{ (collect(old('etapes'))->contains($row->key)) ? 'selected':'' }}>{{  $row->value }}</option>

                                                @endforeach


                                              </select>
                                              <label for="etapes">Etapes</label>
                                        </div>
                                        <div class="col s12 m3 input-field">
                                            <button type="button"
                                                onclick="event.preventDefault();
                                                                                    $('#form').attr('action', '{{ route('caissepp_list') }}'); document.getElementById('form').submit();"
                                                class="btn btn-light" style="margin-right: 1rem;">
                                                <i class="material-icons">search</i></button>
                                            <button type="button"
                                            onclick="window.location ='/caissepp/export?'+$('#form').serialize()"
                                                class="btn btn-download"><i
                                                    class="material-icons">file_download</i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                <div class="list-table" id="app">
                    <div class="card">
                        <div class="card-content">
                            <!-- datatable start -->
                            <div class="responsive-table">
                                <table id="list-datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>

                                            <th> N° Exp </th>
                                            <th> Date de réception </th>
                                            <th> Employé </th>
                                            <th> Montant </th>
                                            <th> Statut </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                        <tr>

                                            <td> <span class="badge gradient-45deg-blue-grey-blue">{{$record->num_expedition}} </td>
                                            <td>
                                            @if(strlen($record->caissepp_date_recp)>2)
                                            {{ date('d/m/Y H:i', strtotime($record->caissepp_date_recp)) }}
                                            @endif </td>
                                            <td> {{ $record->name.' '.$record->first_name }}  </td>
                                            <td> {{ $record->ttc }} </td>
                                            <td class="{{ $record->caissepp_statut == 1 ? 'green' : '' }}"> {{ $record->caissepp_statut == 1 ? 'Reçu' : 'En cours' }} </td>
                                            <td>
                                                @if($record->caissepp_statut != 1)
                                                <a  href="#!" onclick="openSuppModal({{$record->id}})"><i class="material-icons tooltipped" style="color: green;" data-position="top"
                                            data-tooltip="Valider la réception">check</i></a>
                                            @endif
                                            </td>
                                        </tr>
                                        @endforeach

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="delete_modal" class="modal">
    <div class="modal-content">
    <h4> Confirmation de la réception</h4>
        <div>
        Êtes-vous sûr de vouloir valider la réception ?
        </div>
        <input type="hidden" name="delId" id="delId">
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
        <a href="#!" class="waves-effect waves-green btn green" onclick="validRecord()">Valider la réception</a>
    </div>
</div>
@stop
@section('js')
<script>
    function openSuppModal(id) {
        $("#delId").val(id);
        $('#delete_modal').modal('open');
    }
    function validRecord() {
        window.location.replace("/caissepp/valid/"+$("#delId").val());
    }
    $(document).ready(function() {
        $('.modal').modal();
    });
</script>
@stop
