@extends($layout)
<style>
    .total_tr {
        border-top: 2px solid #666;
    }

    .total_tr th,.total_tr td {
        font-size: 20px;
        font-weight: 900;
    }


</style>
@section('content')
    <div class="row">
        <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a href="#modal2"
                class="btn-floating modal-trigger btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i
                    class="material-icons" style="font-size:2.6rem!important;">update</i></a>
        </div>

        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="list-table" id="app">
                        <div class="card">
                            <div class="card-content">
                                <!-- datatable start -->
                                <div class="responsive-table">
                                    <table id="list-datatable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nom de l'agent</th>

                                                <th>Ramassé</th>
                                                <th>Transféré</th>
                                                <th>Retour</th>
                                                <th>Chargé</th>
                                                <th>Arrivé</th>
                                                <th>En cours de livraison</th>
                                                <th>Non livré</th>
                                                <th>Total général</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total = 0;
                                                $ramasse = 0;
                                                $transf = 0;
                                                $retour = 0;
                                                $charge = 0;
                                                $arrive = 0;
                                                $encour = 0;
                                                $nolvr = 0;

                                            @endphp
                                            @if (Auth()->user()->role == '1')
                                                @foreach ($charger_de_comptes as $charger)
                                                    <tr>
                                                        @php
                                                            $total += $charger->getExp_pilotage($charger)->count();
                                                            $ramasse += $charger
                                                                ->getExp_pilotage($charger)
                                                                ->where('etape', 2)
                                                                ->count();
                                                            $transf += $charger
                                                                ->getExp_pilotage($charger)
                                                                ->where('etape', 9)
                                                                ->count();
                                                            $retour += $charger
                                                                ->getExp_pilotage($charger)
                                                                ->where('etape', 6)
                                                                ->count();
                                                            $charge += $charger
                                                                ->getExp_pilotage($charger)
                                                                ->where('etape', 4)
                                                                ->count();
                                                            $arrive += $charger
                                                                ->getExp_pilotage($charger)
                                                                ->where('etape', 10)
                                                                ->count();
                                                            $encour += $charger
                                                                ->getExp_pilotage($charger)
                                                                ->where('etape', 16)
                                                                ->count();
                                                            $nolvr += $charger
                                                                ->getExp_pilotage($charger)
                                                                ->where('etape', 20)
                                                                ->count();
                                                        @endphp


                                                        <td>{{ $charger->EmployeDetail->libelle }}</td>
                                                        <td> @if($charger->getExp_pilotage($charger)->where('etape', 2)->count() == 0)
                                                            {{  $charger->getExp_pilotage($charger)->where('etape', 2)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $charger->getExp_pilotage($charger)->where('etape', 2)->pluck('num_expedition')->toArray())}}">
                                                                {{ $charger->getExp_pilotage($charger)->where('etape', 2)->count() }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td> @if($charger->getExp_pilotage($charger)->where('etape', 9)->count() == 0)
                                                            {{  $charger->getExp_pilotage($charger)->where('etape', 9)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $charger->getExp_pilotage($charger)->where('etape', 9)->pluck('num_expedition')->toArray())}}">
                                                                {{ $charger->getExp_pilotage($charger)->where('etape', 9)->count() }}
                                                                </a>
                                                            @endif
                                                        </td>

                                                        <td> @if($charger->getExp_pilotage($charger)->where('etape', 6)->count() == 0)
                                                            {{  $charger->getExp_pilotage($charger)->where('etape', 6)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $charger->getExp_pilotage($charger)->where('etape', 6)->pluck('num_expedition')->toArray())}}">
                                                                {{ $charger->getExp_pilotage($charger)->where('etape', 6)->count() }}
                                                                </a>
                                                            @endif
                                                        </td>

                                                        <td>


                                                            @if($charger->getExp_pilotage($charger)->where('etape', 4)->count() == 0)
                                                            {{  $charger->getExp_pilotage($charger)->where('etape', 4)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $charger->getExp_pilotage($charger)->where('etape', 4)->pluck('num_expedition')->toArray())}}">
                                                                {{ $charger->getExp_pilotage($charger)->where('etape', 4)->count() }}
                                                                </a>
                                                            @endif

                                                        </td>

                                                        <td> @if($charger->getExp_pilotage($charger)->where('etape', 10)->count() == 0)
                                                            {{  $charger->getExp_pilotage($charger)->where('etape', 10)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $charger->getExp_pilotage($charger)->where('etape', 10)->pluck('num_expedition')->toArray())}}">
                                                                {{ $charger->getExp_pilotage($charger)->where('etape', 10)->count() }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td> @if($charger->getExp_pilotage($charger)->where('etape', 16)->count() == 0)
                                                            {{  $charger->getExp_pilotage($charger)->where('etape', 16)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $charger->getExp_pilotage($charger)->where('etape', 16)->pluck('num_expedition')->toArray())}}">
                                                                {{ $charger->getExp_pilotage($charger)->where('etape', 16)->count() }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($charger->getExp_pilotage($charger)->where('etape', 20)->count() == 0)
                                                            {{  $charger->getExp_pilotage($charger)->where('etape', 20)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $charger->getExp_pilotage($charger)->where('etape', 20)->pluck('num_expedition')->toArray())}}">
                                                                {{ $charger->getExp_pilotage($charger)->where('etape', 20)->count() }}
                                                                </a>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if($charger->getExp_pilotage($charger)->count() == 0)
                                                            {{  $charger->getExp_pilotage($charger)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $charger->getExp_pilotage($charger)->pluck('num_expedition')->toArray())}}">
                                                                {{ $charger->getExp_pilotage($charger)->count() }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach ($villes as $ville)
                                                    <tr>
                                                        @php
                                                            $total += $expeditions->where('agence_des', $ville->id)->count();
                                                            $ramasse += $expeditions->where('agence_des', $ville->id)
                                                                ->where('etape', 2)
                                                                ->count();
                                                            $transf += $expeditions->where('agence_des', $ville->id)
                                                                ->where('etape', 9)
                                                                ->count();
                                                            $retour += $expeditions->where('agence_des', $ville->id)
                                                                ->where('etape', 6)
                                                                ->count();
                                                            $charge += $expeditions->where('agence_des', $ville->id)
                                                                ->where('etape', 4)
                                                                ->count();
                                                            $arrive += $expeditions->where('agence_des', $ville->id)
                                                                ->where('etape', 10)
                                                                ->count();
                                                            $encour += $expeditions->where('agence_des', $ville->id)
                                                                ->where('etape', 16)
                                                                ->count();
                                                            $nolvr += $expeditions->where('agence_des', $ville->id)
                                                                ->where('etape', 20)
                                                                ->count();
                                                        @endphp


                                                        <td>{{ $ville->libelle }}</td>
                                                        <td>
                                                            @if($expeditions->where('agence_des', $ville->id)->where('etape', 2)->count() == 0)
                                                            {{ $expeditions->where('agence_des', $ville->id)->where('etape', 2)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $expeditions->where('agence_des', $ville->id)->where('etape', 2)->pluck('num_expedition')->toArray())}}">
                                                                {{ $expeditions->where('agence_des', $ville->id)->where('etape', 2)->count() }}
                                                                </a>
                                                            @endif


                                                        </td>
                                                        <td>
                                                            @if($expeditions->where('agence_des', $ville->id)->where('etape', 9)->count() == 0)
                                                            {{ $expeditions->where('agence_des', $ville->id)->where('etape', 9)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $expeditions->where('agence_des', $ville->id)->where('etape', 9)->pluck('num_expedition')->toArray())}}">
                                                                {{ $expeditions->where('agence_des', $ville->id)->where('etape', 9)->count() }}
                                                                </a>
                                                            @endif
                                                        </td>

                                                        <td>

                                                            @if($expeditions->where('agence_des', $ville->id)->where('etape', 6)->count() == 0)
                                                            {{ $expeditions->where('agence_des', $ville->id)->where('etape', 6)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $expeditions->where('agence_des', $ville->id)->where('etape', 6)->pluck('num_expedition')->toArray())}}">
                                                                {{ $expeditions->where('agence_des', $ville->id)->where('etape', 6)->count() }}
                                                                </a>
                                                            @endif



                                                        </td>

                                                        <td>
                                                            @if($expeditions->where('agence_des', $ville->id)->where('etape', 4)->count() == 0)
                                                            {{ $expeditions->where('agence_des', $ville->id)->where('etape', 4)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $expeditions->where('agence_des', $ville->id)->where('etape', 4)->pluck('num_expedition')->toArray())}}">
                                                                {{ $expeditions->where('agence_des', $ville->id)->where('etape', 4)->count() }}
                                                                </a>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if($expeditions->where('agence_des', $ville->id)->where('etape', 10)->count() == 0)
                                                            {{ $expeditions->where('agence_des', $ville->id)->where('etape', 10)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $expeditions->where('agence_des', $ville->id)->where('etape', 10)->pluck('num_expedition')->toArray())}}">
                                                                {{ $expeditions->where('agence_des', $ville->id)->where('etape', 10)->count() }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td>

                                                            @if($expeditions->where('agence_des', $ville->id)->where('etape', 16)->count() == 0)
                                                            {{ $expeditions->where('agence_des', $ville->id)->where('etape', 16)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $expeditions->where('agence_des', $ville->id)->where('etape', 16)->pluck('num_expedition')->toArray())}}">
                                                                {{ $expeditions->where('agence_des', $ville->id)->where('etape', 16)->count() }}
                                                                </a>
                                                            @endif

                                                        </td>
                                                        <td>
                                                            @if($expeditions->where('agence_des', $ville->id)->where('etape', 20)->count() == 0)
                                                            {{ $expeditions->where('agence_des', $ville->id)->where('etape', 20)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $expeditions->where('agence_des', $ville->id)->where('etape', 20)->pluck('num_expedition')->toArray())}}">
                                                                {{ $expeditions->where('agence_des', $ville->id)->where('etape', 20)->count() }}
                                                                </a>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if($expeditions->where('agence_des', $ville->id)->count() == 0)
                                                            {{ $expeditions->where('agence_des', $ville->id)->count() }}
                                                            @else
                                                            <a target="_blank" href="/expedition/list?exp={{implode('|', $expeditions->where('agence_des', $ville->id)->pluck('num_expedition')->toArray())}}">
                                                                {{ $expeditions->where('agence_des', $ville->id)->count() }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            <tr class="total_tr">
                                                <th>TOTAL</th>

                                                <td>
                                                    {{ $ramasse }}
                                                </td>
                                                <td>{{ $transf }}</td>
                                                <td>{{ $retour }}</td>
                                                <td>{{ $charge }}</td>
                                                <td>{{ $arrive }}</td>
                                                <td>{{ $encour }}</td>
                                                <td>{{ $nolvr }}</td>
                                                <td>{{ $total }}</td>
                                            </tr>

                                    </table>

                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        <div class="content-overlay"></div>
    </div>
@stop

@section('js')
    <script src="/assets/js/scripts/dashboard-ecommerce.js"></script>
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
                                    [2, "ASC"]
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
    <script></script>
@stop
