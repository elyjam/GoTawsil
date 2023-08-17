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
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Expeditions > Livraison & encaissement</span></h5>
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
                                        <div class="col s12 m2 input-field">
                                            <input id="n_colis" value="{{ old('num_exp') }}" name="num_exp"
                                                type="text" placeholder="">
                                            <label for="num_exp">N° Expéd </label>
                                        </div>
                                        <div class="col s12 m2 input-field">
                                            <input id="num_bon" value="{{ old('num_bon') }}" name="num_bon"
                                                type="text" placeholder="">
                                            <label for="num_bon">N° Bon </label>
                                        </div>
                                        <div class="col s2 input-field">
                                            <select name='employe' id='employe' class="select2 browser-default">
                                                <option value=''>Tous</option>
                                                @foreach ($employes as $record)
                                                    <option value="{{ $record->id }}"
                                                        {{ $record->id == old('employe') ? 'selected' : '' }}>
                                                        {{ $record->libelle }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="employe"> Livreur</label>
                                        </div>
                                        <div class="col s12 m3 input-field">
                                            <button type="button"
                                                onclick="event.preventDefault();
                                                        $('#form').attr('action', '{{ route('expedition_livraison') }}'); document.getElementById('form').submit();"
                                                class="btn btn-light" style="margin-right: 1rem;">
                                                <i class="material-icons">search</i></button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </form>
                        <div class="row">
                            @foreach ($records as $record)
                                <div class="col s12 m6 l6">
                                    <div class="card">

                                        <div class="card-content">

                                            <div class="row" style="padding-inline: 15px;">
                                                <strong style="color: #000 !important;"> <span
                                                        style="color: #478FCA !important;">
                                                        N°
                                                        Expéd : {{ $record->num_expedition }}
                                                        ({{ date('d/m/Y', strtotime($record->created_at)) }})
                                                    </span>
                                                    <div class="right">


                                                        <b> B. Liv :</b>
                                                        <a href="{{ route('bonliv_download', ['bl' => $record->bl_id]) }}"
                                                            target="_blank"><span
                                                                class="badge grey">{{ $record->bl_code }}</span></a>
                                                    </div>
                                                </strong><br>
                                                <b> Livreur : </b>{{ $record->livreur }} <br>
                                                <b> Exp : </b>{{ $record->client }} <br>
                                                <b> Dest : </b>{{ $record->destinataire }}
                                                ,{{ $record->adresse_destinataire }} -
                                                {{ $record->agence }}<br>
                                                <b> Tél : </b>{{ $record->telephone }}<br>
                                                <b class="right" style="color: #000;">Total Net :
                                                    {{ number_format($record->fond, 2) }} Dhs</b>

                                            </div>
                                        </div>
                                        <div class="card-action blue lighten-5">
                                            <a class="waves-effect waves-light btn green right"
                                                style="margin-inline:10px;padding: 0 1rem;"
                                                onclick="openLivreConfirm({{ $record->id }}, 1)"><i
                                                    class="material-icons left">local_shipping</i>Livrer
                                                colis</a>
                                            <a class="waves-effect waves-light btn pink right" style="padding: 0 1rem;"
                                                onclick="openCommModal({{ $record->id }}, 0)"><i
                                                    class="material-icons left">highlight_off</i>
                                                Non Livrer</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    </div>

    <div id="comm_modal" class="modal">
        <div class="modal-content">
            <h4> Votre Commentaire</h4>
            <div>
                <div class="row">
                    <div class="col s12 m8 input-field">
                        <select style="width: 100%;" name="comment" id="comment" class="select2 browser-default">
                            <option class="option" value="0"></option>
                            @foreach ($commentaires as $commentaire)
                                <option class="option" value="{{ $commentaire->id }}"> {{ $commentaire->libelle }}
                                </option>
                            @endforeach
                        </select>
                        <label for="comment">Commentaire</label>
                    </div>

                </div>
                <p style="color:red" id="alertComment"></p>
            </div>
            <input type="hidden" name="expId" id="expId">
            <input type="hidden" name="type" id="type">

        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
            <a href="#!" onclick="changeStatusNoLivre()" class="waves-effect waves-green btn ">Commenter</a>
        </div>
    </div>

    <div id="livre_modal" class="modal">
        <div class="modal-content">
            <h4> Confirmation de livraison</h4>
            <div>
                Êtes-vous sûr de vouloir livrer ?
            </div>
            <input type="hidden" name="delId" id="delId">
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
            <a href="#!" class="waves-effect waves-green btn green" onclick="changeStatus()">livrer</a>
        </div>
    </div>
@stop
@section('js')
    <script>
        function openCommModal(id, type) {
            $("#expId").val(id);
            $("#type").val(type);
            $('#comm_modal').modal('open');
        }

        function openLivreConfirm(id, type) {
            $("#expId").val(id);
            $("#type").val(type);
            $('#livre_modal').modal('open');
        }

        function changeStatusNoLivre() {
            if ($('#comment').val() != '0') {
                var status = $("#type").val() == 1 ? 14 : 20;
                window.location.replace("/expedition/change-status/" + $("#expId").val() + "/" + status + "/" + $(
                        '#comment')
                    .val());
            } else {
                $('#alertComment').html('Vous devez choisir le commentaire pour commencer')
            }

        }

        function changeStatus() {

            var status = $("#type").val() == 1 ? 14 : 20;
            window.location.replace("/expedition/change-status/" + $("#expId").val() + "/" + status + "/" + $('#comment')
                .val());


        }
        $(document).ready(function() {
            $('.modal').modal();
        });
    </script>
@stop
