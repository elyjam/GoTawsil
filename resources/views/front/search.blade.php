@extends('layouts/front')
@section('content')

    <style>
        .steps {
            counter-reset: step;
            max-width: 80%;
            margin: auto;
            display: flex;
            position: relative;
            list-style: none;
        }

        .step {
            color: var(--primary-color);
            flex: 1;
            counter-increment: step;
            padding-top: calc(40px + 1rem);
            /*40px is for the circle*/
            text-align: center;
            position: relative;
            background-image: linear-gradient(to bottom, transparent calc(20px - 2px), currentColor 0, currentColor calc(20px + 2px), transparent 0);
            font-weight: 100;
        }

        .ctnr {
            padding-top: 50px;
            --primary-color: hsl(200, 100%, 80%);
            --active-color: #1991ce;
        }

        .step::before {
            display: flex;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            content: counter(step);
            position: absolute;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 1.5rem;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .step:last-child,
        .step:first-child {
            background-size: 50% 100%;
            background-repeat: no-repeat;
        }

        .step:first-child {
            background-position: 100% 0;
        }

        .step-info::before {
            font-weight: bold;
            display: block;
            /* content: "STEP "counter(step); */
        }

        .step-info .fas {
            font-size: 30px;
        }

        /* States */
        .step.active {
            color: var(--active-color);
        }

        .step.livre div {
            color: #0c9f1e !important;
        }

        .step.active::before {
            background: var(--active-color);
        }

        .step.livre::before {
            color: white;
            background-color: green;
        }

        .step.completed::before {
            content: '✓';
        }

        .timeline {
            list-style: none;
        }

        .timeline>li {
            margin-bottom: 60px;
        }

        /* for Desktop */
        @media (min-width : 640px) {
            .timeline>li {
                overflow: hidden;
                margin: 0;
                position: relative;
            }

            .timeline-date {
                width: 180px;
                float: left;
                margin-top: 20px;
            }

            .timeline-content {

                float: left;
                border-left: 3px #f7aab9 solid;
                padding-left: 30px;
                height: 70px
            }

            .timeline-content:before {
                content: '';
                width: 12px;
                height: 12px;
                background: #1991ce;
                position: absolute;
                left: 175px;
                top: 24px;
                border-radius: 100%;
            }
        }
    </style>



    <div class="content-header ">
        <center>
            <img src="/assets/front/delivery-truck.png" height="250px" class="mx-auto " alt="">
            <h1 class="text-center" style="margin-top:20px;">Résultat de la recherche</h1>
        </center>
    </div>

    @if (empty($expedition))
        <div class="container">
            <p class="fs-4 p-4 mx-auto my-4 bg-secondary text-white  border text-center" style="max-width: 40%"><i
                    class=" mx-1 fas fa-exclamation-circle"></i> Aucune information pour ce numero.</p>
        </div>
    @else
        <div class="ctnr">
            <ul class="steps">
                @if ($expedition->etape == 1 || $expedition->etape == 2 || $expedition->etape == 3 || $expedition->etape == 9)
                    <li class="step active">
                    @else
                    <li class="step completed">
                @endif
                <div class="step-info">
                    <span class="step-name"><i class="fas fa-shopping-bag"></i></span>
                    <p>{{ $expedition->agenceDetail->libelle }}</p>
                </div>
                </li>

                @if ($expedition->etape == 1 || $expedition->etape == 2 || $expedition->etape == 3 || $expedition->etape == 9)
                    <li class="step ">
                    @elseif($expedition->etape == 4 || $expedition->etape == 10 || $expedition->etape == 15 || $expedition->etape == 13)
                    <li class="step active">
                    @else
                    <li class="step completed">
                @endif
                <div class="step-info">
                    <span class="step-name"><i class="fas fa-dolly-flatbed"></i></span>
                    <p>Chargement</p>
                </div>
                </li>
                @if (
                    $expedition->etape == 1 ||
                        $expedition->etape == 2 ||
                        $expedition->etape == 3 ||
                        $expedition->etape == 9 ||
                        $expedition->etape == 4 ||
                        $expedition->etape == 10 ||
                        $expedition->etape == 15 ||
                        $expedition->etape == 13)
                    <li class="step">
                    @elseif($expedition->etape == 16 || $expedition->etape == 18)
                    <li class="step active">
                    @else
                    <li class="step completed">
                @endif
                <div class="step-info">
                    <span class="step-name"><i class="fas fa-shipping-fast"></i></span>
                    <p>En cour de livraison</p>
                </div>
                </li>
                @if (
                    $expedition->etape == 1 ||
                        $expedition->etape == 2 ||
                        $expedition->etape == 3 ||
                        $expedition->etape == 9 ||
                        $expedition->etape == 4 ||
                        $expedition->etape == 10 ||
                        $expedition->etape == 15 ||
                        $expedition->etape == 13 ||
                        $expedition->etape == 16 ||
                        $expedition->etape == 18)
                    <li class="step">
                        <div class="step-info">
                            <span class="step-name"><i class="fas fa-home"></i></span>
                            <p>{{ $expedition->agenceDesDetail->libelle }}</p>
                        @elseif($expedition->etape == 14 || $expedition->etape == 7 || $expedition->etape == 8)
                    <li class="step livre completed">
                        <div class="step-info">
                            <span class="step-name"><i class="fas fa-home"></i></span>
                            <h5 class="mb-0" style="color :#0c9f1e;">Bien livrée a</h5>
                            <p>{{ $expedition->agenceDesDetail->libelle }}</p>
                        @else
                    <li class="step active">
                        <div class="step-info">
                            <span class="step-name"><i class="fas fa-home"></i></span>
                            <p>{{ $expedition->agenceDesDetail->libelle }}</p>
                @endif

        </div>
        </li>
        </ul>

        </div>

        <div class="row container">
            <div class="col-lg-6 col-12  p-5 ">
                <h4 class="pb-3">Detail de l'éxpedtion</h4>
                <ul class="list-group ">
                    <li class="list-group-item text-white" style="background: #1991ce">
                        <h4>{{ $expedition->num_expedition }}</h4>
                    </li>
                    <li class="list-group-item"><strong>Date :</strong> {{ $expedition->created_at }}</li>
                    <li class="list-group-item"><strong>Destination : </strong>{{ $expedition->agenceDesDetail->libelle }}
                    </li>
                    {{-- <li class="list-group-item"><strong>Expéditeur : </strong>{{$expedition->clientDetail->libelle}}</li> --}}

                </ul>

            </div>
            <div class="col-lg-6 col-12 p-5 ">
                <h4 class="pb-4">Historique de l'éxpedtion</h4>
                <ul class="timeline ">


                    @foreach ($expedition->etapeHistoryclient as $etap)
                        @if ($etap->etape != '13' && $etap->etape != '15' && $etap->etape != null)
                            <li>

                                <p class="timeline-date">{{ $etap->created_at }}</p>
                                <div class="timeline-content">
                                    <h5 style="margin-top: 17px;">{{ $etap->getEtape() }}</h5>
                                    <p> </p>
                                </div>

                            </li>
                        @endif
                    @endforeach




                </ul>

            </div>
        </div>
    @endif

@stop
