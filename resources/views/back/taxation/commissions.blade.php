@extends($layout)
@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0">

                    <span>Commissions ></span> paramétrage des commissions livreurs
                </h5>
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
                                        <select id="livreur" name="livreur" placeholder=""
                                            class="select select2 browser-default" onchange="$('#form').submit()">
                                            <option value='0'>Tous</option>
                                            @foreach ($livreurs as $livreur)
                                            <option class='option'
                                                {{ $livreur->id == Request::get('livreur') ? 'selected' : '' }}
                                                value='{{ $livreur->id }}'> {{ $livreur->libelle }}</option>
                                            @endforeach
                                        </select>
                                        <label>Livreur</label>
                                    </div>
                                    <div class="col s12 m3 input-field">
                                        <select id="ville" name="ville" placeholder="" class="select2 browser-default"
                                            onchange="$('#form').submit()">
                                            <option value='0'>Tous</option>
                                            @foreach ($villes as $ville)
                                            <option class='option' value='{{ $ville->id }}'
                                                {{Request::get('ville') == $ville->id ? 'selected' : ''}}>
                                                {{ $ville->libelle }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <label>Ville</label>
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
                                                <th> Livreur </th>
                                                <th> Ville </th>
                                                <th style="width: 200px;"> Prix TTC </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($records as $record)
                                            <tr>
                                                <td> {{$record['livreur']}} </td>
                                                <td> {{$record['ville']}} </td>
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