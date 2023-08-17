<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
        integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
        crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="/assets/front/home_style.css" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <meta charset="UTF-8">
    <title>GO TAWSIL</title>
    <link rel="apple-touch-icon" href="/assets/images/favicon/apple-touch-icon-152x152.png" />
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon/favicon-32x32.png" />
</head>
<style>
    .content-header {
        padding-top: 130px;
        color: #c81537
    }

    .terms .fas {
        color: #c10027;
    }

    .terms ul {
        margin-left: 10%;
    }

    .terms p {
        margin-bottom: 2%;
    }

    .table-fill {
        background: white;
        border-radius: 3px;
        border-collapse: collapse;
        height: 320px;
        margin: auto;
        max-width: 600px;
        padding: 5px;
        width: 100%;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        animation: float 5s infinite;
    }

    th {
        color: #D5DDE5;
        ;
        background: #1991ce;
        border-bottom: 4px solid #9ea7af;
        border-right: 1px solid #0b7eb8;
        font-size: 23px;
        font-weight: 100;
        padding: 24px;
        text-align: left;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        vertical-align: middle;
    }

    th:first-child {
        border-top-left-radius: 3px;
    }

    th:last-child {
        border-top-right-radius: 3px;
        border-right: none;
    }

    tr {
        border-top: 1px solid #C1C3D1;
        border-bottom-: 1px solid #C1C3D1;
        color: #666B85;
        font-size: 16px;
        font-weight: normal;
        text-shadow: 0 1px 1px rgba(256, 256, 256, 0.1);
    }

    tr:hover td {
        background: #c10027;
        color: #FFFFFF;
        border-top: 1px solid #FFFFFF;
    }

    tr:first-child {
        border-top: none;
    }

    tr:last-child {
        border-bottom: none;
    }

    tr:nth-child(odd) td {
        background: #e7f3f9;
    }

    tr:nth-child(odd):hover td {
        background: #c10027;
    }

    tr:last-child td:first-child {
        border-bottom-left-radius: 3px;
    }

    tr:last-child td:last-child {
        border-bottom-right-radius: 3px;
    }

    td {
        background: #f6f6f9;
        padding: 20px;
        text-align: left;
        vertical-align: middle;
        font-weight: 300;
        font-size: 18px;
        text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1);
        border-right: 1px solid #C1C3D1;
    }

    td:last-child {
        border-right: 0px;
    }

    th.text-left {
        text-align: left;
    }

    th.text-center {
        text-align: center;
    }

    th.text-right {
        text-align: right;
    }

    td.text-left {
        text-align: left;
    }

    td.text-center {
        text-align: center;
    }

    td.text-right {
        text-align: right;
    }

    .content-header {
        padding-top: 130px;
        color: #c81537
    }

    .terms .fas {
        color: #c10027;
    }

    .terms ul {
        margin-left: 10%;
    }

    .terms p {
        margin-bottom: 2%;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: #fff !important;
        opacity: 1;
    }

    .navbar-light .navbar-nav .nav-link {
        color: rgba(0, 0, 0) !important;
        font-weight: 500;
    }

</style>

<body>




    <nav
        class="navbar fixed-top navbar-expand-lg navbar-light d-flex flex-wrap align-items-center justify-content-center bg-white justify-content-md-between p-3 mb-4 border-bottom">
        <div class="container">
            <a href="{{ route('home') }}"
                class="d-flex align-items-center mb-2 mb-md-0 text-dark text-decoration-none">
                <img src="/assets/front/logo-hori.png" height="50px"> </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse  justify-content-md-between" id="navbarNav">

                <ul class="navbar-nav mx-auto ml-3 col-md-auto mb-2 justify-content-center mb-md-0">

                    <li class="nav-item active">
                        <a href="{{ route('home') }}" class="nav-link link px-2 link-primary">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('home') }}#Accuiel" class="nav-link link px-2 link-dark">À propos de
                            nous</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('home') }}#services" class="nav-link link px-2 link-dark">Nos services</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tarifs') }}" class="nav-link link px-2 link-dark">Tarifs</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('conditions-utilisation') }}"
                            class="nav-link link px-2 link-dark">Conditions
                            d’utilisation</a>
                    </li>

                </ul>
                <form class="form-inline text-end">
                    <a href="{{ route('login') }}" type="button" style="background-color: #c10027"
                        class="text-white btn me-2">Espace Client
                    </a>
                    <a href="{{ route('register') }}" type="button" class="btn btn-primary">S'inscrire</a>
                </form>

            </div>
        </div>

    </nav>
    @yield('content')

    <div class="gotawsilfooter">
        <div class="site-footer">
            <footer class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <img src="/assets/front/logo%20-%20Go%20Tawsil%20vertical.png" class="mb-4"
                            height="80" alt="">
                        <p class="text-justify py-1"><i class="fas fa-map-marker-alt mx-3" style="font-size:30px; "></i>
                            5
                            rue Georges Sand / 139 Boulevard Brahim Roudani Casablanca</p>
                        <p class="text-justify py-1"><i class="fas fa-phone-alt mx-3" style="font-size:30px; "></i>
                            (+212)
                            522 25 08 11</p>


                    </div>

                    <div class="col-xs-6 col-md-3">
                        <h6>ESPACE CLIENT </h6>
                        <a href='{{ route('login') }}' class="btn " style="background-color: #c10027;color: white">Espace
                            Client</a>
                        <h6 class="mt-4">SUIVEZ NOUS </h6>
                        <ul class="social-icons">
                            <li><a class="facebook" target="_blank" href="https://www.facebook.com/GOTawsil"><i class="fab fa-facebook-f"></i></a></li>
                            {{-- <li><a class="twitter" target="_blank" href="#"><i class="fab fa-twitter"></i></a></li> --}}
                            <li><a class="dribbble" target="_blank" href="https://instagram.com/go_tawsil?igshid=YmMyMTA2M2Y="><i class="fab fa-instagram"></i></a></li>
                            <li><a class="linkedin" target="_blank" href="https://www.linkedin.com/company/go-tawsil/"><i class="fab fa-linkedin"></i></a></li>
                        </ul>
                    </div>

                    <div class="col-xs-6 col-md-3">
                        <h6>LIENS</h6>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}">Accueil</a></li>
                            <li><a href="{{ route('home') }}#Accuiel">À propos de nous</a></li>
                            <li><a href="{{ route('home') }}#services">Nos services</a></li>
                            <li><a href="{{ route('tarifs') }}">Tarifs</a></li>
                            <li><a href="{{ route('conditions-utilisation') }}">Conditions d’utilisation</a></li>
                        </ul>
                    </div>
                </div>

            </footer>

        </div>
        <div class="Copyright">
            <p class="py-2 copyright-text text-center text-white" style="background-color: #1991ce;">Copyright ©
                2022
                Tous
                les droits sont réservés |
                <a style="font-weight: bold;color: white;" href="{{ route('home') }}">GO TAWSIL</a>.
            </p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    @yield('js')
</body>

</html>
