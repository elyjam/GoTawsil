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
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span> Remboursements
                        </span></h5>
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
                    <form target="_blank" action="{{ route('remboursements') }}" method="post">
                        @csrf
                        <div class="list-table centered" id="app">
                            <div class="card">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col s12 m6 input-field">
                                            <input id="start_date" value="{{ old('start_date') }}" name="start_date"
                                                type="text" placeholder="" class="datepicker">
                                            <label for="start_date">Du </label>
                                        </div>
                                        <div class="col s12 m6 input-field">
                                            <input id="end_date" value="{{ old('end_date') }}" name="end_date"
                                                type="text" placeholder="" class="datepicker">
                                            <label for="end_date">Au </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div>

                            <ul class="collection">
                                <li class="collection-item dismissable">
                                    <div>Remboursements éffectués <button
                                            class="btn waves-effect waves-light right btn-small " type="submit"
                                            name="remboursementsEffectues">
                                            <i class="material-icons right" style="font-size: 30px">send</i> </button>
                                    </div>
                                </li>
                                <li class="collection-item dismissable">
                                    <div>Remboursements / CLients * <button
                                            class="btn waves-effect waves-light right btn-small " type="submit"
                                            name="remboursementsCLients">
                                            <i class="material-icons right" style="font-size: 30px">send</i> </button></div>
                                </li>
                                <li class="collection-item dismissable"><div>Remboursements / CLients *M* <button class="btn waves-effect waves-light right btn-small " type="submit" name="encaisseAgenceExp">
                                <i class="material-icons right" style="font-size: 30px">send</i> </button></div></li>
                                <li class="collection-item dismissable">
                                    <div>Remboursements / Ville * <button
                                            class="btn waves-effect waves-light right btn-small " type="submit"
                                            name="remboursementsVille">
                                            <i class="material-icons right" style="font-size: 30px">send</i> </button></div>
                                </li>
                                <li class="collection-item dismissable"><div>Remboursements / Ville *M*<button class="btn waves-effect waves-light right btn-small " type="submit" name="EncaisseClient">
                                <i class="material-icons right" style="font-size: 30px">send</i> </button></div></li>
                            </ul>
                        </div> --}}
                        <div class="row">

                            <div class="col s12 m6 l6 xl4">
                                <div class="card animate fadeRight">
                                    <div class="card-content blue lighten-5 black-text" style="text-align: center">
                                        <button type="submit" name="remboursementsEffectues"
                                            style="background: none;border:none">
                                            <h4 class="card-stats-number blue-text">
                                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                    width="30px" viewBox="0 0 550.801 550.801"
                                                    style="enable-background:new 0 0 550.801 550.801;" xml:space="preserve">
                                                    <g>
                                                        <path fill="red"
                                                            d="M160.381,282.225c0-14.832-10.299-23.684-28.474-23.684c-7.414,0-12.437,0.715-15.071,1.432V307.6
		                                                    c3.114,0.707,6.942,0.949,12.192,0.949C148.419,308.549,160.381,298.74,160.381,282.225z" />
                                                        <path fill="red"
                                                            d="M272.875,259.019c-8.145,0-13.397,0.717-16.519,1.435v105.523c3.116,0.729,8.142,0.729,12.69,0.729
		                                                    c33.017,0.231,54.554-17.946,54.554-56.474C323.842,276.719,304.215,259.019,272.875,259.019z" />
                                                        <path fill="red"
                                                            d="M488.426,197.019H475.2v-63.816c0-0.398-0.063-0.799-0.116-1.202c-0.021-2.534-0.827-5.023-2.562-6.995L366.325,3.694
		                                                    c-0.032-0.031-0.063-0.042-0.085-0.076c-0.633-0.707-1.371-1.295-2.151-1.804c-0.231-0.155-0.464-0.285-0.706-0.419
		                                                    c-0.676-0.369-1.393-0.675-2.131-0.896c-0.2-0.056-0.38-0.138-0.58-0.19C359.87,0.119,359.037,0,358.193,0H97.2
		                                                    c-11.918,0-21.6,9.693-21.6,21.601v175.413H62.377c-17.049,0-30.873,13.818-30.873,30.873v160.545
		                                                    c0,17.043,13.824,30.87,30.873,30.87h13.224V529.2c0,11.907,9.682,21.601,21.6,21.601h356.4c11.907,0,21.6-9.693,21.6-21.601
		                                                    V419.302h13.226c17.044,0,30.871-13.827,30.871-30.87v-160.54C519.297,210.838,505.47,197.019,488.426,197.019z M97.2,21.605
		                                                    h250.193v110.513c0,5.967,4.841,10.8,10.8,10.8h95.407v54.108H97.2V21.605z M362.359,309.023c0,30.876-11.243,52.165-26.82,65.333
		                                                    c-16.971,14.117-42.82,20.814-74.396,20.814c-18.9,0-32.297-1.197-41.401-2.389V234.365c13.399-2.149,30.878-3.346,49.304-3.346
		                                                    c30.612,0,50.478,5.508,66.039,17.226C351.828,260.69,362.359,280.547,362.359,309.023z M80.7,393.499V234.365
		                                                    c11.241-1.904,27.042-3.346,49.296-3.346c22.491,0,38.527,4.308,49.291,12.928c10.292,8.131,17.215,21.534,17.215,37.328
		                                                    c0,15.799-5.25,29.198-14.829,38.285c-12.442,11.728-30.865,16.996-52.407,16.996c-4.778,0-9.1-0.243-12.435-0.723v57.67H80.7
		                                                    V393.499z M453.601,523.353H97.2V419.302h356.4V523.353z M484.898,262.127h-61.989v36.851h57.913v29.674h-57.913v64.848h-36.593
		                                                    V232.216h98.582V262.127z" />
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                    <g>
                                                    </g>
                                                </svg>
                                            </h4>
                                        </button>
                                        <button type="submit" name="remboursementsEffectues_Excel"
                                            style="background: none;border:none">
                                            <h4 class="card-stats-number blue-text">
                                                <svg width="30px" fill="green" version="1.1"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                    viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000"
                                                    xml:space="preserve">
                                                    <metadata> Svg Vector Icons : http://www.onlinewebfonts.com/icon
                                                    </metadata>
                                                    <g>
                                                        <path
                                                            d="M884.4,332.8H728.1v97h156.3V332.8z M884.4,456.3H728.1v97.1h156.3V456.3z M884.4,209.3H728.1v97h156.3V209.3z M884.4,698.8H728.1v97.1h156.3V698.8z M956.2,112.3H580.3c-4.5,0-8.8,0.9-12.7,2.6V10L10,121.1v757.8L567.6,990v-99.6c3.9,1.7,8.2,2.6,12.7,2.6h375.9c18.7,0,33.8-15.8,33.8-35.3V147.6C990,128.1,974.9,112.3,956.2,112.3z M385.9,663.6l-67.6-4.4l-50.7-127.9l-54.9,119.2l-63.4-4.4l80.3-145.6l-71.8-158.7l63.3-4.4l42.2,119.1h8.4l46.5-127.9l67.6-4.4l-84.5,167.6L385.9,663.6z M947.8,848.8H567.6v-52.9h126.7v-97.1H567.6v-22h126.7v-97H567.6v-26.4h126.7v-97.1H567.6v-26.5h126.7v-97H567.6v-26.4h126.7v-97H567.6v-52.9h380.2L947.8,848.8L947.8,848.8z M884.4,579.8H728.1v97h156.3V579.8z" />
                                                    </g>
                                                </svg>
                                            </h4>
                                        </button>
                                        <p class="card-stats-compare">
                                            Remb. éffectués
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col s12 m6 l6 xl4">
                                <div class="card animate fadeRight">
                                    <div class="card-content blue lighten-5 black-text" style="text-align: center">
                                        <button type="submit" name="remboursementsCLients"
                                            style="background: none;border:none">
                                            <h4 class="card-stats-number blue-text">
                                                <i class="material-icons" style="font-size: 30px">file_download</i>
                                            </h4>
                                        </button>
                                        <p class="card-stats-compare">
                                            Remboursements / CLients *
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col s12 m6 l6 xl4">
                                <div class="card  animate fadeRight">
                                    <div class="card-content blue lighten-5 black-text" style="text-align: center">
                                        <button type="submit" name="remboursementsVille"
                                            style="background: none;border:none">
                                            <h4 class="card-stats-number blue-text">
                                                <i class="material-icons" style="font-size: 30px">file_download</i>
                                            </h4>
                                        </button>
                                        <p class="card-stats-compare">
                                            Remboursements / Ville *
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

        $(document).ready(function() {
            $('.datepicker').datepicker();
        });
    </script>
@stop
