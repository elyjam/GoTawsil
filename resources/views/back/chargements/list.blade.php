@extends($layout)
@section('content')

<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Chargement des colis</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('bon_list') }}">Liste des bons</a>
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
                @if (session()->has('validate'))
                <div class="card-alert card green">
                    <div class="card-content white-text">
                        <p> {{ session()->get('validate') }}</p>
                    </div>
                    <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                @endif
                <br>

                <form id="form" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col s12 m5 input-field">
                                    <select id="agence_exp" name="agence_exp" placeholder=""
                                        class="select select2 browser-default">
                                        <option value='0'></option>
                                        @php
                                        $agencesOri = is_array(old('agences')) ? old('agences') : array(0);
                                        @endphp
                                        @foreach ($agences as $row)
                                        <option class='option' {{ $row->id == old('agence_exp')  ? 'selected' : '' }}
                                            value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                        @endforeach
                                    </select>
                                    <label for="agence"> Origine</label>
                                </div>
                                <div class="col s12 m5 input-field">
                                    <select name="agence_des" placeholder="" class="select2 browser-default">
                                        <option value='0'></option>
                                        @php
                                        $agenceDes = is_array(old('agence_des')) ? old('agence_des') : array(0);
                                        @endphp

                                        @foreach ($agence_des as $row)
                                        <option class='option' {{ ($row->id == old('agence_des'))  ? 'selected' : '' }}
                                            value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                        @endforeach
                                    </select>
                                    <label for="agence_des"> Destination</label>
                                </div>
                                <div class="col s12 m1 input-field">
                                    <button type="submit" class="btn btn-light" style="margin-right: 1rem;">
                                        <i class="material-icons">search</i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <form id="affecter" method="post" action="{{route('chargement_create')}}">
                    @csrf

                    <div class="card">
                        <div class="card-content" style="padding: 0px;">

                            <div class="row" style="padding: 15px;">
                                <div class="col s12 m5 input-field">
                                    <select name="transporteur" class="select2 browser-default">
                                        @foreach ($transporteurs as $transporteur)
                                        <option value="{{ $transporteur->id }}">{{ $transporteur->libelle }}</option>
                                        @endforeach
                                    </select>
                                    <label for="transporteur"> Transporteur</label>
                                </div>
                                <div class="col s12 m2 input-field">
                                    <button type="button" class="btn btn-info"
                                        onclick="openSuppModal()">Affecter</button>
                                    <button style="visibility: hidden;" type="submit"
                                        class="btn btn-info">Affecter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list-table" id="app">
                        <div class="card">
                            <div class="card-content">
                                <!-- datatable start -->
                                <div class="responsive-table">
                                    <table id="table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label>
                                                        <input type="checkbox" id='checkall'>
                                                        <span></span>
                                                    </label>
                                                </th>
                                                {{-- <th></th> --}}
                                                <th>N° Exp</th>
                                                <th> Expéditeur</th>
                                                <th> Destinataire</th>
                                                <th class="hide-on-small-only"> Nature</th>
                                                <th class="hide-on-small-only"> Prix colis</th>
                                                <th class="hide-on-small-only"> Nb. Colis</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($expeditions as $expedition)
                                            <tr>
                                                <td style="padding-left: 18px;"> <label>
                                                        <input class="checkbox" value="{{$expedition->id}}"
                                                            type="checkbox" name='expeditions[]'>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                {{-- <td>
                                                    @if ($expedition->type == 'CDP')
                                                    <i class="blue-text material-icons"
                                                        title="Contre document">email</i>
                                                    @elseif ($expedition->type == 'ECOM')
                                                    <i class="red-text material-icons" title="Contre espèce">inbox</i>
                                                    @elseif ($expedition->type == 'COLECH')
                                                    <i class=" material-icons" title="Colis en échange"
                                                        style="color: #d8a71d ">autorenew</i>
                                                    @endif
                                                </td> --}}
                                                <td>
                                                    <span class="badge gradient-45deg-blue-grey-blue">
                                                        {{$expedition->num_expedition}} </span>
                                                    <p style="font-size:80%;"> <span> Saisie le : </span>
                                                        {{$expedition->created_at_exp}}</p>
                                                    <p style="font-size:80%;"> Ramasser le :
                                                        {{$expedition->bons_date_validation}} </p>
                                                </td>
                                                <td>
                                                    {{$expedition->client}}<p> <span class=" badge grey"
                                                            data-badge-caption="{{$expedition->agence}}"> </span> </p>
                                                </td>
                                                <td>
                                                    {{$expedition->destinataire}}<p> <span class=" badge grey"
                                                            data-badge-caption="{{$expedition->destination}}"> </span>
                                                    </p>
                                                    <p> {{$expedition->telephone}} </p>
                                                </td>
                                                <td>
                                                    {{$expedition->retour_fond == 'CR' ? 'C. espèce' : 'Simple'}}
                                                </td>
                                                <td>
                                                    {{$expedition->ttc}}
                                                </td>
                                                <td>
                                                    {{$expedition->colis}}
                                                </td>
                                            </tr>


                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a href="{{route('bon_create')}}"
        class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i
            class="material-icons">add</i></a>
    </div> --}}
</div>
</div>

<div id="confirmModal" class="modal">
    <div class="modal-content">
        <h4> Confirmation de Chargement</h4>
        <div>
            Êtes-vous sûr de vouloir valider le chargement ?
        </div>
        <input type="hidden" name="delId" id="delId">
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
        <a href="#!" class="waves-effect waves-green btn green" onclick="ConfrmChargement()">Valider</a>
    </div>
</div>

@stop
@section('js')


<script src="/assets/js/scripts/data-tables.js"></script>


<script>
function openSuppModal() {
    $('#confirmModal').modal('open');
}

function ConfrmChargement() {
    $('#affecter').submit();
}

// Basic Select2 select
$(".select2").select2({
    dropdownAutoWidth: true,
    width: '100%'
});


function suppRecord() {
    window.location.replace("/bon/delete/" + $("#delId").val());
}

$(document).ready(function() {

    $("#checkall").change(function(e) {
        $('.checkbox').prop("checked", $('#checkall').prop("checked"));
    });

    $('#selectagence').change(function() {
        let cid = $(this).val();
        if (cid != '') {
            $.ajax({
                url: '/chargement/ville/list',
                type: 'post',
                data: 'cid=' + cid + '&_token={{ csrf_token() }}',
                success: function(result) {
                    $('#datax').html(result)
                }
            });
        }
    });
    $('.modal').modal();
});
$(document).ready(function() {
    $("#table").DataTable({
        "aaSorting": [
            [0, "desc"]
        ],
        paging: false,
        "language": {
            url: '/assets/vendors/data-tables/i18n/fr_fr.json'
        }
    });
});
</script>


@stop
