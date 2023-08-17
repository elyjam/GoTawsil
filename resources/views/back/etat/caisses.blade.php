@extends($layout)
<style>
    .card .card-content h4 {
        margin: 0;
        margin-bottom: 10px;
    }

    .card-stats-compare {
        font-weight: 900;
    }

    .centered {
        margin: auto;
        width: 50%;
        padding: 10px;
    }

    @media only screen and (max-width: 600px) {
        .centered {

            width: 90%;

        }
    }
</style>
@section('content')

<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>  Caisses
                    </span></h5>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">
                @if(session()->has('validate'))
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
                <form target="_blank" action="{{route('etat_caisses')}}" method="post">
                    @csrf
                    <div class="list-table centered" >
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s12 m6 input-field">
                                        <input id="start_date" value="{{ old('start_date') }}" name="start_date" type="text" placeholder="" class="datepicker">
                                        <label for="start_date">Du </label>
                                    </div>
                                    <div class="col s12 m6 input-field">
                                        <input id="end_date" value="{{ old('end_date') }}" name="end_date" type="text" placeholder="" class="datepicker">
                                        <label for="end_date">Au </label>
                                    </div>
                                    <div class="col s12 m6 input-field">
                                    <select name='ville' id='ville' class="select2 browser-default">

                                        @foreach ($villes as $row)
                                            <option class='option' value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                        @endforeach
                                    </select>
                                    <label for="ville">ville</label>
                                    @error('ville')
                                        <span class="helper-text materialize-red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" >

                        <div class="col s12" style="padding-inline: 20%;">
                            <div class="card animate fadeRight " >
                                <div class="card-content blue lighten-5 black-text"  style="text-align: center">
                                    <button type="submit" name="commission" style="background: none;border:none">
                                        <h4 class="card-stats-number blue-text">
                                            <i class="material-icons" style="font-size: 30px">file_download</i>
                                            </h4>
                                    </button>
                                    <p class="card-stats-compare">
                                        Caisse global détaillée
                                    </p>
                                </div>
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

<div id="delete_modal" class="modal">
    <div class="modal-content">
        <h4> Confirmation de suppression</h4>
        <div>
            Êtes-vous sûr de vouloir supprimer ?
        </div>
        <input type="hidden" name="delId" id="delId">
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
        <a href="#!" class="waves-effect waves-green btn red" onclick="suppRecord()">Supprimer</a>
    </div>
</div>
@stop
@section('js')
<script>
function openSuppModal(id) {
    $("#delId").val(id);
    $('#delete_modal').modal('open');
}

function suppRecord() {
    window.location.replace("/bon/delete/" + $("#delId").val());
}

$(document).ready(function() {
    $('.modal').modal();
});

$(document).ready(function(){
    $('.datepicker').datepicker();
  });



</script>
@stop
