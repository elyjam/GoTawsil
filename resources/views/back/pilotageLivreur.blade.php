@extends($layout)
@section('content')
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <div class="row">
                        <div class="col s12 m4">
                          <div class="card gradient-shadow gradient-45deg-teal-cyan border-radius-3">
                            <div class="card-content center">
                              <img src="/assets/images/livreur/courier.png" alt="images" class="width-30">
                              <h5 style="font-weight: 900"  class="black-text lighten-4">{{$nbr_colis}}</h5>
                              <p class="black-text lighten-4">Colis en cours de livraison</p>
                            </div>
                          </div>
                        </div>
                        <div class="col s12 m4">
                          <div class="card gradient-shadow gradient-45deg-orange-deep-orange border-radius-3">
                            <div class="card-content center">
                              <img src="/assets/images/livreur/encours.png" alt="images" class="width-30">
                              <h5 style="font-weight: 900"  class="black-text lighten-4">{{$taux_livraison}} %</h5>
                              <p class="black-text lighten-4">Taux de livraison</p>
                            </div>
                          </div>
                        </div>
                        <div class="col s12 m4">
                          <div class="card gradient-shadow gradient-45deg-light-green-amber border-radius-3">
                            <div class="card-content center">
                              <img src="/assets/images/livreur/salary.png" alt="images" class="width-30">
                              <h5 style="font-weight: 900"  class="black-text lighten-4">{{$commissions_globale}} Dhs</h5>
                              <p class="black-text lighten-4">Commissions global</p>
                            </div>
                          </div>
                        </div>

                      </div>

                </div>

            </div>
        </div>
        <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a href="#modal2"
                class="btn-floating modal-trigger btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i
                    class="material-icons" style="font-size:2.6rem!important;">update</i></a>
        </div>
        <div id="modal2" class="modal modal-fixed-footer" style="height: 40%;">
            <form method="POST" action="{{ route('Dashboard_Pilotage') }}">
                @csrf
                <div class="modal-content">
                    <h4 style="text-align: center;padding-block:10px;border-radius:10px;"
                        class="gradient-45deg-indigo-light-blue white-text">Changer les date de statistique</h4>
                    <br>
                    <div class="col s12 m6 input-field">
                        <input id="start_date" value="{{ old('start_date') }}" required name="start_date" placeholder=""
                            type="date">
                        <label for="start_date">Du </label>
                    </div>
                    <div class="col s12 m6 input-field">
                        <input id="end_date" value="{{ old('end_date') }}" required name="end_date" placeholder=""
                            type="date">
                        <label for="end_date">Au </label>
                    </div>
                </div>
                <div class="modal-footer">

                    <a class="btn red waves-effect waves-light modal-action modal-close" style="margin-inline: 5px;">Fermer
                        <i class="material-icons left">close</i>
                    </a>
                    <button class="btn waves-effect modal-action waves-light" type="submit">Actualiser
                        <i class="material-icons right">update</i>
                    </button>
                </div>
            </form>
        </div>

    </div>
@stop

@section('js')
    <script src="/assets/js/scripts/dashboard-ecommerce.js"></script>
    <script></script>

@stop
