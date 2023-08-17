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
    <div class="row mt-2">
        <div class="col s12 m3">
            <a href="{{route('ville_list')}}">

            <div class="card gradient-shadow gradient-45deg-light-blue-cyan border-radius-3">
                <div class="card-content center">
                    <img src="/assets/images/parametrage/ville.png" alt="images" class="width-40">
                    <h6 class="title white-text lighten-4">Gestion des villes</h6>
                </div>
            </div>
            </a>
        </div>
        <div class="col s12 m3">
            <a href="{{route('agence_list')}}" >
            <div class="card gradient-shadow gradient-45deg-red-pink border-radius-3">
                <div class="card-content center">
                    <img src="/assets/images/parametrage/agence.png" alt="images" class="width-40">
                    <h6 class="title white-text lighten-4">Gestion des agences</h6>
                </div>
            </div>
            </a>
        </div>
        <div class="col s12 m3">
            <a href="{{route('transporteur_list')}}">
            <div class="card gradient-shadow gradient-45deg-green-teal border-radius-3">
                <div class="card-content center">
                    <img src="/assets/images/parametrage/loader.png" alt="images" class="width-40">
                    <h6 class="title white-text lighten-4">Gestion des transporteurs</h6>
                </div>
            </div>
            </a>
        </div>
        <div class="col s12 m3">
            <a href="{{route('region_list')}}">

                <div class="card gradient-shadow gradient-45deg-purple-amber border-radius-3">
                    <div class="card-content center">
                        <img src="/assets/images/parametrage/world.png" alt="images" class="width-40">
                        <h6 class="title white-text lighten-4">Gestion des régions</h6>
                    </div>
                </div>
            </a>
        </div>

        <div class="col s12 m3">
            <a href="{{route('types_commentaire_list')}}">
            <div class="card gradient-shadow gradient-45deg-amber-amber border-radius-3">
                <div class="card-content center">
                    <img src="/assets/images/parametrage/comment.png" alt="images" class="width-40">
                    <h6 class="title white-text lighten-4">Gestion des commentaires</h6>
                </div>
            </div>
            </a>
        </div>

        <div class="col s12 m3">
            <a href="{{route('banque_list')}}">

                <div class="card gradient-shadow gradient-45deg-purple-deep-purple border-radius-3">
                    <div class="card-content center">
                        <img src="/assets/images/parametrage/bank.png" alt="images" class="width-40">
                        <h6 class="title white-text lighten-4">Gestion des banques</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col s12 m3">
            <a href="{{route('typereclamation_list')}}">

                <div class="card gradient-shadow gradient-45deg-brown-brown border-radius-3">
                    <div class="card-content center">
                        <img src="/assets/images/parametrage/complain.png" alt="images" class="width-40">
                        <h6 class="title white-text lighten-4">Types de réclamations</h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col s12 m3">
            <a href="{{route('statut_list')}}">

                <div class="card gradient-shadow gradient-45deg-blue-grey-blue border-radius-3">
                    <div class="card-content center">
                        <img src="/assets/images/parametrage/status.png" alt="images" class="width-40">
                        <h6 class="title white-text lighten-4">Gestion des statuts</h6>
                    </div>
                </div>
            </a>
        </div>

        <div class="col s12 m3">
            <a href="{{route('groupstatuts_list')}}">

                <div class="card gradient-shadow gradient-45deg-orange-amber border-radius-3">
                    <div class="card-content center">
                        <img src="/assets/images/parametrage/review.png" alt="images" class="width-40">
                        <h6 class="title white-text lighten-4">groupes de statuts</h6>
                    </div>
                </div>
            </a>
        </div>



    </div>
@stop

@section('js')

@stop
