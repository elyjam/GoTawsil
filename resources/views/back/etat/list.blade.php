@extends($layout)
@section('content')
    <style>
        h5 {
            font-size: 16px;
        }
    </style>
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Etats & Editions > Liste des états
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
                    <div class="row">
                        <div class="col s12 m6 l3 card-width">
                            <a href="{{ route('realisation') }}">
                                <div class="card border-radius-6">
                                    <div class="card-content center-align">
                                        <img src="/assets/images/edition/hand.png" alt="images" class="width-30" style="margin-bottom:10px;">
                                        <h5 class="m-0"><b>Réalisation & Encaissement</b></h5>

                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col s12 m6 l3 card-width">
                            <a href="{{ route('remboursements') }}">
                                <div class="card border-radius-6">
                                    <div class="card-content center-align">
                                        <img src="/assets/images/edition/repayment.png" alt="images" class="width-30" style="margin-bottom:10px;">
                                        <h5 class="m-0"><b>Remboursements</b></h5>

                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col s12 m6 l3 card-width">
                            <a href="{{ route('commission') }}">
                                <div class="card border-radius-6">
                                    <div class="card-content center-align">
                                        <img src="/assets/images/edition/commission.png" alt="images" class="width-30" style="margin-bottom:10px;">
                                        <h5 class="m-0"><b>Commission</b></h5>

                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col s12 m6 l3 card-width">
                            <a href="{{ route('indicateurs') }}">
                                <div class="card border-radius-6">
                                    <div class="card-content center-align">
                                        <img src="/assets/images/edition/indicator.png" alt="images" class="width-30" style="margin-bottom:10px;">
                                        <h5 class="m-0"><b>Indicateurs</b></h5>

                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col s12 m6 l3 card-width">
                            <a href="{{ route('tarification') }}">
                                <div class="card border-radius-6">
                                    <div class="card-content center-align">
                                        <img src="/assets/images/edition/indicator.png" alt="images" class="width-30" style="margin-bottom:10px;">
                                        <h5 class="m-0"><b> Tarification</b></h5>

                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col s12 m6 l3 card-width">
                            <a href="{{ route('auditmodification') }}">
                                <div class="card border-radius-6">
                                    <div class="card-content center-align">
                                        <img src="/assets/images/edition/indicator.png" alt="images" class="width-30" style="margin-bottom:10px;">
                                        <h5 class="m-0"><b> Audit modification</b></h5>

                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col s12 m6 l3 card-width">
                            <a href="{{ route('etat_caisses') }}">
                                <div class="card border-radius-6">
                                    <div class="card-content center-align">
                                        <img src="/assets/images/edition/indicator.png" alt="images" class="width-30" style="margin-bottom:10px;">
                                        <h5 class="m-0"><b>Caisses</b></h5>

                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

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
    </script>
@stop
