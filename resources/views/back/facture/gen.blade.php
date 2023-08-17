@extends($layout)

@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Facturation > Générer fatcures</span></h5>
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">
                <form method="GET">
                    <br>
                    <div class="card">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="row">
                                        <div class="col s4 input-field">
                                            <input id="selection_date" value="{{$date}}" name="date" type="text"
                                                placeholder="" class="datepicker" required>
                                            <label for="selection_date">Date sélection </label>
                                            @error('selection_date')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col s4 input-field">
                                            <button type="submit" class="btn btn-light">Charger </button>
                                            <!--<a href="{{route('facture_gen')}}">
                                                <button type="button" class="btn indigo" style="margin-left: 1rem;">
                                                    Réinitialiser</button>
                                            </a>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if($date != null)
                <form method="POST" id="form">
                    @csrf
                    <div class="col s12 display-flex justify-content-end">

                        <button type="button" onclick="$('#type').val(2); $('form').submit();" class="btn green"
                            style="margin-left: 1rem;">
                            <i style="line-height: normal;" class="material-icons">insert_drive_file</i> Générer la
                            facture
                        </button>
                    </div>
                    <br><br>
                    @error('clients')
                    <div class="red lighten-1 white-text" style="padding: 5px;">{{ $message }}
                    </div>
                    @enderror
                    <div class="users-list-table">
                        <div class="card">
                            <div class="card-content">
                                <!-- datatable start -->
                                <div class="responsive-table">
                                    <input type="hidden" name="type" id="type">
                                    <table class="data-table" id="table" class="table">
                                        <thead>
                                            <tr>
                                                <th width="20px" style="width: 20px !important;">
                                                    <label>
                                                        <input type="checkbox" id='checkall'>
                                                        <span></span>
                                                    </label>
                                                </th>
                                                <th>Client</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($clients as $client)
                                            <tr>
                                                <td style="padding-left: 18px;"> <label>
                                                        <input class="checkbox" value="{{$client->id}}" type="checkbox"
                                                            name='clients[]'>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td>{{$client->libelle}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>



    </div>
</div>

@stop
@section('js')
@section('js')
<script>
$(document).ready(function() {
    $("#checkall").change(function(e) {
        $('.checkbox').prop("checked", $('#checkall').prop("checked"));
    });
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