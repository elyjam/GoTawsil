@extends($layout)
<style>
    td,
    th {

        border-radius: 0px !important;
    }


    .table-fill {
        background: white;
        border-radius: 3px;
        border-collapse: collapse;

        margin: auto;

        padding: 5px;
        width: 100%;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        animation: float 5s infinite;
    }

    .table-fill th {
        color: #fff;
        ;
        background: #c81537;
        border-bottom: 4px solid #9ea7af;
        border-right: 1px solid #fff;
        font-size: 23px;
        font-weight: 100;
        padding: 24px;
        text-align: left;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        vertical-align: middle;
    }

    .table-fill th:first-child {
        border-top-left-radius: 3px;
    }

    .table-fill th:last-child {
        border-top-right-radius: 3px;
        border-right: none;
    }

    .table-fill tr {
        border-top: 1px solid #C1C3D1;
        border-bottom-: 1px solid #C1C3D1;
        color: #666B85;
        font-size: 16px;
        font-weight: normal;
        text-shadow: 0 1px 1px rgba(256, 256, 256, 0.1);
    }

    /* .table-fill tr:hover td {
        background: #1991ce;
        color: #FFFFFF;
        border-top: 1px solid #fff;
    } */

    .table-fill tr:first-child {
        border-top: none;
    }

    .table-fill tr:last-child {
        border-bottom: none;
    }

    .table-fill tr:nth-child(odd) td {
        background: #EBEBEB;
    }

    /* .table-fill tr:nth-child(odd):hover td {
        background: #1991ce;
    } */

    .table-fill tr:last-child td:first-child {
        border-bottom-left-radius: 3px;
    }

    .table-fill tr:last-child td:last-child {
        border-bottom-right-radius: 3px;
    }

    .table-fill td {
        background: #FFFFFF;
        padding: 20px;
        text-align: left;
        vertical-align: middle;
        font-weight: 300;
        font-size: 18px;
        text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1);
        border-right: 1px solid #C1C3D1;
    }

    .table-fill td:last-child {
        border-right: 0px;
    }

    .dropdown-content{
        width: auto!important;
    }
</style>
@section('content')
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="col s12">
                    <div class="card-content">
                        <h4 class="header">
                            Les réalisations par client </span>
                        </h4>
                        <table class="table-fill" style="margin-top: 20px!important;">
                            <thead>
                                <tr>
                                    <th>Client <i class="material-icons right" style="font-size: 33px!important;">assignment_ind</i></th>
                                    <th>Nbr total Colis <i class="material-icons right" style="font-size: 33px!important;">clear_all</i></th>
                                    <th>Nbr d'envoi <i class="material-icons right" style="font-size: 33px!important;">trending_up</i></th>
                                    <th>Nbr de retour <i class="material-icons right" style="font-size: 33px!important;">rotate_left</i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($client_commercial as $client)
                                    @if ($client->exp_count() != 0)
                                        <tr>
                                            <td>
                                                {{ $client->libelle }}
                                                @if ($client->port == 'PPE')
                                                    <span class="green badge" style="margin-left:5px;">Client standard</span>
                                                @else
                                                    <span class="grey badge" style="margin-left:5px;">Client en
                                                        compte</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $client->exp_count() }}
                                            </td>
                                            <td>
                                                <ul id="dropdown{{ $client->id }}" class="dropdown-content">
                                                    <li><a href="#!">Colis contre remb.<span class="grey badge" style="margin-left:10px;"> {{ $client->ecom_count() }}</span></a></li>
                                                    <li><a href="#!">Colis déjà payer<span class="grey badge" style="margin-left:10px;"> {{ $client->cdp_count() }}</span></a></li>
                                                </ul>
                                                <a class="dropdown-trigger" href="#!"
                                                    data-target="dropdown{{ $client->id }}">{{ $client->envoi_count() }}<i
                                                        class="material-icons right">arrow_drop_down</i></a>
                                            </td>
                                            <td>
                                                {{ $client->retour_count() }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
@stop

@section('js')
    <script src="/assets/js/scripts/dashboard-ecommerce.js"></script>


    <script>
        const Rmensuel = new Chart(
            document.getElementById('Rmensuel'),
            config
        );

        const EvolutionChiffre = new Chart(
            document.getElementById('EvolutionChiffre'),
            configEvolutionChiffre
        );
        const Tauxretour = new Chart(
            document.getElementById('Tauxretour'),
            configTauxretour
        );
    </script>
@stop
