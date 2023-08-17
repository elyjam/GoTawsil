@extends($layout)

<style>
    .card .title{
        margin-top: 15px;

    }
    .card img{
        transition: transform 0.5s;
    }

    .card:hover img{
        transform: rotate(360deg);
    }

    .card:focus .title,
    .card:hover .title{
        color: #1e3799;
        animation: animate .4s linear 1;
    }
    @keyframes animate{
        30%{ transform: translate3d(0, -5px, 0) rotate(5deg); }
        50%{ transform: translate3d(0, -3px, 0) rotate(-4deg); }
        80%{ transform: translate3d(0, 0, 0) rotate(-3deg); }
        100%{ transform: rotate(0deg); }
    }
    @media only screen and (max-width: 767px){
        .link{ margin-bottom: 30px; }
    }

</style>
@section('content')
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
         style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Autres Paramétrages</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('autre_parametrage')}}">Autres Paramétrages</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2" style="">

        <div class="col s12 m3">
            <a href="{{route('taxations')}}">

            <div class="card gradient-shadow gradient-45deg-indigo-blue border-radius-3">
                <div class="card-content center">
                    <img src="/assets/images/parametrage/buildings.png" alt="images" class="width-40">
                    <h6 class="title white-text lighten-4">Taxations par villes</h6>
{{--                    <p class="white-text lighten-4">On apple watch</p>--}}
                </div>
            </div>
            </a>
        </div>
        <div class="col s12 m3">
            <a href="{{route('taxations_region')}}" >
            <div class="card gradient-shadow gradient-45deg-purple-deep-orange border-radius-3">
                <div class="card-content center">
                    <img src="/assets/images/parametrage/place.png" alt="images" class="width-40">
                    <h6 class="title white-text lighten-4">Taxations par regions</h6>
{{--                    <p class="white-text lighten-4">On Canon Printer</p>--}}
                </div>
            </div>
            </a>
        </div>



    </div>
@stop

@section('js')

@stop
