@extends($layout)
<style>
@media only screen and (max-width: 600px) {
    .dataTable {
        font-size: 10px !important;
    }
}

.btn-download {
    background-color: #c81537 !important;
}
</style>
@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg-client.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Mes bons de rammasage</span></h5>
            </div>
            <div class="col s12 m6 l6 right-align-md">
                <ol class="breadcrumbs mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('expedition_list') }}">Liste des expeditions</a>
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
                <br>
                <div>

                    <form id="form" method="POST">
                        @csrf

                        <div class="card">

                            <div class="card-content">
                                <div class="row">
                                    <div class="col s12 m3 input-field">
                                        <input id="start_date" value="{{ old('start_date') }}" name="start_date"
                                            type="text" placeholder="" class="datepicker">
                                        <label for="start_date">Du </label>
                                    </div>
                                    <div class="col s12 m3 input-field">
                                        <input id="end_date" value="{{ old('end_date') }}" name="end_date" type="text"
                                            placeholder="" class="datepicker">
                                        <label for="end_date">Au </label>
                                    </div>
                                    <div class="col s12 m2 input-field">
                                        <input id="n_colis" value="{{ old('n_colis') }}" name="n_colis" type="text"
                                            placeholder="">
                                        <label for="n_colis">N° Expedition </label>
                                    </div>

                                </div>

                                <div class="row">
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

                                            <td>N° Demande</td>
                                            <td>Date Création</td>
                                            <td>Rapport</td>
                                            <th>Etiquettes</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                        <tr>
                                            <td>{{ $record->code }}</td>
                                            <td> {{ $record->created_at }} </td>
                                            <td> <a href="{{route('bon_print_detail',$record->id)}}" target="_blank"><i
                                                class="material-icons tooltipped" style="color: #c10027;"
                                                data-position="top"
                                                data-tooltip="Imprimer bon N° {{ $record->code }} ">print</i></a>
                                    </td>
                                            <td>   <a
                                                href="{{ route('pdf_bon', $record->id) }} "target="_blank"><i
                                                    class="material-icons tooltipped" style="color: #c10027;"
                                                    data-position="top" data-tooltip="Imprimer">print</i></a></td>

                                        </tr>

                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a --}}
    {{-- href="{{route('expedition_create')}}" --}}
    {{-- class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i --}}
    {{-- class="material-icons">add</i></a></div> --}}
</div>
</div>
@stop
@section('js')
<script>
    $(document).ready(function() {

const App = {
    mounted() {
        this.loadData();
        $('.tooltipped').tooltip();
    },
    methods: {
        loadData() {
            if ($("#list-datatable").length > 0) {
                $("#list-datatable").DataTable({
                    "aaSorting": [
                        [1, "desc"]
                    ],
                    "language": {
                        url: '/assets/vendors/data-tables/i18n/fr_fr.json'
                    }
                });
            };

        },
    },
}

Vue.createApp(App).mount('#app');

});
</script>
<script>

    document.getElementById("bon_ramassage").classList.add("activate");
function openSuppModal(id) {
    $("#delId").val(id);
    $('#delete_modal').modal('open');
}


function suppRecord() {
    window.location.replace("/expedition/delete/" + $("#delId").val());
}

$(document).ready(function() {
    $('.modal').modal();
});
document.getElementById("form").style.display = "none";

function advancedsearch() {
    var x = document.getElementById("form");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}


</script>
@stop
