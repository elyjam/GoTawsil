@extends($layout)
@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Tarifications ></span> paramétrage tarifs et conventions
                    clients</h5>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">
                <div>
                    <form id="form" method="GET">
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s12 m3 input-field">
                                        <select id="client" name="client" placeholder=""
                                            class="select select2 browser-default" onchange="$('#form').submit()">
                                            <option value='0'>Tous</option>
                                            @foreach ($ClientRecords as $client)
                                            <option class='option'
                                                {{ $client->id == Request::get('client') ? 'selected' : '' }}
                                                value='{{ $client->id }}'> {{ $client->libelle }}</option>
                                            @endforeach
                                        </select>
                                        <label>Client</label>
                                    </div>
                                    <div class="col s12 m3 input-field">
                                        <select id="depart" name="depart" placeholder="" class="select2 browser-default"
                                            onchange="$('#form').submit()">
                                            <option value='0'>Tous</option>
                                            @foreach ($villes as $ville)
                                            <option class='option' value='{{ $ville->id }}'
                                                {{Request::get('depart') == $ville->id ? 'selected' : ''}}>
                                                {{ $ville->libelle }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <label>Départ</label>
                                    </div>
                                    <div class="col s12 m3 input-field">
                                        <select id="destination" name="destination" placeholder=""
                                            class="select2 browser-default" onchange="$('#form').submit()">
                                            <option value='0'>Tous</option>
                                            @foreach ($villes as $ville)
                                            <option {{Request::get('destination') == $ville->id ? 'selected' : ''}}
                                                class='option' value='{{ $ville->id }}'> {{ $ville->libelle }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <label>Destination</label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <select id="sens" name="sens" placeholder="" class="select2 browser-default"
                                            onchange="$('#form').submit()">
                                            <option value='ENVOI' {{Request::get('sens') == 'ENVOI' ? 'selected' : ''}}>
                                                Envoi</option>
                                            <option value='RETOUR'
                                                {{Request::get('sens') == 'RETOUR' ? 'selected' : ''}}>Retour</option>
                                        </select>
                                        <label>Sens</label>
                                    </div>
                                    <div class="col s12 m1 input-field">
                                        <button type="submit" class="btn btn-light" style="margin-right: 1rem;">
                                            <i class="material-icons">search</i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <form id="priceForm" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col s12 display-flex justify-content-end">
                            <button type="button"
                                onclick="$('#table').DataTable().search('').draw();$('#priceForm').submit();"
                                class="btn indigo" style="margin-left: 1rem;">Enregistrer</button>
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
                                                <th> Client </th>
                                                <th> V.Départ </th>
                                                <th> V.Destination </th>
                                                <th> Sens </th>
                                                <th style="width: 200px;"> PU/Colis </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($records as $record)
                                            <tr>
                                                <td> {{$record['client']}} </td>
                                                <td> {{$record['depart']}} </td>
                                                <td> {{$record['destination']}}</td>
                                                <td> {{$record['sens']}}</td>
                                                <td>
                                                    <input type="text" name="prix[{{$record['key']}}]"
                                                        value="{{$record['value']}}"
                                                        style="width: 200px;text-align: right; padding-right: 10px; {{ $record['value'] != ''  ? 'background-color: #66d466;' : ''}}">
                                                </td>
                                            </tr>
                                            @endforeach

                                    </table>
                                </div>
                                <!-- /.card-body -->
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

<script>
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

