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
                <form method="POST">
                    @csrf
                    <br>
                    <div class="card">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="row">

                                        <div class="col s4 input-field">
                                            <input id="selection_date" value="{{old('selection_date')}}"
                                                name="selection_date" type="text" placeholder="" class="datepicker"
                                                required>
                                            <label for="selection_date">Date sélection </label>
                                            @error('selection_date')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col s4 input-field">
                                            <button type="submit" class="btn btn-light">
                                                Charger</button>
                                            <a href="{{route('facture_rem_gen')}}"><button type="button"
                                                    class="btn indigo" style="margin-left: 1rem;">
                                                    Réinitialiser</button></a>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="users-list-table">
                                <div class="card">
                                    <div class="card-content">
                                        <!-- datatable start -->
                                        <div class="responsive-table">
                                            <table class="data-table" id="list-datatable" class="table">
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
                                                        <td style="padding-right: 18px;"> <label>
                                                                <input class="checkbox" value="{{$client->id}}"
                                                                    type="checkbox" name='clients[]'>
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
    $("#checkall").change(function(e) {
        $('.checkbox').prop("checked", $('#checkall').prop("checked"));
    });
});
</script>
@stop